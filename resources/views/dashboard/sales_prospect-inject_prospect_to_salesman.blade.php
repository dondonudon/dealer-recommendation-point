@extends('dashboard.layout')

@section('page title','SALES PROSPECT Inject to Salesman')

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
                                        <label for="dateRange">Inject to Salesman</label>
                                        <select id="salesman"></select>
                                    </div>
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
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-10"></div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-block btn-primary" id="btnSimpan" disabled>Simpan</button>
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
        const iSalesman = new SlimSelect({
            select: '#salesman',
            placeholder: 'Pilih target salesman'
        });

        const btnSimpan = $('#btnSimpan');

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
                url: "{{ url('sales-prospect/inject-to-salesman/list') }}",
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

        function getSalesman() {
            $.ajax({
                url: '{{ url('master-data/salesman/list') }}',
                method: 'post',
                success: function(response) {
                    // console.log(response);
                    let data = JSON.parse(response);
                    let arr = [
                        {'placeholder': true, 'text': 'Pilih Target Salesman'}
                    ];
                    data.data.forEach(function(v,i) {
                        arr.push({
                            text: v['username'],
                            value: v['username']
                        });
                    });
                    iSalesman.setData(arr);
                }
            });
        }


        $(document).ready(function () {
            updateTableIndex();
            getSalesman();
            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                iStartDate = picker.startDate.format('YYYY-MM-DD');
                iEndDate = picker.endDate.format('YYYY-MM-DD');
                updateTableIndex();
            });

            btnSimpan.click(function (e) {
                e.preventDefault();
                let defBtn = btnSimpan.html();
                btnSimpan.html(loading);
                btnSimpan.attr('disabled',true);
                let domSalesman = $('#salesman');
                if (domSalesman.val() === 'Pilih Target Salesman') {
                    Swal.fire({
                        title: 'Target salesman tidak boleh kosong',
                        type: 'warning',
                    })
                } else {
                    let data = tableIndex.rows('.selected').data();
                    let dtArr = [];

                    for (let i = 0; i < data.length; i++) {
                        dtArr.push(data[i]);
                    }
                    let dtSend = JSON.stringify(dtArr);

                    $.ajax({
                        url: '{{ url('sales-prospect/inject-to-salesman/inject') }}',
                        method: 'post',
                        data: {data: dtSend, salesman:domSalesman.val()},
                        success: function (response) {
                            btnSimpan.html(defBtn);
                            console.log(response);
                            if (response === 'success') {
                                updateTableIndex();
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Sales berhasil ditambahkan',
                                    type: 'success',
                                })
                            } else {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Silahkan coba lagi',
                                    type: 'error',
                                })
                            }
                        }
                    })
                }

            })
        });
    </script>
@endsection
