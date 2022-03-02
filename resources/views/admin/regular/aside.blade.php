<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
    data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
    <li @class(['nav-item', 'menu-open' => request()->route()->named('admin.book.*')])>
        <a href="#" @class(['nav-link', 'active' => request()->route()->named('admin.book.*')])>
            <i class="nav-icon fas fa-book"></i>
            <p>
                Работа с книгами
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.book.list')}}" @class(['nav-link', 'active' => request()->route()->named('admin.book.list')])>
                    <p>Администрирование</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.book.create')}}" @class(['nav-link', 'active' => request()->route()->named('admin.book.create')])>
                    <p>Добавление книги</p>
                </a>
            </li>
            {{--
            <li class="nav-item">
                <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                    <p>SEO настройки</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/layout/boxed.html" class="nav-link">
                    <p>Добавление в категории</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/layout/fixed-sidebar.html" class="nav-link">
                    <p>Статистика</p>
                </a>
            </li>
            --}}
        </ul>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-headphones"></i>
            <p>
                Работа с аудиокнигами
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.audio_book.index')}}" class="nav-link">
                    <p>Администрирование</p>
                </a>
            </li>
{{--            <li class="nav-item">--}}
{{--                <a href="" class="nav-link">--}}
{{--                    <p>Добавление книги</p>--}}
{{--                </a>--}}
{{--            </li>--}}
            {{--
            <li class="nav-item">
                <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                    <p>SEO настройки</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/layout/boxed.html" class="nav-link">
                    <p>Добавление в категории</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/layout/fixed-sidebar.html" class="nav-link">
                    <p>Статистика</p>
                </a>
            </li>
    --}}
        </ul>
    </li>
    <li @class(['nav-item', 'menu-open' => request()->route()->named('admin.category.*')])>
        <a href="#" @class(['nav-link', 'active' => request()->route()->named('admin.category.*')])>
            <i class="nav-icon fas fa-list"></i>
            <p>
                Категории/разделы
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.category.index')}}" @class(['nav-link', 'active' => request()->route()->named('admin.category.index')])>
                    <p>Редактирование/Удаление</p>
                </a>
            </li>
            {{--
            <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                    <p>Администрирование</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/charts/flot.html" class="nav-link">
                    <p>SEO настройки</p>
                </a>
            </li>
            --}}
        </ul>
    </li>
    {{--
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
            <i class="nav-icon fas fa-band-aid"></i>
            <p>
                Банеры
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
