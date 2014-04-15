<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$id = 'elements['.$element.']';

?>

<div id="<?php echo $id; ?>">

    <div class="row">
        <?php echo $this->app->html->_('control.selectdirectory', ('root:'.$directory), false, 'elements[' . $element . '][value]', $value); ?>
    </div>

    <div class="row">
        <?php echo $this->app->html->_('control.text', 'elements[' . $element . '][title]', $title, 'maxlength="255" title="'.JText::_('Thumbnail Title').'" placeholder="'.JText::_('Thumbnail Title').'"'); ?>
    </div>

</div>