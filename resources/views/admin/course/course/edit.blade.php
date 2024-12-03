@extends('admin.layouts.app')
@section('title', 'Edit Kelas')
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
                    </a> Edit Data Kelas
                </h4>
            </div>
            <form action="{{ route('admin.course.course.update', $data->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                                <label class="fw-bold" for="sub_category_id">Sub Kategori<span
                                        class="text-danger">*</span></label>
                                <select name="sub_category_id" id="sub_category_id" required>
                                    <option value="">Pilih Sub Kategori</option>
                                    @foreach ($subCategory as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $data->sub_category_id == $item->id ? 'selected' : '' }}>
                                            [{{ $item->category->divisiCategory->nama }}][{{ $item->category->nama }}] -
                                            {{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="fw-bold" for="nama_kelas">Judul/Nama<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_kelas" id="nama_kelas"
                                    placeholder="Judul/Nama Kelas" value="{{ $data->nama_kelas }}" required>
                            </div>
                            <div class="form-group">
                                <label for="image-dropify" class="fw-bold">Upload Thumbnail Kelas <span
                                        class="text-danger">*</span></label></label>
                                <div class="form-group" id="container_upload">
                                    @include('components.upload_image.html')
                                </div>
                                <textarea id="image-dropify-send" class="d-none" name="image"></textarea>

                                <div id="container_photos">
                                    <img class="w-50" src="{{ $data->thumbnail_url }}">
                                </div>
                                <button type="button" class="btn btn-secondary mt-3" id="btn_change">
                                    Change <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                    <button type="submit" class="btn btn-primary col-12 col-md-3 rounded-3">Simpan</button>
                </div>
            </form>
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
    @include('components.upload_image.js')
    <script>
        var selectCategory = $("#sub_category_id").selectize({
            respect_word_boundaries: false,
            closeAfterSelect: true,
            plugins: ["clear_button"],
        });
    </script>
    <script>
        CKEDITOR.replace('deskripsi');
        var test = `{!! $data->deskripsi !!}`;
        $(document).ready(function() {
            CKEDITOR.instances['deskripsi'].setData(test)
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#container_upload').hide();

            $('#btn_change').click(function(e) {
                e.preventDefault();
                $('#container_upload').toggle('slow');
                $('#container_photos').toggle('slow');
            });
        });
    </script>
@endsection
