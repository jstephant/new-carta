<?php

namespace App\Services\Menu;

use App\Models\MenuCarta;
use App\Models\MenuCartaRole;
use App\Services\Menu\IMenu;

class SMenu implements IMenu
{
    private $menuCarta;
    private $menuCartaRole;

    public function __construct(MenuCarta $menuCarta, MenuCartaRole $menuCartaRole)
    {
        $this->menuCarta = $menuCarta;
        $this->menuCartaRole = $menuCartaRole;    
    }

    public function getActiveByRole($role_id)
    {
        return $this->menuCartaRole->with(['menu_carta'])
                    ->whereHas('menu_carta', function($q){
                        $q->where('is_active', true);
                    })
                    ->where('role_id', $role_id)
                    ->get();
    }

    public function getMainMenuByRole($role_id)
    {
        return $this->menuCartaRole->with(['menu_carta'])
                    ->whereHas('menu_carta', function($q){
                        $q->where('is_active', true)
                          ->where('is_main_menu', true)
                          ->wherenull('parent_id')
                          ->orderby('sequence');
                    })
                    ->where('role_id', $role_id)
                    ->get();
    }

    public function getSubMenuByParent($role_id, $parent_id)
    {
        return $this->menuCartaRole->with(['menu_carta'])
                    ->whereHas('menu_carta', function($q) use($parent_id){
                        $q->where('is_active', true)
                          ->where('is_main_menu', true)
                          ->where('parent_id', $parent_id)
                          ->orderby('sequence');
                    })
                    ->where('role_id', $role_id)
                    ->get();
    }

    public function getMenuByParent($parent_id=0, $is_main_menu=true)
    {
        if($is_main_menu)
            return $this->menuCarta->where('is_active', true)->where('parent_id', $parent_id)->where('is_main_menu', true)->orderby('sequence', 'asc')->get();
        else return $this->menuCarta->where('is_active', true)->where('parent_id', $parent_id)->orderby('sequence', 'asc')->get();
    }

    public function getUserPermission($role_id, $menu_carta_id, $action = null)
    {
        $menu_carta_role =  $this->menuCartaRole
                              ->where('role_id', $role_id)
                              ->where('menu_carta_id', $menu_carta_id);
        if($action)
        {
            $menu_carta_role = $menu_carta_role->where($action, true);
        }

        return $menu_carta_role->first();
    }
}