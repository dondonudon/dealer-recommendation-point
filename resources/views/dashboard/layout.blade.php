@php
$segments = request()->segments();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    @include('dashboard.partials.head')
    @yield('style')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    @include('dashboard.partials.navbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        @include('dashboard.partials.sidebar')
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            @if(\Illuminate\Support\Facades\Session::exists('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Halaman yang anda inginkan tidak tersedia untuk anda, silahkan menghubungi administrator!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">@yield('page title')</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @if($segments !== [])
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">home</a></li>
                                <li class="breadcrumb-item">{{ $segments[0] }}</li>
                                <li class="breadcrumb-item active">{{ $segments[1] }}</li>
                            @endif
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        @yield('content')
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    @include('dashboard.partials.footer')
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@include('dashboard.partials.footer-script')
@yield('script')
</body>
</html>
