@php
$sidebarMenu = \App\Http\Controllers\Dashboard::sidebarMenu();
$segment = request()->segments();
@endphp

<!-- Brand Logo -->
<a href="{{ url('/') }}" class="brand-link">
    <img src="{{ asset('img/DRP_.jpg') }}" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{ config('app.app_name_small') }}</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <div class="img-circle" style="font-size: 25px; color: Dodgerblue;">
                <i class="fas fa-user-circle"></i>
            </div>
        </div>
        <div class="info">
            <a href="{{ url('master-data/profile') }}" class="d-block">
                <strong class="text-uppercase">
                    {{ \Illuminate\Support\Facades\Session::get('username') }}
                </strong>
            </a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>Home</p>
                </a>
            </li>
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            @foreach($sidebarMenu as $sm)
                @php
                    $menuOpen = '';
                    $groupActive = '';
                    if (isset($segment[0]) && $segment[0] == $sm['group']['segment_name']) {
                        $menuOpen = 'menu-open';
                        $groupActive = 'active';
                    }
                @endphp
                <li class="nav-item has-treeview {{ $menuOpen }}">
                    <a href="#" class="nav-link {{ $groupActive }}">
                        <i class="nav-icon {{ $sm['group']['icon'] }}"></i>
                        <p>
                            {{ $sm['group']['nama'] }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach($sm['menu'] as $m)
                            <li class="nav-item">
                                @php
                                $status = '';
                                if (isset($segment[1]) && $segment[1] == $m['segment_name']) {
                                    $status = 'active';
                                }
                                @endphp
                                <a href="{{ url($m['url']) }}" class="nav-link {{ $status }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ $m['nama'] }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
