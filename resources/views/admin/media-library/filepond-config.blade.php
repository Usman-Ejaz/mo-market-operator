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
    let actionId = "";

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
            actionId = e.target.dataset.id;
            if (!actionId) {
                console.log(actionId);
                toastr.error("Could not find media file.");
                return;
            } else {
                $('#deleteModal').modal('toggle');
            }
        });

        $('#deleteForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route("admin.media-library.files.remove") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: { id: actionId },
                success: (response) => {
                    let { status, message } = response;
                    if (status === 'success') {
                        toastr.success(message);
                        loadAllImages();
                        $('#deleteModal').modal('toggle');
                    }
                },
                error: (error) => {
                    console.log(error);
                    toastr.error("Something went wrong");
                    $('#deleteModal').modal('toggle');
                }
            })
            // $(this).attr('action', action);
        });

		$('.editor-modal-close').on('click', function () {
			disableCropper();
			$('#imageViewModal').modal('hide');
		});

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

    /**
     * Load all images against the selected event from the resource.
     *
     * @retrun void
     */
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
                                    <button class="btn btn-danger btn-sm btn-remove" data-id="${ item.id }"><i class="fa fa-trash" aria-hidden="true" data-id="${ item.id }"></i></button>
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

    /**
     * Register a new Fileponf object in the DOM with the options object.
     *
     * @return void
    */
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
            maxFiles: 10,
            maxParallelUploads: 5,
            credits: false,
            allowMultiple: true,
            required: true,
            labelIdle: 'Drag & Drop your files or <span class="filepond--label-action"> Browse </span><br /><small style="font-size: 12px;">Maximum 10 files are allowed to upload at a time.</small>',
            labelTapToUndo: '',
            labelTapToCancel: '',
            labelTapToRetry: '',
            server: {
                process: {
                    url: '{{ route("admin.media-library.files.upload", $mediaLibrary->id) }}',
                    method: 'POST',
                    onload: (response) => {

                    },
                    onerror: (response) => {
                        toastr.error('Something went wrong.');
                    },
                },
                revert: null,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            onprocessfile: (error, file) => {
                setTimeout(() => {
                    pond.removeFile(file.id);
                    loadAllImages();
                    toastr.success('{{ __("messages.record_updated", ["module" => "Media file"]) }}');
                }, 16);
            },
            // onprocessfiles: (error, file) => {
            //     setTimeout(() => {
            //         pond.removeFiles();
            //         loadAllImages();
            //         toastr.success('Media file uploaded successfully!');
            //     }, 16);
            // },
            onwarning: (err) => {
                if (err.body === "Max files") {
                    toastr.error('Maximum 10 files are allowed to upload at a time.');
                }
            }
        });
    }

    /**
     * Enables CropperJs library to the given image tag with some cropper default actions
     *
     * @return void
    */
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
     * Disables CropperJs library from the DOM.
     *
     * @return void
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
     * Saves customized image in the resource.
     *
     * @return void
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
                    $('.editor-modal-close').click();
                    disableCropper();
                    $('#saveImageInfo').prop('disabled', false);
                    toastr.success("{{ __('messages.record_updated', ['module' => 'Media file']) }}");
                }
            },
            error: () => {

            }
        })
    }

    /**
     * Get the latest image after applying opacity.
     *
     * @return promise
    */
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

    /**
     * Converts Blob to Base64 string
     *
     * @return promise
    */
    async function convertBlobToBase64(image) {
        return new Promise((resolve, _) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.readAsDataURL(image);
        });
    }

</script>
