<?php
/*
	Joomler!.net Google Custom Search Module Helper - version 1.1.1 for 1.5.x
______________________________________________________________________
	License http://www.gnu.org/copyleft/gpl.html GNU/GPL
	Copyright(c) 2008 Joomler!.net All Rights Reserved.
	Comments & suggestions: http://www.joomler.net/
*/

defined('_JEXEC') or die('Restricted access'); ?>

<style type="text/css">@import url("<?php echo trim( $params->get('gwebsearch_css', 'http://www.google.com/uds/css/gsearch.css') );?>");</style>
<?php GWebSearch::makeCustomCSS($Jm_gws['custom_css']); ?>
<script type="text/javascript" src="http://www.google.com/jsapi?key=<?php echo trim( $params->get('APIKey') ); ?>"></script>
<script language="Javascript" type="text/javascript">
//<![CDATA[
google.load("search", "1");
function OnLoad<?php echo $module->id;?>() {
  var searchControl = new GSearchControl();
  var drawOptions = new GdrawOptions();
  var options = new GsearcherOptions();
<?php echo GWebSearch::SearchControl_Order($Jm_gws); ?>
  drawOptions.setDrawMode(GSearchControl.<?php echo $params->get('drawmode', 'DRAW_MODE_TABBED');?>);
  searchControl.draw(document.getElementById("searchcontrol<?php echo $module->id;?>"), drawOptions);
  searchControl.setLinkTarget(<?php echo $params->get('target', 'google.search.Search.LINK_TARGET_BLANK');?>);
  searchControl.execute("<?php echo $Jm_gws['searchwords']; ?>");
}
google.setOnLoadCallback(OnLoad<?php echo $module->id;?>);
//]]>
</script>
<div id="searchcontrol<?php echo $module->id;?>"><?php GWebSearch::makeLoading($Jm_gws['loading']); ?></div>