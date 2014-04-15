<?php
/**
 * @version $Id: message.thankyou.php 3008 2010-07-11 16:39:45Z mahagr $
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2010 Kunena Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.com
 *
 **/

// Dont allow direct linking
defined ( '_JEXEC' ) or die ();
?>
<div class="kpost-thankyou">
	<?php echo $this->message_thankyou; ?>
</div>
<?php if(!empty($this->thankyou)): ?>
<div class="kmessage-thankyou">
<?php
	echo JText::_('COM_KUNENA_THANKYOU').': ';
	echo implode(', ', $this->thankyou);
	if (count($this->thankyou) > 9) echo '...';
?>
</div>
<?php endif; ?>
