<?php 
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$this->app->html->_('behavior.tooltip');

// add script
$this->app->document->addScript('assets:js/type.js');

?>

<form id="assign-elements" class="menu-has-level3" action="index.php" method="post" name="adminForm" accept-charset="utf-8">

<?php echo $this->partial('menu'); ?>

<div class="box-bottom">

	<div class="col col-left width-50">

		<fieldset>
		<legend><?php echo JText::_('Positions'); ?></legend>

			<?php	
				$elements  = $this->type->getSubmittableElements();
				$count	   = count($elements);
				$positions = $this->positions['positions'];
				if (count($positions)) {
					foreach ($positions as $position => $name) {
						echo '<div class="position">'.$name.'</div>';
						echo '<ul class="element-list" role="'.$position.'">';
						
						if ($this->config && isset($this->config[$position])) {
							$i = 0;
							foreach ($this->config[$position] as $data) {
								if (isset($elements[$data['element']])) {
									$element = $elements[$data['element']];
									unset($elements[$data['element']]);
									
									// render partial
									echo $this->partial('assignsubmittableelement', array('element' => $element, 'data' => $data, 'position' => $position, 'index' => $i++));
								}
							}
						}

						echo '</ul>';
					}
				} else {
					echo '<i>'.JText::_('No positions defined').'</i>';
				}
			?>

		</fieldset>

	</div>

	<div class="col col-right width-50">

		<fieldset>
		<legend><?php echo JText::_('Submittable Elements'); ?></legend>
		
		<?php
			if ($count <= 0) {
				echo '<i>'.JText::_('There are no elements to assign').'</i>';
			}
			
			if ($elements !== false) {
				$i = 0;
                echo '<ul class="element-list unassigned" role="unassigned">';
				foreach ($elements as $element) {

					// render partial
					echo $this->partial('assignsubmittableelement', array('element' => $element, 'data' => array(), 'position' => 'unassigned', 'index' => $i++));
				}
				echo '</ul>';
			} 
		?>
		
		</fieldset>

	</div>

</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="group" value="<?php echo $this->group; ?>" />
<input type="hidden" name="type" value="<?php echo $this->type->id; ?>" />
<input type="hidden" name="template" value="<?php echo $this->template; ?>" />
<input type="hidden" name="layout" value="<?php echo $this->layout; ?>" />
<?php echo $this->app->html->_('form.token'); ?>

</form>

<script type="text/javascript">
	jQuery(function($) {
		$('#assign-elements').AssignSubmission();
	});
</script>


<?php echo ZOO_COPYRIGHT; ?>