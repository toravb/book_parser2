<?php

namespace App\Http\Controllers\Audio;

use App\Http\Controllers\Controller;
use App\Jobs\Audio\ParseAudioNavigationJob;
use App\Jobs\Audio\ReleaseAudioAudiobookJob;
use App\Jobs\Audio\ReleaseAudioAuthorsJob;
use App\Jobs\Audio\ReleaseAudioBooksImagesJob;
use App\Jobs\Audio\ReleaseAudioBooksLinksJob;
use App\Models\AudioAudiobook;
use App\Models\AudioAuthor;
use App\Models\AudioAuthorsLink;
use App\Models\AudioBook;
use App\Models\AudioBooksLink;
use App\Models\AudioGenre;
use App\Models\AudioImage;
use App\Models\AudioLetter;
use App\Models\AudioReader;
use App\Models\AudioSeries;
use App\Models\AudioSite;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $authorJobs = DB::table('jobs')->where('queue', '=', 'audio_parse_authors')->count();
        $bookJobs = DB::table('jobs')->where('queue', '=', 'audio_parse_books')->count();
        $imageJobs = DB::table('jobs')->where('queue', '=', 'audio_parse_images')->count();
        $audioBookJobs = DB::table('jobs')->where('queue', '=', 'audio_parse_audio')->count();
        $sites = AudioSite::with(['defaultStatus', 'authorStatus', 'bookStatus', 'imageStatus', 'audioBookStatus'])->get();
        return view('audio.parsing_menu', [
            'sites' => $sites,
            'site' => $sites[0],
            'authorJobs' => $authorJobs,
            'bookJobs' => $bookJobs,
            'imageJobs' => $imageJobs,
            'audioBookJobs' => $audioBookJobs,
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

    public function startImagesParsing(AudioSite $site)
    {
        $status = $site->imageStatus()->first();
        if ($status == null){
            $status = $site->imageStatus()->create([
                'status' => 'Собираем очередь',
                'created_at' => now(),
                'status_id' => 3,
                'last_parsing' => null,
                'doParse' => 0,
                'min_count' => 0,
                'max_count' => 0,
            ]);
        }else{
            $jobs = DB::table('jobs')->where('queue', '=', 'audio_parse_images')->count();
            if ($status->doParse && $jobs > 0){
                $status->paused = !$status->paused;
                $status->save();
                if ($status->paused) {
                    return back()->with('success', 'Парсинг изображений на паузе');
                }
                return back()->with('success', 'Парсинг изображений продолжен');
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

        ReleaseAudioBooksImagesJob::dispatch($status)->onQueue('audio_default');
        return back()->with('success', 'Парсинг изображений запущен');
    }

    public function startAudioBooksParsing(AudioSite $site)
    {
        $status = $site->audioBookStatus()->first();
        if ($status == null){
            $status = $site->audioBookStatus()->create([
                'status' => 'Собираем очередь',
                'created_at' => now(),
                'status_id' => 4,
                'last_parsing' => null,
                'doParse' => 0,
                'min_count' => 0,
                'max_count' => 0,
            ]);
        }else{
            $jobs = DB::table('jobs')->where('queue', '=', 'audio_parse_audio')->count();
            if ($status->doParse && $jobs > 0){
                $status->paused = !$status->paused;
                $status->save();
                if ($status->paused) {
                    return back()->with('success', 'Парсинг аудио на паузе');
                }
                return back()->with('success', 'Парсинг аудио продолжен');
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

        ReleaseAudioAudiobookJob::dispatch($status)->onQueue('audio_default');
        return back()->with('success', 'Парсинг аудио запущен');
    }

    public function booksList()
    {
        $books = AudioBook::paginate(100);
        $sites = AudioSite::first();

        return view('audio.books_list', ['books' => $books, 'sites' => [$sites]]);
    }

    public function booksTable()
    {
        $books = AudioBook::with('image')
            ->with('genre')
            ->with('series')
            ->with('authors')
            ->with('actors')
            ->paginate(15);

        $sites = AudioSite::first();

        return view('audio.books_table', ['books' => $books, 'sites' => [$sites]]);
    }

    public function authorsList()
    {
        $authors = AudioAuthor::paginate(100);
        $sites = AudioSite::first();

        return view('audio.authors_list', ['authors' => $authors, 'sites' => [$sites]]);
    }

    public function actorsList()
    {
        $actors = AudioReader::paginate(100);
        $sites = AudioSite::first();

        return view('audio.reader_actors_list', ['actors' => $actors, 'sites' => [$sites]]);
    }

    public function booksGenre(AudioGenre $genre)
    {
        $books = $genre->books()
            ->paginate(15);
        $sites = AudioSite::first();

        return view('audio.books_table', ['books' => $books, 'sites' => [$sites]]);
    }
    public function booksSeries(AudioSeries $series)
    {
        $books = $series->books()
            ->paginate(15);
        $sites = AudioSite::first();

        return view('audio.books_table', ['books' => $books, 'sites' => [$sites]]);
    }
    public function booksFromAuthor(AudioAuthor $author)
    {
        $books = $author->books()
            ->paginate(15);
        $sites = AudioSite::first();

        return view('audio.books_table', ['books' => $books, 'sites' => [$sites]]);
    }
    public function booksFromActor(AudioReader $actor)
    {
        $books = $actor->books()
            ->paginate(15);
        $sites = AudioSite::first();

        return view('audio.books_table', ['books' => $books, 'sites' => [$sites]]);
    }
    public function booksItem(AudioBook $book)
    {
        $sites = AudioSite::first();
        return view('audio.book_item', ['book' => $book, 'sites' => [$sites]]);
    }
    public function checkErrors(AudioSite $site)
    {
        $authors_count = AudioAuthorsLink::where('doParse', '=', 2)->count();
        $books_count = AudioBooksLink::where('doParse', '=', 2)->count();
        $images_count = AudioImage::where('doParse', '=', 2)->count();
        $audio_count = AudioAudiobook::where('doParse', '=', 2)->count();

        $msg = '';
        if ($authors_count > 0){
            AudioAuthorsLink::query()->where('doParse', '=', 2)->update([
                'doParse' => 1,
            ]);
            $msg .= 'Добавлено '.$authors_count.' авторов.<br>';
        }
        if ($books_count > 0){
            AudioBooksLink::query()->where('doParse', '=', 2)->update([
                'doParse' => 1,
            ]);
            $msg .= 'Добавлено '.$books_count.' книг.<br>';
        }
        if ($images_count > 0){
            AudioImage::query()->where('doParse', '=', 2)->update([
                'doParse' => 1,
            ]);
            $msg .= 'Добавлено '.$images_count.' изображений.<br>';
        }
        if ($audio_count > 0){
            AudioAudiobook::query()->where('doParse', '=', 2)->update([
                'doParse' => 1,
            ]);
            $msg .= 'Добавлено '.$audio_count.' аудио.<br>';
        }
        if ($authors_count > 0 || $books_count > 0 || $images_count){
            $msg .= 'Запустите очереди';
        }else{
            $msg = 'Ошибок не обнаружено';
        }
        return back()->with('success', $msg);
    }
}
