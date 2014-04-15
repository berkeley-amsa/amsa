/*
Script: 	
	JVSlideshowAdmin - The JV Slideshow Module allows to display the images with effects.

License:
	Proprietary - JoomlaVi Club members only

Copyright:
	Copyright (C) JoomlaVi. All rights reserved.
*/

var JVSlideshowAdmin = new Class({
	
	initialize: function(){
		this.initFn();
	},	
	
	initFn: function(data){
		if($('jform_params_jvslideshow_source0')){
			$('jform_params_jvslideshow_source0').addEvent('click', function(e){
				$('jform_params_jvslideshow_images').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_links').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_titles').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_descriptions').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_bannerid').getParent().setStyle('display', 'none');	
				if(e){
					$('basic-options').getNext().setStyle('height', 'auto');
				}
			});
			if($('jform_params_jvslideshow_source0').checked){
				$('jform_params_jvslideshow_source0').fireEvent('click');
			}
		}
		if($('jform_params_jvslideshow_source1')){
			$('jform_params_jvslideshow_source1').addEvent('click', function(e){
				$('jform_params_jvslideshow_images').getParent().setStyle('display', '');
				$('jform_params_jvslideshow_links').getParent().setStyle('display', '');
				$('jform_params_jvslideshow_titles').getParent().setStyle('display', '');
				$('jform_params_jvslideshow_descriptions').getParent().setStyle('display', '');
				$('jform_params_jvslideshow_bannerid').getParent().setStyle('display', 'none');
				if(e){
					$('basic-options').getNext().setStyle('height', 'auto');
				}
			});
			if($('jform_params_jvslideshow_source1').checked){
				$('jform_params_jvslideshow_source1').fireEvent('click');
			}
		}
		if($('jform_params_jvslideshow_source2')){
			$('jform_params_jvslideshow_source2').addEvent('click', function(e){
				$('jform_params_jvslideshow_images').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_links').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_titles').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_descriptions').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_bannerid').getParent().setStyle('display', '');
				if(e){
					$('basic-options').getNext().setStyle('height', 'auto');
				}
			});
			if($('jform_params_jvslideshow_source2').checked){
				$('jform_params_jvslideshow_source2').fireEvent('click');
			}
		}
		if($('jform_params_jvslideshow_fxusing0')){
			$('jform_params_jvslideshow_fxusing0').addEvent('click', function(e){
				$('jform_params_jvslideshow_fxswap').getParent().setStyle('display', '');
				$('jform_params_jvslideshow_fxhorizontalslider').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_fxverticalslider').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_fxboxslider').getParent().setStyle('display', 'none');
				if(e){
					$('basic-options').getNext().setStyle('height', 'auto');
				}
			});
			if($('jform_params_jvslideshow_fxusing0').checked){
				$('jform_params_jvslideshow_fxusing0').fireEvent('click');
			}
		}
		if($('jform_params_jvslideshow_fxusing1')){
			$('jform_params_jvslideshow_fxusing1').addEvent('click', function(e){
				$('jform_params_jvslideshow_fxswap').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_fxhorizontalslider').getParent().setStyle('display', '');
				$('jform_params_jvslideshow_fxverticalslider').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_fxboxslider').getParent().setStyle('display', 'none');
				if(e){
					$('basic-options').getNext().setStyle('height', 'auto');
				}
			});
			if($('jform_params_jvslideshow_fxusing1').checked){
				$('jform_params_jvslideshow_fxusing1').fireEvent('click');
			}
		}
		if($('jform_params_jvslideshow_fxusing2')){
			$('jform_params_jvslideshow_fxusing2').addEvent('click', function(e){
				$('jform_params_jvslideshow_fxswap').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_fxhorizontalslider').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_fxverticalslider').getParent().setStyle('display', '');
				$('jform_params_jvslideshow_fxboxslider').getParent().setStyle('display', 'none');
				if(e){
					$('basic-options').getNext().setStyle('height', 'auto');
				}
			});
			if($('jform_params_jvslideshow_fxusing2').checked){
				$('jform_params_jvslideshow_fxusing2').fireEvent('click');
			}
		}
		if($('jform_params_jvslideshow_fxusing3')){
			$('jform_params_jvslideshow_fxusing3').addEvent('click', function(e){
				$('jform_params_jvslideshow_fxswap').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_fxhorizontalslider').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_fxverticalslider').getParent().setStyle('display', 'none');
				$('jform_params_jvslideshow_fxboxslider').getParent().setStyle('display', '');
				if(e){
					$('basic-options').getNext().setStyle('height', 'auto');
				}
			});
			if($('jform_params_jvslideshow_fxusing3').checked){
				$('jform_params_jvslideshow_fxusing3').fireEvent('click');
			}
		}
	}
});

window.addEvent('domready', function(){ 
	new JVSlideshowAdmin();	
});
		