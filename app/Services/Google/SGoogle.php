<?php

namespace App\Services\Google;

use App\Services\Google\IGoogle;
use Google_Service_Sheets_BatchUpdateSpreadsheetRequest;
use Google_Service_Sheets_ClearValuesRequest;
use Google_Service_Sheets_CopySheetToAnotherSpreadsheetRequest;

class SGoogle implements IGoogle
{
    public function  getSheetById($service, $id)
    {
        return $service->spreadsheets->get($id);
    }

    public function renameSheet($service, $spreadsheetId)
    {
        $s = $this->getSheetById($service, $spreadsheetId);
        $total_sheet = count($s->sheets);
        $new_sheet_id = $s->sheets[count($s->sheets)-1]->properties->sheetId;
        $title = strval($total_sheet);
        $body = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
            array(
                'requests' => array(
                    'updateSheetProperties' => array(
                        'properties' => array(
                            'title' => $title,
                            'sheetId' => $new_sheet_id
                        ),
                        "fields" => "title"
                    )
                )
        ));
        
        return $service->spreadsheets->batchUpdate($spreadsheetId, $body);
    }

    public function copyTo($service, $spreadsheetId, $destinationsheetId)
    {
        $sheetId = 0;
        $requestBody = new Google_Service_Sheets_CopySheetToAnotherSpreadsheetRequest(
            array("destinationSpreadsheetId" => $destinationsheetId)
        );

        return $service->spreadsheets_sheets->copyTo($spreadsheetId, $sheetId, $requestBody);
    }

    public function clearValues($service, $spreadsheetId, $range)
    {
        $requestBody = new Google_Service_Sheets_ClearValuesRequest();
        return $service->spreadsheets_values->clear($spreadsheetId, $range, $requestBody);
    }
}