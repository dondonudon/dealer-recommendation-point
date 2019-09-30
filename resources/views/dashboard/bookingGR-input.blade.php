@extends('dashboard.layout')

@section('page title','Booking GR - Input')

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
    <iframe id="downloadFile" style="display: none"></iframe>
@endsection

@section('script')
    <script src="{{ asset('vendor/filepond-master/filepond.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const downloadFile = document.getElementById('downloadFile');
        const uploadArea = document.getElementById('cardUpload_uploadFile');
        const btnSample = document.getElementById('btnSample');

        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }

        function uploadResponse(response) {
            if (response !== 'success') {
                if (isJson(response)) {
                    console.log(JSON.parse(response));
                    Swal.fire({
                        title: 'Gagal',
                        html: 'Check log',
                        type: 'error',
                    });
                } else {
                    console.log(response);
                    Swal.fire({
                        title: 'Gagal',
                        html: 'Check Log',
                        type: 'error',
                    });
                }
            } else {
                Swal.fire({
                    title: 'Data Tersimpan',
                    type: 'success',
                });
            }
        }

        $(document).ready(function () {
            btnSample.addEventListener('click',function (e) {
                e.preventDefault();
                downloadFile.src = '{{ url('booking-general-repair/input-booking/download-sample') }}';
            });
            {{--btnSample.click(function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    downloadFile.src = '{{ url('booking-general-repair/input-booking/download-sample') }}';--}}
            {{--});--}}

            FilePond.create( uploadArea );
            FilePond.setOptions({
                allowMultiple: false,
                allowDrop: true,
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort) => {
                        const formData = new FormData();
                        formData.append(fieldName, file, file.name);

                        const request = new XMLHttpRequest();
                        request.open('POST','{{ url('booking-general-repair/input-booking/upload') }}');
                        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                        request.upload.onprogress = (e) => {
                            progress(e.lengthComputable, e.loaded, e.total);
                        };

                        request.onload = function () {
                            if (request.status >= 200 && request.status < 300) {
                                load(request.responseText);
                                uploadResponse(request.responseText);
                            } else {
                                console.log(request.responseText);
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
