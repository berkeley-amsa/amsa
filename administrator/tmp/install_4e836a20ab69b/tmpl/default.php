<?php // no direct access
/**
* @version	$Id: default.php 2009-05-30  $
* @package	Google Web Elements - Custom Search
* @copyright	Copyright (C) 2009 Open4G Media. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
*/
defined('_JEXEC') or die('Restricted access'); ?>
<!-- Google Custom Search Element Code -->
<?php if ($params->get('googlewecustomsearch_customsearchid')) { ?>

<div id="cse" style="width:<?php echo $gwe_customsearch_w; ?>; height:<?php echo $gwe_customsearch_h; ?>;"><?php echo $params->get('googlewecustomsearch_loadingtext'); ?></div>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript">
  google.load('search', '1');
  google.setOnLoadCallback(function(){
    new google.search.CustomSearchControl('<?php echo $params->get('googlewecustomsearch_customsearchid'); ?>').draw('cse');
  }, true);
</script>

<?php } else { ?>

<?php if ($params->get('googlewecustomsearch_adsensepubid')) { ?>
<div id="cse" style="width:<?php echo $gwe_customsearch_w; ?>; height:<?php echo $gwe_customsearch_h; ?>;"><?php echo $params->get('googlewecustomsearch_loadingtext'); ?></div>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript">
  google.load('search', '1');
  google.setOnLoadCallback(function(){
    var cse = new google.search.CustomSearchControl();
    cse.enableAds('<?php echo $params->get('googlewecustomsearch_adsensepubid'); ?>');
    cse.draw('cse');
  }, true);
</script>
<?php } else { ?>
<div id="cse" style="width:<?php echo $gwe_customsearch_w; ?>; height:<?php echo $gwe_customsearch_h; ?>;"><?php echo $params->get('googlewecustomsearch_loadingtext'); ?></div>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript">
  google.load('search', '1');
  google.setOnLoadCallback(function(){
    new google.search.CustomSearchControl().draw('cse');
  }, true);
</script>
<?php }
}?>
<!-- Google Custom Search Element -->