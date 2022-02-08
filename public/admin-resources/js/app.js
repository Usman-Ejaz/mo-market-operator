;
$('form').on("change paste", function (e) {
    const elemId = '#' + e.target.id;
    trimSpaces(e.target.id);
    if ($(elemId).val().length > 0) {
        $(elemId).hasClass("my-error-class") && $(elemId).removeClass("my-error-class");
        $(elemId).next().hasClass("my-error-class") && $(elemId).next().remove();
        $(elemId).next().hasClass("form-text text-danger") && $(elemId).next().text("");
    }
});

// To remove spaces from start and end of a string
function trimSpaces(elementId) {
    $('#' + elementId).val($('#' + elementId).val().trim());
}