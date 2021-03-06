<?php 
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

defined('_JEXEC') or die('Restricted access');

$this->app->html->_('behavior.tooltip');

// filter output
JFilterOutput::objectHTMLSafe($this->application, ENT_QUOTES, array('params')); 

?>

<form id="frontpage-default" action="index.php" method="post" name="adminForm" accept-charset="utf-8">

<?php echo $this->partial('menu'); ?>

<div class="box-bottom">

	<div class="col col-left width-60">
	
		<fieldset class="creation-form">
		<legend><?php echo JText::_('Details'); ?></legend>
		<div class="element element-description">
			<strong><?php echo JText::_('Description'); ?></strong>
			<div>
				<?php
					// parameters : areaname, content, width, height, cols, rows, show xtd buttons
					echo $this->app->system->editor->display('description', $this->application->description, null, null, '60', '20', array('pagebreak', 'readmore', 'article')) ;
				?>
			</div>
		</div>
		</fieldset>

	</div>

	<div class="col col-right width-40">

		<div id="parameter-accordion">
			<h3 class="toggler"><?php echo JText::_('Content'); ?></h3>
			<div class="content">
				<?php echo $this->application->getParamsForm()->setValues($this->params->get('content.'))->render('params[content]', 'application-content'); ?>
			</div>
			<h3 class="toggler"><?php echo JText::_('Config'); ?></h3>
			<div class="content">
				<?php echo $this->application->getParamsForm()->setValues($this->params->get('config.'))->render('params[config]', 'category-config'); ?>
			</div>
			<h3 class="toggler"><?php echo JText::_('Template'); ?></h3>
			<div class="content">
				<?php
					if ($template = $this->application->getTemplate()) {
						echo $template->getParamsForm(true)->setValues($this->params->get('template.'))->render('params[template]', 'category');
					} else {
						echo '<em>'.JText::_('Please select a Template').'</em>';
					}
				?>
			</div>
		</div>

	</div>

</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<?php echo $this->app->html->_('form.token'); ?>

</form>

<?php echo ZOO_COPYRIGHT; ?>