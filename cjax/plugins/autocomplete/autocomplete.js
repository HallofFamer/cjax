/* global CJAX */

/**
 * autocomplete 1.0
 * 
 * Auto Complete Plugin for Cjax
 */

//allows to import these files before the plugin is ran.

CJAX.importFile({
	files: 'css/style.css,helper.js',
	plugin:'autocomplete',
	callback: function() {
		AC.init(CJAX.xml('elementId',CJAX._plugins['autocomplete']));
	}
});

function autocomplete(url){
	version = CJAX.version.replace(/[^0-9\.].*/,'');
	CJAX.ajaxSettings.cache = true;
	
	url = url.replace(/\/+$/,"");//remove any slashes at the end
	this.get(url+'/'+this.element.value+'/', function(data) {
		if(data) {
			AC.data = data;
		}
	},'json');
}