<?php

namespace App\Http\Controllers\Parser\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProxySettingsController extends Controller
{
    public function index(){
        $sites = DB::table('sites')->select('site')->get();
        return view('pages.parser.proxy', compact('sites', $sites));
    }

    public function addAuthData(Request $request){
        if ($request->login != null or $request->password != null) {
            if (isset($request->login) && $request->login != null) {
                DB::table('options')->updateOrInsert(['parameter' => 'login'], ['parameter' => 'login', 'value' => $request->login]);
            }
            if (isset($request->password) && $request->password != null) {
                DB::table('options')->updateOrInsert(['parameter' => 'password'], ['parameter' => 'password', 'value' => $request->password]);
            }

            return back()->with('success', 'Обновлено');
        }
        return back()->with('error', 'Поля не могут быть пустыми');
    }
}
