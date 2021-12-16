@extends('layouts.main')
@section('title', 'Parser')
@section('content')
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
                            <button type="submit" class="btn btn-block btn-outline-secondary btn-lg">
                                Включить обход по сайту (навигация, ссылки на авторов)
                            </button>
                        </div>
                    </form>
                    @if($site->defaultStatus)
                        @include('audio.partials.parsing_status', ['status' => $site->defaultStatus])
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
                            <button type="submit" class="btn btn-block btn-outline-secondary btn-lg">
                                Включить парсинг авторов
                            </button>
                        </div>
                    </form>
                    @if($site->authorStatus)

                            @include('audio.partials.parsing_status', ['status' => $site->authorStatus])

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
                            <button type="submit" class="btn btn-block btn-outline-secondary btn-lg">
                                Включить парсинг книг
                            </button>
                        </div>
                    </form>
                    @if($site->bookStatus)

                        @include('audio.partials.parsing_status', ['status' => $site->bookStatus])

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
                            <button type="submit" class="btn btn-block btn-outline-secondary btn-lg">
                                Включить парсинг изображений
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
                                <button type="submit" class="btn btn-block btn-outline-secondary btn-lg">
                                    Включить парсинг аудиокниг
                                </button>
                            </div>
                        </form>
                        @if($site->audioBookStatus)

                            @include('audio.partials.parsing_status', ['status' => $site->audioBookStatus])

                        @endif
                    </div>
            </div>
        </div>
@endsection
