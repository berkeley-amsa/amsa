/* Copyright (C) 2007 - 2011 YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function(b){b.fn.matchHeight=function(c){var a=0;this.each(function(){a=Math.max(a,b(this).height())});if(c)a=Math.max(a,c);return this.each(function(){b(this).css("min-height",a)})}})(jQuery);
