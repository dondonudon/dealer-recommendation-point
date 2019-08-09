@extends('dashboard.layout')

@section('page title','Monitoring Booking')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="dateRange">Tanggal Booking</label>
                                        <input type="text" class="form-control form-control-sm" id="dateRange">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered display nowrap" id="tableIndex" width="100%">
                                <thead class="bg-dark">
                                <tr>
                                    <th>No Booking</th>
                                    <th>Nama</th>
                                    <th>No Telp</th>
                                    <th>No Pol</th>
                                    <th>Model Kendaraan</th>
                                    <th>Tahun Kendaraan</th>
                                    <th>Tgl Booking</th>
                                    <th>Jam Booking</th>
                                    <th>Tipe Service</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-10"></div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-block btn-primary" id="btnDetail" disabled>Detail</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="cardComponent" class="card card-success card-outline d-none">
                        <div class="card-header">
                            <h3 class="card-title">Detail Booking</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-light" id="btnClose">
                                    <i class="fas fa-times" style="color: red;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Nomor Booking</dt>
                                        <dd class="col-sm-8" id="vNoBooking"></dd>

                                        <dt class="col-sm-4">Nama</dt>
                                        <dd class="col-sm-8" id="vNama"></dd>

                                        <dt class="col-sm-4">Nomor Telp.</dt>
                                        <dd class="col-sm-8" id="vNoTelp"></dd>

                                        <dt class="col-sm-4">Tanggal Booking</dt>
                                        <dd class="col-sm-8" id="vTglBooking"></dd>

                                        <dt class="col-sm-4">Jam Booking</dt>
                                        <dd class="col-sm-8" id="vJamBooking"></dd>
                                    </dl>
                                </div>
                                <div class="col-lg-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">No Polisi</dt>
                                        <dd class="col-sm-8" id="vNoPol"></dd>

                                        <dt class="col-sm-4">Model</dt>
                                        <dd class="col-sm-8" id="vModel"></dd>

                                        <dt class="col-sm-4">Tahun Kendaraan</dt>
                                        <dd class="col-sm-8" id="vTahunKendaraan"></dd>

                                        <dt class="col-sm-4">Tipe Service</dt>
                                        <dd class="col-sm-8" id="vTipeService"></dd>

                                        <dt class="col-sm-4">Hasil Follow Up</dt>
                                        <dd class="col-sm-8" id="vTipeService"></dd>
                                    </dl>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered display nowrap" id="tableKeluhan" width="100%">
                                <thead class="bg-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Keluhan</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const iRange = $('#dateRange').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        let noBooking;

        const btnDetail = $('#btnDetail');
        const btnClose = $('#btnClose');

        const cardComponent = $('#cardComponent');
        let vNoBooking = $('#vNoBooking');
        let vNama = $('#vNama');
        let vNoTelp = $('#vNoTelp');
        let vTglBooking = $('#vTglBooking');
        let vJamBooking = $('#vJamBooking');
        let vNoPol = $('#vNoPol');
        let vModel = $('#vModel');
        let vTahunKendaraan = $('#vTahunKendaraan');
        let vTipeService = $('#vTipeService');

        function resetForm() {
            vNoBooking.html('');
            vNama.html('');
            vNoTelp.html('');
            vTglBooking.html('');
            vJamBooking.html('');
            vNoPol.html('');
            vModel.html('');
            vTahunKendaraan.html('');
            vTipeService.html('');
        }

        let tableIndex = $('#tableIndex').DataTable({
            scrollX: true,
            "ajax": {
                "method": "POST",
                "url": "{{ url('booking-general-repair/monitoring/list') }}",
                data: {
                    date_filter: moment().format('YYYY-MM-DD'),
                },
                "header": {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                },
                "complete": function (xhr,responseText) {
                    if (responseText === 'error') {
                        console.log(xhr);
                        console.log(responseText);
                    }
                }
            },
            "columns": [
                { "data": "no_booking" },
                { "data": "nama" },
                { "data": "no_telp" },
                { "data": "no_pol" },
                { "data": "model_kendaraan" },
                { "data": "tahun_kendaraan" },
                { "data": "tgl_booking" },
                { "data": "jam_booking" },
                { "data": "tipe_service" },
            ],
        });
        $('#tableIndex tbody').on( 'click', 'tr', function () {
            let data = tableIndex.row( this ).data();
            noBooking = data.no_booking;
            // console.log(data);
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
                btnDetail.attr('disabled','true');
            } else {
                tableIndex.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                btnDetail.removeAttr('disabled');
                vNoBooking.html(data.no_booking);
                vNama.html(data.nama);
                vNoTelp.html(data.no_telp);
                vTglBooking.html(data.tgl_booking);
                vJamBooking.html(data.jam_booking);
                vNoPol.html(data.no_pol);
                vModel.html(data.model_kendaraan);
                vTahunKendaraan.html(data.tahun_kendaraan);
                vTipeService.html(data.tipe_service);
            }
        });

        let tableKeluhan = $('#tableKeluhan').DataTable({
            autoWidth: false,
            paging: false,
            searching: false,
            bInfo: false,
            "columnDefs": [
                { "width": "6%", "targets": 0}
            ]
        });

        $(document).ready(function () {
            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                $.ajax({
                    url: "{{ url('booking-general-repair/monitoring/list') }}",
                    method: "post",
                    data: {date_filter: picker.startDate.format('YYYY-MM-DD')},
                    success: function (response) {
                        // console.log(response);
                        let data = JSON.parse(response);
                        tableIndex.clear().draw();
                        tableIndex.rows.add(data.data).draw();
                    }
                })
            });

            btnDetail.click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ url('booking-general-repair/monitoring/keluhan') }}",
                    method: 'post',
                    data: {no_booking: noBooking},
                    success: function (response) {
                        // console.log(response);
                        let data = JSON.parse(response);
                        tableKeluhan.clear().draw();
                        let i = 0;
                        data.data.forEach(function (v,i) {
                            i++;
                            tableKeluhan.row.add([i, v.keluhan]).draw();
                        });
                        cardComponent.removeClass('d-none');
                        $('html, body').animate({
                            scrollTop: cardComponent.offset().top
                        }, 500);
                    }
                });
            });
            btnClose.click(function (e) {
                e.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, 500, function () {
                    resetForm();
                    cardComponent.addClass('d-none');
                    btnDetail.attr('disabled','true');
                });
            });
        });
    </script>
@endsection
