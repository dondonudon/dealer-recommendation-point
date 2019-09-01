@extends('dashboard.layout')

@section('page title','SALES PROSPECT Input Prospect')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/filepond-master/filepond.min.css') }}">
@endsection

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
                                <div class="col-lg-7"></div>
                                <div class="col-lg-2">
                                    <button class="btn btn-block btn-outline-primary" id="btnUploadFile">
                                        <i class="fas fa-upload"></i> Upload File
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered display nowrap" id="tableIndex" width="100%">
                                <thead class="bg-dark">
                                <tr>
                                    <th>No ID</th>
                                    <th>Nama Cust.</th>
                                    <th>No Telp</th>
                                    <th>Model Kendaraan</th>
                                    <th>Kabupaten</th>
                                    <th>Kecamatan</th>
                                    <th>Alamat</th>
                                    <th>Pekerjaan</th>
                                    <th>Opsi Bayar</th>
                                    <th>Salesman</th>
                                    <th>Status Follow UP</th>
                                    <th>Waktu Telp</th>
                                    <th>Username</th>
                                    <th>Tanggal Input</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div id="cardComponent" class="card card-success card-outline d-none">
                        <div class="card-header">
                            <h3 class="card-title">Upload File</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-light" id="btnClose">
                                    <i class="fas fa-times" style="color: red;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg">
                                    <input id="cardUpload_uploadFile" type="file">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-3">
                                    <button class="btn btn-block btn-outline-secondary" id="btnSample">
                                        <i class="fas fa-download"></i> Download Sample
                                    </button>
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
    <script src="{{ asset('vendor/filepond-master/filepond.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const loading = '<i class="fas fa-spinner fa-pulse"></i>';
        const uploadArea = document.getElementById('cardUpload_uploadFile');

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

        const btnSimpan = $('#btnSimpan');
        const btnUpload = $('#btnUploadFile');
        const btnClose = $('#btnClose');
        const btnSample = $('#btnSample');

        const cardComponent = $('#cardComponent');

        let tableIndex = $('#tableIndex').DataTable({
            scrollX: true,
            "columns": [
                { "data": "no_sales" },
                { "data": "nama_customer" },
                { "data": "no_telephone" },
                { "data": "model_kendaraan" },
                { "data": "kabupaten" },
                { "data": "kecamatan" },
                { "data": "alamat" },
                { "data": "pekerjaan" },
                { "data": "kebutuhan" },
                { "data": "salesman" },
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
                { "data": "waktu_telp" },
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
            $(this).toggleClass('selected');
            if (tableIndex.rows('.selected').data().length > 0) {
                btnSimpan.removeAttr('disabled');
            } else {
                btnSimpan.attr('disabled','true');
            }
        });

        function updateTableIndex() {
            $.ajax({
                url: "{{ url('sales-prospect/monitoring/list') }}",
                method: "post",
                data: {
                    start_date: iStartDate,
                    end_date: iEndDate,
                    status_fu: 'all'
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

            btnUpload.click(function (e) {
                e.preventDefault();
                cardComponent.removeClass('d-none');
                $('html, body').animate({
                    scrollTop: cardComponent.offset().top
                }, 500);
            });
            btnClose.click(function (e) {
                e.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, 500, function () {
                    cardComponent.addClass('d-none');
                    updateTableIndex();
                });
            });
            btnSample.click(function (e) {
                e.preventDefault();
                window.open('{{ url('sales-prospect/input-prospect/sample') }}');
            });

            FilePond.create( uploadArea );
            FilePond.setOptions({
                allowMultiple: false,
                allowDrop: true,
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort) => {
                        const formData = new FormData();
                        formData.append(fieldName, file, file.name);

                        const request = new XMLHttpRequest();
                        request.open('POST','{{ url('sales-prospect/input-prospect/upload') }}');
                        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                        request.upload.onprogress = (e) => {
                            progress(e.lengthComputable, e.loaded, e.total);
                        };

                        request.onload = function () {
                            if (request.status >= 200 && request.status < 300) {
                                load(request.responseText);
                                console.log(request.responseText);
                            } else {
                                error('gagal');
                            }
                        };

                        request.send(formData);

                        return {
                            abort: () => {
                                request.abort();

                                abort();
                            }
                        }
                    }
                }
            });

        });
    </script>
@endsection
