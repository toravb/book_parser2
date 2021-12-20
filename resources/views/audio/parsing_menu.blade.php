@extends('layouts.main')
@section('title', 'Parser')
@section('content')
        <div class="container container-table main-menu">
            <div class="sub-container main-menu-left">
                @if(session('success'))
                    <div class="alert alert-success">
                        {!! session('success') !!}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {!! session('error') !!}
                    </div>
                @endif

                <div class="form-container">
                    <form class="form-horizontal" method="post" action="{{route('audio.parsing.default', ['site' => $site])}}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input name="id" type="hidden" class="form-control" id="id" value="{{$site->id}}">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-block btn-outline-secondary btn-lg @if(($site->defaultStatus && $site->defaultStatus->doParse) || ($authorJobs > 0 || $bookJobs > 0 || $imageJobs > 0)) disabled @endif">
                                Включить обход по сайту (навигация, ссылки на авторов)
                            </button>
                        </div>
                    </form>
                    @if($site->defaultStatus)
                        @include('audio.partials.parsing_status', ['status' => $site->defaultStatus])
                    @endif
                </div>

                <div class="form-container">
                    <form class="form-horizontal" method="post" action="{{route('audio.parsing.authors', ['site' => $site])}}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input name="id" type="hidden" class="form-control" id="id" value="{{$site->id}}">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-block btn-outline-secondary btn-lg @if($site->authorStatus && !$site->authorStatus->doParse && $authorJobs > 0) disabled @endif">
                                @if($site->authorStatus && $site->authorStatus->paused && $authorJobs > 0)
                                    Отключить паузу
                                @elseif($authorJobs > 0)
                                    Поставить паузу
                                @else
                                Включить парсинг авторов
                                @endif
                            </button>
                        </div>

                    </form>
                    @if($site->authorStatus)

                            @include('audio.partials.parsing_status', ['status' => $site->authorStatus])

                    @endif
                </div>

                <div class="form-container">
                    <form class="form-horizontal" method="post" action="{{route('audio.parsing.books', ['site' => $site])}}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input name="id" type="hidden" class="form-control" id="id" value="{{$site->id}}">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-block btn-outline-secondary btn-lg @if($site->bookStatus && !$site->bookStatus->doParse && $bookJobs > 0) disabled @endif">
                                @if($site->bookStatus && $site->bookStatus->paused && $bookJobs > 0)
                                    Отключить паузу
                                @elseif($bookJobs > 0)
                                    Поставить паузу
                                @else
                                    Включить парсинг книг
                                @endif
                            </button>
                        </div>
                    </form>
                    @if($site->bookStatus)

                        @include('audio.partials.parsing_status', ['status' => $site->bookStatus])

                    @endif
                </div>
                <div class="form-container">
                    <form class="form-horizontal" method="post" action="{{ route('audio.parsing.images', ['site' => $site]) }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input name="id" type="hidden" class="form-control" id="id" value="{{$site->id}}">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-block btn-outline-secondary btn-lg @if($site->imageStatus && !$site->imageStatus->doParse && $imageJobs > 0) disabled @endif">
                                @if($site->imageStatus && $site->imageStatus->paused && $imageJobs > 0)
                                    Отключить паузу
                                @elseif($imageJobs > 0)
                                    Поставить паузу
                                @else
                                    Включить парсинг изображений
                                @endif
                            </button>
                        </div>
                    </form>
                    @if($site->imageStatus)

                        @include('audio.partials.parsing_status', ['status' => $site->imageStatus])

                    @endif
                </div>
                    <div class="form-container">
                        <form class="form-horizontal" method="post" action="#">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="col-sm-10">
                                        <input name="id" type="hidden" class="form-control" id="id" value="{{$site->id}}">
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-block btn-outline-secondary btn-lg disabled">
                                    Включить парсинг аудиокниг
                                </button>
                            </div>
                        </form>
                        @if($site->audioBookStatus)

                            @include('audio.partials.parsing_status', ['status' => $site->audioBookStatus])

                        @endif
                    </div>
            </div>

            <div class="sub-container main-menu-right">
                <div class="form-container">
                    <form class="form-horizontal" method="post" action="{{route('audio.parsing.check', ['site' => $site])}}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input name="id" type="hidden" class="form-control" id="id" value="{{$site->id}}">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-block btn-outline-secondary btn-lg @if(($authorJobs > 0 || $bookJobs > 0 || $imageJobs > 0)) disabled @endif">
                                Проверить очереди на ошибки
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
