<header class="header-area header-sticky animate__animated animate__fadeInDown">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="{{ route('home') }}" class="logo">
                        <img style="height:90px;" src="{{ asset('images/logo.png') }}">
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li class="scroll-to-section">
                            <a href="{{ route('home') }}"
                                class="{{ Route::currentRouteName() == 'home' ? 'active' : '' }}">Home</a>
                        </li>


                        @guest
                            <li class="scroll-to-section">
                                <a href="{{ route('login') }}"
                                    class="{{ Route::currentRouteName() == 'login' ? 'active' : '' }}">Login</a>
                            </li>
                            <li class="scroll-to-section">
                                <a href="{{ route('registration') }}"
                                    class="{{ Route::currentRouteName() == 'registration' ? 'active' : '' }}">Registration</a>
                            </li>
                        @else
                            <li class="scroll-to-section">
                                <div class="main-red-button">
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>

                            </li>
                        @endguest

                    </ul>
                    <a class="menu-trigger">
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>
