@extends('dashboard.layout')

@section('page title','REMINDER SERVICE')

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
                                        <select class="form-control form-control-sm" id="statusFollowUp" disabled>
                                            <option value="0">Belum Follow Up</option>
                                            <option value="all">Tampilkan Semua</option>
                                            <option value="1">BOOK</option>
                                            <option value="2">RESCHEDULE</option>
                                            <option value="3">CANCEL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4"></div>
                                <div class="col-lg-2">
                                    <button class="btn btn-block btn-outline-primary" id="btnExport">
                                        <i class="fas fa-file-excel mr-3"></i> EXPORT Excel
                                    </button>
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
                                    <th>Target User FU</th>
                                    <th>Status FU</th>
                                    <th>Status Kehadiran</th>
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

                                        <dt class="col-sm-4">Target User FU</dt>
                                        <dd class="col-sm-8" id="vTrgtUserFU"></dd>
                                    </dl>
                                </div>
                            </div>
                            <label for="tableNotes">History Follow UP:</label>
                            <table class="table table-sm table-bordered display nowrap" id="tableNotes" width="100%">
                                <thead class="bg-dark">
                                <tr>
                                    <th>Waktu Follow Up</th>
                                    <th>User Follow UP</th>
                                    <th>Status FU</th>
                                    <th>Catatan</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="vHasilFU">Hasil Follow UP</label>
                                                <select class="custom-select" id="vHasilFU">
                                                    <option value="0">Belum Follow Up</option>
                                                    <option value="1">BOOKING</option>
                                                    <option value="2">Re-Schedule</option>
                                                    <option value="3" class="bg-red">Cancel</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group d-none" id="DOMTglReschedule">
                                                <label for="tglReschedule">Tanggal Reschedule</label>
                                                <input type="text" class="form-control" id="tglReschedule">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputCatatan">Input Catatan</label>
                                        <input type="text" class="form-control" id="inputCatatan" placeholder="Catatan">
                                    </div>
                                </div>
                                <div class="col-lg-2"></div>
                                <div class="col-lg-2 mt-2 mt-sm-2">
                                    <div class="btn-group btn-block d-none" id="btnCustomerDatang" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-outline-secondary" onclick="updateDatang('2')">Tidak Datang</button>
                                        <button type="button" class="btn btn-primary" onclick="updateDatang('1')">Datang</button>
                                    </div>
                                </div>
                                <div class="col-lg-2 mt-2 mt-sm-2">
                                    <button class="btn btn-success btn-block" type="button" id="btnUpdateFU">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <iframe id="downloadFile" style="display: none;"></iframe>
@endsection

