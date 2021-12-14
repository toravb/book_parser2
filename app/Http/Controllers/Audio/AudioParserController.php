<?php

namespace App\Http\Controllers\Audio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

include 'app/Modules/simple_html_dom.php';

class AudioParserController extends Controller
{
    public static string $domain = 'https://knigavuhe.org';

    public static function parse()
    {
        $url = 'https://knigavuhe.org/book/prikljuchenija-toma-bombadila-i-drugie-istoriiiz-alojj-knigi-zapadnykh-predelov/';
        $title = '';
        $genre = '';
        $authors = [];
        $readers = [];
        $series = '';
        $description = '';
        $params = [];
        $audio_links = [];
        $images = [];

        $response = file_get_contents($url);
        //audio links
        preg_match_all('(title\":[^,]+.\"url\":\"[^\"]+)', $response, $matches);
        foreach ($matches[0] as $match){
            $strings = explode('","', $match);
            $link = [];
            foreach ($strings as $element){
                $el = explode('":"', $element);
                if (is_array($el) && !empty($el)){
                    $name = $el[0];
                    unset($el[0]);
                    $link[$name] = implode('":"',$el);
                }
            }
            $audio_links[] = $link;
        }

        $html = str_get_html($response);

        //genre
        foreach ($html->find('div.book_genre_pretitle') as $element){
            foreach ($element->find('a') as $text){
                $genre .= $text->innertext;
            }
        }
        //images
        foreach ($html->find('div.book_cover_wrap') as $element){
            foreach ($element->find('div.book_cover') as $el){
                foreach ($el->find('img') as $img){
                    $images[] = $img->getAttribute('src');
                }
            }
        }
        //title, authors, readers
        foreach($html->find('div.page_title') as $element){
            foreach ($element->find('span[itemprop=name]') as $el){
                $title = trim($el->innertext);
            }
            foreach ($element->find('span[itemprop=author]') as $el){
                foreach ($el->find('a') as $author){
                    $authors[] = trim($author->innertext);
                }
            }
            foreach ($element->find('span.book_title_elem') as $el){
                foreach ($el->find('span.page_title_gray') as $text){
                    $text = trim($text->innertext);
                    if ($text == 'читают' || $text == 'читает'){
                        foreach ($el->find('a') as $reader){
                            if ($reader->find('div.verified_icon')){
                                continue;
                            }
                            $readers[] = trim($reader->innertext);
                        }
                    }
                }
            }
        }
        //series
        foreach ($html->find('div.book_serie_block_title') as $element){
            foreach ($element->find('a') as $el){
                $series = trim($el->innertext);
            }
        }
        //description
        foreach($html->find('div.book_description') as $element){
            foreach ($element->find('div.spoiler') as $spoiler){
                $spoiler->outertext = '';
            }
            $description .= trim($element->innertext) . ' ';
        }
        $description = trim($description);
        //params
        foreach ($html->find('div.book_fiction_block_cat') as $element){
            $param_name = '';
            foreach ($element->find('div.book_fiction_block_cat_name') as $el){
                $param_name = $el->innertext;
            }
            foreach ($element->find('a.book_fiction_block_tag') as $el){
                $params[$param_name][] = $el->innertext;
            }
        }



        dd([
            $title,
            $genre,
            $images,
            $authors,
            $readers,
            $series,
            $description,
            $params,
            $audio_links,
        ]);
    }

    public static function parseLetters()
    {
        $url = self::$domain.'/authors/';
        $nav_links = [];

        $response = file_get_contents($url);
        $html = str_get_html($response);

        foreach ($html->find('div.authors_letters_block.clearfix') as $element){
            foreach ($element->find('a') as $link){
                $nav_links[] = self::$domain . trim($link->getAttribute('href'));
            }
        }

        return $nav_links;
    }

    public static function parseAuthors($url)
    {
        $links = [];

        $headers = get_headers($url);
        $code = substr($headers[0], 9, 3);

        if ($code == 200){
            $response = file_get_contents($url);
            $html = str_get_html($response);

            foreach ($html->find('div.common_list') as $element) {
                foreach ($element->find('div.common_list_item') as $el) {
                    foreach ($el->find('a.author_item_name') as $link) {
                        $links[] = self::$domain . trim($link->getAttribute('href'));
                    }
                }
            }

            return $links;
        }
        return false;
    }

    public static function parseAuthor($url)
    {
        $links = [];
        $j = 0;

        $response = file_get_contents($url);
        $html = str_get_html($response);

        foreach ($html->find('div.pn_buttons') as $element){
            foreach ($element->find('div.pn_page_buttons') as $el){
                $page_link =  @end($el->find('a.pn_button.-page'));
                $i = $page_link->innertext;
                if ($i > $j){
                    $j = $i;
                }
            }
        }

        self::parseAuthorBookLinks(null, $links, $html);
        if ($j > 0){
            for ($i = 2; $i <= $j; $i++){
                $parse_url = $url .$i . '/';
                self::parseAuthorBookLinks($parse_url, $links);
            }
        }
        return $links;
    }

    private static function parseAuthorBookLinks($url, &$array, $content = null){
        if ($content){
            $html = $content;
        }else {
            $response = file_get_contents($url);
            $html = str_get_html($response);
        }

        foreach ($html->find('div#books_list') as $element){
            foreach ($element->find('a.bookkitem_name') as $link){
                $array[] = self::$domain . trim($link->getAttribute('href'));
            }
        }
    }
}
