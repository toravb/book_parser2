<!-- Left navbar links -->
<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
</ul>

<ul class="navbar-nav ml-auto">
    <li class="nav-item">
        <form action="{{route('logout')}}" method="POST">
            @csrf
            <button type="submit" class="nav-link btn">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </button>
        </form>
    </li>
</ul>
