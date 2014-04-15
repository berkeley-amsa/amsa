<?php

/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	JoomDOC
 * @author      ARTIO s.r.o., info@artio.net, http:://www.artio.net
 * @copyright	Copyright (C) 2011 Artio s.r.o.. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

$this->regInfo = $this->get('RegisteredInfo');

$this->newVer = $this->get('NewestVersion');
$data = JApplicationHelper::parseXMLInstallFile(ARTIO_UPGRADE_MANIFEST);

$this->oldVer = JString::trim(reset(explode(' ', $data['version'])));

$needConfirm = (ARTIO_UPGRADE_DOWNLOAD_ID && (is_null($this->regInfo) || ($this->regInfo->code != 10)));

$downloadPaid = true;

JFactory::getDocument()->addScriptDeclaration(sprintf('var ANeedConfirm = %s;', ($needConfirm ? 'true' : 'false')));
JFactory::getDocument()->addScriptDeclaration(sprintf('var ATxtConfirm = "%s";', JText::_('You will obtain the non-paid version of Component. Are you sure you want to use the automatic upgrade from server?', true)));

?>

<script type="text/javascript">
	 function submitbutton(pressbutton) {
		var form = document.adminForm;
		var sendOk = true;
		if (ANeedConfirm)
			sendOk = confirm(ATxtConfirm);
		if (sendOk) {
			form.fromserver.value = '1';
			form.submit();
		}
	}
</script>


<div class="width-100">
<fieldset class="adminform">
	<legend><?php echo JText::_('Online Upgrade'); ?></legend>
	<table class="adminform">
		<tr>
    		<th colspan="2"><?php echo JText::_('Version Info'); ?></th>
		</tr>
		<tr>
    		<td width="20%"><?php echo JText::_('Installed version').':'; ?></td>
    		<td><?php echo $this->oldVer; ?></td>
		</tr>
		<tr>
    		<td><?php echo JText::_('Newest version').':'; ?></td>
    		<td><?php echo $this->newVer; ?></td>
		</tr>
	</table>
<?php $available = false; ?>
<?php if (ARTIO_UPGRADE_DOWNLOAD_ID) { ?>
    <table class="adminform">
    	<tr>
        	<th colspan="2"><?php echo JText::_('Registration Info'); ?></th>
    	</tr>
    	<?php if (is_null($this->regInfo)) { ?>
	        <tr>
	            <td colspan="2"><?php echo JText::_('Could not retrieve registration information.'); ?></td>
	        </tr>
        <?php } else if ($this->regInfo->code == 90) { ?>
        	<tr>
            	<td colspan="2"><?php echo JText::_('Download ID was not found in our database.'); ?></td>
        	</tr>
        <?php } else {
        	$regTo = $this->regInfo->name;
        	if (! empty($this->regInfo->company))
            	$regTo .= ', ' . $this->regInfo->company;
            $available = true;
        ?>
        <tr>
            <td width="20%"><?php echo JText::_('Registered to'); ?>:</td>
            <td><?php echo $regTo; ?></td>
        </tr>
        <?php
	        if ($this->regInfo->code == 10 || $this->regInfo->code == 30) {
	            $dateText = JText::_('Free upgrades available until');
	        } elseif ($this->regInfo->code == 20) {
	            $dateText = JText::_('Free upgrades expired');
	        }
        ?>
        <tr>
            <td><?php echo $dateText; ?>:</td>
            <td><?php echo $this->regInfo->date; ?></td>
        </tr>
        <?php } ?>
    </table>
    <?php } ?>

<form enctype="multipart/form-data" action="index.php?option=<?php echo ARTIO_UPGRADE_OPTION; ?>&task=upgrade.run" method="post" name="adminForm">
<?php
	$btnText = '';
	if ((strnatcasecmp($this->newVer, $this->oldVer) > 0) ||
		(strnatcasecmp($this->newVer, substr($this->oldVer, 0, strpos($this->oldVer, '-'))) == 0) ||
		($this->newVer == "?.?.?"))
	{
        $btnText = JText::_('Upgrade from ARTIO Server');
	} elseif ($this->newVer == $this->oldVer) {
    	$btnText = JText::_('Reinstall from ARTIO Server');
	}
	
	if ($available)
	{
?>
	    <table class="adminform">
	        <tr>
	            <th><?php echo $btnText; ?></th>
	        </tr>
	        <tr>
	            <td>
	                   <?php
	                   if ($this->newVer == '?.?.?') {
	                       echo JText::_('Server not available.');
	                   } else { ?>
	                       <input class="button" type="button" value="<?php echo $btnText; ?>" onclick="submitbutton()" />
	                   <?php } ?>
	            </td>
	        </tr>
	    </table>
	<?php } ?>
		<table class="adminform">
			<tr>
		    	<th colspan="2"><?php echo JText::_('Upload Package File'); ?></th>
			</tr>
			<tr>
		    	<td width="120">
		        	<label for="install_package"><?php echo JText::_('Package File'); ?>:</label>
		    	</td>
		    	<td>
		        	<input class="input_box" id="install_package" name="install_package" type="file" size="57" />
		        	<input class="button" type="submit" value="<?php echo JText::_('Upload File'); ?> &amp; <?php echo JText::_('Install'); ?>" />
		    	</td>
			</tr>
		</table>

	<input type="hidden" name="fromserver" value="0" />
	<input type="hidden" name="ext" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>
</fieldset>
</div>