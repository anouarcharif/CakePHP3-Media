var myEditorForMedia = null;
var myEditorDialog = null;

function send_to_ckeditor(content, win){
    myEditorForMedia.insertHtml(content);
	CKEDITOR.dialog.getCurrent().hide()
}

CKEDITOR.dialog.add('grafikart', function(editor)
{
	myEditorForMedia = editor;
	return {
		title : 'Insérer un média',
		minWidth : 1000,
		minHeight : 500,
		buttons : [],
		contents :
		[
			{
				id : 'iframe',
				label : '',
				expand : true,
				elements :
				[ {
					type   : 'iframe',
					width  : '100%',
					height : '500px',
					src    : $('#explorer').val() + '/editor:ckeditor',
				} ]
			},
		]
	};
});