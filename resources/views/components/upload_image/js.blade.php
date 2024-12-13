{{-- <script>
    $(document).ready(function() {
        $('.dropify').dropify();
    });
    var uploadCrop = $('#cropie-demo').croppie({
        viewport: {
            width: 200,
            height: 200
        },
        boundary: {
            width: 300,
            height: 300
        },
        showZoomer: true,
    });
    $('#image-dropify').on('change', function() {
        $('#myModal').modal('show');
        var reader = new FileReader();
        reader.onload = function(e) {
            uploadCrop.croppie('bind', {
                url: e.target.result
            }).then(function() {
                $('.dropify-render').empty();
                $('.dropify-render').append(
                    '<div class="text-center mt-3"><div class="spinner-grow" style="width: 4rem; height: 4rem;" role="status"><span class="sr-only">Loading...</span></div><h1>Loading...</h1></div>'
                );
            });
        }
        reader.readAsDataURL(this.files[0]);
    });
    $('#crop').on('click', function() {
        var result = uploadCrop.croppie('result', {
            type: 'base64',
            size: {
                width: 500,
                height: 500
            }
        }).then(function(blob) {
            $('#myModal').modal('hide');
            $('.dropify-render').empty();
            $('.dropify-render').append('<img src="' + blob + '">');
            $('#image-dropify-send').val(blob);
        });
    });
</script> --}}

{{-- Upload Image Tanpa Crop --}}
<script>
$(document).ready(function() {
    // Inisialisasi Dropify
    $('.dropify').dropify();

    // Ketika file gambar dipilih
    $('#image-dropify').on('change', function() {
        var reader = new FileReader();

        // Saat file dibaca
        reader.onload = function(e) {
            // Ambil base64 hasil pembacaan file
            var base64Image = e.target.result;

            // Masukkan base64 ke dalam textarea
            $('#image-dropify-send').val(base64Image);

            // Jika Anda ingin menampilkan gambar di halaman, Anda bisa melakukannya
            // dengan menambahkan gambar ke elemen HTML tertentu
            // Misalnya menampilkan gambar di elemen preview:
            $('.dropify-render').empty(); // Mengosongkan elemen preview
            $('.dropify-render').append('<img src="' + base64Image + '">');
        };

        // Baca file sebagai URL base64
        reader.readAsDataURL(this.files[0]);
    });
});
</script>
