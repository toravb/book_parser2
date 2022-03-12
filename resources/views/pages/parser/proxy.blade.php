@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{session('error')}}
            </div>
        @endif
        <form class="form-horizontal profile" method="post" action="{{route('admin.add.authdata')}}">
            {{ csrf_field() }}
            <div class="box-body">
                <label for="inputLogin" class="col-sm-2 control-label">Данные от аккаунта fineproxy</label>
                <div class="form-group">

                    <div class="col-sm-10">
                        <input name="login" type="" class="form-control" id="inputLogin" placeholder="login">
                    </div>
                </div>
                <div class="form-group">

                    <div class="col-sm-10">
                        <input name="password" type="password" class="form-control" id="inputPassword" placeholder="password">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Обновить</button>
            </div>
            <!-- /.box-footer -->
        </form>
        <a type="button" class="btn btn-block btn-outline-secondary btn-lg parse-proxy profile" href="{{route('admin.parser.parse.proxy')}}">Парсить proxy</a>
    </div>
@endsection
