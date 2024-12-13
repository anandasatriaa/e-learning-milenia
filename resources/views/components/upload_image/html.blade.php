{{-- CARA PAKAI --}}
{{-- Tambahkan 

    <textarea id="image-dropify-send" class="d-none" name="image" required></textarea> 
    
    untuk hasil dari imagenya, name sesuaikan dengan attribut database
    --}}
<input id="image-dropify" type="file" class="form-control dropify" data-width="200" data-height="200" accept="image/*"
    data-max-file-size="2M">
<p class="text-danger text-small font-weight-bold m-0">*Image size max 2MB</p>
{{-- <div class="modal " tabindex="-1" data-backdrop="static" data-keyboard="false" id="myModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-black-gradient text-white text-center">
                <h5 class="modal-title ">Adjust image <i class="fas fa-crop-alt"></i></h5>
            </div>
            <div class="modal-body">
                <div id="cropie-demo"></div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" id="crop" class="btn btn-black col-6">Cut</button>
            </div>
        </div>
    </div>
</div> --}}
