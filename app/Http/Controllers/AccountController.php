<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountModel;
use Auth;

class AccountController extends Controller
{
    /**
     * Show the account index.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return response()->redirectTo("/account/dashboard");
    }

    /**
     * Show the account dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $accountModel=new AccountModel();
        $info=$accountModel->detail(Auth::user()->id);
        $feed=$accountModel->feed(Auth::user()->id);
        $extraInfo = $accountModel->getExtra(Auth::user()->id, ['gender', 'contanct', 'school', 'country', 'location'],100);
        $socialiteInfo = $accountModel->getSocialiteInfo(Auth::user()->id,100);
        return view("account.dashboard", [
            'page_title'=>"DashBoard",
            'site_title'=>config("app.name"),
            'navigation'=>"DashBoard",
            'info'=>$info,
            'userView'=>false,
            'settingsView' => false,
            'feed'=>$feed,
            'extra_info' => $extraInfo,
            'socialite_info' => $socialiteInfo,
        ]);
    }

    /**
     * Show the account dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $accountModel=new AccountModel();
        $info=$accountModel->detail(Auth::user()->id);
        if(!empty(session('last_email_send'))){
            $email_cooldown = 300 - (time() - session('last_email_send'));
        }
        $extraInfo = $accountModel->getExtra(Auth::user()->id, ['gender', 'contanct', 'school', 'country', 'location'],100);
        $socialiteInfo = $accountModel->getSocialiteInfo(Auth::user()->id,100);
        return view("account.dashboard", [
            'page_title'=>"Settings",
            'site_title'=>config("app.name"),
            'navigation'=>"Settings",
            'info'=>$info,
            'userView'=>false,
            'settingsView' => true,
            'email_cooldown' => $email_cooldown ?? null,
            'extra_info' => $extraInfo,
            'socialite_info' => $socialiteInfo,
        ]);
    }
}
