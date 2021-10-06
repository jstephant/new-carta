<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Menu\SMenu;
use App\Services\Role\SRole;
use App\Services\SGlobal;
use App\Services\User\SUser;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    private $sglobal;
    private $user;
    private $sMenu;
    private $sRole;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SGlobal $sglobal, SUser $user, SMenu $sMenu, SRole $sRole)
    {
        $this->middleware('guest')->except('logout');
        $this->sglobal = $sglobal;
        $this->user = $user;
        $this->sMenu = $sMenu;
        $this->sRole = $sRole;
    }

    public function viewLogin(){
        if(Session::has('nik'))
        {
            $link = $this->sglobal->getActiveLink();
            return redirect($link);
        }
        
        return $this->sglobal->view('auth.login');
    }

    public function doLogin(Request $request) {
        $data=array(
            'code'    => 400, 
            'message' => '',
            'data'    => null,
        );

        $nik = $request->nik;
        $password = $request->password;
        $token = $request->token;

        $url = 'https://apps.vivere.co.id/vservice/action/login/';

        $auth = "d6d344759f92627372aa381f3ed0e2d7";
        $url = $url.$auth."/".$nik."/".$password;
        $grab = $this->sglobal->curlAPI('GET', $url);
        $hasil = $grab['content'];

        if($hasil->result == 'false')
        {
            alert()->error('Error', 'Invalid NIK or password');
            return redirect('/');
        }

        $updateToken = $this->user->updateToken($nik, $token);
        $user = $this->user->findByNIK($nik);
        if(!$user['status'])
        {
            alert()->error('Error', 'User not authorized to access this application');
            return redirect('/');
        }

        $active_menu = $this->sMenu->getActiveByRole($user['data']['role_id']);
        if(count($active_menu)==0)
        {
            alert()->error('Error', 'You don\'t have access.');
            return redirect('/');
        }
        
        $main_menu = $this->setMenu($user['data']['role_id']);

        if($request->session()->has('user_name')) $request->session()->forget('user_name');
        if($request->session()->has('nik')) $request->session()->forget('nik');
        if($request->session()->has('role_id')) $request->session()->forget('role_id');
        if($request->session()->has('active_menu')) $request->session()->forget('active_menu');
        if($request->session()->has('main_menu')) $request->session()->forget('main_menu');
        $request->session()->put('user_name', $user['data']['name']);
        $request->session()->put('nik', $user['data']['nik']);
        $request->session()->put('role_id', $user['data']['role_id']);
        $request->session()->put('active_menu', $active_menu);
        $request->session()->put('main_menu', $main_menu);
        
        $data['status'] = true;
        $data['message'] = 'Login Success';
        $data['data'] = $user['data'];
        
        $link = $this->sglobal->getActiveLink();
        return redirect($link);
    }

    public function logout()
    {
        // Auth::logout();
        Session::forget('message');
        Session::forget('errors');
        Session::forget('nik');
        Session::forget('name');
        Session::forget('role_id');
        Session::forget('main_menu');
        Session::forget('active_menu');
        return Redirect::to('/');
    }

    public function setMenu($role_id)
    {
        $main_menu = $this->sMenu->getMenuByParent();
        $content = "";
        if($main_menu) 
        {
            $arr_parent = array();
            foreach ($main_menu as $value) {
                $menu_active = false;
                $detail = $this->sRole->findDetailById($role_id, $value->id);
                if(!$value->has_sub_menu)
                {
                    if($detail->is_view) $menu_active = true;
                } else {
                    $sub_menu = $this->sMenu->getMenuByParent($value->id);
                    foreach ($sub_menu as $item) {
                        $detail2 = $this->sRole->findDetailById($role_id, $item->id);
                        if($detail2->is_view) {
                            $menu_active = true;
                            break;
                        }
                    }
                }

                $arr_parent[] = $menu_active;
            }

            foreach ($main_menu as $key => $value) {
                $detail = $this->sRole->findDetailById($role_id, $value->id);
                $content .= '<li class="nav-item">';
                if(!$value->has_sub_menu)
                {
                    if($detail->is_view)
                    {
                        $content .= '<a class="nav-link" href="' . url($value->link) . '">';
                        $content .= ($value->icon) ? $value->icon : '';
                        $content .= '<span class="nav-link-text">'. $value->name . '</span>';
                        $content .= '</a>';
                    }
                } else {
                    $sub_menu = $this->sMenu->getMenuByParent($value->id);

                    if($arr_parent[$key])
                    {
                        $content .= '<a class="nav-link collapsed" href="#navbar-'. $value->id . '" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-dashboards">';
                        $content .= ($value->icon) ? $value->icon : '';
                        $content .= '<span class="nav-link-text">'. $value->name . '</span>';
                        $content .= '</a>';
                        $content .= '<div class="collapse" id="navbar-'.  $value->id.'" style="">';
                        $content .= '<ul class="nav nav-sm flex-column">';
                        foreach ($sub_menu as $item) {
                            $detail2 = $this->sRole->findDetailById($role_id, $item->id);
                            if($detail2->is_view) {
                                $content .= '<li class="nav-item">';
                                $content .= '<a href="'. url($item->link) . '" class="nav-link ml-2">';
                                $content .= ($item->icon) ? $item->icon : '';
                                $content .= '<span class="nav-link-text">' . $item->name . '</span>'; 
                                $content .= '</a>';
                                $content .= '</li>';
                            }
                        }
                    }
                    
                    $content .= '</ul>';
                    $content .= '</div>';
                }
                
                $content .= '</li>';
            }
        }
        
        return $content;

        // $main_menu = $this->sMenu->getMainMenuByRole($role_id);
        // $content = "";
        // if($main_menu) 
        // {
        //     foreach ($main_menu as $value) {
        //         if($value->is_view) {
        //             $content .= '<li class="nav-item">';
        //             if(!$value->menu_carta->has_sub_menu)
        //             {
        //                 $content .= '<a class="nav-link" href="' . url($value->menu_carta->link) . '">';
        //                 $content .= ($value->menu_carta->icon) ? $value->menu_carta->icon : '';
        //                 $content .= '<span class="nav-link-text">'. $value->menu_carta->name . '</span>';
        //                 $content .= '</a>';
        //             } else {
        //                 $sub_menu = $this->sMenu->getSubMenuByParent($role_id, $value->menu_carta_id);
    
        //                 $content .= '<a class="nav-link collapsed" href="#navbar-'. $value->menu_carta_id . '" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-dashboards">';
        //                 $content .= ($value->menu_carta->icon) ? $value->menu_carta->icon : '';
        //                 $content .= '<span class="nav-link-text">'. $value->menu_carta->name . '</span>';
        //                 $content .= '</a>';
        //                 $content .= '<div class="collapse" id="navbar-'.  $value->menu_carta_id.'" style="">';
        //                 $content .= '<ul class="nav nav-sm flex-column">';
        //                 foreach ($sub_menu as $item) {
        //                     if($item->is_view) {
        //                         $content .= '<li class="nav-item">';
        //                         $content .= '<a href="'. url($item->menu_carta->link) . '" class="nav-link">';
        //                         $content .= '<span class="sidenav-mini-icon">';
        //                         $content .= ($item->menu_carta->icon) ? $item->menu_carta->icon : '';
        //                         $content .= '</span>';
        //                         $content .= '<span class="sidenav-normal">' . $item->menu_carta->name . '</span>'; 
        //                         $content .= '</a>';
        //                         $content .= '</li>';
        //                     }
        //                 }
        //                 $content .= '</ul>';
        //                 $content .= '</div>';
        //             }
                    
        //             $content .= '</li>';
        //         }
        //     }
        // }
        
        // return $content;
    }
}
