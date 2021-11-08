<?php

namespace App\Http\Controllers\Parser\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function addSite(Request $request){
        DB::table('sites')->insert(['site'=>$request->site]);
        return back();
    }



    public function main(){
        $sites = DB::table('sites')->select('site')->get();
        return view('pages.mainpage', compact('sites', $sites));
    }
}
