<?php
/**
* @package   com_zoo Component
* @file      _alphaindex.php
* @version   2.4.9 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div class="alpha-index <?php if ($this->params->get('template.alignment') == 'center') echo 'alpha-index-center'; ?>">			
	<?php echo $this->alpha_index->render(); ?>
</div>