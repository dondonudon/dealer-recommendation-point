@extends('dashboard.layout')

@section('page title','User Management')

@php
    $sidebar = \App\Http\Controllers\DashboardSysUser::menu()
@endphp

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
                                    <th>Status</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-2">
                                    <button type="button" class="btn btn-block btn-info" id="btnReset" disabled>Reset Password</button>
                                </div>
                                <div class="col-lg-4"></div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-block btn-outline-danger" id="btnHapus" disabled>Disable</button>
                                </div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-block btn-warning" id="btnEdit" disabled>Edit</button>
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
                        <form id="dataForm">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" id="inputType" value="new">
                                <div class="form-group">
                                    <label for="group">Username</label>
                                    <input type="text" class="form-control" id="username" name="username">
                                    <small id="emailHelp" class="form-text text-muted">Password untuk user baru sama seperti username</small>
                                </div>
                                <hr style="border-width: 10px;">
                                @foreach($sidebar as $s)
                                    <div class="form-group row">
                                        <div class="col-sm-2">{{ $s['group'] }}</div>
                                        <div class="col-sm-10">
                                            <div class="row">
                                                @foreach($s['menu'] as $m)
                                                    <div class="col-sm-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="permission_{{ $m['id'] }}" name="menu_permission[]" value="{{ $m['id'] }}">
                                                            <label class="form-check-label" for="permission_{{ $m['id'] }}">{{ $m['nama'] }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const btnHapus = $('#btnHapus');
        const btnEdit = $('#btnEdit');
        const btnBaru = $('#btnBaru');
        const btnReset = $('#btnReset');
        const btnClose = $('#btnClose');

        const cardComponent = $('#cardComponent');
        let groupData = [];

        const dataForm = $('#dataForm');
        const inputType = $('#inputType');
        const iUsername = $('#username');
        const iCheckbox = $('form input:checkbox');

        let selectedData;

        function resetForm() {
            iUsername.val('');
            iUsername.prop('readonly',false);
            iCheckbox.prop('checked',false);
        }

        const tableIndex = $('#tableIndex').DataTable({
            "ajax": {
                "method": "POST",
                "url": "{{ url('/system-utility/user-management/list') }}",
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
                { data: "username" },
                {
                    data: "isDel",
                    render: function ( data, type, row ) {
                        if (data === 0) {
                            return 'Active';
                        } else {
                            return 'Disabled';
                        }
                    }
                },
            ],
        });
        $('#tableIndex tbody').on( 'click', 'tr', function () {
            let data = tableIndex.row( this ).data();
            iUsername.val(data.username);
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
                btnEdit.attr('disabled','true');
                btnHapus.attr('disabled','true');
                btnReset.attr('disabled','true');
            } else {
                tableIndex.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                btnEdit.removeAttr('disabled');
                btnHapus.removeAttr('disabled');
                btnReset.removeAttr('disabled');
            }
        });

        $(document).ready(function () {
            /*
            Button Action
             */
            btnBaru.click(function (e) {
                e.preventDefault();
                inputType.val('new');
                resetForm();
                cardComponent.removeClass('d-none');
                $('html, body').animate({
                    scrollTop: cardComponent.offset().top
                }, 500);
            });
            btnEdit.click(function (e) {
                e.preventDefault();
                inputType.val('edit');
                iUsername.attr('readonly','true');
                $.ajax({
                    url: '{{ url('system-utility/user-management/user-permission') }}',
                    method: 'post',
                    data: {username: iUsername.val()},
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        data.forEach(function (v,i) {
                            $('#permission_'+v.id_menu).prop('checked',true);
                        })
                    }
                });
                cardComponent.removeClass('d-none');
                $('html, body').animate({
                    scrollTop: cardComponent.offset().top
                }, 500);
            });
            btnHapus.click(function (e) {
                e.preventDefault();
                Swal.fire({
                    title: "Disable user "+iUsername.val(),
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Disabled'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ url('system-utility/user-management/delete') }}',
                            method: 'post',
                            data: {username: iUsername.val()},
                            success: function (response) {
                                console.log(response);
                                if (response === 'success') {
                                    Swal.fire({
                                        title: 'Data tersimpan!',
                                        type: 'success',
                                        onClose: function () {
                                            tableIndex.ajax.reload();
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
            btnReset.click(function (e) {
                e.preventDefault();
                Swal.fire({
                    title: "Reset password user "+iUsername.val()+"?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Reset'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ url('system-utility/user-management/reset') }}',
                            method: 'post',
                            data: {username: iUsername.val()},
                            success: function (response) {
                                console.log(response);
                                if (response === 'success') {
                                    Swal.fire({
                                        title: 'Reset password berhasil!',
                                        text: 'Password sama seperti username',
                                        type: 'success',
                                        onClose: function () {
                                            tableIndex.ajax.reload();
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
            btnClose.click(function (e) {
                e.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, 500, function () {
                    resetForm();
                    cardComponent.addClass('d-none');
                    tableIndex.ajax.reload();
                    btnEdit.attr('disabled','true');
                    btnHapus.attr('disabled','true');
                });
            });

            /*
            SUBMIT DATA
            First: Check new or edit data
             */
            dataForm.submit(function (e) {
                e.preventDefault();
                let url;
                if (inputType.val() === 'new') {
                    url = "{{ url('system-utility/user-management/add') }}";
                } else {
                    url = "{{ url('system-utility/user-management/edit') }}";
                }
                $.ajax({
                    url: url,
                    method: 'post',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response === 'success') {
                            Swal.fire({
                                type: 'success',
                                title: 'Data Tersimpan',
                                onClose: function () {
                                    $("html, body").animate({ scrollTop: 0 }, 500, function () {
                                        cardComponent.addClass('d-none');
                                        tableIndex.ajax.reload();
                                    });
                                }
                            })
                        } else {
                            Swal.fire(
                                'Gagal!',
                                'Username atau Password Salah',
                                'warning'
                            )
                        }
                    }
                })
            })
        });
    </script>
@endsection
