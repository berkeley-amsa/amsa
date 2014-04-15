<?php
/**
* @package   com_zoo Component
* @file      _submission.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$this->app->document->addScript('libraries:jquery/jquery-ui.custom.min.js');
$this->app->document->addStylesheet('libraries:jquery/jquery-ui.custom.css');
$this->app->document->addScript('libraries:jquery/plugins/timepicker/timepicker.js');
$this->app->document->addStylesheet('libraries:jquery/plugins/timepicker/timepicker.css');
$this->app->document->addStylesheet('media:zoo/assets/css/submission.css');
$this->app->document->addScript('media:zoo/assets/js/submission.js');
$this->app->document->addScript('assets:js/placeholder.js');
$this->app->document->addScript('assets:js/item.js');

if ($this->submission->showTooltip()) {	
	$this->app->html->_('behavior.tooltip');
}

?>

<?php if ($this->form->isBound() && !$this->form->isValid()): ?>
	<?php $msg = count($this->form->getErrors()) > 1 ? JText::_('Oops. There were errors in your submission.') : JText::_('Oops. There was an error in your submission.'); ?>
	<?php $msg .= ' '.JText::_('Please take a look at all highlighted fields, correct your data and try again.'); ?>
	<p class="message"><?php echo $msg; ?></p>
<?php endif; ?>

<form id="item-submission" action="<?php echo $this->app->link(); ?>" method="post" name="submissionForm" accept-charset="utf-8" enctype="multipart/form-data">

	<?php

		echo $fields;

		if ($this->submission->isInTrustedMode()) {
			echo $this->partial('administration');
		}

	?>

	<p class="info"><?php echo JText::_('REQUIRED_INFO'); ?></p>

	<div class="submit">
		<button type="submit" id="submit-button" class="button-green"><?php echo JText::_('Submit Item'); ?></button>
		<?php if (!empty($this->cancel_url)) : ?>
			<a href="<?php echo JRoute::_($this->cancel_url); ?>" id="cancel-button"><?php echo JText::_('Cancel'); ?></a>
		<?php endif; ?>
	</div>

	<input type="hidden" name="option" value="<?php echo $this->app->component->self->name; ?>" />
	<input type="hidden" name="controller" value="submission" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="submission_id" value="<?php echo $this->submission->id; ?>" />
	<input type="hidden" name="type_id" value="<?php echo $this->type->id; ?>" />
	<input type="hidden" name="item_id" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="submission_hash" value="<?php echo $this->hash; ?>" />
	<input type="hidden" name="redirect" value="<?php echo $this->redirectTo; ?>" />

	<?php echo $this->app->html->_('form.token'); ?>

</form>

<script type="text/javascript">
	jQuery(function($) {
		$('#item-submission').EditItem();
		$('#yoo-zoo').Submission({ uri: '<?php echo JURI::root(); ?>' });
	});
</script>