<?php

namespace App\Services\User;

interface IUser
{
    public function findByNIK($nik);
    public function updateToken($nik, $token);
    public function get($keyword='');
    public function listUser($keyword, $start, $length, $order);
    public function all();
    public function listUserByRole($role_id);

    public function create($input);
    public function update($nik, $input);
    public function findByNIK2($nik);

    public function findUserTeritory($nik);
    public function createUserTeritory($input);
    public function deleteUserTeritory($nik, $teritory_id = null);
}