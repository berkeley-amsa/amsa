<?php
/**
* @package   com_zoo Component
* @file      alphaindex.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// include assets css/js
if (strtolower(substr($GLOBALS[($this->app->joomla->isVersion('1.5') ? 'mainframe' : 'app')]->getTemplate(), 0, 3)) != 'yoo') {
	$this->app->document->addStylesheet('media:zoo/assets/css/reset.css');
}
$this->app->document->addStylesheet($this->template->resource.'assets/css/zoo.css');

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>

<div id="yoo-zoo" class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-alphaindex'; ?>">

	<?php if ($this->params->get('template.show_alpha_index')) : ?>
		<?php echo $this->partial('alphaindex'); ?>
	<?php endif; ?>

	<?php if ($this->params->get('template.show_title')) : ?>
	<div class="details <?php echo 'alignment-'.$this->params->get('template.alignment'); ?>">
		<h1 class="title"><?php echo JText::_('Movies starting with').' '.strtoupper($this->alpha_char); ?></h1>
	</div>
	<?php endif; ?>

	<?php

		// render categories
		$has_categories = false;
		if (!empty($this->selected_categories)) {
			$has_categories = true;
			echo $this->partial('categories');
		}
		
	?>
	
	<?php

		// render items
		if (count($this->items)) {
			echo $this->partial('items', compact('has_categories'));
		}
		
	?>

</div>