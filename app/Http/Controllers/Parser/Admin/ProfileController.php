<?php

namespace App\Http\Controllers\Parser\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index(){
        $sites = DB::table('sites')->select()->get();
        return view('pages.changepass', compact('sites', $sites));
    }

    public function change(Request $request){
        if ($request->email == null){
            return back()->with('error', 'Поле email не может быть пустым');
        }
        $user = $request->user();
        $user->edit($request->all());
        return back()->with('success', 'Обновлено');
    }
}
