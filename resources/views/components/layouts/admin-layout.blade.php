<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{csrf_token()}}">
    <title>{{config('app.name')}} @if($title??false)- {{$title}}@endif</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/0653de358c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/dropzone.js"></script>

    <link rel="stylesheet" href="{{mix('css/admin.css')}}">

    <script src="https://cdn.tiny.cloud/1/igg9k7wuvfmznxcxg0rdfjpz233fea3x2soialmkztple8wp/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <script src="{{mix('js/admin.js')}}" defer></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Modals and notifications -->

@if(session()->has('success'))
<x-toast-notification :title="session()->get('success')"></x-toast-notification>
@endif

@if($errors->any())
    <x-toast-notification
        icon="error"
        timer="4000"
        title="Ошибка при отправке формы"
    ></x-toast-notification>
@endif


<!-- Page data -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        @include('admin.regular.navbar')
    </nav>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{route('admin.index')}}" class="brand-link">
            <span class="brand-text font-weight-light">FoxBooks</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                @include('admin.regular.aside')
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12 col-sm-6">
                        <h1 class="m-0">{{$title ?? ''}}</h1>
                    </div>

                    @if($actions??false)
                        <div class="col d-flex justify-content-end">
                            {{$actions}}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                {{$slot}}
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
</body>
</html>
