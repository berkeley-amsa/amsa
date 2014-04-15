<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.filesystem.folder' );

function com_install() {
	
	$document			= JFactory::getDocument();
	$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/phocagallery.css');
	
	
	$lang = JFactory::getLanguage();
	$lang->load('com_phocagallery.sys');
	$lang->load('com_phocagallery');

	
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
	background: #6699cc url(\''.JURI::base(true).'/components/com_phocagallery/assets/images/bg-install.png\') 10px center no-repeat;';
	
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
	background: #6699cc url(\''.JURI::base(true).'/components/com_phocagallery/assets/images/bg-upgrade.png\') 10px center no-repeat;';	
	
	
	$folder[0][0]	=	'images' . DS . 'phocagallery' . DS ;
	$folder[0][1]	= 	JPATH_ROOT . DS .  $folder[0][0];
	$folder[1][0]	=	'images' . DS . 'phocagallery' . DS . 'avatars' . DS;
	$folder[1][1]	= 	JPATH_ROOT . DS .  $folder[1][0];
	
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
	
	
	$message .= '<p>&nbsp;</p><p>Please select if you want to Install or Upgrade Phoca Gallery component. Click Install for new Phoca Gallery installation. If you click on Install and some previous Phoca Gallery version is installed on your system, all Phoca Gallery data stored in database will be lost. If you click on Upgrade, previous Phoca Gallery data stored in database will be not removed. Disable Debug System parameter if enabled before installing or upgrading.</p>';
	
	/*
	$message .= '<h2>IMPORTANT INSTALLATION INFORMATION</h2>';
	$message .= '<p>If you get the following message while installation of Phoca Gallery: <b>JInstaller: :Install: Cannot find Joomla XML setup file</b>, then it seems like your server has a limitation in that it can only extract or create archives with less files than its OS\'s file handle limit. Phoca Gallery is large component which includes more files than your server is able to unpack (due to the limitation). In such case, please upload extracted files to your server, to e.g. folder: <b>tmp/phocagallery</b> and use Joomla\'s "Install from Directory" feature. See: <a target="_blank"  href="http://www.phoca.cz/documents/2-phoca-gallery-component/438-cannot-find-joomla-xml-setup-file">Phoca Gallery - Install from Directory guide</a>.</p>';*/
	

	?>
	<div style="padding:20px;border:1px solid #b36b00;background:#fff">
		<a style="text-decoration:underline" href="http://www.phoca.cz/" target="_blank"><?php
			echo  JHTML::_('image', 'administrator/components/com_phocagallery/assets/images/icon-phoca-logo.png', 'Phoca.cz');
		?></a>
		<div style="position:relative;float:right;">
			<?php echo  JHTML::_('image', 'administrator/components/com_phocagallery/assets/images/logo-phoca.png', 'Phoca.cz');?>
		</div>
		<p>&nbsp;</p>
		<?php echo $message; ?>
		<div style="clear:both">&nbsp;</div>
		<div style="text-align:center"><center><table border="0" cellpadding="20" cellspacing="20">
			<tr>
				<td align="center" valign="middle">
					<div id="pg-install"><a style="<?php echo $styleInstall; ?>" href="index.php?option=com_phocagallery&amp;task=phocagalleryinstall.install"><?php echo JText::_('COM_PHOCAGALLERY_INSTALL'); ?></a></div>
				</td>
				
				<td align="center" valign="middle">
					<div id="pg-upgrade"><a style="<?php echo $styleUpgrade; ?>" href="index.php?option=com_phocagallery&amp;task=phocagalleryinstall.upgrade"><?php echo JText::_('COM_PHOCAGALLERY_UPGRADE'); ?></a></div>
				</td>
			</tr>
		</table></center></div>
		
		
		<p>&nbsp;</p><p>&nbsp;</p>
		<p>
		<a href="http://www.phoca.cz/phocagallery/" target="_blank">Phoca Gallery Main Site</a><br />
		<a href="http://www.phoca.cz/documentation/" target="_blank">Phoca Gallery User Manual</a><br />
		<a href="http://www.phoca.cz/forum/" target="_blank">Phoca Gallery Forum</a><br />
		</p>
		
		<p>&nbsp;</p>
		<p><center><a style="text-decoration:underline" href="http://www.phoca.cz/" target="_blank">www.phoca.cz</a></center></p>		
<?php	
}
?>