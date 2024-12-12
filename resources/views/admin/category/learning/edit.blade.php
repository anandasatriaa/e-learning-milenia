@extends('admin.layouts.app')
@section('title', 'Edit Data ' . $data->nama)
@section('css')
    <link href="{{ asset('vendor/dropify/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/croppie/croppie.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="page-inner">
        <div class="card text-start">
            <div class="card-header">
                <h4 class="card-title">
                    <a href="{{ route('admin.category.learning.index') }}"class="btn btn-icon btn-round btn-light">
                        <i class="fas fa-chevron-left"></i>
                    </a> Edit Data {{ $data->nama }}
                </h4>
            </div>
            <form action="{{ route('admin.category.learning.update', $data->id) }}" method="POST"
                enctype="multipart/form-data">
                @method('PUT')
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
                                <label class="fw-bold" for="nama">Learning Category<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama" id="nama"
                                    placeholder="Learning Category Name" value="{{ $data->nama }}">
                            </div>
                            <div class="form-group">
                                <label for="image-dropify" class="fw-bold">Upload Thumbnail
                                    Learning Category <span class="text-danger">*</span></label></label>
                                <div class="form-group" id="container_upload">
                                    @include('components.upload_image.html')
                                </div>
                                <textarea id="image-dropify-send" class="d-none" name="image"></textarea>

                                <div id="container_photos">
                                    <img class="w-50" src="{{ $data->image_url }}">
                                </div>
                                <button type="button" class="btn btn-secondary mt-3" id="btn_change">
                                    Change <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="deskripsi" class="fw-bold">
                                    Description
                                    <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-center py-3">
                    <button type="submit" class="btn btn-primary col-12 col-md-3 rounded-3">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('vendor/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('vendor/croppie/croppie.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    @include('components.upload_image.js')
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
