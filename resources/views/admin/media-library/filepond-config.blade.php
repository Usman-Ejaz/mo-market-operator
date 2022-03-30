<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.js"></script>

<script type="module">
	
	let cropper = null;    
    let cropperEnabled = false;

	$(document).ready(function () {

        loadAllImages();

        registerFilePondObject();

		$('body').on('click', '.folder-container', function () {
			let { id, src, featured } = $(this).data();
			
			$("#featured").prop("checked", featured === 1);
			$('#imageSrc').attr('src', src);
			$('#imageId').val(id);

            $('#cropper-actions').css({display: 'none'});
			$('#imageViewModal').modal({backdrop: 'static', keyboard: false});
		});

		$('.editor-modal').on('click', function () {
			disableCropper();
			$('#imageViewModal').modal('hide');
		})

		$('#saveImageInfo').on('click', function (e) {
			saveImageInfo();
		});

        $('.crop-box-ratio').on('keyup', function (e) {
            let val = $(this).val().replace(/[^0-9]/g, '');
            let data = cropper.getData();
            if ($(this).attr('id') === "dataWidth") {
                data.width = val;
            } else {
                data.height = val;
            }
            cropper.setData(data);
            $(this).val(val);
        });
        
        $('#customizeImage').on('click', () => {
            cropperEnabled = !cropperEnabled;
            cropperEnabled ? enableCropper() : disableCropper();
        });


        $('#opcaityDropdown').on('click', (e) => {
            let { value } = e.target.dataset;
            value /= 100;
            $('.cropper-drag-box.cropper-crop').css({
                opacity: value,
            })
        });
	});	

    function loadAllImages() {
        $.ajax({
            url: '{{ route("admin.media-library.files.list", $mediaLibrary->id) }}',
            type: 'GET',
            success: (response) => {
                let {status, data} = response;
                if (status === 'success') {
                    let html = ``;

                    data.forEach(item => {
                        html += `
                            <div class="folder-container" data-id="${ item.id }" data-featured="${ item.featured }" data-src="${ item.file }">
								<div class="folder-icon">
									<img src="${ item.file}" alt="" class="image-aspact-ratio">
								</div>
							</div>                            
                        `;
                        // <div style="position: relative;top: 182px;right: 30px; height: 1px; display:inline-block; bottom: 0;">
                        // <a style="color:red; cursor:pointer" href="javascript:void(0);" data-id="${item.id}" class="image-remove"><i class="fa fa-times"></i></button>
                        // </div>
                    });

                    $('#mediafiles').html(html);
                }
            },
            error: (error) => {
                console.log(error)
            }
        })
    }

    function registerFilePondObject() {
        const inputElement = document.getElementById('filepond');

        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize
        );
        
        const pond = FilePond.create(inputElement, {
            acceptedFileTypes: ['image/*'],
            maxFileSize: '2MB',
            credits: false,
            allowMultiple: true,
            required: true,
            server: {
                process: {
                    url: '{{ route("admin.media-library.files.upload", $mediaLibrary->id) }}',
                    method: 'POST',
                    onload: (response) => {
                        
                    },
                    onerror: (response) => console.log('response.data => ', response.data),
                },
                revert: null,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            // onprocessfile: (error, file) => {
            //     loadAllImages()
            //     setTimeout(() => { pond.removeFile(); }, 600);
            // },
            onprocessfiles: (error, file) => {
                loadAllImages()
                setTimeout(() => { pond.removeFiles(); }, 600);
            }
        });
    }

    function enableCropper() {

        let elem = document.getElementById('imageSrc');
        let height = elem.height + 4;

        $('#cropper-actions').css({display: 'block'});
        $('#customizeImage').html('Cancel Customization');

        $('.cropper-img-preview').css({
            width: '100%',
            overflow: 'hidden',
            height: height,
            maxWidth: elem.width,
            maxHeight: height
        });

        cropper = new Cropper(elem, {
            aspectRatio: 'free',
            viewMode: 1,
            preview: '.cropper-img-preview',
            ready: () => {
                this.cropper.clear();
            },
            crop: (e) => {
                $('#dataWidth').val(Math.round(e.detail.width));
                $('#dataHeight').val(Math.round(e.detail.height));
            },
        });

        let flipX = 1, flipY = 1, rotationAngle = 45, data = null;
        $('.cropper-action-button').on('click', function () {
            let { method, option } = $(this).data();
            data = cropper.getCropBoxData();

            let editorHidden = document.getElementsByClassName('cropper-crop-box cropper-hidden').length > 0;
            
            // cropper.getImageData();

            switch(method) {
                case 'scaleX':
                    flipX = -flipX;
                    cropper.scaleX(flipX);
                    if (editorHidden) {
                        let e = cropper.getImageData();
                        $('.cropper-img-preview').css({
                            width: '100%',
                            overflow: 'hidden',
                            height: e.height,
                            maxWidth: e.width,
                            maxHeight: e.height / 2
                        });
                        $('.cropper-img-preview > img').css({
                            width: '100%',
                            overflow: 'hidden',
                            height: e.height,
                            maxWidth: e.width,
                            maxHeight: e.height / 2,
                            transform: `scaleX(${flipX}) scaleY(${flipY})`
                        })
                    }
                    break;
                case 'scaleY':
                    flipY = -flipY;
                    cropper.scaleY(flipY);
                    let e = cropper.getImageData();
                    $('.cropper-img-preview').css({
                        width: '100%',
                        overflow: 'hidden',
                        height: e.height,
                        maxWidth: e.width,
                        maxHeight: e.height / 2
                    });
                    $('.cropper-img-preview > img').css({
                        width: '100%',
                        overflow: 'hidden',
                        height: e.height,
                        maxWidth: e.width,
                        maxHeight: e.height / 2,
                        transform: `scaleX(${flipX}) scaleY(${flipY})`
                    })
                    break;
                case 'rotate':
                    cropper.rotate(option);
                    break;
                case 'crop':
                case 'clear':
                    cropper[method]();
                    break;
            }
            cropper.setCropBoxData(data);
        });
    }

    /**
     * 
     * 
     * 
     */
    function disableCropper () {
        $('#cropper-actions').css({display: 'none'});
        $('#customizeImage').html('Customize Image');
        if (cropper !== null) {
            cropperEnabled = false;
            cropper.destroy();
            cropper = null;
        }
    }

    /**
     * 
     * 
     * 
     */
    function saveImageInfo() {
        let payload = {};
        if (cropper !== null) {
            let canvas = cropper.getCroppedCanvas({maxWidth: 4096, maxHeight: 4096});
            let dataURL = canvas.toDataURL();
            payload.dataURL = dataURL;
        }

        payload.id = $('#imageId').val();
        payload.featured = $('#featured').prop('checked');

        $('#saveImageInfo').prop('disable', true);

        $.ajax({
            url: '{{ route("admin.media-library.updateFile") }}',
            method: 'POST',
            data: payload,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: (response) => {                
                let { status } = response;
                if (status === "success") {
                    loadAllImages();
                    $('.editor-modal').click();
                    $('#saveImageInfo').prop('disable', false);
                    toastr.success("Image updated successfully!");
                }
            },
            error: () => {

            }
        })
    }
</script>