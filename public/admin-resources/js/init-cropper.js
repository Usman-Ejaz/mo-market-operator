;
let cropper = null;
let imageOpacity = 1;
let imageScale = null; // in percent
let actionId = "";

$('body').on('click', '#customizeImage', function () {
    let { src } = $(this).data();
    $('#imageSrc').attr('src', src);
    $('#imageViewModal').modal({backdrop: 'static', keyboard: false});
    setTimeout(() => {
        enableCropper();
    }, 200);
});

$('.editor-modal-close').on('click', function () {
    disableCropper();
    $('#imageViewModal').modal('hide');
});

$('#opcaityDropdown').on('click', (e) => {
    let { value } = e.target.dataset;
    value /= 100;
    imageOpacity = value;
    $('.cropper-canvas, .cropper-img-preview').css({
        opacity: `${value}`,
    });
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

$('#saveImageInfo').on('click', function (e) {
    saveImageInfo();
});

function enableCropper() {

    let elem = document.getElementById('imageSrc');
    let height = elem.height + 4;
    $('#cropper-actions').css({display: 'block'});

    $('.cropper-img-preview').css({
        width: '100%',
        height: height,
        overflow: 'hidden',
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
    });

    let flipX = 1, flipY = 1, data = null, rotation = 0;
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

function disableCropper () {
    $('#cropper-actions').css({display: 'none'});
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

    payload.id = $('#postId').val();

    $('#saveImageInfo').prop('disabled', true);

    $.ajax({
        url: $('#uploadImageUrl').val(),
        method: 'POST',
        data: payload,
        headers: {
            'X-CSRF-TOKEN': $('#csrf_token').val()
        },
        success: (response) => {
            let { status } = response;
            if (status === "success") {
                $('.editor-modal-close').click();
                disableCropper();
                $('#saveImageInfo').prop('disabled', false);
                toastr.success(response.message);
                setTimeout(() => {
                    window.location.reload();
                }, 200);
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
