<?php

namespace App\Http\Controllers;

use App\Services\SGlobal;
use App\Services\User\SUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    private $sGlobal;
    private $sUser;
    private $sales_org;

    public function __construct(SGlobal $sGlobal, SUser $sUser)
    {
        $this->sGlobal = $sGlobal;
        $this->sUser = $sUser;
        $this->sales_org = Config::get('carta.sales_org');    
    }

    public function search(Request $request)
    {
        $users = $this->sUser->get($request->q);
        return response()->json($users, 200);
    }

    public function index()
    {
        $my_menu = $this->sGlobal->getDetailActiveMenu(Config::get('menu.menu.user'));
        $data = array(
            'title'       => 'Carta - User',
            'active_menu' => 'User',
            'my_menu'     => collect($my_menu),
        );
        return $this->sGlobal->view('user.index', $data);
    }

    public function listUser(Request $request)
    {
        $users = $this->sUser->listUser($request->keyword, $request->start, $request->length, $request->order);
        $users['draw'] = $request->draw;
        return $users;
    }
    
    public function create()
    {
        $roles = $this->sGlobal->getActiveRoles();
        $teritory = $this->sGlobal->getTeritories();
        $users = $this->sUser->all();
        $nik_list = array();
        $user_list = array();
        if(count($users)>0)
        {
            foreach ($users as $value) {
                $nik_list[] = str_pad($value->nik, 9, '0', STR_PAD_LEFT);
                $user_list[] = array(
                    'nik'  => str_pad($value->nik, 9, '0', STR_PAD_LEFT),
                    'name' => $value->name
                );
            }
        }

        $staff = $this->sGlobal->getStaff();
        foreach ($staff as $value) {
            $value->disabled = (in_array($value->NIK, $nik_list)) ? true : false;
        }

        $data = array(
            'title'       => 'Create User',
            'active_menu' => 'Create User',
            'roles'       => $roles,
            'teritory'    => $teritory,
            'staff'       => $staff,
            'sales_org'   => $this->sales_org,
            'nik_list'    => $nik_list,
            'user_list'   => $user_list
        );

        return $this->sGlobal->view('user.create', $data);
    }

    public function doCreate(Request $request)
    {
        $nik         = $request->nik;
        $name        = $request->name;
        $email       = $request->email;
        $nik_atasan  = $request->nik_atasan;
        $nik_atasan2 = $request->nik_atasan2;
        $role        = $request->role;
        $teritory    = $request->teritory;
        $sales_org   = $request->sales_org;

        $input = array(
            'nik'         => $nik,
            'name'        => $name,
            'email'       => $email,
            'role_id'     => $role,
            'nik_atasan'  => $nik_atasan,
            'nik_atasan2' => $nik_atasan2,
            'sales_org'   => $sales_org,
            'st'          => 1
        );
        
        $created = $this->sUser->create($input);
        if(!$created['status'])
        {
            alert()->error('Error', $created['message']);
            return redirect()->back()->withInput($request->all());
        }

        if(count($teritory)>0)
        {
            foreach($teritory as $value)
            {
                $input_detail = array(
                    'nik'         => $nik,
                    'teritory_id' => $value
                );

                $user_teritory = $this->sUser->createUserTeritory($input_detail);
            }
        }
        alert()->success('Success', 'Data created successfuly');
        return redirect()->route('user');
    }

    public function getDetailStaff($nik)
    {
        $staff = $this->sGlobal->getStaff($nik);
        return response()->json($staff, 200);
    }

    public function edit($id)
    {
        $roles = $this->sGlobal->getActiveRoles();
        $teritory = $this->sGlobal->getTeritories();
        $user = $this->sUser->findByNIK($id);
        $user_teritory = $this->sUser->findUserTeritory($id);
        $users = $this->sUser->all();
        $user_list = array();
        if(count($users)>0)
        {
            foreach ($users as $value) {
                $user_list[] = array(
                    'nik'  => str_pad($value->nik, 9, '0', STR_PAD_LEFT),
                    'name' => $value->name
                );
            }
        }

        $data = array(
            'title'         => 'Edit User',
            'active_menu'   => 'Edit User',
            'user'          => $user['data'],
            'user_teritory' => $user_teritory,
            'user_list'     => $user_list,
            'roles'         => $roles,
            'teritory'      => $teritory,
            'sales_org'     => $this->sales_org
        );

        return $this->sGlobal->view('user.edit', $data);
    }

    public function doUpdate(Request $request)
    {
        $nik         = $request->nik;
        $name        = $request->name;
        $email       = $request->email;
        $nik_atasan  = $request->nik_atasan;
        $nik_atasan2 = $request->nik_atasan2;
        $role        = $request->role;
        $teritory    = $request->teritory;
        $sales_org   = $request->sales_org;
        $status      = $request->status;

        $input = array(
            'nik'         => $nik,
            'name'        => $name,
            'email'       => $email,
            'role_id'     => $role,
            'nik_atasan'  => $nik_atasan,
            'nik_atasan2' => $nik_atasan2,
            'sales_org'   => $sales_org,
            'st'          => $status
        );
        
        $updated = $this->sUser->update($nik, $input);
        if(!$updated['status'])
        {
            alert()->error('Error', $updated['message']);
            return redirect()->back()->withInput($request->all());
        }

        $deleted_user_teritory = $this->sUser->deleteUserTeritory($nik);
        if(count($teritory)>0)
        {
            foreach($teritory as $value)
            {
                $input_detail = array(
                    'nik'         => $nik,
                    'teritory_id' => $value
                );

                $user_teritory = $this->sUser->createUserTeritory($input_detail);
            }
        }

        alert()->success('Success', 'Data updated successfuly');
        return redirect()->route('user');
    }
}
