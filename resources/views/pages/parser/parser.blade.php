@extends('layouts.main')
@section('title', 'Parser')
@section('content')
    @if($site)
{{--    <div style="display: none">--}}
{{--    @foreach($parsingStatus as $element)--}}
{{--                @if($element->Count == 0)--}}
{{--                    {{$element->Count = 1}}--}}
{{--                @endif--}}
{{--    @endforeach--}}
{{--    </div>--}}
    <div class="container container-table">
        <div class="sub-container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

            <div class="form-container">
                <form class="form-horizontal" method="post" action="{{route('parser.parse.links')}}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-10">
                                <input name="site" type="hidden" class="form-control" id="site" value="{{$site->site}}">
                                <input name="id" type="hidden" class="form-control" id="id" value="{{$site->id}}">
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-block btn-outline-secondary btn-lg">
                            Включить парсинг ссылок на книги
                        </button>
                    </div>
                </form>
                @foreach($parsingStatus as $element)
                    @if($element->parse_type == '0' && $element->Count != 0)

                        <span class="sr-only">
                            @if($element->last_parsing != null)
                            {{$element->last_parsing}} ({{$element->Progress}}/{{$element->Count}})
                            </span>
                            @else
                            Статус парсинга ссылок({{$element->Progress}}/{{$element->Count}})
                            </span>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{$element->Progress}}"
                                 aria-valuemin="0" aria-valuemax="{{$element->Count}}"
                                 style="width: {{($element->Progress/$element->Count) * 100}}%">
                            </div>
                        </div>
                        @endif
                    @endif
                @endforeach
            </div>

            <div class="form-container">
                <form class="form-horizontal" method="post" action="{{route('parser.parse.books')}}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-10">
                                <input name="doParseBooks" type="hidden" class="form-control" id="doParseBooks" value="{{$site->doParseBooks}}">
                                <input name="site" type="hidden" class="form-control" id="site" value="{{$site->site}}">
                                <input name="id" type="hidden" class="form-control" id="id" value="{{$site->id}}">
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-block btn-outline-secondary btn-lg"
                                style="background-color: <?=(!$site->doParseBooks)?'':'red'?>">
                            @if($site->doParseBooks) Выключить парсинг превью @else Включить парсинг превью @endif
                    </div>
                </form>
                @if($statuses['books'] == 0)
                    <span class="badge badge-success">
                    Парсер превью готов к работе
                </span>
                @endif
                @foreach($parsingStatus as $element)
                    @if($element->parse_type == '1' && $element->Count != 0)

                        <span class="sr-only">
                            @if($element->last_parsing != null)
                                {{$element->last_parsing}} ({{$element->Progress}}/{{$element->Count}})
                            </span>
                    @else
                        Статус парсинга превью({{$element->Progress}}/{{$element->Count}})
                        </span>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{$element->Progress}}"
                                 aria-valuemin="0" aria-valuemax="{{$element->Count}}"
                                 style="width: {{($element->Progress/$element->Count) * 100}}%">
                            </div>
                        </div>
                    @endif
                    @endif
                @endforeach
            </div>

            <div class="form-container">
                <form class="form-horizontal" method="post" action="{{route('parser.parse.pages')}}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-10">
                                <input name="doParsePages" type="hidden" class="form-control" id="doParsePages" value="{{$site->doParsePages}}">
                                <input name="site" type="hidden" class="form-control" id="site" value="{{$site->site}}">
                                <input name="id" type="hidden" class="form-control" id="id" value="{{$site->id}}">
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-block btn-outline-secondary btn-lg"
                                style="background-color: <?=(!$site->doParsePages)?'':'red'?>">
                            @if($site->doParsePages) Выключить парсинг страниц @else Включить парсинг страниц @endif
                    </div>
                </form>
                @if($statuses['pages'] == 0)
                    <span class="badge badge-success">
                    Парсер страниц готов к работе
                </span>
                @endif
                @foreach($parsingStatus as $element)
                    @if($element->parse_type == '2' && $element->Count != 0)

                        <span class="sr-only">
                            @if($element->last_parsing != null)
                                {{$element->last_parsing}} ({{$element->Progress}}/{{$element->Count}})
                            </span>
                    @else
                        Статус парсинга страниц({{$element->Progress}}/{{$element->Count}})
                        </span>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{$element->Progress}}"
                                 aria-valuemin="0" aria-valuemax="{{$element->Count}}"
                                 style="width: {{($element->Progress/$element->Count) * 100}}%">
                            </div>
                        </div>
                    @endif
                    @endif
                @endforeach
            </div>
            <div class="form-container">
                <form class="form-horizontal" method="post" action="{{route('parser.parse.images')}}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-10">
                                <input name="doParseImages" type="hidden" class="form-control" id="doParseImages" value="{{$site->doParseImages}}">
                                <input name="site" type="hidden" class="form-control" id="site" value="{{$site->site}}">
                                <input name="id" type="hidden" class="form-control" id="id" value="{{$site->id}}">
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-block btn-outline-secondary btn-lg"
                                style="background-color: <?=(!$site->doParseImages)?'':'red'?>">
                            @if($site->doParseImages) Выключить парсинг изображений @else Включить парсинг изображений @endif
                    </div>
                </form>
                @if($statuses['images'] == 0)
                    <span class="badge badge-success">
                    Парсер изображений готов к работе
                </span>
                @endif
                @foreach($parsingStatus as $element)
                    @if($element->parse_type == '3' && $element->Count != 0)

                        <span class="sr-only">
                            @if($element->last_parsing != null)
                                {{$element->last_parsing}} ({{$element->Progress}}/{{$element->Count}})
                            </span>
                    @else
                        Статус парсинга изображений({{$element->Progress}}/{{$element->Count}})
                        </span>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{$element->Progress}}"
                                 aria-valuemin="0" aria-valuemax="{{$element->Count}}"
                                 style="width: {{($element->Progress/$element->Count) * 100}}%">
                            </div>
                        </div>
                    @endif
                    @endif
                @endforeach
            </div>
    </div>
    </div>
    @endif
@endsection
