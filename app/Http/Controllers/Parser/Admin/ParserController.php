<?php

namespace App\Http\Controllers\Parser\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DownloadExcelJob;
use App\Jobs\ParseImageJob;
use App\Jobs\ParsePageJob;
use App\Jobs\ParseSitemapJob;
use App\Parser\Controllers\ExcelController;
use App\Parser\Controllers\ProxyAggregator;
use Illuminate\Http\Request;
use App\Parser\Controllers\SiteMap;
use Illuminate\Support\Facades\DB;

class ParserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sites = DB::table('sites')->select()->get();
        $data = null;
        $parsingStatus = null;

        foreach ($sites as $site){
            if ($site->site == $request->site){
                $data = $site;
                $pagesStatus = DB::table('parsing_status')->where('site_id', $data->id)->select()->get();
                $statusLinks = DB::table('jobs')->where('queue', '=', 'doParseLinks')->count();
                $statusBooks = DB::table('jobs')->where('queue', '=', 'doParseBooks')->count();
                $statusPages = DB::table('jobs')->where('queue', '=', 'doParsePages')->count();
                $statusImages= DB::table('jobs')->where('queue', '=', 'doParseImages')->count();
                $statuses = [
                    'links' => $statusLinks,
                    'books' => $statusBooks,
                    'pages' => $statusPages,
                    'images' => $statusImages,
                ];
            }
        }
        return view('pages.parser.parser',['site' => $data, 'parsingStatus' => $pagesStatus, 'statuses' => $statuses] , compact('sites', $sites));
    }

/**/
    public function parseLink(Request $request)
    {
        DB::table('sites')->where('site', $request->site)->update(['doParseLinks' => true]);
        DB::table('parsing_status')->updateOrInsert(['site_id' => $request->id, 'parse_type' => 'links'],
            ['Count' => 0, 'Progress' => 0, 'last_parsing' => null]);

        return back()->with('success', 'Парсинг ссылок запущен');
    }

    public function parseBooks(Request $request)
    {
        DB::table('sites')->where('site', $request->site)->update(['doParseBooks' => !$request->doParseBooks]);

        if ($request->doParseBooks == false) {
            $count = DB::table('book_links')->where('doParse', '=', 1)->count();

            DB::table('parsing_status')->updateOrInsert(['site_id' => $request->id, 'parse_type' => 'books'],
                ['Count' => $count, 'Progress' => 0, 'last_parsing' => null]);
        }
        if ($request->doParseBooks == true) {
            DB::table('parsing_status')->updateOrInsert(['site_id' => $request->id, 'parse_type' => 'books'],['last_parsing' => now()]);
        }

        return back();
    }

    public function parsePages(Request $request){

        DB::table('sites')->where('site', $request->site)->update(['doParsePages' => !$request->doParsePages]);

        if ($request->doParsePages == false) {
            $count = DB::table('page_links')->where('doParse', '=', 1)->count();

            DB::table('parsing_status')->updateOrInsert(['site_id' => $request->id, 'parse_type' => 'page'],
                ['Count' => $count, 'Progress' => 0, 'last_parsing' => null]);
        }
        if ($request->doParsePages == true) {
            DB::table('parsing_status')->updateOrInsert(['site_id' => $request->id, 'parse_type' => 'page'],['last_parsing' => now()]);
        }


        return back();
    }

    public function parseImages(Request $request){

        DB::table('sites')->where('site', $request->site)->update(['doParseImages' => !$request->doParseImages]);

        if ($request->doParseImages == false) {
            $count = DB::table('images')->where('doParse', '=', 1)->count();

            DB::table('parsing_status')->updateOrInsert(['site_id' => $request->id, 'parse_type' => 'images'],
                ['Count' => $count, 'Progress' => 0, 'last_parsing' => null]);
        }
        if ($request->doParsePages == true) {
            DB::table('parsing_status')->updateOrInsert(['site_id' => $request->id, 'parse_type' => 'images'],['last_parsing' => now()]);
        }


        return back();
    }
    /**/

    public function parseProxy(){
        $proxy = new ProxyAggregator;
        $mess = $proxy->saveProxy();
        return back()->with('success',$mess['message']);
    }

    public function addPagesToQueue(Request $request){
        $sites = DB::table('sites')->select()->get();

        foreach ($sites as $site){
            if ($site->site == $request->site){
                $data = $site;
                $page = DB::table('product_url')->where('site_id', $data->id)->where('doParsePages', false)->update(['doParsePages' => true]);

                $count = DB::table('product_url')->where('site_id', $data->id)->where('doParsePages', '=', 1)->count();
                DB::table('parsing_status')->updateOrInsert(['site_id' => $data->id, 'parse_type' => 'page'],
                    ['Count' => $count, 'Progress' => 0, 'last_parsing' => null]);
//                $image = DB::table('images')->where('site_id', $data->id)->where('doParse', false)->update(['doParse' => true]);

                return back()->with('success', "В очередь было добавлено: $page страниц");// и $image изображений");
            }
        }
    }

}
