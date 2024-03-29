@extends('dashboard.layout')

@section('page title','BP ESTIMATION Monitoring & Follow UP')

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
                                        <label for="dateRange">Tanggal Input</label>
                                        <input type="text" class="form-control form-control-sm" id="dateRange">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="statusFollowUp">Status Follow UP</label>
                                        <select class="form-control form-control-sm" id="statusFollowUp">
                                            <option value="0">Belum Follow Up</option>
                                            <option value="all">Tampilkan Semua</option>
                                            <option value="1">BOOK</option>
                                            <option value="2">RESCHEDULE</option>
                                            <option value="3">CANCEL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered display nowrap" id="tableIndex" width="100%">
                                <thead class="bg-dark">
                                <tr>
                                    <th>No Estimasi</th>
                                    <th>Nama</th>
                                    <th>No Telp</th>
                                    <th>No Pol</th>
                                    <th>Model Kendaraan</th>
                                    <th>Grand Total</th>
                                    <th>Status FU</th>
                                    <th>Username</th>
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
                                        <dt class="col-sm-4">Nomor Estimasi</dt>
                                        <dd class="col-sm-8" id="vNoEstimasi"></dd>

                                        <dt class="col-sm-4">Nama</dt>
                                        <dd class="col-sm-8" id="vNama"></dd>

                                        <dt class="col-sm-4">Nomor Telp.</dt>
                                        <dd class="col-sm-8" id="vNoTelp"></dd>
                                    </dl>
                                </div>
                                <div class="col-lg-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">No Polisi</dt>
                                        <dd class="col-sm-8" id="vNoPol"></dd>

                                        <dt class="col-sm-4">Model</dt>
                                        <dd class="col-sm-8" id="vModel"></dd>

                                        <dt class="col-sm-4">Grand Total</dt>
                                        <dd class="col-sm-8">Rp <span id="vGrandTotal"></span></dd>
                                    </dl>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered display nowrap" id="tableKeluhan" width="100%">
                                <thead class="bg-dark">
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Sub Total</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-4">
                                    <select class="custom-select" id="vHasilFU">
                                        <option value="0">Belum Follow Up</option>
                                        <option value="1">BOOKING</option>
                                        <option value="2">Re-Schedule</option>
                                        <option value="3" class="bg-red">Cancel</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
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
@endsection

@section('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const loading = '<i class="fas fa-spinner fa-pulse"></i>';

        let iStartDate = moment().subtract(7,'days').format('YYYY-MM-DD');
        let iEndDate = moment().format('YYYY-MM-DD');
        const iRange = $('#dateRange');
        iRange.daterangepicker({
            startDate: moment().subtract(7,'days').format('DD-MM-YYYY'),
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

        let noEstimasi;

        const btnDetail = $('#btnDetail');
        const btnClose = $('#btnClose');
        const btnUpdateFU = $('#btnUpdateFU');

        const cardComponent = $('#cardComponent');
        const vNoEstimasi = $('#vNoEstimasi');
        const vNama = $('#vNama');
        const vNoTelp = $('#vNoTelp');
        const vNoPol = $('#vNoPol');
        const vModel = $('#vModel');
        const vGrandTotal = $('#vGrandTotal');
        const vHasilFU = $('#vHasilFU');

        function resetForm() {
            vNoEstimasi.html('');
            vNama.html('');
            vNoTelp.html('');
            vNoPol.html('');
            vModel.html('');
            vGrandTotal.html('');
        }

        const tableIndex = $('#tableIndex').DataTable({
            scrollX: true,
            "columns": [
                { "data": "no_estimation" },
                { "data": "nama" },
                { "data": "no_telp" },
                { "data": "no_pol" },
                { "data": "model_kendaraan" },
                { "data": "grand_total" },
                {
                    "data": "status_fu",
                    "render": function ( data, type, row, meta ) {
                        let result;
                        switch (parseInt(data)) {
                            case 1:
                                result = 'BOOK';
                                break;

                            case 2:
                                result = 'RESCHEDULE';
                                break;

                            case 3:
                                result = 'CANCEL';
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
            noEstimasi = data.no_estimation;
            // console.log(data);
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
                btnDetail.attr('disabled','true');
            } else {
                tableIndex.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                btnDetail.removeAttr('disabled');
                vNoEstimasi.html(data.no_estimation);
                vNama.html(data.nama);
                vNoTelp.html(data.no_telp);
                vNoPol.html(data.no_pol);
                vModel.html(data.model_kendaraan);
                vGrandTotal.html(data.grand_total);
                vHasilFU.val(data.status_fu);
            }
        });

        let tableKeluhan = $('#tableKeluhan').DataTable({
            autoWidth: false,
            paging: false,
            searching: false,
            bInfo: false,
            "columnDefs": [
                { "width": "60%", "targets": 0},
                { "width": "10%", "targets": 1},
                { "width": "30%", "targets": 2, "className": 'text-right'},
            ],
            "columns": [
                { "data": "item" },
                { "data": "qty" },
                {
                    "data": "subtotal",
                    render: function(data, type, row, meta) {
                        return numeral(data).format('0,0.00');
                    }
                },
            ],
        });

        function updateTableIndex() {
            $.ajax({
                url: "{{ url('body-paint-estimation/monitoring-dan-follow-up/list') }}",
                method: "post",
                data: {
                    start_date: iStartDate,
                    end_date: iEndDate,
                    status_fu: iStatusFU.val()
                },
                success: function(response) {
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
                    url: "{{ url('body-paint-estimation/monitoring-dan-follow-up/trn') }}",
                    method: 'post',
                    data: {no_estimation: noEstimasi},
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        tableKeluhan.clear().draw();
                        tableKeluhan.rows.add(data.data);
                        tableKeluhan.draw();
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
                    url: "{{ url('body-paint-estimation/monitoring-dan-follow-up/update-fu') }}",
                    method: "post",
                    data: {hasil_fu: vHasilFU.val(), no_estimasi: noEstimasi},
                    success: function (response) {
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
