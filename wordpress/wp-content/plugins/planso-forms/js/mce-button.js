(function() {
	if(typeof planso != 'undefined' && typeof planso.forms != 'undefined' && typeof planso.forms.shortcodes != 'undefined'){
		tinymce.PluginManager.add('planso_forms_shortcodes', function( editor, url ) {
			planso.forms.editor = editor;
			editor.addButton( 'planso_forms_shortcodes', {
				title: 'PlanSo Forms',
				icon: 'planso-gears-icon',
				type: 'menubutton',
				menu: planso.forms.shortcodes
				
			});
		});
	}
})();