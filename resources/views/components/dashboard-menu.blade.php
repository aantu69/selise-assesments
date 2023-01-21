<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard.home') }}" class="brand-link">
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
                <a href="{{ route('dashboard.user-profile') }}"
                    class="d-block">{{ Auth::user()->name ?? 'Sohrab Hossan' }}</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard.home') }}"
                        class="nav-link {{ Route::currentRouteName() == 'dashboard.home' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt orange"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard.taxpayers') }}"
                        class="nav-link {{ Route::currentRouteName() == 'dashboard.taxpayers' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users green"></i>
                        <p>Taxpayers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard.reminders') }}"
                        class="nav-link {{ Route::currentRouteName() == 'dashboard.reminders' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell orange"></i>
                        <p>Reminder List</p>
                    </a>
                </li>
                <li
                    class="nav-item has-treeview {{ Route::currentRouteName() == 'dashboard.demand-reports' || Route::currentRouteName() == 'dashboard.collection-reports' ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ Route::currentRouteName() == 'dashboard.demand-reports' || Route::currentRouteName() == 'dashboard.collection-reports' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th green"></i>
                        <p>Reports<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('dashboard.demand-reports') }}"
                                class="nav-link {{ Route::currentRouteName() == 'dashboard.demand-reports' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>Demands</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.collection-reports') }}"
                                class="nav-link {{ Route::currentRouteName() == 'dashboard.collection-reports' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>Collections</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item has-treeview {{ Route::currentRouteName() == 'dashboard.circles' ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ Route::currentRouteName() == 'dashboard.circles' ? 'active' : '' }}">
                        <i class="fa fa-gear nav-icon orange"></i>
                        <p>Settings<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('dashboard.circles') }}"
                                class="nav-link {{ Route::currentRouteName() == 'dashboard.circles' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>Circles</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @role('Commissioner')
                    <li
                        class="nav-item has-treeview {{ Route::currentRouteName() == 'dashboard.user-permissions' || Route::currentRouteName() == 'dashboard.user-roles' || Route::currentRouteName() == 'dashboard.users' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Route::currentRouteName() == 'dashboard.user-permissions' || Route::currentRouteName() == 'dashboard.user-roles' || Route::currentRouteName() == 'dashboard.users' ? 'active' : '' }}">
                            <i class="fa fa-users nav-icon green"></i>
                            <p>User Management<i class="right fa fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('dashboard.user-permissions') }}"
                                    class="nav-link {{ Route::currentRouteName() == 'dashboard.user-permissions' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-drum-steelpan"></i>
                                    <p>Permissions</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dashboard.user-roles') }}"
                                    class="nav-link {{ Route::currentRouteName() == 'dashboard.user-roles' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-dice-d20 blue"></i>
                                    <p>Roles</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dashboard.users') }}"
                                    class="nav-link {{ Route::currentRouteName() == 'dashboard.users' ? 'active' : '' }}">
                                    <i class="fa fa-users nav-icon"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole
                <li class="nav-item">
                    <a href="{{ route('dashboard.change-password') }}"
                        class="nav-link {{ Route::currentRouteName() == 'dashboard.change-password' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-unlock-alt orange"></i>
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