@section('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const loading = '<i class="fas fa-spinner fa-pulse"></i>';

        let iStartDate = moment().format('YYYY-MM-DD');
        let iEndDate = moment().add(7,'days').format('YYYY-MM-DD');
        const iRange = $('#dateRange');
        iRange.daterangepicker({
            startDate: moment().format('DD-MM-YYYY'),
            endDate: moment().add(7,'days').format('DD-MM-YYYY'),
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        iRange.on('apply.daterangepicker', function(ev, picker) {
            iStartDate = picker.startDate.format('YYYY-MM-DD');
            iEndDate = picker.endDate.format('YYYY-MM-DD');
        });
        const iTglReschedule = $('#tglReschedule');
        iTglReschedule.daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            minDate: moment().format('HH:mm DD-MM-YYYY'),
            drops: 'up',
            locale: {
                format: 'HH:mm DD-MM-YYYY'
            }
        });
        const iStatusFU = $('#statusFollowUp');

        let noBooking,tglReschedule,jamReschedule;

        const btnDetail = $('#btnDetail');
        const btnClose = $('#btnClose');
        const btnExport = $('#btnExport');
        const btnUpdateFU = $('#btnUpdateFU');
        const btnCustDatang = $('#btnCustomerDatang');

        const DownloadFile = document.getElementById('downloadFile');
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
        const vInputCatatan = $('#inputCatatan');
        const vTrgtUserFU = $('#vTrgtUserFU');

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
            vInputCatatan.val('');
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
                { "data": "user_fu" },
                {
                    "data": "status_fu",
                    "render": function ( data, type, row, meta ) {
                        let result;
                        switch (parseInt(data)) {
                            case 1:
                                result = 'Booking';
                                break;

                            case 2:
                                result = 'Reschedule';
                                break;

                            case 3:
                                result = 'Cancel';
                                break;

                            default:
                                result = 'Belum Follow UP';
                                break;
                        }
                        return result;
                    }
                },
                {
                    "data": "isDatang",
                    "render": function ( data, type, row, meta ) {
                        let result;
                        switch (parseInt(data)) {
                            case 1:
                                result = 'Datang';
                                break;

                            case 2:
                                result = 'Tidak Datang';
                                break;

                            default:
                                result = 'Belum Booking';
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
                vTrgtUserFU.html(data.user_fu);
                vTipeService.html(data.tipe_service);
                vHasilFU.val(data.status_fu);
            }
        });

        let tableNotes = $('#tableNotes').DataTable({
            autoWidth: false,
            paging: false,
            searching: false,
            bInfo: false,
            "columnDefs": [
                { "width": "20%", "targets": 0},
                { "width": "20%", "targets": 1},
                { "width": "10%", "targets": 2},
            ],
            "columns": [
                { "data": "created_at" },
                { "data": "user_fu" },
                {
                    "data": "status_fu",
                    render: function(data, type, row, meta) {
                        let result;
                        switch (parseInt(data)) {
                            case 0:
                                result = 'Belum Follow UP';
                                break;

                            case 1:
                                result = 'Booking';
                                break;

                            case 2:
                                result = 'Reschedule';
                                break;

                            case 3:
                                result = 'Cancel';
                                break;
                        }
                        return result;
                    }
                },
                { "data": "note" },
            ],
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

        function getTableNotes() {
            $.ajax({
                url: "{{ url('booking-general-repair/monitoring-dan-follow-up/notes') }}",
                method: 'post',
                data: {no_booking: noBooking},
                success: function (response) {
                    // console.log(response);
                    let data = JSON.parse(response);
                    tableNotes.clear().draw();
                    tableNotes.rows.add(data.data).draw();
                    // let i = 0;
                    // data.data.forEach(function (v,i) {
                    //     i++;
                    //     tableNotes.row.add([i, v.keluhan]).draw();
                    // });
                    cardComponent.removeClass('d-none');
                    $('html, body').animate({
                        scrollTop: cardComponent.offset().top
                    }, 500);
                }
            });
        }

        function setTglReschedule(ev,picker) {
            tglReschedule = picker.startDate.format('YYYY-MM-DD');
            jamReschedule = picker.endDate.format('HH:mm:ss');
        }

        function updateDatang(status) {
            $.ajax({
                url: '{{ url('booking-general-repair/monitoring-dan-follow-up/update-datang') }}',
                method: 'post',
                data: {
                    no_booking: noBooking,
                    status: status
                },
                success: function (response) {
                    if (response === 'success') {
                        Swal.fire({
                            type: 'success',
                            title: 'Data tersimpan',
                        });
                    } else {
                        console.log(response);
                        Swal.fire({
                            type: 'error',
                            title: 'Gagal tersimpan',
                        });
                    }
                }
            })
        }

        $(document).ready(function () {
            updateTableIndex();
            iTglReschedule.on('apply.daterangepicker', function(ev, picker) {
                setTglReschedule(ev, picker);
            });
            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                iStartDate = picker.startDate.format('YYYY-MM-DD');
                iEndDate = picker.endDate.format('YYYY-MM-DD');
                updateTableIndex();
            });
            iStatusFU.change(function (e) {
                e.preventDefault();
                updateTableIndex();
            });
            vHasilFU.change(function () {
                if (vHasilFU.val() === '2') {
                    $('#DOMTglReschedule').removeClass('d-none');
                    btnCustDatang.addClass('d-none');
                } else if (vHasilFU.val() === '1') {
                    btnCustDatang.removeClass('d-none');
                } else {
                    $('#DOMTglReschedule').addClass('d-none');
                    btnCustDatang.addClass('d-none');
                }
            });

            btnExport.click(function (e) {
                e.preventDefault();
                let url = '{{ url('booking-general-repair/monitoring-dan-follow-up/export') }}/'+iStartDate+'/'+iEndDate+'/'+iStatusFU.val();
                DownloadFile.src = url;
                console.log(url);

            });
            btnDetail.click(function (e) {
                e.preventDefault();
                getTableNotes();
                if (vHasilFU.val() == '1') {
                    btnCustDatang.removeClass('d-none');
                }
                if (vHasilFU.val() == '2') {
                    $('#DOMTglReschedule').removeClass('d-none');
                }
            });
            btnClose.click(function (e) {
                e.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, 500, function () {
                    resetForm();
                    updateTableIndex();
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
                    data: {
                        hasil_fu: vHasilFU.val(),
                        no_booking: noBooking,
                        catatan: vInputCatatan.val(),
                        tgl_reschedule: tglReschedule,
                        jam_reschedule: jamReschedule,
                    },
                    success: function (response) {
                        console.log(response);
                        if (response === 'success') {
                            updateTableIndex();
                            getTableNotes();
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
