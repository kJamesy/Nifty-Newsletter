/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'en-gb';
	config.uiColor = '#46B8DA';
	// config.removeButtons = 'Save,NewPage,SelectAll,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,BidiLtr,BidiRtl,SetLanguage';
	// config.allowedContent = true;

	config.toolbar = 'Full';
	 
	config.toolbar_Full =
	[
		{ name: 'document', items : ['Templates','-','Preview','Print' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','Scayt'] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline' ] },
		{ name: 'morebasicstyles', items : [ 'Strike','Subscript','Superscript' ] },
		{ name: 'tools', items : [ 'ShowBlocks', 'Maximize' ] },
		{ name: 'html', items : ['Source'] },
		'/',
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'paragraph', items : [ 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', '-', 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote'] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		{ name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar'] }
	];

	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	config.allowedContent = true;		
};
