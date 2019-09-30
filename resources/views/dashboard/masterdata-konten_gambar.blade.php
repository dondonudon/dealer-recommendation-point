@extends('dashboard.layout')

@section('page title','MASTER DATA Konten Gambar')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/filepond-master/filepond.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/PhotoSwipe-4.1.3/photoswipe.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/PhotoSwipe-4.1.3/default-skin/default-skin.css') }}">
@endsection

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
                                    <th>Nama File</th>
                                    <th>File Location</th>
                                    <th>Keterangan</th>
                                    <th>Info</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button class="btn btn-block btn-outline-danger" id="btnDelete" disabled>Delete</button>
                                </div>
                                <div class="col-lg-6 mt-2 mt-sm-0"></div>
                                <div class="col-lg-2">
                                    <button class="btn btn-block btn-outline-primary" id="btnPreview" disabled>Preview</button>
                                </div>
                                <div class="col-lg-2 mt-2 mt-sm-0">
                                    <button class="btn btn-block btn-primary" id="btnUploadFile">
                                        <i class="fas fa-upload"></i> Upload File
                                    </button>
                                </div>
                            </div>
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
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Info Gambar</label>
                                <select class="form-control" id="infoGambar">
                                    <option value="0">Price List</option>
                                    <option value="1">Konten</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <input type="text" class="form-control" name="keterangan" id="keterangan">
                            </div>
                            <div class="row">
                                <div class="col-lg">
                                    <input id="cardUpload_uploadFile" type="file">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Root element of PhotoSwipe. Must have class pswp. -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <!-- Background of PhotoSwipe.
             It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>

        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">

            <!-- Container that holds slides.
                PhotoSwipe keeps only 3 of them in the DOM to save memory.
                Don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">

                    <!--  Controls are self-explanatory. Order can be changed. -->

                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                    <button class="pswp__button pswp__button--share" title="Share"></button>

                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                    <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                    <!-- element will get class pswp__preloader--active when preloader is running -->
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                </button>

                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                </button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>

            </div>

        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('vendor/filepond-master/filepond.min.js') }}"></script>
    <script src="{{ asset('vendor/PhotoSwipe-4.1.3/photoswipe.min.js') }}"></script>
    <script src="{{ asset('vendor/PhotoSwipe-4.1.3/photoswipe-ui-default.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const loading = '<i class="fas fa-spinner fa-pulse"></i>';
        const uploadArea = document.getElementById('cardUpload_uploadFile');

        const btnUpload = $('#btnUploadFile');
        const btnClose = $('#btnClose');
        const btnPreview = $('#btnPreview');
        const btnDelete = $('#btnDelete');

        const cardComponent = $('#cardComponent');
        const iInfoGambar = $('#infoGambar');
        let infoGambar = 0;
        let imgID;
        let idImage, namaImage;
        let imageItem = [];

        let tableIndex = $('#tableIndex').DataTable({
            scrollX: true,
            "columns": [
                { "data": "file_name" },
                { "data": "file_location_laravel" },
                { "data": "keterangan" },
                { "data": "info" },
            ],
        });
        $('#tableIndex tbody').on( 'click', 'tr', function () {
            let data = tableIndex.row( this ).data();
            imgID = data.no;
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
                btnPreview.attr('disabled','true');
                btnDelete.attr('disabled','true');
            } else {
                tableIndex.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                btnPreview.removeAttr('disabled');
                btnDelete.removeAttr('disabled');
                idImage = data.id;
                namaImage = data.file_name;
            }
        });

        const openPhotoSwipe = function(index) {
            let pswpElement = document.querySelectorAll('.pswp')[0];

            // define options (if needed)
            let options = {
                // history & focus options are disabled on CodePen
                history: false,
                focus: false,

                showAnimationDuration: 0,
                hideAnimationDuration: 0

            };

            let gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, imageItem, options);
            gallery.init();
            gallery.goTo(index);
        };

        function updateTableIndex() {
            $.ajax({
                url: "{{ url('master-data/konten-gambar/list') }}",
                method: "post",
                success: function(response) {
                    // console.log(response);
                    let data = JSON.parse(response);
                    tableIndex.clear().draw();
                    tableIndex.rows.add(data.data).draw();
                }
            })
        }

        function reloadImage() {
            $.ajax({
                url: '{{ url('master-data/konten-gambar/preview') }}',
                method: "post",
                success: function(result) {
                    // console.log(result);
                    imageItem = JSON.parse(result);
                }
            });
        }

        function deleteFile(id, filename) {
            let result;
            $.ajax({
                url: '{{ url('master-data/konten-gambar/delete') }}',
                method: 'post',
                data: {id: id, filename: filename},
                success: function(response) {
                    if (response === 'success') {
                        result = 'success';
                    } else {
                        result = 'failed';
                    }
                    return result;
                }
            });
        }

        $(document).ready(function () {
            updateTableIndex();
            reloadImage();
            iInfoGambar.change(function () {
                infoGambar = iInfoGambar.val();
                console.log(infoGambar);
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
                    reloadImage();
                });
            });
            btnPreview.click(function (e) {
                e.preventDefault();
                openPhotoSwipe(imgID);
            });
            btnDelete.click(function (e) {
                e.preventDefault();
                Swal.fire({
                    title: "Yakin akan menghapus gambar?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'HAPUS'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ url('master-data/konten-gambar/delete') }}',
                            method: 'post',
                            data: {id: idImage, filename: namaImage},
                            success: function(response) {
                                if (response === 'success') {
                                    Swal.fire({
                                        title: 'Data tersimpan!',
                                        type: 'success',
                                        onClose: function () {
                                            updateTableIndex();
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

            FilePond.create( uploadArea );
            FilePond.setOptions({
                allowImageTransform: true,
                allowImageResize: true,
                imageResizeMode: 'cover',
                imageResizeTargetHeight: 700,
                imageResizeTargetWidth: 1200,
                imageTransformOutputMimeType: 'image/jpeg',
                allowMultiple: true,
                allowDrop: true,
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort) => {
                        const formData = new FormData();
                        formData.append(fieldName, file, file.name);
                        formData.append('keterangan', $('#keterangan').val());

                        const request = new XMLHttpRequest();
                        request.open('POST','{{ url('master-data/konten-gambar/upload') }}/'+infoGambar);
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
