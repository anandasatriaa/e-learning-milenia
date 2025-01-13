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
            <form action="{{ route('admin.course.course.update', $course->id) }}" method="POST"
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
                                <label class="fw-bold" for="form_dropdown">Sub Kategori<span
                                        class="text-danger">*</span></label>
                                <select name="form_dropdown" id="form_dropdown" required class="form-control">
                                    <option value="">Choose Category</option>
                                    <!-- Loop untuk menampilkan learningCategories -->
                                    @foreach ($learningCategories as $category)
                                        <option value="learningCategory_{{ $category->id }}"
                                            {{ $course->learning_cat_id == $category->id ? 'selected' : '' }}>
                                            [{{ $category->nama }}]
                                        </option>
                                    @endforeach

                                    <!-- Loop untuk kombinasi learningCategory dan divisiCategory -->
                                    @foreach ($learningCategories as $category)
                                        @foreach ($category->divisiCategories as $divisiCategory)
                                            <option
                                                value="divisiCategory_{{ $divisiCategory->id }}_learningCategory_{{ $category->id }}"
                                                {{ $course->divisi_category_id == $divisiCategory->id && $course->learning_cat_id == $category->id ? 'selected' : '' }}>
                                                [{{ $category->nama }}] - [{{ $divisiCategory->nama }}]
                                            </option>
                                        @endforeach
                                    @endforeach

                                    <!-- Loop untuk kombinasi learningCategory, divisiCategory, dan category -->
                                    @foreach ($learningCategories as $category)
                                        @foreach ($category->divisiCategories as $divisiCategory)
                                            @foreach ($divisiCategory->categories as $cat)
                                                <option
                                                    value="category_{{ $cat->id }}_divisiCategory_{{ $divisiCategory->id }}_learningCategory_{{ $category->id }}"
                                                    {{ $course->category_id == $cat->id && $course->divisi_category_id == $divisiCategory->id && $course->learning_cat_id == $category->id ? 'selected' : '' }}>
                                                    [{{ $category->nama }}] - [{{ $divisiCategory->nama }}] -
                                                    [{{ $cat->nama }}]
                                                </option>
                                            @endforeach
                                        @endforeach
                                    @endforeach

                                    <!-- Loop untuk kombinasi learningCategory, divisiCategory, category, dan subCategory -->
                                    @foreach ($learningCategories as $category)
                                        @foreach ($category->divisiCategories as $divisiCategory)
                                            @foreach ($divisiCategory->categories as $cat)
                                                @foreach ($cat->subCategories as $subCategory)
                                                    <option
                                                        value="subCategory_{{ $subCategory->id }}_category_{{ $cat->id }}_divisiCategory_{{ $divisiCategory->id }}_learningCategory_{{ $category->id }}"
                                                        {{ $course->sub_category_id == $subCategory->id && $course->category_id == $cat->id && $course->divisi_category_id == $divisiCategory->id && $course->learning_cat_id == $category->id ? 'selected' : '' }}>
                                                        [{{ $category->nama }}] - [{{ $divisiCategory->nama }}] -
                                                        [{{ $cat->nama }}] - [{{ $subCategory->nama }}]
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="fw-bold" for="nama_kelas">Judul/Nama<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_kelas" id="nama_kelas"
                                    placeholder="Judul/Nama Kelas" value="{{ $course->nama_kelas }}" required>
                            </div>
                            <div class="form-group">
                                <label for="image-dropify" class="fw-bold">Edit Thumbnail Kelas <span
                                        class="text-danger">*</span></label></label>
                                <div class="form-group" id="container_upload">
                                    @include('components.upload_image.html')
                                </div>
                                <textarea id="image-dropify-send" class="d-none" name="image"></textarea>

                                <div id="container_photos">
                                    <img class="w-50" src="{{ $course->thumbnail_url }}">
                                </div>
                                <button type="button" class="btn btn-secondary mt-3" id="btn_change">
                                    Change <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group input-bar">
                                <label class="fw-bold" for="thumbnail_video">Edit Thumbnail Video<span
                                        class="text-danger">*</span></label>
                                <input id="thumbnail_video" type="file" name="thumbnail_video" class="form-control"
                                    accept="video/mp4,video/x-m4v,video/*">
                            </div>
                            <div class="form-group input-bar">
                                <label class="fw-bold">Preview</label>
                                <div style="height:300px" id="preview_media">
                                    @if (!empty($course->thumbnail_video))
                                        <video id="video_preview" controls style="max-height: 100%; width: 100%;">
                                            <source
                                                src="{{ asset('storage/course/thumbnail_video/' . $course->thumbnail_video) }}"
                                                type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <p id="no_video_text">No video available.</p>
                                    @endif
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
        var selectCategory = $("#form_dropdown").selectize({
            respect_word_boundaries: false,
            closeAfterSelect: true,
            plugins: ["clear_button"],
        });
    </script>
    <script>
        CKEDITOR.replace('deskripsi');
        var test = `{!! $course->deskripsi !!}`;
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
    <script>
        document.getElementById('thumbnail_video').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const previewMedia = document.getElementById('preview_media');

            if (file) {
                // Hapus teks "No video available" jika ada
                const noVideoText = document.getElementById('no_video_text');
                if (noVideoText) {
                    noVideoText.remove();
                }

                // Buat elemen video baru untuk preview
                const videoPreview = document.getElementById('video_preview') || document.createElement('video');
                videoPreview.id = 'video_preview';
                videoPreview.controls = true;
                videoPreview.style.maxHeight = '100%';
                videoPreview.style.width = '100%';

                // Mengatur sumber video ke file baru yang diunggah
                const objectUrl = URL.createObjectURL(file);
                videoPreview.src = objectUrl;

                // Menambahkan video ke container
                if (!document.getElementById('video_preview')) {
                    previewMedia.appendChild(videoPreview);
                }
            }
        });
    </script>
@endsection
