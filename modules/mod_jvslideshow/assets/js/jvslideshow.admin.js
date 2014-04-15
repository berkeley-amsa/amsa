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
        var select,
            custom =  $('jform_params_JVSlideshow_slides').getParent();
        [0,1,2].each(function(i){
            var checkBox = $('jform_params_jvslideshow_source'+i);
            checkBox.addEvent('change',function(){
                if(this.value.toInt() === 1) custom.setStyle('display','');
                else custom.setStyle('display','none');
             });
             if(checkBox.checked) checkBox.fireEvent('change');
        });
	}
});

window.addEvent('domready', function(){ 
	new JVSlideshowAdmin();	
});
		