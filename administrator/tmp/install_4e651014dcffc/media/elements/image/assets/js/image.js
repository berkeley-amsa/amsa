/* Copyright (C) 2007 - 2011 YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function(e){var b=function(){};e.extend(b.prototype,{name:"ImageSubmission",options:{uri:""},initialize:function(c,d){this.options=e.extend({},this.options,d);var a=this;this.element=c;this.advanced=c.find("select.image");this.select_image=this.advanced.length?this.advanced:c.find("input.image");c.find("span.image-cancel").bind("click",function(){a.select_image.val("");a.sanatize()});this.advanced.length&&this.select_image.bind("change",function(){c.find("img").attr("src",a.options.uri+a.select_image.val());
a.sanatize()});a.sanatize()},sanatize:function(){if(this.select_image.val()){this.element.find("div.image-select").addClass("hidden");this.element.find("div.image-preview").removeClass("hidden")}else{this.element.find("div.image-select").removeClass("hidden");this.element.find("div.image-preview").addClass("hidden")}}});e.fn[b.prototype.name]=function(){var c=arguments,d=c[0]?c[0]:null;return this.each(function(){var a=e(this);if(b.prototype[d]&&a.data(b.prototype.name)&&d!="initialize")a.data(b.prototype.name)[d].apply(a.data(b.prototype.name),
Array.prototype.slice.call(c,1));else if(!d||e.isPlainObject(d)){var f=new b;b.prototype.initialize&&f.initialize.apply(f,e.merge([a],c));a.data(b.prototype.name,f)}else e.error("Method "+d+" does not exist on jQuery."+b.name)})}})(jQuery);