@extends('dashboard.layout')

@section('page title','Master Data Kendaraan')

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
                                    <th>Kategori</th>
                                    <th>Nama Model</th>
                                    <th>Tahun</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-block btn-outline-danger" id="btnHapus" disabled>Hapus</button>
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
                                <input type="hidden" id="idData" name="id">
                                <div class="form-group">
                                    <label for="group">Kategori</label>
                                    <select id="kategori" name="kategori"></select>
                                </div>
                                <div class="form-group">
                                    <label for="group">Nama Model</label>
                                    <input type="text" class="form-control" id="model_name" name="model_name">
                                </div>
                                <div class="form-group">
                                    <label for="group">Tahun</label>
                                    <input type="text" class="form-control" id="tahun" name="tahun">
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const btnHapus = $('#btnHapus');
        const btnEdit = $('#btnEdit');
        const btnBaru = $('#btnBaru');
        const btnClose = $('#btnClose');

        const cardComponent = $('#cardComponent');

        let groupData = [];

        const dataForm = $('#dataForm');
        const inputType = $('#inputType');
        const iID = $('#idData');
        const iModel = $('#model_name');
        const iTahun = $('#tahun');
        const iKategori = new SlimSelect({
            select: '#kategori',
            data: [
                {text: 'SUV', value: 'SUV'},
                {text: 'Hybrid', value: 'Hybrid'},
                {text: 'MPV', value: 'MPV'},
                {text: 'Sedan', value: 'Sedan'},
                {text: 'Hatchback', value: 'Hatchback'},
                {text: 'Commercial', value: 'Commercial'},
                {text: 'Sport', value: 'Sport'},
            ]
        });

        function resetForm() {
            iID.val('');
            iModel.val('');
            iTahun.val('');
        }

        const tableIndex = $('#tableIndex').DataTable({
            scrollX: true,
            "ajax": {
                "method": "POST",
                "url": "{{ url('master-data/kendaraan/list') }}",
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
                { "data": "category" },
                { "data": "model" },
                { "data": "tahun" },
            ],
        });
        $('#tableIndex tbody').on( 'click', 'tr', function () {
            let data = tableIndex.row( this ).data();
            iID.val(data.id);
            $('#kategori').val(data.category);
            iModel.val(data.model);
            iTahun.val(data.tahun);
            // console.log(data);
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
                btnEdit.attr('disabled','true');
                btnHapus.attr('disabled','true');
            } else {
                tableIndex.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                btnEdit.removeAttr('disabled');
                btnHapus.removeAttr('disabled');
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
                cardComponent.removeClass('d-none');
                $('html, body').animate({
                    scrollTop: cardComponent.offset().top
                }, 500);
            });
            btnHapus.click(function (e) {
                e.preventDefault();
                Swal.fire({
                    title: iModel.val()+" akan dihapus",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus Data'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ url('master-data/kendaraan/delete') }}',
                            method: 'post',
                            data: {id: iID.val()},
                            success: function (response) {
                                console.log(response);
                                if (response === 'success') {
                                    Swal.fire({
                                        title: 'Data terhapus!',
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
                    url = "{{ url('master-data/kendaraan/add') }}";
                } else {
                    url = "{{ url('master-data/kendaraan/edit') }}";
                }
                $.ajax({
                    url: url,
                    method: 'post',
                    data: $(this).serialize(),
                    success: function (response) {
                        // console.log(response);
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
                                'Silahkan coba lagi',
                                'warning'
                            )
                        }
                    }
                })
            })
        });
    </script>
@endsection
