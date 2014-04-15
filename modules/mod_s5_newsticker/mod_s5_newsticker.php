<?php
/**
@version 1.0: mod_S5_newsticker
Author: Shape 5 - Professional Template Community
Copyright 2008
Available for download at www.shape5.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


$pretext		= $params->get( 'pretext', '' );
$tween_time		= $params->get( 'tween_time', '' );
$height		        = $params->get( 'height', '' );
$width   		= $params->get( 'width', '' );

$text1line		= $params->get( 'text1line', '' );
$text2line		= $params->get( 'text2line', '' );
$text3line		= $params->get( 'text3line', '' );
$text4line		= $params->get( 'text4line', '' );
$text5line		= $params->get( 'text5line', '' );
$text6line		= $params->get( 'text6line', '' );
$text7line		= $params->get( 'text7line', '' );
$text8line		= $params->get( 'text8line', '' );
$text9line		= $params->get( 'text9line', '' );
$text10line		= $params->get( 'text10line', '' );
$display_time  	= $params->get( 'display_time', '' );
$s5_jsmoos5		= $params->get( 'xml_s5_jsmoos5', '' );
$s5_mooarrows	= $params->get( 'xml_s5_mooarrows', '' );

$tween_time = $tween_time*1000;
$display_time = $display_time*1000;


$s5_newsticker_tween = $tween_time;
$s5_newsticker_display = $display_time;

$url = JURI::root();

?>

<?php if ($pretext != "") { ?>
<div style="float:left;margin-right:10px;">
<?php echo $pretext ?>
</div>
<?php } ?>


<?php
$br = strtolower($_SERVER['HTTP_USER_AGENT']);
$browser = "other";

if(strrpos($br,"msie 6") > 1) {
$iss_ie6 = "yes";} 
else {$iss_ie6 = "no";}

if(strrpos($br,"msie 7") > 1) {
$iss_ie7 = "yes";} 
else {$iss_ie7 = "no";}

?>
	
	

<?php

echo "<script language=\"javascript\" type=\"text/javascript\" >var s5_newsticker_tween = ".$s5_newsticker_tween.";</script>
	<script language=\"javascript\" type=\"text/javascript\" >var s5_newsticker_display = ".$s5_newsticker_display.";</script>";
	?>

<?php if ($iss_ie7 != "yes") { ?>		
<?php if($s5_jsmoos5 == "moo") { ?>
<script type="text/javascript">//<![CDATA[
document.write('<link href="<?php echo $url ?>/modules/mod_s5_newsticker/s5_newsticker/style.css" rel="stylesheet" type="text/css" media="screen" />');
//]]></script>

		<script type="text/javascript" src="<?php echo $url ?>modules/mod_s5_newsticker/s5_newsticker/mtshcore.js"></script>
		<script type="text/javascript" src="<?php echo $url ?>modules/mod_s5_newsticker/s5_newsticker/icarousel.js"></script>


		<script type="text/javascript">
	
window.addEvent("domready", function() {
	//new Accordion($$(".accordion_toggler"), $$(".accordion_content"));

	dp.SyntaxHighlighter.HighlightAll("usage");

	new iCarousel("s5textrotatecls_otr", {
		idPrevious: "s5textrotate_previous",  
        idNext: "s5textrotate_next", 
		idToggle: "example_1_toggle",
		item: {klass: "s5textrotatecls"},
		animation: {
			type: "fade",
			duration: 500,
			interval: 1000,
			transition: Fx.Transitions.linear,
			rotate: {
				type: "auto",
					
			}
		},
			


	});
});
	
		</script>


<?php } ?>
<?php } ?>

<?php if ($iss_ie7 != "yes") { ?>	
<?php if($s5_jsmoos5 == "moo") { ?>
<?php if($s5_mooarrows == "mooon") { ?>
<div id="s5_textrotatebuttons">
<a href="#" id="s5textrotate_previous"></a> 
<a href="#" id="s5textrotate_next"></a>	
</div>
<?php } ?>	
<?php } ?>	
<?php } ?>
		
<div style="float:left;">
<div id="s5textrotatecls_otr" style="position:relative;display:block;width:<?php echo $width ?>;height:<?php echo $height ?>; overflow:hidden;">
<?php if ($text1line != "") { ?>
<div id="text1" class="s5textrotatecls" style="padding:0px;<?php if($s5_jsmoos5 == "moo") { ?>display:block;left:0;position:absolute;<?php } ?>	<?php if($s5_jsmoos5 == "s5") { ?>display:none;  opacity:.0;<?php } ?> <?php if ($iss_ie6 == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width ?>; overflow:hidden;">
<?php if ($text1line != "") { ?>
<?php echo $text1line ?>
<?php } ?>
</div>
<?php } ?>

<?php if ($text2line != "") { ?>
<div id="text2" class="s5textrotatecls" style="padding:0px;<?php if($s5_jsmoos5 == "moo") { ?>display:block;left:0;position:absolute;<?php } ?>	 <?php if($s5_jsmoos5 == "s5") { ?>display:none;  opacity:.0;<?php } ?> <?php if ($iss_ie6 == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width ?>; overflow:hidden;">
<?php if ($text2line != "") { ?>
<?php echo $text2line ?>
<?php } ?>
</div>
<?php } ?>

<?php if ($text3line != "") { ?>
<div id="text3" class="s5textrotatecls" style="padding:0px; <?php if($s5_jsmoos5 == "moo") { ?>display:block;left:0;position:absolute;<?php } ?>	<?php if($s5_jsmoos5 == "s5") { ?>display:none;  opacity:.0;<?php } ?><?php if ($iss_ie6 == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width ?>; overflow:hidden;">
<?php if ($text3line != "") { ?>
<?php echo $text3line ?>
<?php } ?>
</div>
<?php } ?>

<?php if ($text4line != "") { ?>
<div id="text4" class="s5textrotatecls" style="padding:0px;<?php if($s5_jsmoos5 == "moo") { ?>display:block;left:0;position:absolute;<?php } ?>	<?php if($s5_jsmoos5 == "s5") { ?>display:none;  opacity:.0;<?php } ?> <?php if ($iss_ie6 == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width ?>; overflow:hidden;">
<?php if ($text4line != "") { ?>
<?php echo $text4line ?>
<?php } ?>
</div>
<?php } ?>



<?php if ($text5line != "") { ?>
<div id="text5" class="s5textrotatecls" style="padding:0px; <?php if($s5_jsmoos5 == "moo") { ?>display:block;left:0;position:absolute;<?php } ?>	<?php if($s5_jsmoos5 == "s5") { ?>display:none;  opacity:.0;<?php } ?><?php if ($iss_ie6 == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width ?>; overflow:hidden; ">
<?php if ($text5line != "") { ?>
<?php echo $text5line ?>
<?php } ?>
</div>
<?php } ?>



<?php if ($text6line!= "") { ?>
<div id="text6" class="s5textrotatecls" style="padding:0px;<?php if($s5_jsmoos5 == "moo") { ?>display:block;left:0;position:absolute;<?php } ?>	<?php if($s5_jsmoos5 == "s5") { ?>display:none;  opacity:.0;<?php } ?><?php if ($iss_ie6 == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width ?>; overflow:hidden;">
<?php if ($text6line != "") { ?>
<?php echo $text6line ?>
<?php } ?>
</div>
<?php } ?>



<?php if ($text7line != "") { ?>
<div id="text7" class="s5textrotatecls" style="padding:0px; <?php if($s5_jsmoos5 == "moo") { ?>display:block;left:0;position:absolute;<?php } ?>	<?php if($s5_jsmoos5 == "s5") { ?>display:none;  opacity:.0;<?php } ?><?php if ($iss_ie6 == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width ?>; overflow:hidden;">
<?php if ($text7line != "") { ?>
<?php echo $text7line ?>
<?php } ?>
</div>
<?php } ?>



<?php if ($text8line != "") { ?>
<div id="text8" class="s5textrotatecls" style="padding:0px; <?php if($s5_jsmoos5 == "moo") { ?>display:block;left:0;position:absolute;<?php } ?>	<?php if($s5_jsmoos5 == "s5") { ?>display:none;  opacity:.0;<?php } ?> <?php if ($iss_ie6 == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width ?>; overflow:hidden;">
<?php if ($text8line != "") { ?>
<?php echo $text8line ?>
<?php } ?>
</div>
<?php } ?>


<?php if ($text9line != "") { ?>
<div id="text9" class="s5textrotatecls" style="padding:0px; <?php if($s5_jsmoos5 == "moo") { ?>display:block;left:0;position:absolute;<?php } ?>	<?php if($s5_jsmoos5 == "s5") { ?>display:none;  opacity:.0;<?php } ?><?php if ($iss_ie6 == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width ?>; overflow:hidden;">
<?php if ($text9line != "") { ?>
<?php echo $text9line ?>
<?php } ?>
</div>
<?php } ?>


<?php if ($text10line != "") { ?>
<div id="text10" class="s5textrotatecls" style="padding:0px; <?php if($s5_jsmoos5 == "moo") { ?>display:block;left:0;position:absolute;<?php } ?>	<?php if($s5_jsmoos5 == "s5") { ?>display:none;  opacity:.0;<?php } ?> <?php if ($iss_ie6 == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width ?>; overflow:hidden;">
<?php if ($text10line != "") { ?>
<?php echo $text10line ?>
<?php } ?>
</div>
<?php } ?>
</div>
</div>


<?php if($s5_jsmoos5 == "s5") { ?>
<script language="javascript" type="text/javascript" src="modules/mod_s5_newsticker/s5_newsticker/fader.js"></script>
<script language="javascript" type="text/javascript" src="modules/mod_s5_newsticker/s5_newsticker/timing.js"></script>
<?php } ?>


<?php if ($iss_ie7 == "yes" && $s5_jsmoos5 == "moo") { ?>	
<script language="javascript" type="text/javascript" src="modules/mod_s5_newsticker/s5_newsticker/fader.js"></script>
<script language="javascript" type="text/javascript" src="modules/mod_s5_newsticker/s5_newsticker/timing.js"></script>
<?php } ?>