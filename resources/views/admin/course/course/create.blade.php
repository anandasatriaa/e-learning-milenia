@extends('admin.layouts.app')
@section('title', 'Tambah Kelas')
@section('css')
    <link href="{{ asset('vendor/dropify/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/croppie/croppie.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/selectize/selectize.bootstrap5.css') }}" rel="stylesheet" crossorigin="anonymous" />
@endsection
@section('content')
    <div class="page-inner">
        <div class="card text-start">
            <div class="card-header">
                <h4 class="card-title">
                    <a href="{{ route('admin.course.course.index') }}"class="btn btn-icon btn-round btn-light">
                        <i class="fas fa-chevron-left"></i>
                    </a> Add Course
                </h4>
            </div>
            <form method="POST" enctype="multipart/form-data" id="uploadFormCourse">
                @csrf
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}
                                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold" for="sub_category_id">Sub Category<span
                                        class="text-danger">*</span></label>
                                <select name="sub_category_id" id="sub_category_id" required>
                                    <option value="">Choose Sub Category</option>
                                    @foreach ($subCategory as $item)
                                        <option value="{{ $item->id }}"> [{{ $item->category->divisiCategory->learningCategory->nama }}] -
                                            [{{ $item->category->divisiCategory->nama }}] - [{{ $item->category->nama }}] -
                                            {{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="fw-bold" for="nama_kelas">Judul/Nama<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_kelas" id="nama_kelas"
                                    placeholder="Judul/Nama Kelas" required>
                            </div>
                            <div class="form-group">
                                <label for="image-dropify" class="fw-bold">Upload Thumbnail
                                    Kelas <span class="text-danger">*</span></label></label>
                                <div class="form-group">
                                    @include('components.upload_image.html')
                                </div>
                                <textarea id="image-dropify-send" class="d-none" name="image" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group input-bar">
                                <label class="fw-bold" for="thumbnail_video">Masukan Thumbnail Video<span
                                        class="text-danger">*</span></label>
                                <input id="thumbnail_video" type="file" name="thumbnail_video" class="form-control"
                                    accept="video/mp4,video/x-m4v,video/*">
                            </div>
                            <div class="form-group input-bar">
                                <label class="fw-bold">Preview</label>
                                <div style="height:300px" id="preview_media">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi" class="fw-bold">
                                    Deskripsi
                                    <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-center py-3">
                    <button type="submit" class="btn btn-primary col-12 col-md-3 rounded-3" id="btnupload">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal" id="modalStore" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
        aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="progress-card my-auto">
                        <div class="progress-status">
                            <span class="text-muted" id="progress_title_status">Proses Upload Data</span>
                            <span class="text-muted fw-bold" id="progress_status"></span>
                        </div>
                        <div class="progress">
                            <div id="progress_bar"
                                class="progress-bar progress-bar-striped bg-warning progress-bar-animated"
                                role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" data-toggle="tooltip" data-placement="top">
                            </div>
                        </div>
                        <div class="mt-2">
                            <small><i>*jangan me-refresh atau menutup halaman ini</i></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
        integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('vendor/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('vendor/croppie/croppie.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/core/jq-ajax-progress.min.js') }}"></script>
    @include('components.upload_image.js')
    <script>
        var selectCategory = $("#sub_category_id").selectize({
            respect_word_boundaries: false,
            closeAfterSelect: true,
            plugins: ["clear_button"],
        });
        CKEDITOR.replace('deskripsi');

        $("#thumbnail_video").change(function() {
            var tipe_media = $('#tipe_media').val()

            var fileInput = this.files[0];
            if (fileInput) {
                var fileUrl = window.URL.createObjectURL(fileInput);
                $("#preview_media").html(`<video controls src="${fileUrl}" height="300"></video>`);
            }

        });
    </script>
    <script>
        $('#uploadFormCourse').submit(function(e) {
            e.preventDefault();
            const modalStore = new bootstrap.Modal(
                document.getElementById("modalStore")
            );
            modalStore.show();
            var formData = new FormData(this);
            formData.append('deskripsi', CKEDITOR.instances.deskripsi.getData());

            var jqxhr = $.ajax({
                type: 'POST',
                url: "{{ route('admin.course.course.store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(data) {
                    modalStore.hide();
                    $('#btnupload').prop('disabled', true)
                    $.notify({
                        icon: "icon-check",
                        title: 'Sukses',
                        message: 'Berhasil mengupload data. Halaman akan dialihkan...',
                    }, {
                        type: "primary",
                        allow_dismiss: true,
                        offset: {
                            x: 20,
                            y: 80
                        },
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        showProgressbar: true,
                        timer: 500,
                        delay: 1500,
                        onClose: redirectOnClose
                    });
                },
                error: function(data) {
                    modalStore.hide();
                    console.log(data);
                    $.notify({
                        icon: "icon-close",
                        title: `Terjadi kesalahan!`,
                        message: `(${data.status}) ${data.responseJSON.message}`,
                    }, {
                        type: "danger",
                        allow_dismiss: true,
                        offset: 50,
                        spacing: 50,
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        timer: 500,
                    });
                },
                uploadProgress: uploadProgress
            });

            $(jqxhr).on('uploadProgress', uploadProgress);
        });

        function uploadProgress(e) {
            if (e.lengthComputable) {
                var percentComplete = (e.loaded * 100) / e.total;
                $('#progress_status').text(Math.round(percentComplete) + '%')
                $('#progress_bar').css('width', percentComplete + '%')
                $('#progress_bar').attr('aria-valuenow', percentComplete)
                if (percentComplete >= 100) {
                    // process completed
                    $('#progress_title_status').text('Menyelesaikan proses...')
                }
            }
        }

        function redirectOnClose() {
            window.location.replace("{{ route('admin.course.course.index') }}");
        }
    </script>
@endsection
