/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

function selectZooItem(d,c,a){jQuery("#"+a+"_id").val(d);jQuery("#"+a+"_name").val(c);SqueezeBox.close?SqueezeBox.close():$("sbox-window").close()}
(function(d){var c=function(){};d.extend(c.prototype,{name:"ZooApplication",options:{url:null,msgSelectItem:"Select Item"},initialize:function(a,b){this.options=d.extend({},this.options,b);var e=this;this.input=a;this.content_panel=a.closest("div.content");this.app=a.find("select.application");this.categories=a.find("div.categories");this.types=a.find("div.types");this.item=a.find("div.item");var c=a.find("select.mode");if(c.length)this.mode=c.val(),c.bind("change",function(){e.mode=c.val();e.setValues()});
else if(this.categories.length)this.mode="categories";else if(this.types.length)this.mode="types";else if(this.item.length)this.mode="item";this.setValues();this.app.bind("change",function(){e.setValues();e.item.length&&(e.item.find("input:hidden").val(""),e.item.find("input:text").val(e.options.msgSelectItem))});a.delegate("select","change",function(){d(this).closest("div.content").css("height","")});d(a.closest("fieldset")).find("input[id$=mode]:hidden, input[id$=type]:hidden, input[id$=category]:hidden, input[id$=item_id]:hidden").remove()},
setValues:function(){var a=this.app.val();this.categories.length&&(a&&this.mode=="categories"?this.categories.removeClass("hidden"):this.categories.addClass("hidden"),this.input.find("select.category").each(function(){var b=d(this);b.hasClass("app-"+a)?(b.removeClass("hidden"),b.attr("name",b.attr("role"))):(b.addClass("hidden"),b.attr("name",""))}));this.types.length&&(a&&this.mode=="types"?this.types.removeClass("hidden"):this.types.addClass("hidden"),this.input.find("select.type").each(function(){var b=
d(this);b.hasClass("app-"+a)?(b.removeClass("hidden"),b.attr("name",b.attr("role"))):(b.addClass("hidden"),b.attr("name",""))}));this.item.length&&(a&&this.mode=="item"?this.item.removeClass("hidden"):this.item.addClass("hidden"),this.item.find("a").attr("href",this.options.url+"&app_id="+a))}});d.fn[c.prototype.name]=function(){var a=arguments,b=a[0]?a[0]:null;return this.each(function(){var e=d(this);if(c.prototype[b]&&e.data(c.prototype.name)&&b!="initialize")e.data(c.prototype.name)[b].apply(e.data(c.prototype.name),
Array.prototype.slice.call(a,1));else if(!b||d.isPlainObject(b)){var f=new c;c.prototype.initialize&&f.initialize.apply(f,d.merge([e],a));e.data(c.prototype.name,f)}else d.error("Method "+b+" does not exist on jQuery."+c.name)})}})(jQuery);
