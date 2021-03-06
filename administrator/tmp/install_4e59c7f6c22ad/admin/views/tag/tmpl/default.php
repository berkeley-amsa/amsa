<?php 
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// add js
$this->app->document->addScript('assets:js/tag.js');

?>

<form id="tags-default" action="index.php" method="post" name="adminForm" accept-charset="utf-8">

<?php echo $this->partial('menu'); ?>

<div class="box-bottom">

	<?php if ($this->is_filtered || count($this->tags) > 0) :?>

	<ul class="filter">
		<li class="filter-left">
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" />
			<button onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
		</li>
	</ul>

	<?php endif;
	
	if(count($this->tags) > 0) : ?>

	<table class="list stripe">
		<thead>
			<tr>
				<th class="checkbox">
					<input type="checkbox" class="check-all" />
				</th>
				<th class="name" colspan="2">
					<?php echo $this->app->html->_('grid.sort', 'Name', 'a.name', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th class="items" colspan="1">
					<?php echo $this->app->html->_('grid.sort', 'Items', 'items', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->tags as $tag) : ?>
			<tr>				
				<td class="checkbox">
					<input type="checkbox" name="cid[]" value="<?php echo $tag->name; ?>" />
				</td>
				<td class="icon"></td>
				<td class="name">
					<span class="edit-tag">
						<a href="#" title="<?php echo JText::_('Edit Tag');?>"><?php echo $tag->name; ?></a>
					</span>
				</td>
				<td class="items">
					<?php echo $tag->items; ?>
				</td>				
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php elseif($this->is_filtered) :

			$title   = JText::_('SEARCH_NO_TAG').'!';
			$message = null;
			echo $this->partial('message', compact('title', 'message'));

		else : 
	
			$title   = JText::_('NO_TAGS_YET').'!';
			$message = JText::_('TAG_MANAGER_DESCRIPTION');
			echo $this->partial('message', compact('title', 'message'));
		
		endif;
	?>

</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo $this->app->html->_('form.token'); ?>

<script type="text/javascript">
	jQuery(function($) {
		$('#tags-default').BrowseTags({ msgSave: '<?php echo JText::_('Save'); ?>', msgCancel: '<?php echo JText::_('Cancel'); ?>' });
	});	
</script>

</form>

<?php echo ZOO_COPYRIGHT; ?>