<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.home') }}" class="brand-link">
        <img src="{{ asset('images/bdgov.png') }}" alt="eDocuments" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'Starter') }}</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->profile->thumb_url }}" class="img-circle elevation-2" alt="">
            </div>
            <div class="info">
                <a href="{{ route('super-admin.user-profile') }}"
                    class="d-block">{{ Auth::user()->name ?? 'Sohrab Hossan' }}</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('super-admin.tenants') }}"
                        class="nav-link {{ Route::currentRouteName() == 'super-admin.tenants' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Tenants</p>
                    </a>
                </li>
                <li
                    class="nav-item has-treeview {{ Route::currentRouteName() == 'super-admin.user-permissions' || Route::currentRouteName() == 'super-admin.user-roles' || Route::currentRouteName() == 'super-admin.users' ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ Route::currentRouteName() == 'super-admin.user-permissions' || Route::currentRouteName() == 'super-admin.user-roles' || Route::currentRouteName() == 'super-admin.users' ? 'active' : '' }}">
                        <i class="fa fa-users nav-icon orange"></i>
                        <p>User Management<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('super-admin.user-permissions') }}"
                                class="nav-link {{ Route::currentRouteName() == 'super-admin.user-permissions' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-drum-steelpan"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('super-admin.user-roles') }}"
                                class="nav-link {{ Route::currentRouteName() == 'super-admin.user-roles' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-dice-d20 blue"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('super-admin.users') }}"
                                class="nav-link {{ Route::currentRouteName() == 'super-admin.users' ? 'active' : '' }}">
                                <i class="fa fa-users nav-icon"></i>
                                <p>Users</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.change-password') }}"
                        class="nav-link {{ Route::currentRouteName() == 'super-admin.change-password' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-unlock-alt"></i>
                        <p>Change Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="nav-icon fa fa-power-off red"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
