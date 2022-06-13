<?php

namespace App\Http\Controllers;

use App\Models\BookLink;
use Exception;
use Illuminate\Support\Facades\DB;

include_once app_path('Modules/simple_html_dom.php');

class BookParserController extends Controller
{
    private const DOMAIN = 'http://loveread.ec/';

    public static function parseBooksList($let)
    {
        $url = self::DOMAIN.'letter_nav.php?let='.$let;

        $links = [];
        $response = file_get_contents($url);
        $html = str_get_html($response);
        foreach ($html->find('ul.let_ul') as $element){
            foreach ($element->find('a.letter_nav_s') as $link){
                $uri = self::DOMAIN . trim($link->getAttribute('href'));
                $donor_id = explode('id=', $uri);
                $links[] = [
                    'link' => $uri,
                    'donor_id' => @end($donor_id),
                ];
            }
            break;
        }
        return $links;
    }

    public static function parseBook($url, $donor_id)
    {
        $response = file_get_contents($url);
        $html = str_get_html($response);
        $params = [
            'genre' => null,
            'series' => null,
            'author' => null,
            'title' => null,
            'year' => null,
            'params' => null,
            'publisher' => null,
            'preview_text' => null,
            'book_anchors' => null,
            'book_pages_link' => null,
            'preview_image' => null,
        ];
        foreach ($html->find('table.table_view_gl tr.td_top_color p') as $table){
            if ($table->tag == 'p') {
                $converted = $table->text();
                $l_genre = mb_substr($converted, 5);
                $params['genre'] = $l_genre;
            }
            break;
        }
        foreach ($html->find('table.table_view_gl tr.td_center_color td.span_str p') as $table){
            if ($table->tag == 'p') {
                foreach ($table->find('img') as $image){
                    if ($image->tag == 'img') {
                        $params['preview_image'] = self::DOMAIN . trim($image->getAttribute('src'));
                    }
                    break;
                }
                $content = trim($table->text());
                $replaced = preg_replace('/\s\s+/', '||=|==|=||', $content);
                $self_params = explode('||=|==|=||', $replaced);
                $self_params = array_filter($self_params);
                $local_params = [];

                for ($i = 0; $i < count($self_params); $i++) {
                    if ($self_params[$i] == 'Серия:') {
                        $params['series'] = $self_params[$i + 1];
                        $local_params['series'] = $self_params[$i + 1];
                        $i++;
                        continue;
                    }
                    if ($self_params[$i] == 'Автор:') {
                        $authors = [
                        ];

                        $c_content = trim($self_params[$i+1]);
                        while (
                            $c_content != 'Название:'
                            &&
                            $c_content != 'Серия:'
                            &&
                            $c_content != 'Издательство:'
                            &&
                            $c_content != 'Год:'
                            &&
                            $c_content != 'ISBN:'
                            &&
                            $c_content != 'Тираж:'
                            &&
                            $c_content != 'Формат:'
                            &&
                            $c_content != 'Перевод книги:'
                            &&
                            $c_content != 'Язык:'
                            &&
                            $c_content != 'Жанр:'
                        ){
                            $authors[] = $c_content;
                            $i++;
                            if (!isset($self_params[$i+1])){
                                return false;
                            }
                            $c_content = $self_params[$i+1];
                        }

                        $authors = implode('', $authors);
                        $authors = explode(',', $authors);
                        $authors = array_filter($authors);

                        $params['author'] = $authors;
                        $local_params['author'] = $authors;
                        continue;
                    }
                    if ($self_params[$i] == 'Название:') {
                        $params['title'] = $self_params[$i + 1];
                        $local_params['title'] = $self_params[$i + 1];
                        $i++;
                        continue;
                    }
                    if ($self_params[$i] == 'Издательство:') {
                        $publishers = [
                        ];

                        $c_content = trim($self_params[$i+1]);
                        while (
                            $c_content != 'Название:'
                            &&
                            $c_content != 'Серия:'
                            &&
                            $c_content != 'Автор:'
                            &&
                            $c_content != 'Год:'
                            &&
                            $c_content != 'ISBN:'
                            &&
                            $c_content != 'Тираж:'
                            &&
                            $c_content != 'Формат:'
                            &&
                            $c_content != 'Перевод книги:'
                            &&
                            $c_content != 'Язык:'
                            &&
                            $c_content != 'Жанр:'
                        ){
                            $publishers[] = $c_content;
                            $i++;
                            if (!isset($self_params[$i+1])){
                                return false;
                            }
                            $c_content = $self_params[$i+1];
                        }

                        $publishers = implode('', $publishers);
                        $publishers = explode(',', $publishers);
                        $publishers = array_filter($publishers);

                        $params['publisher'] = $publishers;
                        $local_params['publisher'] = $publishers;
                        continue;
                    }
                    if ($self_params[$i] == 'Год:') {
                        $params['year'] = $self_params[$i + 1];
                        $local_params['year'] = $self_params[$i + 1];
                        $i++;
                        continue;
                    }
                    if ($self_params[$i] == 'ISBN:') {
                        $new_params['isbn'] = $self_params[$i + 1];
                        $local_params['isbn'] = $self_params[$i + 1];
                        $i++;
                        continue;
                    }
                    if ($self_params[$i] == 'Страниц:') {
                        $local_params['pages'] = $self_params[$i + 1];
                        $i++;
                        continue;
                    }
                    if ($self_params[$i] == 'Тираж:') {
                        $local_params['count'] = $self_params[$i + 1];
                        $i++;
                        continue;
                    }
                    if ($self_params[$i] == 'Формат:') {
                        $local_params['format'] = $self_params[$i + 1];
                        $i++;
                        continue;
                    }
                    if ($self_params[$i] == 'Перевод книги:') {
                        $local_params['translator'] = $self_params[$i + 1];
                        $i++;
                        continue;
                    }
                    if ($self_params[$i] == 'Язык:') {
                        $local_params['language'] = $self_params[$i + 1];
                        $i++;
                        continue;
                    }
                    if ($self_params[$i] == 'Жанр:') {
                        $local_params['genre'] = $self_params[$i + 1];
                        $i++;
                        continue;
                    }
                }
                $params['params'] = json_encode($local_params);
            }
            break;
        }
        foreach ($html->find('table.table_view_gl tr.td_center_color td:not(.span_str) p.span_str') as $table){
            if ($table->tag == 'p') {
                $content = trim($table->text());
                $replaced = preg_replace('/\s\s+/', ' ', $content);
                $params['preview_text'] = $replaced;
            }
            break;
        }
        foreach ($html->find('table.table_view_gl tr.td_center_color td:not(.span_str) p.global_link a') as $table){
            if ($table->tag == 'a') {
                $params['book_anchors'] = self::DOMAIN . trim($table->getAttribute('href'));
            }
            break;
        }
        $params['book_pages_link'] = self::DOMAIN . 'read_book.php?id='.$donor_id.'&p=1';

        return $params;
    }

    public static function parseGenres()
    {
        $url = self::DOMAIN;
        $genres = [];

        $response = file_get_contents($url);
        $html = str_get_html($response);

        foreach ($html->find('#menu_left') as $menu){
            $content = trim($menu->text());
            $replaced = preg_replace('/\s\s+/', '||=|==|=||', $content);
            $genres = explode('||=|==|=||', $replaced);
            $genres = array_filter($genres);
            unset($genres[0]);
        }
        return $genres;
    }

    public static function parseAuthors($let)
    {
        $url = self::DOMAIN.'letter_author.php?let='.$let;

        $authors = [];
        $response = file_get_contents($url);
        $html = str_get_html($response);
        foreach ($html->find('ul.let_ul') as $element){
            $content = trim($element->text());
            $replaced = preg_replace('/\s\s+/', '||=|==|=||', $content);
            $authors = explode('||=|==|=||', $replaced);
            $authors = array_filter($authors);
            break;
        }
        return $authors;
    }
}
