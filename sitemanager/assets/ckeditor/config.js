/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */
CKEDITOR.editorConfig = function( config ) {
    config.toolbarGroups = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
        { name: 'editing', groups: [ 'spellchecker', 'find', 'selection', 'editing' ] },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'forms', groups: [ 'forms' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
        { name: 'links', groups: [ 'links' ] },
        { name: 'insert', groups: [ 'insert' ] },
        '/',
        '/',
        { name: 'colors', groups: [ 'colors' ] },
        { name: 'styles', groups: [ 'styles' ] },
        { name: 'tools', groups: [ 'tools' ] },
        { name: 'others', groups: [ 'others' ] },
        { name: 'about', groups: [ 'about' ] }
    ];

    config.removeButtons = 'Find,SelectAll,Form,CopyFormatting,Blockquote,Save,NewPage,Preview,Print,Undo,Redo,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,About,Maximize,ShowBlocks,BGColor,Flash,Smiley,SpecialChar,PageBreak,Iframe,Language,BidiRtl,BidiLtr,CreateDiv,Superscript,Subscript,Underline,Replace';
};