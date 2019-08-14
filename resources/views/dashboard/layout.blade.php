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
        <div class="row">
            <div class="col-lg m-2">
                <a class="btn btn-block btn-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-power-off"></i>
                    Logout
                </a>
            </div>
        </div>
        <hr>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    @include('dashboard.partials.footer')
</div>
<!-- ./wrapper -->

{{-- MODAL Logout --}}
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apakah anda ingin keluar?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Silahkan klik tombol logout dibawah untuk mengakhiri sesi ini.</div>
            <div class="modal-footer">
                <button class="btn btn-outline-dark" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" type="button" id="btnLogout">Logout</button>
            </div>
        </div>
    </div>
</div>
{{-- ./MODAL Logout --}}

<!-- REQUIRED SCRIPTS -->
@include('dashboard.partials.footer-script')
<script>
    const btnLogout = $('#btnLogout');
    btnLogout.click(function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ url('overview/session-flush') }}",
            method: "get",
            success: function(result) {
                // console.log(result);
                if (result === 'success') {
                    document.location.reload();
                } else {
                    Swal.fire({
                        type: 'info',
                        title: 'Gagal Logout',
                    });
                }
            }
        });
    })
</script>
@yield('script')
</body>
</html>
