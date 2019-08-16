@extends('dashboard.layout')

@section('page title','SALES PROSPECT Monitoring')

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
                                            <option value="3">HOT</option>
                                            <option value="2">MEDIUM</option>
                                            <option value="1">LOW</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="filterSalesman">Filter Salesman</label>
                                        <select id="filterSalesman"></select>
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
        let salesmanData = [{
            text: 'Semua salesman',
            value: 'all',
        }];
        const iSalesman = new SlimSelect({
            select: '#filterSalesman',
            data: salesmanData
        });


        const tableIndex = $('#tableIndex').DataTable({
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
                        switch (parseInt(data)) {
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

        function updateTableIndex() {
            $.ajax({
                url: "{{ url('sales-prospect/monitoring/list') }}",
                method: "post",
                data: {
                    start_date: iStartDate,
                    end_date: iEndDate,
                    status_fu: iStatusFU.val(),
                    salesman: $('#filterSalesman').val(),
                },
                success: function(response) {
                    // console.log(response);
                    let data = JSON.parse(response);
                    tableIndex.clear().draw();
                    tableIndex.rows.add(data.data).draw();
                }
            })
        }

        function updateFilterSalesman() {
            $.ajax({
                url: "{{ url('master-data/salesman/list') }}",
                method: "post",
                success: function(response) {
                    // console.log(response);
                    let data = JSON.parse(response);
                    data.data.forEach(function (v,i) {
                        salesmanData.push({
                            text: v.username,
                            value: v.username,
                        })
                    });
                    iSalesman.setData(salesmanData);
                }
            })
        }

        $(document).ready(function () {
            updateFilterSalesman();
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

            $('#filterSalesman').change(function () {
                updateTableIndex();
            })

        });
    </script>
@endsection
