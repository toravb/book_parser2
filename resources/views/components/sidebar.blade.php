
<!-- Main Sidebar Container -->


<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">

            <div class="info">
                <a href="{{route('profile.change')}}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item nav-item menu-is-opening menu-open">
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('proxy.settings')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Настройки для прокси</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('show.proxies')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>База прокси</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            @foreach($sites as $site)
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item nav-item menu-is-opening menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            {{$site->site}}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('parser.pages', ['site' => $site->site])}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Спаршенные страницы</p>
                            </a>
                        </li>
                        <li>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('parser', ['site' => $site->site])}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Меню парсинга</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('books.show')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Книги</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endforeach
            @if(isset($audio_sites))
                @foreach($audio_sites as $site)
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        <li class="nav-item nav-item menu-is-opening menu-open">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    {{$site->site}}
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('audio.menu')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Меню парсинга</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Книги (список)</p>
                                    </a>
                                </li>
                                <li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Книги (таблицы)</p>
                                    </a>
                                </li>
                                <li>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Авторы</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Актёры озвучки</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endforeach
            @endif
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
