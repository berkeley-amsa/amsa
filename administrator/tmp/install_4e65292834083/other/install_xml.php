<?php
/*********** XML PARAMETERS AND VALUES ************/
$xml_item = "component";// component | template
$xml_file = "phocadownload.xml";		
$xml_name = "com_phocadownload";
$xml_creation_date = "01/09/2011";
$xml_author = "Jan Pavelka (www.phoca.cz)";
$xml_author_email = "";
$xml_author_url = "www.phoca.cz";
$xml_copyright = "Jan Pavelka";
$xml_license = "GNU/GPL";
$xml_version = "2.1.0";
$xml_description = "Phoca Download";
$xml_copy_file = 1;//Copy other files in to administration area (only for development), ./front, ./language, ./other


$xml_menu = array (0 => "COM_PHOCADOWNLOAD", 1 => "option=com_phocadownload", 2 => "components/com_phocadownload/assets/images/icon-16-pdl-menu.png", 3 => 'COM_PHOCADOWNLOAD', 4 => 'phocadownloadcp');
$xml_submenu[0] = array (0 => "COM_PHOCADOWNLOAD_CONTROLPANEL", 1 => "option=com_phocadownload", 2 => "components/com_phocadownload/assets/images/icon-16-pdl-control-panel.png", 3 => 'COM_PHOCADOWNLOAD_CONTROLPANEL', 4 => 'phocadownloadcp');

$xml_submenu[1] = array (0 => "COM_PHOCADOWNLOAD_FILES", 1 => "option=com_phocadownload&view=phocadownloadfiles", 2 => "components/com_phocadownload/assets/images/icon-16-pdl-menu-files.png", 3 => 'COM_PHOCADOWNLOAD_FILES', 4 => 'phocadownloadfiles');

$xml_submenu[2] = array (0 => "COM_PHOCADOWNLOAD_CATEGORIES", 1 => "option=com_phocadownload&view=phocadownloadcats", 2 => "components/com_phocadownload/assets/images/icon-16-pdl-menu-category.png", 3 => 'COM_PHOCADOWNLOAD_CATEGORIES', 4 => 'phocadownloadcats');

$xml_submenu[3] = array (0 => "COM_PHOCADOWNLOAD_LICENSES", 1 => "option=com_phocadownload&view=phocadownloadlics", 2 => "components/com_phocadownload/assets/images/icon-16-pdl-menu-lic.png", 3 => 'COM_PHOCADOWNLOAD_LICENSES', 4 => 'phocadownloadlics');

$xml_submenu[4] = array (0 => "COM_PHOCADOWNLOAD_STATISTICS", 1 => "option=com_phocadownload&view=phocadownloadstat", 2 => "components/com_phocadownload/assets/images/icon-16-pdl-menu-stat.png", 3 => 'COM_PHOCADOWNLOAD_STATISTICS', 4 => 'phocadownloadstat');

$xml_submenu[5] = array (0 => "COM_PHOCADOWNLOAD_USERS", 1 => "option=com_phocadownload&view=phocadownloadusers", 2 => "components/com_phocadownload/assets/images/icon-16-pdl-menu-users.png", 3 => 'COM_PHOCADOWNLOAD_USERS', 4 => 'phocadownloadusers');

$xml_submenu[6] = array (0 => "COM_PHOCADOWNLOAD_FILE_RATING", 1 => "option=com_phocadownload&view=phocadownloadrafile", 2 => "components/com_phocadownload/assets/images/icon-16-pdl-menu-vote-file.png", 3 => 'COM_PHOCADOWNLOAD_FILE_RATING', 4 => 'phocadownloadrafile');

$xml_submenu[7] = array (0 => "COM_PHOCADOWNLOAD_TAGS", 1 => "option=com_phocadownload&view=phocadownloadtags", 2 => "components/com_phocadownload/assets/images/icon-16-pdl-menu-tags.png", 3 => 'COM_PHOCADOWNLOAD_TAGS', 4 => 'phocadownloadtags');

$xml_submenu[8] = array (0 => "COM_PHOCADOWNLOAD_LAYOUT", 1 => "option=com_phocadownload&view=phocadownloadlayouts", 2 => "components/com_phocadownload/assets/images/icon-16-pdl-menu-layout.png", 3 => 'COM_PHOCADOWNLOAD_LAYOUT', 4 => 'phocadownloadlayouts');

$xml_submenu[9] = array (0 => "COM_PHOCADOWNLOAD_INFO", 1 => "option=com_phocadownload&view=phocadownloadinfo", 2 => "components/com_phocadownload/assets/images/icon-16-pdl-menu-info.png", 3 => 'COM_PHOCADOWNLOAD_INFO', 4 => 'phocadownloadinfo');

$xml_install_file = 'install.phocadownload.php'; 
$xml_uninstall_file = 'uninstall.phocadownload.php';
/*********** XML PARAMETERS AND VALUES ************/
?>