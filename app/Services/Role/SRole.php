<?php

namespace App\Services\Role;

use App\Models\MenuCartaRole;
use App\Models\MenuCarta;
use App\Models\Role;
use App\Services\Log\SLog;
use App\Services\Role\IRole;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SRole implements IRole
{
    private $roles;
    private $menuCarta;
    private $menuCartaRole;

    public function __construct(Role $roles, MenuCarta $menuCarta, MenuCartaRole $menuCartaRole)
    {
        $this->roles = $roles;
        $this->menuCarta = $menuCarta;
        $this->menuCartaRole = $menuCartaRole;
    }

    public function create($input)
    {
        $data = array(
            'status'  => false,
            'message' => '',
            'id'      => null
        );

        try {
            DB::beginTransaction();
            $created = Role::create($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
            $data['id'] = $created->role_id;
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function update($id, $input)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $updated = Role::where('role_id', $id)->update($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function delete($id)
    {
        $data = array(
            'status' => false,
            'message' => ''
        );

        $deleted_by = Session::get('nik');

        try {
            DB::beginTransaction();
            $deleted = Role::where('role_id', $id)->first();
            $deleted->st = 0;
            $deleted->save();
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function createDetail($input)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $created = MenuCartaRole::create($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function updateDetail($role_id, $menu_carta_id, $input)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $updated = MenuCartaRole::where('role_id', $role_id)->where('menu_carta_id', $menu_carta_id)->update($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function deleteDetail($role_id, $menu_carta_id = null)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            if($menu_carta_id)
                $deleted = MenuCartaRole::where('role_id', $role_id)->where('menu_carta_id', $menu_carta_id)->delete();
            else $deleted = MenuCartaRole::where('role_id', $role_id)->delete();
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function listRole($keyword, $start, $length, $order)
    {
        $roles = $this->roles;
        if($keyword)
        {
            $keyword = '%'.$keyword.'%';
            $roles = $roles->where('name', 'like', $keyword);
        }

        $count = $roles->count();

        if($length!=-1)
        {
            $roles = $roles->offset($start)->limit($length);
        }

        if(count($order)>0)
        {
            switch ($order[0]['column']) {
                case 0:
                    $roles = $roles->orderby('name', $order[0]['dir']);
                    break;
                default:
                    $roles = $roles->orderby('name', $order[0]['dir']);
                    break;
            }
        }

        $roles = $roles->get();

        $data = array(
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => $roles->toArray()  
        );

        return $data;
    }

    public function getAll()
    {
        return $this->roles->get();
    }

    public function getActiveAll()
    {
        $result = DB::table('menu_carta_role')
                    ->leftjoin('menu_carta', 'menu_carta_role.menu_carta_id', 'menu_carta.id')
                    ->whereraw('menu_carta.is_active = true')
                    ->orderby('role_id', 'asc')
                    ->orderby('menu_carta.parent_id', 'asc')
                    ->orderby('menu_carta.sequence', 'asc')
                    ->get();
        return $result;
    }

    public function findById($id)
    {
        return $this->roles->with(['menu_carta_role'])->where('role_id', $id)->first();
    }

    public function findDetailById($role_id, $menu_carta_id=null)
    {
        if($menu_carta_id)
            return $this->menuCartaRole->where('role_id', $role_id)->where('menu_carta_id', $menu_carta_id)->first();
        else return $this->menuCartaRole->where('role_id', $role_id)->get();
    }

    public function listPermission($role_id, $start, $length, $order)
    {
        if($length==-1)
            $result = DB::table('menu_carta_id')
                        ->leftjoin('menu_carta', 'menu_carta_role.menu_carta_id', 'menu_carta.id')
                        ->where('role_id', $role_id)
                        ->orderby('role_id', 'asc')
                        ->orderby('menu_carta_id', 'asc')
                        ->orderby('menu_carta.sequence', 'asc');

        $count = $result->count();

        $result = $result->get();
        $data = array(
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => $result->toArray()  
        );

        return $data;
    }
}