/*======================================================================*\
|| #################################################################### ||
|| # Package - Joomla Template based on YJSimpleGrid Framework          ||
|| # Copyright (C) 2010  Youjoomla LLC. All Rights Reserved.            ||
|| # Authors - Dragan Todorovic and Constantin Boiangiu                 ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla LLC                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/
var YP = {
	start: function(){
		
		YP.table = $('jform_params_yj_menu_sub_title').getParent().getParent().getParent();
		YP.rows = YP.table.getElements('li');		
		YP.accDiv = YP.table.getParent();
		
		YP.holders();
		YP.normalLink();
		YP.module();
		YP.modulePos();
	},
	
	holders: function(){
		var rows = [2,3,4];
		
		if( $('jform_params_yj_group_holder0').checked ) YP.showRows( rows, true );
		if( $('jform_params_yj_group_holder1').checked ) YP.showRows( rows, false );
		
		$('jform_params_yj_group_holder0').addEvent('click', function(){			
			if( this.checked ){
				YP.showRows( rows, true );
			}			
		})
		$('jform_params_yj_group_holder1').addEvent('click', function(){			
			if( this.checked ){
				YP.showRows( rows, false );
			}			
		})
		
	},
	
	normalLink: function(){
		var hideRows = [6, 7, 8];
		
		var el = $('jform_params_yj_item_type0');
		if( el.checked )
			YP.showRows( hideRows, false );
		
		el.addEvent('click', function(){
			if( this.checked ){
				YP.showRows( hideRows, false );
			}
		})
		
	},
	
	module: function(){
		var hideRows = [8];
		var showRows = [6,7];
		
		var el = $('jform_params_yj_item_type1');
		if( el.checked ){
			YP.showRows( hideRows, false );
			YP.showRows( showRows, true );			
		}
		el.addEvent('click', function(){
			if( this.checked ){
				YP.showRows( hideRows, false );
				YP.showRows( showRows, true );
			}
		})
		
	},
	
	modulePos: function(){
		var hideRows = [7];
		var showRows = [6,8];
		
		var el = $('jform_params_yj_item_type2');
		if( el.checked ){
			YP.showRows( hideRows, false );
			YP.showRows( showRows, true );
		}
		
		el.addEvent('click', function(){
			if( this.checked ){
				YP.showRows( hideRows, false );
				YP.showRows( showRows, true );
			}
		})
		
	},
	
	showRows: function( rows, status ){
		rows.each( function( el ){
			YP.rows[el].setStyle('display', status ? '':'none');			
		})	
		
	}
	
}

window.addEvent('load', YP.start);