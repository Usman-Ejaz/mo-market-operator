;
$('form').on("change paste", function (e) {
    if (e.target.id.trim().length <= 0) return;
    const elemId = '#' + e.target.id;
    trimSpaces(e.target.id, e.target.type);
    if ($(elemId).val().length > 0) {
        $(elemId).hasClass("my-error-class") && $(elemId).removeClass("my-error-class");
        $(elemId).next().hasClass("my-error-class") && $(elemId).next().remove();
        $(elemId).next().hasClass("form-text text-danger") && $(elemId).next().text("");
    }
});

// To remove spaces from start and end of a string
function trimSpaces(elementId, elementType) {
    if (['text', 'email', 'number', 'select-one', 'url', 'textarea'].includes(elementType)) {
        $('#' + elementId) && $('#' + elementId).val($('#' + elementId).val().trim());
    }
}

document.querySelector('input[type="number"]') && document.querySelector('input[type="number"]').addEventListener("keypress", function (evt) {
    if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
        evt.preventDefault();
    }
});

const specialCharacters = ['#', '$', '%', '&', '(', ')', '*', '+', '/', ';', '<', '=', '>', '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|', '}' ];
