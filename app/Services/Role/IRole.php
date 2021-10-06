<?php

namespace App\Services\Role;

interface IRole
{
    public function listRole($keyword, $start, $length, $order);
    public function listPermission($role_id, $start, $length, $order);
    public function getAll();
    public function getActiveAll();
    public function findById($id);

    // feature Role
    public function createDetail($input);
    public function updateDetail($role_id, $menu_carta_id, $input);
    public function deleteDetail($role_id, $menu_carta_id=null);
    public function findDetailById($role_id, $menu_carta_id=null);
}