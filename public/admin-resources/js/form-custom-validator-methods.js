
/**
 *
 *
 *
 */
if ('undefined' !== typeof CKEDITOR) {

    /**
     *
     *
     *
     */
    CKEDITOR?.instances?.description?.on('blur', function(e) {
        var messageLength = CKEDITOR?.instances?.description?.getData().replace(/<[^>]*>/gi, '').replace(/&nbsp;/gi, '').trim().length;
        if (messageLength !== 0) {
            $('#cke_description')?.next().hasClass("my-error-class") && $('#cke_description').next().remove();
        }
    });

    /**
     *
     *
     *
     */
    CKEDITOR?.instances?.answer?.on('blur', function(e) {
        var messageLength = CKEDITOR?.instances?.answer?.getData().replace(/<[^>]*>/gi, '').replace(/&nbsp;/gi, '').trim().length;
        if (messageLength !== 0) {
            $('#cke_answer').next().hasClass("my-error-class") && $('#cke_answer').next().remove();
        }
    });

    /**
     *
     *
     *
     */
    CKEDITOR?.instances?.contents?.on('blur', function(e) {
        var messageLength = CKEDITOR.instances.contents.getData().replace(/<[^>]*>/gi, '').replace(/&nbsp;/gi, '').trim().length;
        if (messageLength !== 0) {
            $('#cke_contents').next().hasClass("my-error-class") && $('#cke_contents').next().remove();
        }
    });


    /**
     *
     *
     *
     */
    $.validator.addMethod("ckeditor_required", function(value, element) {
        var editorId = $(element).attr('id');
        var messageLength = CKEDITOR?.instances[editorId].getData().replace(/<[^>]*>/gi, '').replace(/&nbsp;/gi, '').trim().length;
        return messageLength !== 0;
    }, 'This field is required.');
}


/**
 *
 *
 *
 */
$.validator.addMethod("notNumericValues", function(value, element) {
    return this.optional(element) || isNaN(Number(value)) || value.indexOf('e') !== -1;
}, 'This field cannot contain only numbers.');

/**
 *
 *
 *
 */
$.validator.addMethod("prevent_special_characters", function(value, element) {
    return this.optional(element) || specialCharacters.filter(ch => value.indexOf(ch) !== -1).length === 0;
}, 'This field cannot contain special characters.');

/**
 *
 *
 *
 */
$.validator.addMethod('docx_extension', function (value, element, param) {
    let files = Array.from(element.files);
    param = param.split('|');
    let invalidFiles = files.filter(file => !param.includes(file.name.split('.').at(-1)));
    return this.optional(element) || invalidFiles.length === 0;
}, 'Please attach a file with valid extension.');

/**
 *
 *
 *
 */
$.validator.addMethod('upload_threshold', function (value, element, param) {
    let files = Array.from(element.files).length;
    return this.optional(element) || files <= param;
}, 'Maximum 5 files are allowed to upload in one time.');

/**
 *
 *
 *
 */
$.validator.addMethod("validURL", function(value, element) {
    var pattern = /^(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/;
    return (this.optional(element) || value === "#" || pattern.test(value));
}, 'Please enter a valid URL.');

/**
 *
 *
 *
 */
$.validator.addMethod('validEmailAddress', function (value, element) {
    let pattern = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
    return (this.optional(element) || pattern.test(value));
}, 'Please enter a valid Email.');
