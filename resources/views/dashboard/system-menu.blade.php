@extends('dashboard.layout')

@section('page title','System Menu')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg">

                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <table class="table table-sm table-bordered display nowrap" id="tableIndex" width="100%">
                                <thead class="bg-dark">
                                    <tr>
                                        <th>Group</th>
                                        <th>Nama</th>
                                        <th>URL</th>
                                        <th>Nama Segment</th>
                                        <th>Order</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-block btn-outline-danger" id="btnHapus">Hapus</button>
                                </div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-block btn-warning" id="btnEdit">Edit</button>
                                </div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-block btn-primary" id="btnBaru">Baru</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="cardData" class="card card-success card-outline d-none">
                        <div class="card-header">
                            <h3 class="card-title">Tambah data baru</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-outline-danger" id="btnClose">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <form id="dataForm">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="group">Pilih Group</label>
                                    <select id="group" name="group"></select>
                                </div>
                                <div class="form-group">
                                    <label for="group">Nama Menu</label>
                                    <input type="text" class="form-control" id="nama" name="nama">
                                </div>
                                <div class="form-group">
                                    <label for="group">URL / Link</label>
                                    <input type="text" class="form-control" id="url" name="url">
                                </div>
                                <div class="form-group">
                                    <label for="group">Nama Laravel Segment</label>
                                    <input type="text" class="form-control" id="segment_name" name="segment_name">
                                </div>
                                <div class="form-group">
                                    <label for="group">Menu Order</label>
                                    <input type="number" class="form-control" min="1" id="ord" name="ord">
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-10"></div>
                                    <div class="col-lg-2 mt-2 mt-sm-0">
                                        <button type="submit" class="btn btn-block btn-success">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('script')
    <script>
        const btnHapus = document.getElementById('btnHapus');
        const btnBaru = document.getElementById('btnBaru');

        const cardData = document.getElementById('cardData');
        const group = new SlimSelect({
            select: '#group'
        });
        let groupData;

        const iGroup = document.getElementById('group');
        const iNama = document.getElementById('nama');
        const iUrl = document.getElementById('url');
        const iSegment = document.getElementById('segment_name');
        const iOrder = document.getElementById('ord');

        function serializeArray(data) {
            let result = '';
            data.forEach(function (value,index) {
                if (result === '') {
                    result += index+'='+value;
                } else {
                    result += '&'+index+'='+value;
                }
            })
        }

        function requestData(data,url,cFunc) {
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    cFunc(this);
                }
            };
            xhttp.open('POST', url, true);
            xhttp.send('key=val&kev2=val2');
        }

        function newData(xhttp) {
            console.log(xhttp);
        }

        const tableIndex = $('#tableIndex').DataTable({
            "ajax": {
                "method": "POST",
                "url": "{{ url('/system-utility/menu/list') }}",
                "header": {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                },
                "complete": function (xhr,responseText) {
                    if (responseText == 'error') {
                        console.log(xhr);
                        console.log(responseText);
                    }
                }
            },
            "columns": [
                { "data": "group" },
                { "data": "nama" },
                { "data": "url" },
                { "data": "segment_name" },
                { "data": "ord" },
            ],
        });

        btnBaru.onclick = function (e) {
            e.preventDefault();
            cardData.classList.remove('d-none');
            group.setData([
                {text: 'System Utility', value: '1'}
            ]);
            let data = [
                {group: iGroup.value},
                {group: iNama.value},
                {group: iUrl.value},
                {group: iSegment.value},
                {group: iOrder.value},
            ];
            console.log(data);
        }
    </script>
@endsection
