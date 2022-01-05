<?php

namespace App\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    public function documentation()
    {
        return view('documentation');
    }
}
