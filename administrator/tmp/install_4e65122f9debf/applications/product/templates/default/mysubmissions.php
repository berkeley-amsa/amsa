<?php
/**
* @package   com_zoo Component
* @file      mysubmissions.php
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

<div id="yoo-zoo" class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-'.$this->submission->alias; ?>">

	<div class="mysubmissions">

		<h1 class="headline"><?php echo JText::_('My Submissions'); ?></h1>
		
		<p><?php echo sprintf(JText::_('Hi %s, here you can edit your submissions and add new submission.'), $this->user->name); ?></p>
		
		<?php
		
			echo $this->partial('mysubmissions');
		
		?>

	</div>

</div>
