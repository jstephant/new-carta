<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserTeritory;
use App\Services\User\IUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class SUser implements IUser
{
    private $user;
    private $user_teritory;
    public function __construct(User $user, UserTeritory $user_teritory)
    {
        $this->user = $user;
        $this->user_teritory = $user_teritory;
    }

    public function findByNIK($nik)
    {
        $data = array(
            'status'  => false,
            'message' => '',
            'data'    => null
        );

        $user = $this->user->select('mcs_user.*')->leftjoin('mcs_role', 'mcs_user.role_id', 'mcs_role.role_id')->where('nik', $nik)->first();
        $target = $this->user->where('nik_atasan', $nik)->orwhere('nik_atasan2', $nik)->get()->count();
        if($target > 0)
            $edit_target = 1;
        else
            $edit_target = 0;
        
        if($user)
        {
            $data['status'] = true;
            $data['data'] = array(
                'nik'           => $user->nik,
                'name'          => $user->name,
                'email'         => $user->email,
                'sales_org'     => $user->sales_org,
                'role_id'       => $user->role_id,
                'role_name'     => $user->role_name,
                'nik_atasan'    => $user->nik_atasan,
                'nik_atasan2'   => $user->nik_atasan2,
                'teritory_id'   => $user->teritory_id,
                'st'            => $user->st,
                'edit_target'   => $edit_target
            );
        }

        return $data;
    }

    public function updateToken($nik, $token)
    {
        $data = array(
            'status'  => false,
            'message' => ''
        );

        try {
            DB::beginTransaction();
            $user = $this->user->find($nik);
            $user->token = $token;
            $user->save();
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
        } catch (\Exception $e) {
            DB::rollback();
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function get($keyword = '')
    {
        $users = $this->user->select('nik', 'name')->where('st', 1)->where('role_id', 2)->where('sales_org', '4000');
        if($keyword)
        {
            $users = $users->where('nik', 'like', '%'.$keyword.'%')->orwhere('name', 'like', '%'.$keyword.'%');
        }

        $users = $users->orderby('name', 'ASC')->get();

        foreach ($users as $user) {
            $user->caption = $user->nik . ' | ' .$user->name;
        }
        
        return $users;
    }

    public function listUser($keyword, $start, $length, $order)
    {
        $users = $this->user->with(['role', 'teritory']);

        if($keyword)
        {
            $keyword = '%'.strtolower($keyword).'%';
            $users = $users->whereraw('lower(name) like ? or lower(nik) like ? ', [$keyword, $keyword]);
        }
        
        $count = $users->count();

        if($length!=-1)
        {
            $users = $users->offset($start)->limit($length);
        }

        $users = $users->get();
        foreach ($users as $value) {
            $user_teritory = $this->findUserTeritory(str_pad($value->nik, 9, '0', STR_PAD_LEFT));
            $value->user_teritory = null;
            if(count($user_teritory)>0)
            {
                $content = '';
                foreach ($user_teritory as $value2) {
                    $content .= '<span class="badge badge-default mr-2">'. $value2->teritory->name .'</span>';
                }
                $value->user_teritory = $content;
            }
        }

        $data = [
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'	          => $users->toArray(),
        ];
        
        return $data;
    }

    public function all()
    {
        return $this->user->get();
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
            $created = User::create($input);
            DB::commit();
            $data['status'] = true;
            $data['message'] = 'OK';
            $data['id'] = $input['nik'];
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    public function update($nik, $input)
    {
        $data = array(
            'status'  => false,
            'message' => '',
            'id'      => null
        );

        try {
            DB::beginTransaction();
            $updated = User::where('nik', $nik)->update($input);
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

    public function findByNIK2($nik)
    {
        return $this->user->where('nik', $nik)->first();
    }

    public function createUserTeritory($input)
    {
        $data = array(
            'status'  => false,
            'message' => '',
        );

        try {
            DB::beginTransaction();
            $created = UserTeritory::create($input);
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

    public function deleteUserTeritory($nik, $teritory_id = null)
    {
        $data = array(
            'status'  => false,
            'message' => '',
        );

        try {
            DB::beginTransaction();
            $user_teritory = UserTeritory::where('nik', $nik);
            if($teritory_id)
            {
                $user_teritory = $user_teritory->where('teritory_id', $teritory_id);
            }
            $user_teritory = $user_teritory->delete();
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

    public function findUserTeritory($nik)
    {
        return $this->user_teritory->with(['teritory'])->where('nik', $nik)->get();
    }

    public function listUserByRole($role_id)
    {
        return $this->user->where('role_id', $role_id)->get();
    }
}