<?php

namespace App\Http\Controllers\Audio;

use App\Http\Controllers\Controller;
use App\Models\AudioSite;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $sites = AudioSite::with(['defaultStatus', 'authorStatus', 'bookStatus', 'imageStatus', 'audioBookStatus'])->get();
        return view('audio.parsing_menu', ['sites' => $sites, 'site' => $sites[0]]);
    }
}
