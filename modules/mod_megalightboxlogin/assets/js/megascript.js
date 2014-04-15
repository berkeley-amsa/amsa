/*----------------------------------------------------------------------
 # mod_megalightboxlogin - Mega Lightbox Login Module For Joomla! 1.6
 #----------------------------------------------------------------------
 # author OmegaTheme.com
 # copyright Copyright(C) 2011 - OmegaTheme.com. All Rights Reserved.
 # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Website: http://omegatheme.com
 # Technical support: Forum - http://omegatheme.com/forum/
------------------------------------------------------------------------*/

window.addEvent('domready', function() {
	var overlay = new Element('div', {
			id: 'overlay',
			styles: {display: 'none', zIndex: 99990}
		});
	var win = new Element('div', {
			id: 'container',
			styles: {display: 'none', zIndex: 99992}
		});
	var closeBtn = new Element('a', {id: 'mega-closebox', href: 'javascript:void(0);'}).inject(win);
	$(document.body).adopt(overlay, win);
		
	$('mega-login-label').addEvent('click', function(){
		$$('.mega-tab-content').setStyle('display', 'none');
		$$('.mega-tab').removeClass('mega-tab-actived');
		$('mega-login-tab').addClass('mega-tab-actived');
		$('mega-tab-login-main').setStyle('display', 'block');
		$('mega-lightbox-wrapper').setStyle('display', 'block');
		$('overlay').setStyle('display', 'block').tween('opacity', 0.7);
		$('container').setStyle('display', 'block').tween('opacity', 1);
		$('mega-lightbox-wrapper').inject(win);
		$('mega-lightbox-wrap').setStyle('display', 'block');
		
		var containerSize = $('container').getSize();
		var windowView = $(document.body).getSize();
		var windowScroll = $(document.body).getScroll();
		
		$('container').setStyles({
			'left': '50%',
			'top': windowScroll.y + windowView.y/2 + 'px',
			'margin-top': -(containerSize.y/2),
			'margin-left': -(containerSize.x/2)
		}).tween('opacity', 1);
	});
	
	$('mega-register-label').addEvent('click', function(){
		$$('.mega-tab-content').setStyle('display', 'none');
		$$('.mega-tab').removeClass('mega-tab-actived');
		$('mega-signup-tab').addClass('mega-tab-actived');
		$('mega-tab-signup-main').setStyle('display', 'block');
		$('mega-lightbox-wrapper').setStyle('display', 'block');
		
		$('overlay').setStyle('display', 'block').tween('opacity', 0.7);
		$('container').setStyle('display', 'block').tween('opacity', 1);
		$('mega-lightbox-wrapper').inject(win);
		$('mega-lightbox-wrap').setStyle('display', 'block');
		
		var containerSize = $('container').getSize();
		var windowView = $(document.body).getSize();
		var windowScroll = $(document.body).getScroll();
		
		$('container').setStyles({
			'left': '50%',
			'top': windowScroll.y + windowView.y/2 + 'px',
			'margin-top': -(containerSize.y/2),
			'margin-left': -(containerSize.x/2)
		}).tween('opacity', 1);
	});
	
	$('mega-login-tab').addEvent('click', function(){
		if (this.hasClass('mega-tab-actived')) {
			return;
		} else {
			this.addClass('mega-tab-actived');
			$('mega-tab-login-main').setStyle('display', 'block');
		}
		if ($('mega-signup-tab').hasClass('mega-tab-actived')) {
			$('mega-signup-tab').removeClass('mega-tab-actived');
			$('mega-tab-signup-main').setStyle('display', 'none');
		}
	});
	
	$('mega-signup-tab').addEvent('click', function(){
		if (this.hasClass('mega-tab-actived')) {
			return;
		} else {
			this.addClass('mega-tab-actived');
			$('mega-tab-signup-main').setStyle('display', 'block');
		}
		if ($('mega-login-tab').hasClass('mega-tab-actived')) {
			$('mega-login-tab').removeClass('mega-tab-actived');
			$('mega-tab-login-main').setStyle('display', 'none');
		}
	});
	$('mega-closebox').addEvent('click', function(){
		$$('div#overlay', 'div#container').setStyle('display', 'none');
	});
});
