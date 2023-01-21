<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.home') }}" class="brand-link">
        <img src="{{ asset('images/favicon.png') }}" alt="eCommerce" class="brand-image img-circle elevation-3"
            style="opacity: .8;background-color: #fff;">
        <span class="brand-text font-weight-light">{{ config('app.name', 'Assessment') }}</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('images/profile.png') }}" class="img-circle elevation-2" alt="">
            </div>
            <div class="info">
                <a href="{{ route('admin.home') }}" class="d-block">{{ Auth::user()->name ?? 'Sohrab Hossan' }}</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}"
                        class="nav-link {{ Route::currentRouteName() == 'admin.home' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.books') }}"
                        class="nav-link {{ Route::currentRouteName() == 'admin.books' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>Books</p>
                    </a>
                </li>

                <li
                    class="nav-item has-treeview {{ Route::currentRouteName() == 'admin.admin-permissions' || Route::currentRouteName() == 'admin.admin-roles' || Route::currentRouteName() == 'admin.admins' ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ Route::currentRouteName() == 'admin.admin-permissions' || Route::currentRouteName() == 'admin.admin-roles' || Route::currentRouteName() == 'admin.admins' ? 'active' : '' }}">
                        <i class="fa fa-users nav-icon orange"></i>
                        <p>User Management<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.admin-permissions') }}"
                                class="nav-link {{ Route::currentRouteName() == 'admin.admin-permissions' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-drum-steelpan"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.admin-roles') }}"
                                class="nav-link {{ Route::currentRouteName() == 'admin.admin-roles' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-dice-d20 blue"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.admins') }}"
                                class="nav-link {{ Route::currentRouteName() == 'admin.admins' ? 'active' : '' }}">
                                <i class="fa fa-users nav-icon"></i>
                                <p>Users</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.change-password') }}"
                        class="nav-link {{ Route::currentRouteName() == 'admin.change-password' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-unlock-alt"></i>
                        <p>Change Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="nav-icon fa fa-power-off red"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
