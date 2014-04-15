/*
Script: 	
	JVSlideshow - The JV Slideshow Module allows to display the images with effects.

License:
	Proprietary - JoomlaVi Club members only

Copyright:
	Copyright (C) JoomlaVi. All rights reserved.
*/

if(!window.JVSlides) window.JVSlides = {}
;(function(){
	JVSlides.swap = new Class({
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
		duration: 1500,
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
		var itemElement = new Element('div', {
			'class': 'item'
		});
		new Element('a').adopt(new Element('img')).inject(itemElement);
		this.effectComplete = true;
		this.itemFirst = itemElement.inject(this.items, 'top');
		this.itemSecond = itemElement.clone().inject(this.items, 'top');
		this.itemFirst.fx = new Fx.Morph(this.itemFirst, {duration: that.options.duration, transition: that.options.transition}).set({opacity:0});
		this.itemSecond.fx = new Fx.Morph(this.itemSecond, {duration: that.options.duration, transition: that.options.transition, onComplete: function(){that.effectComplete = true;}}).set({opacity:0});
		this.itemLoader = this.items.getElement('.loader');
		this.itemLoader.fx = new Fx.Morph(this.itemLoader).set({opacity:1});
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
		if(this.firstTime){
			effect = 'fade';
			this.firstTime = false;
		}
		if(!image || (!this.effectComplete && this.options.forceEffect)){
			return;
		}		
		if(this.counter % 2){
			this.image = this.itemSecond;
			this.oldImage = this.itemFirst;	
		}
		else{
			this.image = this.itemFirst;
			this.oldImage = this.itemSecond;
			this.counter = 0;
		}
		this.counter++;		
		this.oldImage.setStyles({			
			'zIndex': 0
		});
		this.image.setStyles({
			'display': 'block', 
			'width': 'auto', 
			'height': 'auto', 
			'visibility': 'hidden',
			'zIndex': 1
		});
		['src', 'height', 'width'].each(function(prop){
			this.image.getElement('img').setProperty(prop, image[prop]);
		}.bind(this));
		
		if(this.options.captions == 1){
			this.image.getElement('img').setProperty('alt', data.title);
			this.captions.getElement('.description').set('html', data.description);
			if(this.options.autoHideCaptions == 1){
				this.captions.fx.cancel().start({height: this.options.captionsHeight, opacity: this.options.captionsOpacity});
			}
			else{
				this.captions.fx.cancel().start({opacity: this.options.captionsOpacity});
			}
		}
		
		var anchor = this.image.getElement('a');
		anchor.setProperty('title', data.title);
		if(data.link != ''){
			anchor.setProperty('href', data.link);
		}
		else{
			anchor.removeProperty('href');
		}
		anchor.setProperty('target', this.options.linkTarget);
		
		this.itemLoader.fx.cancel().start({opacity:0});
		this.effectComplete = false;
		this[effect].call(this);
		
		if(this.options.controls == 1){
			this.controls.getElements('li').removeClass('active')[this.options.display].addClass('active');
		}
	},
	
	reset: function(){
		this.oldImage.fx.cancel().set({left: 0, top: 0});
		this.image.fx.cancel().set({left: 0, top: 0});
	},
	
	fade: function(){
		this.reset();			
		this.image.fx.set({opacity: 0});
		this.oldImage.fx.start({opacity: 0});
		this.image.fx.start({opacity: 1});
	},
	
	slideLeft: function(){
		this.reset();				
		this.oldImage.fx.set({opacity: 1});
		this.image.fx.set({opacity: 1});
		this.oldImage.fx.set({'left':0});
		this.image.fx.set({'left':this.options.width});		
		this.oldImage.fx.start({'left':-this.options.width});
		this.image.fx.start({'left':0});		
	},
	
	slideRight: function(){
		this.reset();				
		this.oldImage.fx.set({opacity: 1});
		this.image.fx.set({opacity: 1});
		this.oldImage.fx.set({'left':0});
		this.image.fx.set({'left':-this.options.width});		
		this.oldImage.fx.start({'left':this.options.width});
		this.image.fx.start({'left':0});		
	},
	
	slideTop: function(){
		this.reset();				
		this.oldImage.fx.set({opacity: 1});
		this.image.fx.set({opacity: 1});
		this.oldImage.fx.set({'top':0});
		this.image.fx.set({'top':this.options.height});		
		this.oldImage.fx.start({'top':-this.options.height});
		this.image.fx.start({'top':0});		
	},
	
	slideBottom: function(){
		this.reset();				
		this.oldImage.fx.set({opacity: 1});
		this.image.fx.set({opacity: 1});
		this.oldImage.fx.set({'top':0});
		this.image.fx.set({'top':-this.options.height});		
		this.oldImage.fx.start({'top':this.options.height});
		this.image.fx.start({'top':0});		
	},
	
	fadeLeft: function(){	
		this.reset();		
		this.oldImage.fx.set({opacity: 1});		
		this.image.fx.set({opacity: 1, left: this.options.width});	
		this.oldImage.fx.start({left: 0, opacity: 0});				
		this.image.fx.start({left: 0});
	},
	
	fadeRight: function(){
		this.reset();
		this.oldImage.fx.set({opacity: 1});		
		this.image.fx.set({opacity: 1, left: -this.options.width});	
		this.oldImage.fx.start({left: 0, opacity: 0});				
		this.image.fx.start({left: 0});
	},
	
	fadeTop: function(){	
		this.reset();
		this.oldImage.fx.set({opacity: 1});		
		this.image.fx.set({opacity: 1, top: this.options.height});	
		this.oldImage.fx.start({top: 0, opacity: 0});				
		this.image.fx.start({top: 0});
	},
	
	fadeBottom: function(){
		this.reset();
		this.oldImage.fx.set({opacity: 1});		
		this.image.fx.set({opacity: 1, top: -this.options.height});	
		this.oldImage.fx.start({top: 0, opacity: 0});				
		this.image.fx.start({top: 0});
	},
	crossFadeLeft: function(){
		this.reset();				
		this.image.fx.set({opacity: 0, left: this.options.width/2});	
		this.oldImage.fx.start({left: this.options.width/2, opacity: 0});				
		this.image.fx.start({left: 0, opacity: 1});
	},
	crossFadeRight: function(){
		this.reset();				
		this.image.fx.set({opacity: 0, left: -this.options.width/2});	
		this.oldImage.fx.start({left: -this.options.width/2, opacity: 0});				
		this.image.fx.start({left: 0, opacity: 1});
	},
	crossFadeTop: function(){
		this.reset();				
		this.image.fx.set({opacity: 0, top: this.options.height/2});	
		this.oldImage.fx.start({top: this.options.height/2, opacity: 0});				
		this.image.fx.start({top: 0, opacity: 1});
	},
	crossFadeBottom: function(){
		this.reset();				
		this.image.fx.set({opacity: 0, top: -this.options.height/2});	
		this.oldImage.fx.start({top: -this.options.height/2, opacity: 0});				
		this.image.fx.start({top: 0, opacity: 1});
	}
});
})();
