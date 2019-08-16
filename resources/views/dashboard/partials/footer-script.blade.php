<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.min.js') }}"></script>

<!-- SweetAlert 2 -->
<script src="{{ asset('vendor/sweetalert2-8.13.1/sweetalert2.all.min.js') }}"></script>

<!-- DataTables -->
<script type="text/javascript" src="{{ asset('vendor/datatables/DataTables-1.10.18/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatables/FixedColumns-3.2.5/js/dataTables.fixedColumns.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/datatables/FixedColumns-3.2.5/js/fixedColumns.bootstrap4.min.js') }}"></script>

<!-- DateRangePicker -->
<script type="text/javascript" src="{{ asset('vendor/daterangepicker-master/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/daterangepicker-master/daterangepicker.js') }}"></script>

<!-- Slim Select -->
<script type="text/javascript" src="{{ asset('vendor/slimselect/slimselect.min.js') }}"></script>

{{-- ApexCharts --}}
<script type="text/javascript" src="{{ asset('vendor/apexcharts/dist/apexcharts.js') }}"></script>

{{-- ApexCharts --}}
<script type="text/javascript" src="{{ asset('vendor/chart.js/dist/Chart.js') }}"></script>

{{-- Numeral JS --}}
<script type="text/javascript" src="{{ asset('vendor/numeral/numeral.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
