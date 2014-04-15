<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<style type="text/css">
<?php if ($params->get('ossystem') == 'lin') {
readfile(JURI::root().'modules/mod_pctechfilebasereader/css/mod_pctechfilebasereader.css'); 
} else {
readfile(JURI::root().'modules/mod_pctechfilebasereader/css/mod_pctechfilebasereaderwin.css'); 
}

?>


</style>

<div class="pctechfilebasereader<?php echo $params->get('moduleclass_sfx'); ?>">
<ul class="pfrfilelist">
<?php foreach($filelist as $file):?>
    <?php if ($params->get('linkfiles') == 'yes'): ?>
    <li class="<?php echo $file->class; ?>"><a target="<?php echo $params->get('readertarget'); ?>" href="<?php echo $params->get('url') . '/' . $file->filepath; ?>"><?php echo $file->filename; ?></a></li>
    <?php else: ?>
    <li class="<?php echo $file->class; ?>"><?php echo $file->filename; ?></li>
    <?php endif; ?>
<?php endforeach; ?>
</ul>
<div class="pfrcopyright">
<a title="Pctech Filebase Reader J1.6 &copy; 2011 by pcte.ch Webservices" href="http://pcte.ch"><strong>Pctech Filebase Reader &copy; 2011</a></strong>
<?php
echo 'Joomla base URI is ' . JURI::base() . "\n";
echo 'Joomla base URI (path only) is ' . JURI::base( true ) . "\n";
$basepath = JPATH_ROOT.DS;
$basepath1 =  $file->filepath;
echo 'base ';
echo "$basepath1";
?>
</div>