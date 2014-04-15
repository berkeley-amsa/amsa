<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>

<?php

$app =& JFactory::getApplication();

$document =& JFactory::getDocument();
$document->addStyleSheet( JURI::root().$sfl_basepath."mod_simplefilelister.css" );

//Check to see if jquery is already included
if( $app->get('jquery') === true ) {
	// Pray that correct version is loded
} else {
	// Don't load it in the header as other jQuery namespaces might steal it!
	$document->addScript( JURI::root().$sfl_basepath."tmpl/jquery-1.4.3.min.js" );
	//Add Conflict removal Code
	$document->addScriptDeclaration("jQuery.noConflict();");
	// AW 2011-05-13, Friday the thirteenth... that's the day to test new stuff... :o
	
	$app->set('jquery', true);
}

?>

<script language="javascript" type="text/javascript">
var $jqsfl = jQuery.noConflict();
var curPageURL = window.location.href;
if (curPageURL.indexOf(".php?") > 0) {
	curPageURL += "&";
} else {
	curPageURL += "?";
}
var curBrowseDir = "<?php echo $sfl_dirlocation; ?>";

( function($jqsfl) {
// wait till the DOM is loaded

$jqsfl(document).ready(function() {

$jqsfl('#sfl_ARefresh').live('click', function() {

	var params = '&sflDir=' + curBrowseDir;
	
	$jqsfl('#div_sflcontent').css('text-align', 'center');
	$jqsfl('#div_sflcontent').html('')
		.append('<img style="position: relative; top: 50px;" src="<?php echo JURI::root().$sfl_basepath; ?>images/ajax-loader.gif" />')
		.fadeIn(700, function() {
			//$('#div_sflcontent').append("DONE!");
		});
	
	$jqsfl.ajax({
		type: 'GET',
		url: curPageURL,
		data: 'sflaction=dir' + params,
		cache: false,
		success: function(data) {
			$jqsfl('#div_sflcontent').css('text-align', 'left');
			$jqsfl('#div_sflcontent').html('').append(data);
		}
	});
	
	return false;

});

$jqsfl('.sfl_btnBrowseDir').live('click', function() {

	var dir = this.rel;
	
	var params = '&sflDir=' + dir;
	curBrowseDir = dir;
	
	$jqsfl('#div_sflcontent').css('text-align', 'center');
	$jqsfl('#div_sflcontent').html('')
		.append('<img style="position: relative; top: 50px;" src="<?php echo JURI::root().$sfl_basepath; ?>images/ajax-loader.gif" />')
		.fadeIn(700, function() {
			//$('#div_sflcontent').append("DONE!");
		});
	
	$jqsfl.ajax({
		type: 'GET',
		url: curPageURL,
		data: 'sflaction=dir' + params,
		cache: false,
		success: function(data) {
			$jqsfl('#div_sflcontent').css('text-align', 'left');
			$jqsfl('#div_sflcontent').html('').append(data);
		}
	});
	
	return false;

});

$jqsfl('.sfl_btnDelete').live('click', function() {

	var del = this.rel;
	var params = '&sflDelete=' + del;
	
	var msg = del.split('**');
	
	if (confirm('<?php echo JText::_('DELETE MSG'); ?>\n(' + msg[1] + ')')) {

		$jqsfl('#div_sflcontent').css('text-align', 'center');
		$jqsfl('#div_sflcontent').html('')
			.append('<img style="position: relative; top: 50px;" src="<?php echo JURI::root().$sfl_basepath; ?>images/ajax-loader.gif" />')
			.fadeIn(700, function() {
			  //$('#div_sflcontent').append("DONE!");
			});
		
		$jqsfl.ajax({
			type: 'GET',
			url: curPageURL,
			data: 'sflaction=delete' + params,
			cache: false,
			success: function(data) {
				alert(data);
				//$('#div_sflcontent').html('').append(data);
				
				params = '&sflDir=' + curBrowseDir;
				$jqsfl.ajax({
					type: 'GET',
					url: curPageURL,
					data: 'sflaction=dir' + params,
					cache: false,
					success: function(data) {
						$jqsfl('#div_sflcontent').css('text-align', 'left');
						$jqsfl('#div_sflcontent').html('').append(data);
					}
				});
			}
		});
	
	}
	return false;
  
});


$jqsfl('#sfl_ASortDesc').live('click', function() {
	var params = '&sflSort=desc&sflDir=' + curBrowseDir;
	
	if (document.getElementById("sflSortDesc").className == "") return false;
	
	document.getElementById("sflSortAsc").className = "sfl_shadow";
	document.getElementById("sflSortDesc").className = "";
	
	$jqsfl('#div_sflcontent').css('text-align', 'center');
	
	$jqsfl('#div_sflcontent').html('')
		.append('<img style="position: relative; top: 50px;" src="<?php echo JURI::root().$sfl_basepath; ?>images/ajax-loader.gif" />')
		.fadeIn(700, function() {
			//$('#div_sflcontent').append("DONE!");
		});
	
	$jqsfl.ajax({
		type: 'GET',
		url: curPageURL,
		data: 'sflaction=sort' + params,
		cache: false,
		success: function(data) {
			$jqsfl('#div_sflcontent').css('text-align', 'left');
			$jqsfl('#div_sflcontent').html('').append(data);
		}
	});
	return false;
	
});

$jqsfl('#sfl_ASortAsc').live('click', function() {
	var params = '&sflSort=asc&sflDir=' + curBrowseDir;
	
	if (document.getElementById("sflSortAsc").className == "") return false;
	
	document.getElementById("sflSortAsc").className = "";
	document.getElementById("sflSortDesc").className = "sfl_shadow";
	
	$jqsfl('#div_sflcontent').css('text-align', 'center');
	
	$jqsfl('#div_sflcontent').html('')
		.append('<img style="position: relative; top: 50px;" src="<?php echo JURI::root().$sfl_basepath; ?>images/ajax-loader.gif" />')
		.fadeIn(700, function() {
			//$('#div_sflcontent').append("DONE!");
		});
	
	$jqsfl.ajax({
		type: 'GET',
		url: curPageURL,
		data: 'sflaction=sort' + params,
		cache: false,
		success: function(data) {
			$jqsfl('#div_sflcontent').css('text-align', 'left');
			$jqsfl('#div_sflcontent').html('').append(data);
		}
	});
	return false;
		
});

$jqsfl('#sfl_btnNext').live('click', function() {

	var nextVal = document.getElementById('sflNextVal').value;
	var params = '&sflNext=' + nextVal + '&sflDir=' + curBrowseDir;
	
	$jqsfl('#div_sflcontent').css('text-align', 'center');
	
	$jqsfl('#div_sflcontent').html('')
		.append('<img style="position: relative; top: 50px;" src="<?php echo JURI::root().$sfl_basepath; ?>images/ajax-loader.gif" />')
		.fadeIn(700, function() {
			//$('#div_sflcontent').append("DONE!");
		});
	
	$jqsfl.ajax({
		type: 'GET',
		url: curPageURL,
		data: 'sflaction=next' + params,
		cache: false,
		success: function(data) {
			$jqsfl('#div_sflcontent').css('text-align', 'left');
			$jqsfl('#div_sflcontent').html('').append(data);
		}
	});
	return false;

});

$jqsfl('#sfl_btnPrev').live('click', function() {

	var params = '';

	var prevVal = document.getElementById('sflPrevVal').value;
	if (prevVal+0 > -1) params = '&sflPrevious=' + prevVal + '&sflDir=' + curBrowseDir;

	$jqsfl('#div_sflcontent').css('text-align', 'center');
	$jqsfl('#div_sflcontent').html('')
		.append('<img style="position: relative; top: 50px;" src="<?php echo JURI::root().$sfl_basepath; ?>images/ajax-loader.gif" />')
		.fadeIn(700, function() {
		  //$('#div_sflcontent').append("DONE!");
		});
	
	$jqsfl.ajax({
		type: 'GET',
		url: curPageURL,
		data: 'sflaction=prev' + params,
		cache: false,
		success: function(data) {
			$jqsfl('#div_sflcontent').html('').append(data);
		}
	});
	return false;
  
});

});
} ) ( jQuery );

</script>

<style>
.sfldel:hover {
	border: solid 1px #CCC;
	-moz-box-shadow: 1px 1px 5px #999;
	-webkit-box-shadow: 1px 1px 5px #999;
    box-shadow: 1px 1px 5px #999;
}
.sfldel {
	height: 12px;
	position: relative;
	top: 2px;
}
</style>
<?php

if ($sfl_maxheight > 0) {
	// We're gonna have a fixed height DIV
?>
	
	<div id="div_sflwrapper" style="position: relative; height: <?php echo $sfl_maxheight; ?>px; overflow: auto; background: <?php echo $sfl_bgcolor ?>;">
	
<?php
}
?>

	<div id="div_sflcontent" class="sfl_content" style="background: <?php echo $sfl_bgcolor ?>; left: <?php echo $sfl_boxleft ?>px;">
		<span style="display: none"><a id="sfl_ARefresh" class="sfl_ARefresh" href="javascript:void(0);">Refresh</a></span>
		<?php echo $results; ?>
	</div>
	
<?php

if ($sfl_maxheight > 0) echo "</div>";
?>