<?php

namespace App\Http\Controllers\Admin;

use App\Api\Http\Controllers\AudioBookController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Models\AudioBook;
use App\Models\Book;
use App\Models\Image;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home.index');
    }
}
