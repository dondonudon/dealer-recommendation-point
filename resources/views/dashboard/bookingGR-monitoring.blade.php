@extends('dashboard.layout')

@section('page title','BOOKING GR Monitoring & Follow UP')

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
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="statusFollowUp">Status Follow UP</label>
                                        <select class="form-control form-control-sm" id="statusFollowUp">
                                            <option value="0">Belum Follow Up</option>
                                            <option value="all">Tampilkan Semua</option>
                                            <option value="3">HOT</option>
                                            <option value="2">MEDIUM</option>
                                            <option value="1">LOW</option>
                                        </select>
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
                                    <th>Status FU</th>
                                    <th>User Input</th>
                                    <th>Tanggal Input</th>
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
                                        <dd class="col-sm-8">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <select class="custom-select" id="vHasilFU">
                                                        <option value="0">Belum Follow Up</option>
                                                        <option value="3">HOT</option>
                                                        <option value="2">MEDIUM</option>
                                                        <option value="1">LOW</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                    <button class="btn btn-success btn-block" type="button" id="btnUpdateFU">Simpan</button>
                                                </div>
                                            </div>
                                        </dd>
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
        const loading = '<i class="fas fa-spinner fa-pulse"></i>';

        let iStartDate = moment().startOf('week').format('YYYY-MM-DD');
        let iEndDate = moment().format('YYYY-MM-DD');
        const iRange = $('#dateRange');
        iRange.daterangepicker({
            startDate: moment().startOf('week').format('DD-MM-YYYY'),
            endDate: moment().format('DD-MM-YYYY'),
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        iRange.on('apply.daterangepicker', function(ev, picker) {
            iStartDate = picker.startDate.format('YYYY-MM-DD');
            iEndDate = picker.endDate.format('YYYY-MM-DD');
        });
        const iStatusFU = $('#statusFollowUp');

        let noBooking;

        const btnDetail = $('#btnDetail');
        const btnClose = $('#btnClose');
        const btnUpdateFU = $('#btnUpdateFU');

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
        const vHasilFU = $('#vHasilFU');

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
                {
                    "data": "status_fu",
                    "render": function ( data, type, row, meta ) {
                        let result;
                        switch (data) {
                            case 1:
                                result = 'LOW';
                                break;

                            case 2:
                                result = 'MEDIUM';
                                break;

                            case 3:
                                result = 'HIGH';
                                break;

                            default:
                                result = 'Belum Follow UP';
                                break;
                        }
                        return result;
                    }
                },
                { "data": "username" },
                {
                    "data": "created_at" ,
                    render: function (data,type,row,meta) {
                        return moment(data).format('DD-MM-YYYY HH:MM:SS');
                    }
                },
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

        function updateTableIndex() {
            $.ajax({
                url: "{{ url('booking-general-repair/monitoring-dan-follow-up/list') }}",
                method: "post",
                data: {
                    start_date: iStartDate,
                    end_date: iEndDate,
                    status_fu: iStatusFU.val()
                },
                success: function(response) {
                    // console.log(response);
                    let data = JSON.parse(response);
                    tableIndex.clear().draw();
                    tableIndex.rows.add(data.data).draw();
                }
            })
        }

        $(document).ready(function () {
            updateTableIndex();
            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                iStartDate = picker.startDate.format('YYYY-MM-DD');
                iEndDate = picker.endDate.format('YYYY-MM-DD');
                updateTableIndex();
            });
            iStatusFU.change(function (e) {
                e.preventDefault();
                updateTableIndex();
            });

            btnDetail.click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ url('booking-general-repair/monitoring-dan-follow-up/keluhan') }}",
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
            btnUpdateFU.click(function (e) {
                e.preventDefault();

                let txtDef = btnUpdateFU.html();
                btnUpdateFU.html(loading);
                btnUpdateFU.attr('disabled',true);

                $.ajax({
                    url: "{{ url('booking-general-repair/monitoring-dan-follow-up/update-fu') }}",
                    method: "post",
                    data: {hasil_fu: vHasilFU.val(), no_booking: noBooking},
                    success: function (response) {
                        console.log(response);
                        if (response === 'success') {
                            updateTableIndex();
                            btnUpdateFU.html(txtDef);
                            btnUpdateFU.removeAttr('disabled');
                            Swal.fire({
                                type: 'success',
                                title: 'Data tersimpan',
                            })
                        } else {
                            console.log(response);
                            btnUpdateFU.html(txtDef);
                            btnUpdateFU.removeAttr('disabled');
                            Swal.fire({
                                type: 'error',
                                title: 'Gagal menyimpan data',
                                text: 'Silahkan coba lagi',
                            })
                        }
                    }
                })
            })
        });
    </script>
@endsection
