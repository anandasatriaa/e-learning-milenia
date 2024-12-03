<script>
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
</script>
