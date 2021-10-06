<?php

namespace App\Http\Controllers;

use App\Services\Logging\SLogging;
use App\Services\SGlobal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

class LogController extends Controller
{
    private $sLogging;
    private $sGlobal;

    public function __construct(SLogging $sLogging, SGlobal $sGlobal)
    {
        $this->sLogging = $sLogging;
        $this->sGlobal = $sGlobal;
        
        if(env('APP_ENV')!='production')
        {
            $this->link_upload = Config::get('carta.link_gdrive.development.upload');
        } else {
            $this->link_upload = Config::get('carta.link_gdrive.production.upload');
        }
    }

    public function index()
    {
        $data = array(
            'title'         => 'Carta - Log Activity',
            'active_menu'   => 'Log Activity'
        );

        return $this->sGlobal->view('logging.index', $data);
    }

    public function listLogging(Request $request)
    {
        $start_date = $this->sGlobal->convertDate($request->start_date);
        $end_date = $this->sGlobal->convertDate($request->end_date);
        $logs = $this->sLogging->listLogging($start_date, $end_date, $request->start, $request->length, $request->order);
        $logs['draw'] = $request->draw;
        return $logs;
    }

    public function doDownload(Request $request)
    {
        $response = array(
            'status' => false,
            'message'=> ''
        );
        
        $start_date = ($request->start_date) ? $this->sGlobal->convertDate($request->start_date) : null;
        $end_date = ($request->end_date) ? $this->sGlobal->convertDate($request->end_date) : null;;
        
        $logs = $this->sLogging->listLogging($start_date, $end_date, 0, -1, null);
        if($logs['recordsTotal']==0)
        {
            $response['message'] = 'Data not found';
            return $response;
        }
        $data = array();
        foreach ($logs['data'] as $value) {
            $data[] = array(
                'nik'         => ($value['user']) ? $value['user']['nik'] : '-', 
                'name'        => ($value['user']) ? $value['user']['name'] : '-',
                'description' => $value['type'],
                'source'      => ($value['source']) ? $value['source'] : '-',
                'created_at'  => ($value['created_at']) ? $value['created_at'] : '-' 
            );
        }

        $file_name = 'log-activity-'.date('YmdHis');
        $path = public_path().'/download';

        Excel::create($file_name, function($excel) use ($data) {
            $excel->sheet('Sheet1', function($sheet) use($data) {   
                $sheet->cell('A1', function($cell) {
                    $cell->setValue('NIK');
                    $cell->setFontWeight('bold');
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cell('B1', function($cell) {
                    $cell->setValue('Name');
                    $cell->setFontWeight('bold');
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cell('C1', function($cell) {
                    $cell->setValue('Description');
                    $cell->setFontWeight('bold');
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cell('D1', function($cell) {
                    $cell->setValue('Source');
                    $cell->setFontWeight('bold');
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cell('E1', function($cell) {
                    $cell->setValue('Created At');
                    $cell->setFontWeight('bold');
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $row = 2;
                foreach ($data as $item)
                {
                    $sheet->cell('A'.$row, function($cell) use($item) {
                        $cell->setValue($item['nik']);
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $sheet->cell('B'.$row, function($cell) use($item) {
                        $cell->setValue($item['name']);
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $sheet->cell('C'.$row, function($cell) use($item) {
                        $cell->setValue($item['description']);
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $sheet->cell('D'.$row, function($cell) use($item) {
                        $cell->setValue($item['source']);
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $sheet->cell('E'.$row, function($cell) use($item) {
                        $cell->setValue($item['created_at']);
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $row++;
                }
            });
        })->store('xlsx', $path);

        $link = url('/download').'/'.$file_name.'.xlsx';
        $header = array('Content-Type:application/json');
        $request_download['application_name'] = 'carta-website';
        $request_download['file']['url'] = $link;
        $request_download['file']['parent_folder'] = null;
        $curl_api = $this->sGlobal->curlAPI(
            'POST',
            $this->link_upload,
            $request_download,
            'json',
            $header
        );

        $response['status'] = ($curl_api['content']) ? true : false;
        if($response['status']) 
            $response['message'] = 'Data has been downloaded successfully.';
        else $response['message'] = $curl_api['error'];
        return $response;
    }
}
