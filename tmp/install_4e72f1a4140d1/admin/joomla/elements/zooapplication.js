/* Copyright (C) 2007 - 2011 YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

function selectZooItem(c,e,a){jQuery("#"+a+"_id").val(c);jQuery("#"+a+"_name").val(e);SqueezeBox.close?SqueezeBox.close():$("sbox-window").close()}
(function(c){var e=function(){};c.extend(e.prototype,{name:"ZooApplication",options:{url:null,msgSelectItem:"Select Item"},initialize:function(a,b){this.options=c.extend({},this.options,b);var d=this;this.input=a;this.content_panel=a.closest("div.content");this.app=a.find("select.application");this.categories=a.find("div.categories");this.types=a.find("div.types");this.item=a.find("div.item");var f=a.find("select.mode");if(f.length){this.mode=f.val();f.bind("change",function(){d.mode=f.val();d.setValues()})}else if(this.categories.length)this.mode=
"categories";else if(this.types.length)this.mode="types";else if(this.item.length)this.mode="item";this.setValues();this.app.bind("change",function(){d.setValues();if(d.item.length){d.item.find("input:hidden").val("");d.item.find("input:text").val(d.options.msgSelectItem)}});a.delegate("select","change",function(){c(this).closest("div.content").css("height","")});c(a.closest("fieldset")).find("input[id$=mode]:hidden, input[id$=type]:hidden, input[id$=category]:hidden, input[id$=item_id]:hidden").remove()},
setValues:function(){var a=this.app.val();if(this.categories.length){a&&this.mode=="categories"?this.categories.removeClass("hidden"):this.categories.addClass("hidden");this.input.find("select.category").each(function(){var b=c(this);if(b.hasClass("app-"+a)){b.removeClass("hidden");b.attr("name",b.attr("role"))}else{b.addClass("hidden");b.attr("name","")}})}if(this.types.length){a&&this.mode=="types"?this.types.removeClass("hidden"):this.types.addClass("hidden");this.input.find("select.type").each(function(){var b=
c(this);if(b.hasClass("app-"+a)){b.removeClass("hidden");b.attr("name",b.attr("role"))}else{b.addClass("hidden");b.attr("name","")}})}if(this.item.length){a&&this.mode=="item"?this.item.removeClass("hidden"):this.item.addClass("hidden");this.item.find("a").attr("href",this.options.url+"&app_id="+a)}}});c.fn[e.prototype.name]=function(){var a=arguments,b=a[0]?a[0]:null;return this.each(function(){var d=c(this);if(e.prototype[b]&&d.data(e.prototype.name)&&b!="initialize")d.data(e.prototype.name)[b].apply(d.data(e.prototype.name),
Array.prototype.slice.call(a,1));else if(!b||c.isPlainObject(b)){var f=new e;e.prototype.initialize&&f.initialize.apply(f,c.merge([d],a));d.data(e.prototype.name,f)}else c.error("Method "+b+" does not exist on jQuery."+e.name)})}})(jQuery);
