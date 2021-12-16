<?php

namespace App\Http\Controllers\Audio;

use App\Http\Controllers\Controller;
use App\Jobs\Audio\ParseAudioNavigationJob;
use App\Jobs\Audio\ReleaseAudioAuthorsJob;
use App\Jobs\Audio\ReleaseAudioBooksLinksJob;
use App\Models\AudioAuthorsLink;
use App\Models\AudioLetter;
use App\Models\AudioSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $authorJobs = DB::table('jobs')->where('queue', '=', 'audio_parse_authors')->count();
        $bookJobs = DB::table('jobs')->where('queue', '=', 'audio_parse_books')->count();
        $sites = AudioSite::with(['defaultStatus', 'authorStatus', 'bookStatus', 'imageStatus', 'audioBookStatus'])->get();
        return view('audio.parsing_menu', [
            'sites' => $sites,
            'site' => $sites[0],
            'authorJobs' => $authorJobs,
            'bookJobs' => $bookJobs
        ]);
    }

    public function startDefaultParsing(AudioSite $site)
    {
        $status = $site->defaultStatus()->first();
        if ($status == null){
            $status = $site->defaultStatus()->create([
                'last_parsing' => null,
                'status_id' => 0,
                'updated_at' => null,
                'created_at' => null,
            ]);
        }
        $status->doParse = !$status->doParse;
        $status->save();

        if ($status->doParse) {
            AudioLetter::query()->update(['doParse' => 1]);
            AudioAuthorsLink::query()->update(['doParse' => 1]);
            ParseAudioNavigationJob::dispatch($status)->onQueue('audio_default');
            return back()->with('success', 'Обход по сайту запущен');
        }
    }

    public function startAuthorsParsing(AudioSite $site)
    {
        $status = $site->authorStatus()->first();
        if ($status == null){
            $status = $site->authorStatus()->create([
                'status' => 'Собираем очередь',
                'created_at' => now(),
                'status_id' => 1,
                'last_parsing' => null,
                'doParse' => 0,
                'min_count' => 0,
                'max_count' => 0,
            ]);
        }else{
            $jobs = DB::table('jobs')->where('queue', '=', 'audio_parse_authors')->count();
            if ($status->doParse && $jobs > 0){
                $status->paused = !$status->paused;
                $status->save();
                if ($status->paused) {
                    return back()->with('success', 'Парсинг авторов на паузе');
                }
                return back()->with('success', 'Парсинг авторов продолжен');
            }else {
                $status->update([
                    'status' => 'Собираем очередь',
                    'created_at' => now(),
                    'last_parsing' => null,
                    'doParse' => 0,
                    'min_count' => 0,
                    'max_count' => 0,
                    'paused' => 0,
                ]);
            }
        }

        ReleaseAudioAuthorsJob::dispatch($status)->onQueue('audio_default');
        return back()->with('success', 'Парсинг авторов запущен');
    }

    public function startBooksParsing(AudioSite $site)
    {
        $status = $site->bookStatus()->first();
        if ($status == null){
            $status = $site->authorStatus()->create([
                'status' => 'Собираем очередь',
                'created_at' => now(),
                'status_id' => 2,
                'last_parsing' => null,
                'doParse' => 0,
                'min_count' => 0,
                'max_count' => 0,
            ]);
        }else{
            $jobs = DB::table('jobs')->where('queue', '=', 'audio_parse_books')->count();
            if ($status->doParse && $jobs > 0){
                $status->paused = !$status->paused;
                $status->save();
                if ($status->paused) {
                    return back()->with('success', 'Парсинг книг на паузе');
                }
                return back()->with('success', 'Парсинг книг продолжен');
            }else {
                $status->update([
                    'status' => 'Собираем очередь',
                    'created_at' => now(),
                    'last_parsing' => null,
                    'doParse' => 0,
                    'min_count' => 0,
                    'max_count' => 0,
                    'paused' => 0,
                ]);
            }
        }

        ReleaseAudioBooksLinksJob::dispatch($status)->onQueue('audio_default');
        return back()->with('success', 'Парсинг книг запущен');
    }
}
