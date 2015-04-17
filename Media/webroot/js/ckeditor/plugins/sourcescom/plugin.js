CKEDITOR.plugins.add('sourcescom',
{
	requires : [ 'iframedialog' ],
	lang : [ 'fr' ],
	
	init : function(editor)
	{
		var pluginName = 'sourcescom';
		
		CKEDITOR.dialog.add('sourcescom', this.path + 'dialogs/sourcescom.js' );

		editor.addCommand( 'sourcescom', new CKEDITOR.dialogCommand( 'sourcescom' ) );

		editor.ui.addButton('Sourcescom',
		{
				label : editor.lang.sourcescom.title,
				command : pluginName,
				icon: this.path + 'images/01sources.png'
		});
	}
});

