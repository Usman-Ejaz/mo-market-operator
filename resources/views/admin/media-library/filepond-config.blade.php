<script src="{{ asset('admin-resources/plugins/filepond/js/filepond-plugin-file-validate-size.js') }}"></script>
<script src="{{ asset('admin-resources/plugins/filepond/js/filepond-plugin-file-validate-type.js') }}"></script>
<script src="{{ asset('admin-resources/plugins/filepond/js/filepond-plugin-image-preview.js') }}"></script>
<script src="{{ asset('admin-resources/plugins/filepond/js/filepond.js') }}"></script>
<script src="{{ asset('admin-resources/plugins/cropperjs/js/cropper.min.js') }}"></script>

<script type="module">
	
	let cropper = null;    
    let cropperEnabled = false;
    let imageOpacity = 1;
    let imageScale = null; // in percent

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

        $('body').on('click', '.btn-remove', (e) => {
            if(confirm('Are you sure you want to delete this record?')) {
                let { id } = e.target.dataset;
                $.ajax({
                    url: '{{ route("admin.media-library.files.remove") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: { id },
                    success: (response) => {
                        let { status, message } = response;
                        if (status === 'success') {
                            toastr.success(message);
                            loadAllImages();
                        }
                    },
                    error: (error) => {
                        console.log(error);
                        toastr.error("Something went wrong");
                    }
                })
            }
        })

		$('.editor-modal').on('click', function () {
			disableCropper();
			$('#imageViewModal').modal('hide');
		})

		$('#saveImageInfo').on('click', function (e) {
			saveImageInfo();
		});

        $('.crop-box-ratio').on('keyup mouseup', function (e) {
            let val = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(val);
            let data = cropper.canvasData;
            if ($(this).attr('id') === "dataWidth") {
                data.width = val;
            } else {
                data.height = val;
            }
            $('.cropper-canvas > img').css({
                width: data.width,
                height: data.height
            });

            imageScale = -1;
        });
        
        $('#customizeImage').on('click', () => {
            cropperEnabled = !cropperEnabled;
            cropperEnabled ? enableCropper() : disableCropper();
        });

        $('#opcaityDropdown').on('click', (e) => {
            let { value } = e.target.dataset;
            value /= 100;
            imageOpacity = value;
            $('.cropper-canvas, .cropper-img-preview').css({
                opacity: `${value}`,
            });
        });

        $('input[type="range"]').on('input', (e) => {
            let img = $('.cropper-canvas > img');
            var d = cropper.getImageData();
            var ratio = e.target.value;  // Used for aspect ratio
            var width = d.width;    // Current image width
            var height = d.height;  // Current image height

            width = (width / 100) * parseInt(ratio);
            height = (height / 100) * parseInt(ratio);
            img.css("width", width);
            img.css("height", height);

            $('#dataWidth').val(Math.round(width));
            $('#dataHeight').val(Math.round(height));

            imageScale = ratio;
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
                            <div class="" style="width: 22%">
                                <div class="folder-container ${item.featured === 1 ? 'featured' : ''}" data-id="${ item.id }" data-featured="${ item.featured }" data-src="${ item.file }">
                                    <div class="folder-icon">
                                        <img src="${ item.file}" alt="" class="image-aspact-ratio" style="width: 100%;">
                                    </div>
                                </div>
                                <div class="btn-container">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove" data-id="${ item.id }"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        `;
                        // <div style="position: relative;top: 182px;right: 30px; height: 1px; display:inline-block; bottom: 0;">
                        //  <a style="color:red; cursor:pointer" href="javascript:void(0);" data-id="${item.id}" class="image-remove"><i class="fa fa-times"></i></button>
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
                    onerror: (response) => { 
                        console.log('response.data => ', response.data);  
                        toastr.error('Something went wrong');
                    },
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
                setTimeout(() => { 
                    pond.removeFiles(); 
                    loadAllImages();
                    toastr.success('Image uploaded successfully!');
                }, 600);
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
            autoCrop: false,
            viewMode: 1,
            preview: '.cropper-img-preview',
            ready: (e) => {
                let el = $('.cropper-canvas > img');
                $('#dataWidth').val(Math.round(el.width()));
                $('#dataHeight').val(Math.round(el.height()));
            },
            crop: (e) => {
                $('#dataWidth').val(Math.round(e.detail.width));
                $('#dataHeight').val(Math.round(e.detail.height));
                imageScale = null;
            },
            // zoom: (e) => {
            //     $('#dataWidth').val(Math.round(e.detail.width));
            //     $('#dataHeight').val(Math.round(e.detail.height));
            // }
        });

        let flipX = 1, flipY = 1, rotationForward = 45, rotationBackward = -45, data = null, rotation = 0;
        $('.cropper-action-button').on('click', function () {
            let { method, option } = $(this).data();
            data = cropper.getCropBoxData();

            let editorHidden = document.getElementsByClassName('cropper-crop-box cropper-hidden').length > 0;

            switch(method) {
                case 'scaleX':
                    flipX = -flipX;
                    cropper.scaleX(flipX);
                    if (editorHidden) {
                        let e = cropper.getImageData();
                        $('.cropper-img-preview > img').css({
                            width: '100%',
                            overflow: 'hidden',
                            height: e.height,
                            maxWidth: e.width / 2,
                            maxHeight: e.height / 2,
                            transform: `scaleX(${flipX}) scaleY(${flipY}) rotate(${rotation}deg)`
                        })
                    }
                    var el = $('.cropper-canvas > img');
                    $('#dataWidth').val(Math.round(el.width()));
                    $('#dataHeight').val(Math.round(el.height()));
                    break;
                case 'scaleY':
                    flipY = -flipY;
                    cropper.scaleY(flipY);
                    if (editorHidden) {
                        let e = cropper.getImageData();
                        $('.cropper-img-preview > img').css({
                            width: '100%',
                            overflow: 'hidden',
                            height: e.height,
                            maxWidth: e.width / 2,
                            maxHeight: e.height / 2,
                            transform: `scaleX(${flipX}) scaleY(${flipY}) rotate(${rotation}deg)`
                        })
                    }
                    var el = $('.cropper-canvas > img');
                    $('#dataWidth').val(Math.round(el.width()));
                    $('#dataHeight').val(Math.round(el.height()));
                    break;
                case 'rotate':
                    if (option > 0) {
                        rotation += 45;
                    } else {
                        rotation -= 45;
                    }
                    cropper.rotate(option);
                    let e = cropper.getImageData();
                    $('.cropper-img-preview > img').css({
                        width: '100%',
                        overflow: 'hidden',
                        height: e.height,
                        maxWidth: e.width / 2,
                        maxHeight: e.height / 2,
                        transform: `scaleX(${flipX}) scaleY(${flipY}) rotate(${rotation}deg)`
                    })
                    var el = $('.cropper-canvas > img');
                    $('#dataWidth').val(Math.round(el.width()));
                    $('#dataHeight').val(Math.round(el.height()));
                    break;
                case 'crop':
                case 'clear':
                    cropper[method]();
                    if (method === "clear") {
                        $('#dataWidth').val(Math.round(cropper.getImageData().width));
                        $('#dataHeight').val(Math.round(cropper.getImageData().height));
                    }
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
            imageOpacity = 1;
            $('.cropper-canvas, .cropper-img-preview').css({
                opacity: imageOpacity,
            });
        }
    }

    /**
     * 
     * 
     * 
     */
    async function saveImageInfo() {
        let payload = {};

        if (cropper !== null) {
            let canvas = cropper.getCroppedCanvas({maxWidth: 4096, maxHeight: 4096});
            let dataURL = canvas.toDataURL();
            
            if (imageOpacity < 1) {
                const image = await getImage(canvas, imageOpacity);
                dataURL = await convertBlobToBase64(image);
            }

            if (imageScale !== null) {
                // let imageURL = await convertBase64ToBlobURL(dataURL);
                var img = new Image();
                img.src = dataURL;

                if (imageScale >= 0) {
                    img.width = (cropper.getImageData().width / 100) * imageScale;
                    img.height = (cropper.getImageData().height / 100) * imageScale;
                } else {
                    img.width = $('.cropper-canvas > img').width();
                    img.height = $('.cropper-canvas > img').height();
                }

                payload.imageWidth = img.width;
                payload.imageHeight = img.height;
            
                // const image = await getImage(img, 1);
                // dataURL = await convertBlobToBase64(image);
            }
            
            payload.dataURL = dataURL;
        }

        payload.id = $('#imageId').val();
        payload.featured = $('#featured').prop('checked');

        $('#saveImageInfo').prop('disabled', true);

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
                    disableCropper();
                    $('#saveImageInfo').prop('disabled', false);
                    toastr.success("Image updated successfully!");
                }
            },
            error: () => {

            }
        })
    }

    async function getImage(canvas, opacity) {
        return new Promise(resolve => {
            const tmpCanvas = document.createElement('canvas');
            tmpCanvas.width = canvas.width;
            tmpCanvas.height = canvas.height;

            const ctx = tmpCanvas.getContext('2d');
            ctx.globalAlpha = opacity;
            ctx.drawImage(canvas, 0, 0);
            tmpCanvas.toBlob(resolve, 'image/png', 0.9);
        });
    }

    async function convertBlobToBase64(image) {
        return new Promise((resolve, _) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.readAsDataURL(image);
        });
    }

</script>