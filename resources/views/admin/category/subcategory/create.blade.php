@extends('admin.layouts.app')
@section('title', 'Add Sub Category')
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
                    <a href="{{ route('admin.category.sub-category.index') }}"class="btn btn-icon btn-round btn-light">
                        <i class="fas fa-chevron-left"></i>
                    </a> Add Sub Category
                </h4>
            </div>
            <form action="{{ route('admin.category.sub-category.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label class="fw-bold" for="category_id">Category<span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" required>
                                    <option value="">Choose Category</option>
                                    @foreach ($category as $item)
                                        <option value="{{ $item->id }}">[{{ $item->divisiCategory->learningCategory->nama ?? 'Tidak ada' }}] - [{{ $item->divisiCategory->nama ?? 'Tidak ada' }}] - {{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="fw-bold" for="nama">Sub Category<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama" id="nama"
                                    placeholder="Sub Category Name" required>
                            </div>
                            <div class="form-group">
                                <label for="image-dropify" class="fw-bold">Upload Sub Category Thumbnail <span class="text-danger">*</span></label></label>
                                <div class="form-group">
                                    @include('components.upload_image.html')
                                </div>
                                <textarea id="image-dropify-send" class="d-none" name="image" required></textarea>
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
                    <button type="submit" class="btn btn-primary col-12 col-md-3 rounded-3">Save</button>
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
        var selectCategory = $("#category_id").selectize({
            respect_word_boundaries: false,
            closeAfterSelect: true,
            plugins: ["clear_button"],
        });
    </script>
    <script>
        CKEDITOR.replace('deskripsi');
    </script>
@endsection
