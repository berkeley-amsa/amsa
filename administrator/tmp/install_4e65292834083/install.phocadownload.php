<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.filesystem.folder' );

function com_install()
{
	$document			= JFactory::getDocument();
	$document->addStyleSheet(JURI::base(true).'/components/com_phocadownload/assets/phocadownload.css');
	
	
	$lang = JFactory::getLanguage();
	$lang->load('com_phocadownload.sys');
	$lang->load('com_phocadownload');
	
	$styleInstall = 
	'display: block;
	background-color: #6699cc;
	margin: 10px;
	padding:10px 25px 10px 45px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	border-radius: 8px;
	border-bottom: 2px solid #215485;
	border-right: 2px solid #215485;
	border-top: 2px solid #8fbbe6;
	border-left: 2px solid #8fbbe6;
	width: 6em;
	text-align:center;
	color: #fff;
	font-weight: bold;
	font-size: x-large;
	text-decoration: none;
	background: #6699cc url(\''.JURI::base(true).'/components/com_phocadownload/assets/images/bg-install.png\') 10px center no-repeat;';
	
	$styleUpgrade = 
	'display: block;
	background-color: #6699cc;
	margin: 10px;
	padding:10px 25px 10px 45px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	border-radius: 8px;
	border-bottom: 2px solid #215485;
	border-right: 2px solid #215485;
	border-top: 2px solid #8fbbe6;
	border-left: 2px solid #8fbbe6;
	width: 6em;
	text-align:center;
	color: #fff;
	font-weight: bold;
	font-size: x-large;
	text-decoration: none;
	background: #6699cc url(\''.JURI::base(true).'/components/com_phocadownload/assets/images/bg-upgrade.png\') 10px center no-repeat;';	

	
	
	$folder[0][0]	=	'phocadownload'  ;
	$folder[0][1]	= 	JPATH_ROOT . DS .  $folder[0][0];
	
	$folder[1][0]	=	'images' . DS . 'phocadownload'  ;
	$folder[1][1]	= 	JPATH_ROOT . DS .  $folder[1][0];
	
	$folder[2][0]	=	'phocadownload' . DS .'userupload';
	$folder[2][1]	= 	JPATH_ROOT . DS .  $folder[2][0];
	
	$message = '';
	$error	 = array();
	foreach ($folder as $key => $value)
	{
		if (!JFolder::exists( $value[1]))
		{
			if (JFolder::create( $value[1], 0755 ))
			{
				$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
				JFile::write($value[1].DS."index.html", $data);
				$message .= '<p><b><span style="color:#009933">Folder</span> ' . $value[0] 
						   .' <span style="color:#009933">created!</span></b></p>';
				$error[] = 0;
			}	 
			else
			{
				$message .= '<p><b><span style="color:#CC0033">Folder</span> ' . $value[0]
						   .' <span style="color:#CC0033">creation failed!</span></b> Please create it manually.</p>';
				$error[] = 1;
			}
		}
		else//Folder exist
		{
			$message .= '<p><b><span style="color:#009933">Folder</span> ' . $value[0] 
						   .' <span style="color:#009933">exists!</span></b></p>';
			$error[] = 0;
		}
	}
	
	$message .= '<p>&nbsp;</p><p>&nbsp;</p><p>Please select if you want to Install or Upgrade Phoca Download component.</p>'
	.'<p><strong>New Install</strong></p>
<p>If this is a <strong>new installation</strong> of the component, please click <strong>Install</strong> to complete installation. If this is not a new install clicking the <strong>Install</strong> button will remove all component data currently stored in the database (if there have been created some data by previous version of the component).</p>
<p><strong>Upgrade</strong></p>
<p>If this is an <strong>upgrade</strong> of the component, please click <strong>Upgrade</strong> to complete installation. Your existing data will <strong>not</strong> be removed.</p>';

?>
	<div style="padding:20px;border:1px solid #b36b00;background:#fff">
		<a style="text-decoration:underline" href="http://www.phoca.cz/" target="_blank"><?php
			echo  JHTML::_('image', 'administrator/components/com_phocadownload/assets/images/icon-phoca-logo.png', 'Phoca.cz');
		?></a>
		<div style="position:relative;float:right;">
			<?php echo  JHTML::_('image', 'administrator/components/com_phocadownload/assets/images/logo-phoca.png', 'Phoca.cz');?>
		</div>
		<p>&nbsp;</p>
		<?php echo $message; ?>
		<div style="clear:both">&nbsp;</div>
		<div style="text-align:center"><center><table border="0" cellpadding="20" cellspacing="20">
			<tr>
				
				<td align="center" valign="middle">
					<div id="pg-install"><a style="<?php echo $styleInstall; ?>" href="index.php?option=com_phocadownload&amp;task=phocadownloadinstall.install"><?php echo JText::_('COM_PHOCADOWNLOAD_INSTALL'); ?></a></div>
				</td>
				
				<td align="center" valign="middle">
					<div id="pg-upgrade"><a style="<?php echo $styleUpgrade; ?>" href="index.php?option=com_phocadownload&amp;task=phocadownloadinstall.upgrade"><?php echo JText::_('COM_PHOCADOWNLOAD_UPGRADE'); ?></a></div>
				</td>
				
			</tr>
		</table></center></div>
		
		<p>&nbsp;</p><p>&nbsp;</p>
		<p>
		<a href="http://www.phoca.cz/phocadownload/" target="_blank">Phoca Download Main Site</a><br />
		<a href="http://www.phoca.cz/documentation/" target="_blank">Phoca Download User Manual</a><br />
		<a href="http://www.phoca.cz/forum/" target="_blank">Phoca Download Forum</a><br />
		</p>
		
		<p>&nbsp;</p>
		<p><center><a style="text-decoration:underline" href="http://www.phoca.cz/" target="_blank">www.phoca.cz</a></center></p>		
<?php	
}
?>