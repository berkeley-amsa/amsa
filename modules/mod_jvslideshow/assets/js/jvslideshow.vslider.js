/*
Script: 	
	JVSlideshow - The JV Slideshow Module allows to display the images with effects.

License:
	Proprietary - JoomlaVi Club members only

Copyright:
	Copyright (C) JoomlaVi. All rights reserved.
*/
if(!window.JVSlides) window.JVSlides = {};
;(function(){
JVSlides.vslider = new Class({
	Implements: [Options, Events],
	options:{
		captions: 1,
		autoHideCaptions: 1,
		arrows: 1,
		autoHideArrows: 1,
		controls: 1,
		autoHideControls: 1,
		keyboard: 1,
		autoPlay: 1,
		width: false,
		height: false,
		captionsOpacity: 0.7,
		captionsHeight: 50,
		display: 0,
		delay: 5000,
		duration: 500,
		slices: 20,
		transition: Fx.Transitions.Sine.easeInOut,
		forceEffect: 1,
		fx: 'fade',
		linkTarget: '_blank',
		itemsClass: '.jv-slideshow-items',
		captionsClass: '.jv-slideshow-captions',
		arrowsClass: '.jv-slideshow-arrows',
		controlsClass: '.jv-slideshow-controls'
	},
	
	initialize: function(selector, data, options){
		this.setOptions(options);
		this.selector = $(selector);
		if(!this.selector) return;		
		this.setup(data);
		this.firstTime = true;
	},	
	
	setup: function(data){
		var that = this;
		if(!this.selector) return;
		if($type(data) != 'array') return;
		this.len = data.length;
		if(this.len < 1) return;
		that.options.display = Math.min(Math.max(0, that.options.display), that.len - 1);
		this.data = data;
		this.preload();
		this.items = this.selector.getElement(that.options.itemsClass);
		var itemsSize = this.items.getSize();
		this.options.width = this.options.width || itemsSize.x;
		this.options.height = this.options.height || itemsSize.y;
		this.selector.setStyles({
			'width': that.options.width,
			'height': that.options.height
		});		
		this.effectComplete = true;
		this.itemLoader = this.items.getElement('.loader');
		this.itemLoader.fx = new Fx.Morph(this.itemLoader).set({opacity:1});
		this.initSlices();
		this.itemLink = new Element('a', {
			'class': 'link',
			'href': '#'
		}).inject(this.items);
		if(that.options.keyboard == 1){
			this.initKeyboard();
		}
		if(that.options.captions == 1){
			this.captions = this.selector.getElement(that.options.captionsClass);	
			this.initCaptions();
		}
		if(that.options.arrows == 1){
			this.arrows = this.selector.getElement(that.options.arrowsClass);	
			this.initArrows();
		}
		if(that.options.controls == 1){
			this.controls = this.selector.getElement(that.options.controlsClass);	
			this.initControls();
		}
		if(that.options.autoPlay == 1){		
			this.initAutoPlay();
		}
		this.counter = 0;		
		this.paused = false;		
		this.load(that.options.display);
	},
	
	initSlices: function(){
		var that = this;		
        for(var i = 0; i<this.options.slices; i++){
			var sliceElement = new Element('div', {
				'class': 'slice'
			});			
			sliceElement.inject(this.items);
			var sliceStyles = {
				top: 0,
				left: Math.round(that.options.width/that.options.slices) * i,
				width: (i == this.options.slices - 1) ? that.options.width - (Math.round(that.options.width/that.options.slices) * i) : Math.round(that.options.width/that.options.slices),
				height: 0
			};

			sliceElement.coord = sliceStyles;
			sliceElement.fx = new Fx.Morph(sliceElement, {
				duration: that.options.duration, 
				transition: that.options.transition
			});
			sliceElement.setStyles(sliceStyles);		
		}
	},
	
	initCaptions: function(){
		if(this.options.autoHideCaptions == 1){
			this.captions.fx = new Fx.Morph(this.captions, {link: false}).set({height: 0 , opacity: this.options.captionsOpacity});
			this.selector.addEvents({
				'mouseenter': function(){
					this.captions.fx.cancel().start({height: this.options.captionsHeight, opacity: this.options.captionsOpacity});
				}.bind(this),
				'mouseleave': function(){
					this.captions.fx.cancel().start({height: 0, opacity: 0});
				}.bind(this)			
			});
		}
		else{
			this.captions.fx = new Fx.Morph(this.captions, {link: false}).set({height: this.options.captionsHeight , opacity: this.options.captionsOpacity});
		}
	},
	
	initKeyboard: function(){
		var that = this;
		var keyupEvt = function(e){
			e = new Event(e);			
			switch(e.key){
				case 'left': 
					that.load(e.shift ? 0 : that.options.display > 0 ? that.options.display - 1: that.len - 1); 
					clearInterval(that.autoTimer);
					if(!that.paused && that.options.autoPlay == 1) that.initAutoPlay();
					break;
				case 'right': 
					that.load(e.shift ? that.len - 1 : that.options.display < that.len - 1 ? that.options.display + 1 : 0); 
					clearInterval(that.autoTimer);
					if(!that.paused && that.options.autoPlay == 1) that.initAutoPlay();
					break;
				case 'p': 
					if(that.arrows.getElement('.pause')){
						that.arrows.getElement('.pause').fireEvent('click');					
					}
					break;
			}
		}.bind(this);		
		document.addEvent('keyup', keyupEvt);				
	},
	
	initArrows: function(){
		var that = this;
		if(this.options.autoHideArrows == 1){
			this.arrows.fx = new Fx.Morph(this.arrows, {link: false}).set({opacity:0});
			this.selector.addEvents({
				'mouseenter': function(){
					this.arrows.fx.cancel().start({opacity: 1});
				}.bind(this),
				'mouseleave': function(){
					this.arrows.fx.cancel().start({opacity: 0});
				}.bind(this)			
			});
		}
		else{
			this.arrows.fx = new Fx.Morph(this.arrows, {link: false}).set({opacity:1});
		}
		
		['first', 'prev', 'next', 'last'].each(function(func, index){
			if(that.arrows.getElement('.' + func)){
				that.arrows.getElement('.' + func).addEvent('click', function(){
					that[func].call(that);
				});
			}
		});
		if(this.arrows.getElement('.pause')){
			this.arrows.getElement('.pause').addEvent('click', function(){
				if(this.hasClass('play')){
					this.removeClass('play');
					that.paused = false;
					if(that.options.autoPlay == 1){
						that.initAutoPlay();
					}
				}
				else{
					this.addClass('play');
					that.paused = true;
					clearInterval(that.autoTimer);				
				}
			});	
		}
	},
	
	initControls: function(){
		var that = this;
		if(this.options.autoHideControls == 1){
    		this.controls.fx = new Fx.Morph(this.controls, {link: false}).set({opacity:0});
    		this.selector.addEvents({
    			'mouseenter': function(){
    				this.controls.fx.cancel().start({opacity: 1});
    			}.bind(this),
    			'mouseleave': function(){
    				this.controls.fx.cancel().start({opacity: 0});
    			}.bind(this)			
    		});
        }
        else{
			this.controls.fx = new Fx.Morph(this.controls, {link: false}).set({opacity:1});
		}
		this.controls.getElements('a').each(function(aLink){
			aLink.addEvent('click', function(e){
				e = new Event(e).stop();
				that.load(this.getProperty('title').trim().toInt() - 1);
			});
		});
	},
	
	initAutoPlay: function(){
		var that = this;
		if(that.len <= 1) return;
		clearInterval(this.autoTimer);
		this.autoTimer = setInterval(function(){
			that.load(that.options.display < that.len - 1 ? that.options.display + 1 : 0);
		}, that.options.delay);
	},
	
	preload: function(){
		var that = this;
		this.data.each(function(imgdata, index){
			if(!imgdata.image && index != that.options.display){
				var assetImg = new Asset.image(imgdata.name, {
					onload: function(){
						imgdata.image = assetImg;
					}
				});
			}
		});
	},
	
	load: function(index){
		this.options.display = index;
		if(!this.data[index].image){			
			if(this.data[index].image !== false){
				clearInterval(this.autoTimer);
				this.itemLoader.fx.cancel().start({opacity: 1});
				this.data[index].image = false;				
				var that = this,
					assetImg = new Asset.image(that.data[index].name, {
					onload: function(){
						that.data[index].image = assetImg;
					}
				});
			}			
			this.load.delay(50, this, index);
		}else{
			if(!this.paused && this.options.autoPlay == 1) this.initAutoPlay();
			this.show();						
		}
	},
	
	first: function(){
		this.load(0);
	},
	
	prev: function(){
		this.load(this.options.display > 0 ? this.options.display - 1: this.len - 1);
	},	
	
	next: function(){
		this.load(this.options.display < this.len - 1 ? this.options.display + 1 : 0);	
	},	
	
	last: function(){
		this.load(this.len - 1);
	},	
	
	show: function(fx){			
		var fxType = this.options.fx.split(',').getRandom();
		if(this.options.captions == 1){
			this.captions.fx.clearChain();
			if(this.options.autoHideCaptions == 1){
				this.captions.fx.cancel().start({height: 0, opacity: 0}).chain(this.start.bind(this, fxType));
			}
			else{
				this.captions.fx.cancel().start({opacity: 0}).chain(this.start.bind(this, fxType));
			}
		}else{			
			this.start(fxType);
		}
	},
	
	start: function(fx){
		var effect = fx ? fx : this.options.fx,
			data = this.data[this.options.display],
			image = data.image;		
		this.image = image;	
		this.timer = 0;
		if(this.firstTime){
			effect = 'fade';
			this.firstTime = false;
			this.items.setStyle('background-image', 'url(' + this.image.getProperty('src') + ')');
		}
	
		if(!this.image || (!this.effectComplete && this.options.forceEffect)){
			return;
		}	
		if(this.options.captions == 1){
			this.captions.getElement('.description').set('html', data.description);
			if(this.options.autoHideCaptions == 1){
				this.captions.fx.cancel().start({height: this.options.captionsHeight, opacity: this.options.captionsOpacity});
			}
			else{
				this.captions.fx.cancel().start({opacity: this.options.captionsOpacity});
			}
		}
		
		this.itemLink.setProperty('title', data.title);
		if(data.link != ''){
			this.itemLink.setProperty('href', data.link);
		}
		else{
			this.itemLink.removeProperty('href');
		}
		this.itemLink.setProperty('target', this.options.linkTarget);
			
		this.itemLoader.fx.cancel().start({opacity:0});
		this.effectComplete = false;
		this[effect].call(this);
		
		if(this.options.controls == 1){
			this.controls.getElements('li').removeClass('active')[this.options.display].addClass('active');
		}
	},
	
	reset: function(){
		this.items.getElements('.slice').each(function(sliceElement, index){
			var coord =  sliceElement.coord;
			sliceElement.fx.cancel();			
            sliceElement.setStyles({
                background: 'url(' + this.image.getProperty('src') + ') no-repeat -'+ coord.left + 'px ' + coord.top * -1 + 'px',
				top: 0,
				left: coord.left,
				width: coord.width,
				height: coord.height,
                opacity: 0,
				bottom: 'auto',
				right: 'auto'
            });
		}.bind(this));
	},
	
	fade: function(){
		var that = this;
		this.reset();
		var sliceElement = this.items.getElements('.slice')[0];
		var sliceFx = {
			opacity: 1
		};
		sliceElement.setStyles({
			width: this.options.width,
			height: this.options.height
		});
		this.runFx(sliceElement, sliceFx, true);	
	},
	
	fold: function(){		
		this.reset();
		this.items.getElements('.slice').each(function(sliceElement, index){
			var sliceFx = {
				opacity: 1,
				width: sliceElement.getCoordinates().width				
			};			
			sliceElement.setStyles({
				width: 0,
				height: this.options.height,
				top: 0,
				bottom: 'auto'			
			});
			this.runFx.delay(100 + this.timer, this, [sliceElement, sliceFx]);				
			this.timer += 50;
		}.bind(this));
	},
	
	sliceDownLeft: function(){		
		this.reset();
		var slices = this.items.getElements('.slice');	
		slices.reverse();	
		var sliceFx = {
			opacity: 1,
			height: this.options.height
		};
		slices.each(function(sliceElement, index){						
			sliceElement.setStyles({				
				top: 0,
				bottom: 'auto'					
			});
			this.runFx.delay(100 + this.timer, this, [sliceElement, sliceFx]);				
			this.timer += 50;
		}.bind(this));
	},
	
	sliceDownRight: function(){		
		this.reset();
		var slices = this.items.getElements('.slice');			
		var sliceFx = {
			opacity: 1,
			height: this.options.height
		};
		slices.each(function(sliceElement, index){						
			sliceElement.setStyles({				
				top: 0,
				bottom: 'auto'	
			});
			this.runFx.delay(100 + this.timer, this, [sliceElement, sliceFx]);				
			this.timer += 50;
		}.bind(this));
	},
	
	sliceUpDownLeft: function(){		
		this.reset();
		var slices = this.items.getElements('.slice');			
		slices.reverse();
		var sliceFx = {
			opacity: 1,
			height: this.options.height
		};	
		slices.each(function(sliceElement, index){				
			if(index % 2 == 0){	
				sliceElement.setStyles({				
					top: 0,
					bottom: 'auto'	
				});
			}
			else{
				sliceElement.setStyles({				
					bottom: 0,
					top: 'auto'
				});
			}
			this.runFx.delay(100 + this.timer, this, [sliceElement, sliceFx]);				
			this.timer += 50;
		}.bind(this));
	},
	
	sliceUpDownRight: function(){		
		this.reset();
		var slices = this.items.getElements('.slice');			
		var sliceFx = {
			opacity: 1,
			height: this.options.height
		};	
		slices.each(function(sliceElement, index){				
			if(index % 2 == 0){	
				sliceElement.setStyles({				
					top: 0,
					bottom: 'auto'			
				});
			}
			else{
				sliceElement.setStyles({				
					bottom: 0,
					top: 'auto'
				});
			}
			this.runFx.delay(100 + this.timer, this, [sliceElement, sliceFx]);				
			this.timer += 50;
		}.bind(this));
	},
	
	sliceUpLeft: function(){		
		this.reset();
		var slices = this.items.getElements('.slice');			
		slices.reverse();
		var sliceFx = {
			opacity: 1,
			height: this.options.height
		};	
		slices.each(function(sliceElement, index){				
			sliceElement.setStyles({				
				bottom: 0,
				top: 'auto'				
			});
			this.runFx.delay(100 + this.timer, this, [sliceElement, sliceFx]);				
			this.timer += 50;
		}.bind(this));
	},
	
	sliceUpRight: function(){		
		this.reset();
		var slices = this.items.getElements('.slice');					
		var sliceFx = {
			opacity: 1,
			height: this.options.height
		};	
		slices.each(function(sliceElement, index){				
			sliceElement.setStyles({				
				bottom: 0,
				top: 'auto'				
			});
			this.runFx.delay(100 + this.timer, this, [sliceElement, sliceFx]);				
			this.timer += 50;
		}.bind(this));
	},
		
	wipeLeft: function(){		
		this.reset();
		var sliceElement = this.items.getElements('.slice')[0];		
		var sliceFx = {			
			width: this.options.width
		};	
		sliceElement.setStyles({
			opacity: 1,
			width: 0,
			height: this.options.height
		});
		this.runFx(sliceElement, sliceFx, true);
	},
	
	wipeRight: function(){		
		this.reset();
		var sliceElement = this.items.getElements('.slice')[0];		
		var sliceFx = {			
			width: this.options.width
		};	
		sliceElement.setStyles({
			opacity: 1,
			width: 0,
			height: this.options.height,
			backgroundPosition: 'top right',
			left: 'auto',
			right: 0
		});
		this.runFx(sliceElement, sliceFx, true);
	},
	
	runFx: function(sliceElement, fxStyle, isFinish){
		sliceElement.fx.start(fxStyle).chain(function(){
			this.counter++;
			if(this.counter == this.options.slices || isFinish){
				this.effectComplete = true;
				this.items.setStyle('background-image', 'url(' + this.image.getProperty('src') + ')');
				this.counter = 0;
			}
		}.bind(this));
	}	
});

})();