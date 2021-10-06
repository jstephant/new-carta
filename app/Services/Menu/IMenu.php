<?php

namespace App\Services\Menu;

interface IMenu
{
    // menu carta
    public function getActiveByRole($role_id);
    public function getMainMenuByRole($role_id);
    public function getSubMenuByParent($role_id, $parent_id);
    public function getMenuByParent($parent_id=0, $is_main_menu=true);
    public function getUserPermission($role_id, $menu_carta_id, $action=null);
}