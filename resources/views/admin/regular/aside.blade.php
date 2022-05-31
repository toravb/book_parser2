<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
    data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
    <li class="nav-item">
        <a href="{{route('admin.authors.index')}}" @class(['nav-link', 'active' => request()->route()->named('admin.authors.*')])>
            <i class="nav-icon fas fa-users"></i>
            <p>
                Авторы
            </p>
        </a>
    </li>

    <li @class(['nav-item', 'menu-open' => request()->route()->named('admin.books.*')])>
        <a href="#" @class(['nav-link', 'active' => request()->route()->named('admin.books.*')])>
            <i class="nav-icon fas fa-book"></i>
            <p>
                Книги
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.books.index')}}" @class(['nav-link', 'active' => request()->route()->named('admin.books.*')])>
                    <p>Администрирование</p>
                </a>
            </li>
        </ul>
    </li>

    <li @class(['nav-item', 'menu-open' => request()->route()->named('admin.audio-books.*')])>
        <a href="#" @class(['nav-link', 'active' => request()->route()->named('admin.audio-books.*')])>
            <i class="nav-icon fas fa-headphones"></i>
            <p>
                Аудио книги
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.audio-books.index')}}" @class(['nav-link', 'active' => request()->route()->named('admin.audio-books.*')])>
                    <p>Администрирование</p>
                </a>
            </li>
        </ul>
    </li>

    <li @class(['nav-item', 'menu-open' => request()->route()->named('admin.genres.*')])>
        <a href="javascript:void(0)" @class(['nav-link', 'active' => request()->route()->named('admin.genres.*')])>
            <i class="nav-icon fas fa-list"></i>
            <p>
                Категории
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.genres.index')}}" @class(['nav-link', 'active' => request()->route()->named('admin.genres.*')])>
                    <p>Администрирование</p>
                </a>
            </li>
        </ul>
    </li>

    <li @class(['nav-item', 'menu-open' => request()->route()->named('admin.compilations.*')])>
        <a href="#" @class(['nav-link', 'active' => request()->route()->named('admin.compilations.*')])>
            <i class="nav-icon fas fa-list"></i>
            <p>
                Подборки
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.compilations.novelties.index')}}"
                    @class(['nav-link', 'active' => request()->route()->named('admin.compilations.novelties.index')])>
                    <p>Новинки книг(Главная)</p>
                </a>
            </li>
        </ul>

        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.compilations.no-time-for-read.index')}}"
                    @class(['nav-link', 'active' => request()->route()->named('admin.compilations.no-time-for-read.index')])>
                    <p>Некогда читать, слушайте(Главная)</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="{{route('admin.years.index')}}" @class(['nav-link', 'active' => request()->route()->named('admin.years.*')])>
            <i class="nav-icon fa-solid fa-calendar"></i>
            <p>Года издания</p>
        </a>
    </li>

    <li @class(['nav-item', 'menu-open' => request()->route()->named('admin.banners.*')])>
        <a href="#" @class(['nav-link', 'active' => request()->route()->named('admin.banners.*')])>
            <i class="nav-icon fas fa-band-aid"></i>
            <p>
                Банеры
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.banners.index')}}" @class(['nav-link', 'active' => request()->route()->named('admin.banners.*')])>
                    <p>Администрирование</p>
                </a>
            </li>
        </ul>
    </li>

    <li @class(['nav-item', 'menu-open' => request()->route()->named('admin.review*')])>
        <a href="javascript:void(0)" @class(['nav-link', 'active' => request()->route()->named('admin.review*')])>
            <i class="nav-icon fas fa-list"></i>
            <p>
                Рецензии
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.review-types.index')}}" @class(['nav-link', 'active' => request()->route()->named('admin.review*')])>
                    <p>Типы рецензий</p>
                </a>
            </li>
        </ul>
    </li>

    {{--
    TODO: end up admin actions
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>
                Работа с меню
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="pages/UI/general.html" class="nav-link">
                    <p>Главное</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/UI/icons.html" class="nav-link">
                    <p>Боковое</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/UI/buttons.html" class="nav-link">
                    <p>Футер</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cube"></i>
            <p>
                Работа с модулями
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="pages/forms/general.html" class="nav-link">
                    <p>Вывод книг</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/forms/advanced.html" class="nav-link">
                    <p>Подбрки</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/forms/editors.html" class="nav-link">
                    <p>Рецензии</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-comments"></i>
            <p>
                Комментарии
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="pages/tables/simple.html" class="nav-link">
                    <p>Администрирование</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/tables/data.html" class="nav-link">
                    <p>Статистика</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-comment-dots"></i>
            <p>
                Рецензии
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="pages/tables/simple.html" class="nav-link">
                    <p>Администрирование</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/tables/data.html" class="nav-link">
                    <p>Статистика</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Менеджер пользователей
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="pages/tables/simple.html" class="nav-link">
                    <p>Администрирование</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/tables/data.html" class="nav-link">
                    <p>Статистика</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon far fa-envelope"></i>
            <p>
                Mailbox
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="pages/mailbox/mailbox.html" class="nav-link">
                    <p>Inbox</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/mailbox/compose.html" class="nav-link">
                    <p>Compose</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/mailbox/read-mail.html" class="nav-link">
                    <p>Read</p>
                </a>
            </li>
        </ul>
    </li>
    --}}
</ul>
