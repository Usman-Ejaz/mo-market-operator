<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.js"></script>

<script type="module">
	
	let cropper = null;    

	let previousSelected = "";
	$(document).ready(function () {

        loadAllImages();

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
            $('#enableCropper').html('Enable Cropper');
			$('#imageViewModal').modal('hide');
		})

		$('#saveImageInfo').on('click', function (e) {
			saveImageInfo();
		});

        let cropperEnabled = false;
        $('#enableCropper').on('click', () => {
            cropperEnabled = !cropperEnabled;
            if (cropperEnabled) {
                $('#enableCropper').html('Disable Cropper');
                enableCropper();
            } else {
                $('#enableCropper').html('Enable Cropper');
                disableCropper();
            }
        })
	});

	registerFilePondObject();

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
									<img src="${ item.file}" alt="" style="object-fit: contain; height: 50px;">
								</div>
								<div class="folder-name">
								</div>
							</div>
                        `;
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
            maxFiles: 3,
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
            preview: '.cropper-img-preview'
        });

        let flipX = 1, flipY = 1, rotationAngle = 45;
        $('.cropper-action-button').on('click', function () {
            let { method, option } = $(this).data();
            switch(method) {
                case 'scaleX':
                    flipX = -flipX;
                    cropper.scaleX(flipX);
                    break;
                case 'scaleY':
                    flipY = -flipY;
                    cropper.scaleY(flipY);
                    break;
                case 'rotate':
                    cropper.rotate(option);
                    break;
            }
        });
    }

    function disableCropper () {
        $('#cropper-actions').css({display: 'none'});
        if (cropper !== null) {
            cropper.destroy();
            cropper = null;
        }
    }

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