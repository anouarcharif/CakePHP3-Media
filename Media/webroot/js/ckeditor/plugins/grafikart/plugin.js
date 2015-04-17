CKEDITOR.plugins.add('grafikart',
{
	requires : [ 'iframedialog' ],
	lang : [ 'fr' ],
	
	init : function(editor)
	{
		var pluginName = 'grafikart';
		
		CKEDITOR.dialog.add('grafikart', this.path + 'dialogs/grafikart.js' );

		
		editor.addCommand( 'grafikart', new CKEDITOR.dialogCommand( 'grafikart' ) );

		editor.ui.addButton('Grafikart',
		{
				label : editor.lang.grafikart.title,
				command : pluginName,
				icon: this.path + 'images/grafikart.png'
		});
	}
});

