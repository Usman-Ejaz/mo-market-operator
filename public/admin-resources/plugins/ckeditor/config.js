/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

// const { toSafeInteger } = require("lodash");

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var BASE_URL = window.location.origin;

    config.extraPlugins = 'filebrowser';
    config.filebrowserUploadUrl = BASE_URL + "/admin/ckeditor/upload?_token=" + CSRF_TOKEN;
    config.filebrowserUploadMethod = 'form';

    config.toolbarGroups = [
        { name: 'document', groups: ['mode', 'document', 'doctools'] },
        { name: 'clipboard', groups: ['clipboard', 'undo'] },
        { name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'] },
        { name: 'forms', groups: ['forms'] },
        { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
        { name: 'colors', groups: ['colors'] },
        { name: 'tools', groups: ['tools'] },
        { name: 'others', groups: ['others'] },
        { name: 'about', groups: ['about'] },
        '/',
        { name: 'styles', groups: ['styles'] },
        '/',
        { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align',/* 'bidi', 'paragraph'*/] },
        { name: 'links', groups: ['links'] },
        { name: 'insert', groups: ['insert'] }
    ];

    // config.removeButtons = 'Save,NewPage,ExportPdf,Preview,Print,Source,Templates,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField';
    config.removeButtons = 'Save,NewPage,ExportPdf,Print,Templates,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField';
    config.copyFormatting_allowRules = true;
    // config.copyFormatting_allowRules = 'b; s; u; strong; span; p; div; table; thead; tbody; h1; h2; h3; h4; h5; h6;' + 'tr; td; th; ol; ul; li; (*)[*]{*}';
};

CKEDITOR.on("instanceReady", function (event) {

    event.editor.document.on('drop', function (ev) {
        ev.data.preventDefault(true);
    });

    event.editor.on("beforeCommandExec", function (event) {
        // Show the paste dialog for the paste buttons and right-click paste
        if (event.data.name == "paste") {
            event.editor._.forcePasteDialog = true;
        }
        // Don't show the paste dialog for Ctrl+Shift+V
        if (event.data.name == "pastetext" && event.data.commandData.from == "keystrokeHandler") {
            event.cancel();
        }
    })
});
