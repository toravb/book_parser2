<?php

namespace App\Http\Controllers\Parser\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Page;
use App\Models\Parser_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sites = DB::table('sites')->select('site')->get();
        $books = Book::with('authors')->with('publishers')->with('image')->with('series')->paginate(15);
        return view('pages.parser.page', ['books' => $books], compact('sites', $sites));
    }

    public function showProxies(){
        $sites = DB::table('sites')->select('site')->get();
        $proxies = DB::table('proxies')->paginate(100);
        return view('pages.parser.showproxy', ['items' => $proxies], compact('sites', $sites));
    }

    public function books()
    {
        $books = Book::select(['id', 'title'])->paginate(50);
        $sites = DB::table('sites')->select('site')->get();
        return view('pages.parser.book',['books' => $books], compact('sites', $sites));
    }

    public function booksPages($id)
    {
        $sites = DB::table('sites')->select('site')->get();
        $book = Book::find($id);
        $pages = Page::where('book_id', $id)->orderBy('page_number')->paginate(1);
        return view('pages.parser.book_item',['pages' => $pages, 'book' => $book], compact('sites', $sites));
    }
}
