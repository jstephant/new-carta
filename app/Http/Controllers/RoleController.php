<?php

namespace App\Http\Controllers;

use App\Services\Menu\SMenu;
use App\Services\Role\SRole;
use App\Services\SGlobal;
use App\Services\User\SUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    private $sGlobal;
    private $sRole;
    private $menu_carta_id;
    private $sMenu;
    private $sUser;

    public function __construct(SGlobal $sGlobal, SRole $sRole, SMenu $sMenu, SUser $sUser)
    {
        $this->sGlobal = $sGlobal;
        $this->sRole = $sRole;
        $this->sMenu = $sMenu;
        $this->sUser = $sUser;
        $this->menu_carta_id = Config::get('menu.menu.role');
    }

    public function index()
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $my_menu = $this->sGlobal->getDetailActiveMenu($this->menu_carta_id);
        
        $roles = $this->sRole->getAll();
        
        $menu_carta_role = $this->sRole->getActiveAll();

        $data = array(
            'title'           => 'Role',
            'active_menu'     => 'Role',
            'my_menu'         => collect($my_menu),
            'roles'           => $roles,
            'menu_carta_role' => $menu_carta_role
        );
        
        return $this->sGlobal->view('role.index', $data);
    }

    public function listRole(Request $request)
    {
        $roles = $this->sRole->listRole($request->keyword, $request->start, $request->length, $request->order);
        $roles['draw'] = $request->draw;
        return $roles;
    }

    public function listPermission(Request $request)
    {
        $role = $this->sRole->findDetailById($request->id);
        if(count($role)==0)
        {
            $roles = array(
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'draw'            => $request->draw
            );
            return $roles;
        }

        $menu = array();
        $features = $this->sMenu->getMenuByParent(0, false);

        foreach ($features as $value) {
			$detail = $this->sRole->findDetailById($request->id, $value->id);
            $menu[] = array(
				'id' 		         => $value->id,
				'name' 		         => $value->name,
				'parent_id'          => $value->parent_id,
				'is_view'	         => $value->is_view,
				'is_create'	         => $value->is_create,
				'is_edit'	         => $value->is_edit,
				'is_delete'	         => $value->is_delete,
				'is_approval'        => $value->is_approval,
                'detail_is_view'     => ($detail) ? $detail->is_view : false,
                'detail_is_create'   => ($detail) ? $detail->is_create : false,
                'detail_is_edit'     => ($detail) ? $detail->is_edit : false,
                'detail_is_delete'   => ($detail) ? $detail->is_delete : false,
                'detail_is_approval' => ($detail) ? $detail->is_approval : false
            );
            if($value->has_sub_menu===true)
            {
                $sub_menu = $this->sMenu->getMenuByParent($value->id, false);
                foreach ($sub_menu as  $value2) {
                    $detail2 = $this->sRole->findDetailById($request->id, $value2->id);
                    $menu[] = array(
                        'id' 		         => $value2->id,
                        'name' 		         => $value2->name,
                        'parent_id'          => $value2->parent_id,
						'is_view'	         => $value2->is_view,
                        'is_create'	         => $value2->is_create,
                        'is_edit'	         => $value2->is_edit,
                        'is_delete'	         => $value2->is_delete,
                        'is_approval'        => $value2->is_approval,
                        'detail_is_view'     => ($detail2) ? $detail2->is_view : false,
                        'detail_is_create'   => ($detail2) ? $detail2->is_create : false,
                        'detail_is_edit'     => ($detail2) ? $detail2->is_edit : false,
                        'detail_is_delete'   => ($detail2) ? $detail2->is_delete : false,
                        'detail_is_approval' => ($detail2) ? $detail2->is_approval : false
                    );
                }
            }
        }

        $roles = array(
            'recordsTotal'    => count($menu),
            'recordsFiltered' => count($menu),
            'data'            => $menu,
            'draw'            => $request->draw
        );
        return $roles;
    }

    public function create()
    {
        if(!Session::has('nik'))
        {
            return redirect('/');
        }

        $menu = array();
        $features = $this->sMenu->getMenuByParent(0, false);

        foreach ($features as $value) {
            $menu[] = array(
				'id' 		  => $value->id,
				'name' 		  => $value->name,
				'parent_id'   => $value->parent_id,
                'is_view'     => $value->is_view,
                'is_create'   => $value->is_create,
                'is_edit'     => $value->is_edit,
                'is_delete'   => $value->is_delete,
                'is_approval' => $value->is_approval
            );
            if($value->has_sub_menu===true)
            {
                $sub_menu = $this->sMenu->getMenuByParent($value->id, false);
                foreach ($sub_menu as  $value2) {
                    $menu[] = array(
                        'id' 		  => $value2->id,
                        'name' 		  => $value2->name,
                        'parent_id'   => $value2->parent_id,
                        'is_view'     => $value2->is_view,
                        'is_create'   => $value2->is_create,
                        'is_edit'     => $value2->is_edit,
                        'is_delete'   => $value2->is_delete,
                        'is_approval' => $value2->is_approval
                    );
                }
            }
        }
        
        $data = array(
            'title'       => 'Create Role',
            'active_menu' => 'Create Role',
			'features'	  => json_decode(json_encode($menu), true)
        );
        return $this->sGlobal->view('role.create', $data);
    }

	public function doCreate(Request $request)
	{
		$role_name = $request->name;

		$menu_carta_id = $request->menu_carta_id;
		$view = $request->view_value;
		$create = $request->create_value;
		$edit = $request->edit_value;
		$delete = $request->delete_value;
		$approval = $request->approval_value;

		$input = array(
			'role_name' => $role_name,
			'st'        => 1
		);

		$created = $this->sRole->create($input);
		if(!$created['status'])
		{
            return redirect()->back()->withInput($request->all())
                                     ->with('error', $created['message']);
		}

		$role_id = $created['id'];

		foreach ($menu_carta_id as $key => $value) {
			$input_detail = array(
				'role_id'	      => $role_id,
				'menu_carta_id'   => $value,
				'is_view' 	      => ($view[$key]==1) ? true : false,
				'is_create' 	  => ($create[$key]==1) ? true : false,
				'is_edit' 		  => ($edit[$key]==1) ? true : false,
				'is_delete' 	  => ($delete[$key]==1) ? true : false,
				'is_approval' 	  => ($approval[$key]==1) ? true : false
            );

			$create_detail = $this->sRole->createDetail($input_detail);
		}

		return redirect()->route('role')->with('success', 'Role created successfuly')->with('title', 'Success');
	}

	public function edit($id)
	{
		if(!Session::has('nik'))
        {
            return redirect('/');
        }

		$role = $this->sRole->findById($id);
        $menu = array();
        $features = $this->sMenu->getMenuByParent(0, false);
        foreach ($features as $value) {
			$detail = $this->sRole->findDetailById($id, $value->id);
            $menu[] = array(
				'id' 		         => $value->id,
				'name' 		         => $value->name,
				'parent_id'          => $value->parent_id,
				'is_view'	         => $value->is_view,
				'is_create'	         => $value->is_create,
				'is_edit'	         => $value->is_edit,
				'is_delete'	         => $value->is_delete,
				'is_approval'        => $value->is_approval,
                'detail_is_view'     => ($detail) ? $detail->is_view : false,
                'detail_is_create'   => ($detail) ? $detail->is_create : false,
                'detail_is_edit'     => ($detail) ? $detail->is_edit : false,
                'detail_is_delete'   => ($detail) ? $detail->is_delete : false,
                'detail_is_approval' => ($detail) ? $detail->is_approval : false
            );
            if($value->has_sub_menu===true)
            {
                $sub_menu = $this->sMenu->getMenuByParent($value->id, false);
                foreach ($sub_menu as  $value2) {
                    $detail2 = $this->sRole->findDetailById($id, $value2->id);
                    $menu[] = array(
                        'id' 		         => $value2->id,
                        'name' 		         => $value2->name,
                        'parent_id'          => $value2->parent_id,
						'is_view'	         => $value2->is_view,
                        'is_create'	         => $value2->is_create,
                        'is_edit'	         => $value2->is_edit,
                        'is_delete'	         => $value2->is_delete,
                        'is_approval'        => $value2->is_approval,
                        'detail_is_view'     => ($detail2) ? $detail2->is_view : false,
                        'detail_is_create'   => ($detail2) ? $detail2->is_create : false,
                        'detail_is_edit'     => ($detail2) ? $detail2->is_edit : false,
                        'detail_is_delete'   => ($detail2) ? $detail2->is_delete : false,
                        'detail_is_approval' => ($detail2) ? $detail2->is_approval : false
                    );
                }
            }
        }
        
        $data = array(
            'title'       => 'Edit Role',
            'active_menu' => 'Edit Role',
			'features'	  => json_decode(json_encode($menu), true),
			'role'		  => $role
        );
        return $this->sGlobal->view('role.edit', $data);		
	}

	public function doUpdate(Request $request)
	{
		$role_id = $request->role_id;
		$role_name = $request->name;
		$status = $request->status;
		
		$menu_carta_id = $request->menu_carta_id;
		$view = $request->view_value;
		$create = $request->create_value;
		$edit = $request->edit_value;
		$delete = $request->delete_value;
		$approval = $request->approval_value;

		$input = array(
			'name' => $role_name,
			'st'   => $status
		);

		$updated = $this->sRole->update($role_id, $input);
		if(!$updated['status'])
		{
			return redirect()->back()->withInput($request->all())
                                     ->with('error', $updated['message']);
		}

        $deleted_detail = $this->sRole->deleteDetail($role_id);
		foreach ($menu_carta_id as $key => $value) {
			$input_detail = array(
                'role_id'       => $role_id,
                'menu_carta_id' => $value,
				'is_view'       => ($view[$key]==1) ? true : false,
				'is_create'     => ($create[$key]==1) ? true : false,
				'is_edit' 	    => ($edit[$key]==1) ? true : false,
				'is_delete'     => ($delete[$key]==1) ? true : false,
				'is_approval'   => ($approval[$key]==1) ? true : false
			);

			$create_detail = $this->sRole->createDetail($input_detail);
		}

		return redirect()->route('role')->with('success', 'Role updated')->with('title', 'Success');
	}

	public function doDelete(Request $request)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        $id = $request->id;
        $role_id = $request->session()->get('role_id');
        
        // check autorization
        $authorize = $this->sMenu->getUserPermission($role_id, $this->menu_carta_id, 'is_delete');
        if(!$authorize)
        {
            $data['message'] = 'You are not authorized';
            return response()->json($data, 200);
        }

        $role = $this->sRole->findById($id);
        if(!$role)
        {
            $data['message'] = 'Role not found';
            return response()->json($data, 200);
        }

        $users = $this->sUser->listUserByRole($id);
        if(count($users)>0)
        {
            $data['message'] = 'Role is in used';
            return response()->json($data, 200);
        }

        $deleted = $this->sRole->delete($id);
        if(!$deleted['status'])
        {
            $data['message'] = 'Error in delete role';
            return response()->json($data, 200);
        }

        $data['status'] = $deleted['status'];
        $data['message'] = 'Role Deleted Successfully';
        return response()->json($data, 200);
    }
}
