/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	config.extraPlugins = 'filebrowser';
	config.filebrowserUploadUrl = 'http://127.0.0.1:8000/admin/pages/ckeditor?_token='+CSRF_TOKEN;
	config.filebrowserUploadMethod = 'form';
};
