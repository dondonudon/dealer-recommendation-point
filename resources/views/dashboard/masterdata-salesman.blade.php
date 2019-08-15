@extends('dashboard.layout')

@section('page title','Master Data Salesman')

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
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>No Telp</th>
                                    <th>Email</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-8"></div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-block btn-outline-danger" id="btnHapus" disabled>Hapus</button>
                                </div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-block btn-primary" id="btnBaru">Baru</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="cardComponent" class="card card-success card-outline d-none">
                        <div class="card-header">
                            <h3 class="card-title">Tambah data baru</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-light" id="btnClose">
                                    <i class="fas fa-times" style="color: red;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered display nowrap" id="tableUser" width="100%">
                                <thead class="bg-dark">
                                <tr>
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>No Telp</th>
                                    <th>Email</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-10"></div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-block btn-primary" id="btnTambah" disabled>Tambah</button>
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

        const btnHapus = $('#btnHapus');
        const btnBaru = $('#btnBaru');
        const btnClose = $('#btnClose');

        const cardComponent = $('#cardComponent');
        const btnTambah = $('#btnTambah');
        let username = '';

        $.ajax({
            url: "{{ url('/master-data/salesman/user') }}",
            method: 'post',
            data: $(this).serialize(),
            success: function (response) {
                console.log(response);
            }
        });

        const tableIndex = $('#tableIndex').DataTable({
            scrollX: true,
            "ajax": {
                "method": "POST",
                "url": "{{ url('/master-data/salesman/list') }}",
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
                { "data": "username" },
                { "data": "nama_lengkap" },
                { "data": "no_telp" },
                { "data": "email" },
            ],
        });
        $('#tableIndex tbody').on( 'click', 'tr', function () {
            let data = tableIndex.row( this ).data();
            username = data.username;
            // console.log(data);
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
                btnHapus.attr('disabled','true');
            } else {
                tableIndex.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                btnHapus.removeAttr('disabled');
            }
        });

        const tableUser = $('#tableUser').DataTable({
            scrollX: true,
            "ajax": {
                "method": "POST",
                "url": "{{ url('/master-data/salesman/user') }}",
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
                { "data": "username" },
                { "data": "nama_lengkap" },
                { "data": "no_telp" },
                { "data": "email" },
            ],
        });
        $('#tableUser tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('selected');
            if (tableUser.rows('.selected').data().length > 0) {
                btnTambah.removeAttr('disabled');
            } else {
                btnTambah.attr('disabled','true');
            }
        });

        $(document).ready(function () {
            /*
            Button Action
             */
            btnBaru.click(function (e) {
                e.preventDefault();
                cardComponent.removeClass('d-none');
                $('html, body').animate({
                    scrollTop: cardComponent.offset().top
                }, 500);
            });
            btnHapus.click(function (e) {
                e.preventDefault();
                Swal.fire({
                    title: "Hapus sales berikut?",
                    text: username,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus Data'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ url('master-data/salesman/delete') }}',
                            method: 'post',
                            data: {username: username},
                            success: function (response) {
                                console.log(response);
                                if (response === 'success') {
                                    Swal.fire({
                                        title: 'Data terhapus!',
                                        type: 'success',
                                        onClose: function () {
                                            tableIndex.ajax.reload();
                                            tableUser.ajax.reload();
                                        }
                                    })
                                } else {
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: 'Silahkan coba lagi',
                                        type: 'error',
                                    })
                                }
                            }
                        });
                    }
                });
            });
            btnTambah.click(function (e) {
                e.preventDefault();
                let data = tableUser.rows('.selected').data();
                let dtArr = [];

                for (let i = 0; i < data.length; i++) {
                    dtArr.push(data[i]);
                }
                let dtSend = JSON.stringify(dtArr);

                $.ajax({
                    url: '{{ url('master-data/salesman/add') }}',
                    method: 'post',
                    data: {data: dtSend},
                    success: function (response) {
                        if (response === 'success') {
                            tableIndex.ajax.reload();
                            tableUser.ajax.reload();
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
            });
            btnClose.click(function (e) {
                e.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, 500, function () {
                    cardComponent.addClass('d-none');
                    tableIndex.ajax.reload();
                    tableUser.ajax.reload();
                    btnEdit.attr('disabled','true');
                    btnHapus.attr('disabled','true');
                });
            });
        });
    </script>
@endsection
