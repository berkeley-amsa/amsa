<?php defined('_JEXEC') or die('Restricted access'); 

$this->app->html->_('behavior.tooltip');

// add script
$this->app->document->addScript('assets:js/type.js');

?>

<form id="manager-editelements" class="menu-has-level3" action="index.php" method="post" name="adminForm" accept-charset="utf-8">

<?php echo $this->partial('menu'); ?>

<div class="box-bottom">

	<div class="col col-left width-50">
		<fieldset>
			<legend><?php echo $this->type->name; ?></legend>
			<ul id="element-list" class="element-list">
			<?php
				$elements = $this->type->getElements();
				if (empty($elements)) {
					echo '<li></li>';
				} else {
					foreach ($elements as $element) {
						echo '<li class="element hideconfig">'.$this->partial('editelement', array('element' => $element)).'</li>';
					}
				} 
			?>
			</ul>
		</fieldset>
	</div>
	
	<div id="add-element" class="col col-right width-50">
		<fieldset>
			<legend><?php echo JText::_('ELEMENT_LIBRARY'); ?></legend>
			<?php
				if (count($this->elements)) {
					$i = 0;
					$html = array();
					$html[] = '<div class="groups">';
					foreach ($this->elements as $group => $elements) {
						if ($i == round(count($this->elements)/2)) { 
							$html[] = '</div><div class="groups">';
						}
						$html[] = '<div class="elements-group-name">'.JText::_($group).'</div>';
						$html[] = '<ul class="elements">';
						foreach ($elements as $element) {
							$element->loadConfigAssets();
							$metadata = $element->getMetaData();
							$html[] = '<li class="'.$element->getElementType().'" title="'.JText::_('Add element').'">'.JText::_($metadata['name']).'</li>';
						}
						
						$html[] = '</ul>';
						$i++;
					}
					$html[] = '</div>';
					echo implode("\n", $html);
				}			
			?>
		</fieldset>
	</div>

</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="group" value="<?php echo $this->group; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $this->type->id; ?>" />
<?php echo $this->app->html->_('form.token'); ?>

</form>

<script type="text/javascript">
	jQuery(function($) {
		$('#manager-editelements').EditElements({ url: '<?php echo $this->app->link(array('controller' => $this->controller, 'group' => $this->group), false); ?>', msgNoElements: '<?php echo JText::_('NO_ELEMENTS_DEFINED'); ?>', msgDeletelog: '<?php echo JText::_('DELETE_ELEMENT'); ?>' });
	});
</script>

<?php echo ZOO_COPYRIGHT; ?>