<?php
/**
* @package   com_zoo Component
* @file      edit.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div id="<?php echo $this->identifier; ?>">
	<table>
		<?php echo $this->app->html->_('zoo.editrow', JText::_('Rating'), $this->getRating()); ?>
		<?php echo $this->app->html->_('zoo.editrow', JText::_('Votes'), (int) $this->_data->get('votes', 0)); ?>
	</table>

	<?php if ($this->_data->get('votes', 0)) : ?>

		<input name="reset-rating" type="button" class="button" value="<?php echo JText::_('Reset'); ?>"/>

		<script type="text/javascript">
			jQuery('#<?php echo $this->identifier; ?>').EditElementRating({ url: '<?php echo $url; ?>' });
		</script>

	<?php endif; ?>

</div>