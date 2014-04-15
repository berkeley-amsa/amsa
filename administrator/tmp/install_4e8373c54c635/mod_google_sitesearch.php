<?php

#=========================================================================
#                                                                         
#  PROJECT:  GoogleSiteSearch                                              
#            Fairtec (www.fairtec.at)                                     
#            Joomla 1.5.x Module 
#                                                                         
#  COPYRIGHT Manfred Hofbauer FAIRTEC
#  LICENSE   GNU/PL
#                                                                         
#  AUTHORS:     MHO: Manfred Hofbauer (opensource@fairtec.at)
#  DESCRIPTION: Module displays different versions of the google-searchbox including the site-search
#  Version:    ok  1.0   base version
#			   ok  1.1   see changelog.txt
#			   ok  1.2   see changelog.txt
#			   ok  1.3   see changelog.txt
#			   ok  1.4   see changelog.txt
#			   ok  1.5   see changelog.txt
#			   ok  2.0   see changelog.txt
#			   ok  3.0   see changelog.txt
#			   ok  3.0.1 see changelog.txt
#			   ok  3.1   see changelog.txt
#
#  Subversion Tags							     
#                                                                         
#    $Author: manfred.hofbauer $                                      
#    $LastChangedDate: 2011-05-05 21:11:13 +0200 (Do, 05 Mai 2011) $     
#    $LastChangedRevision: 2945 $
#    $LastChangedBy: manfred.hofbauer $     
#    $Id: mod_google_sitesearch.php 2945 2011-05-05 19:11:13Z manfred.hofbauer $
#========================================================================= 
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
require_once(dirname(__FILE__).DS.'helper.php');

	$vSearchBox = new GoogleSiteSearchHelper();
	$vLiveSite = str_replace('http://','',JURI::base());
	$vSearchBox->vLanguage 			= $params->get( 'Language');
	$vSearchBox->vGoogleSite 		= $params->get( 'GoogleSite');
	$vSearchBox->vSearchType		= $params->get( 'SearchType','SITESEARCH');
	$vSearchBox->vSearchDomainLabel	= $params->get( 'SearchDomainLabel',$vLiveSite);
	$vSearchBox->vSearchDomain		= $params->get( 'SearchDomain',$vLiveSite);
	$vSearchBox->vBackgroundColor	= $params->get( 'Background');
	$vSearchBox->vFieldLength		= $params->get( 'FieldLength','25');
	$vSearchBox->vSearchButtonText	= $params->get( 'SearchButtonText');
	$vSearchBox->vPartnerId			= $params->get( 'PartnerID','pub-2261208485956002');
	$vSearchBox->vPageCoding		= $params->get( 'PageEncoding');
	$vSearchBox->vChannelNo			= $params->get( 'ChannelNo');
	$vSearchBox->vButtonPos			= $params->get( 'ButtonPos','BELOW');
	$vSearchBox->vLogoPos			= $params->get( 'LogoPos','ABOVE');
	$vSearchBox->vDefaultDomain		= $params->get( 'DefaultDomain','DOMAIN');
	$vSearchBox->vOpenResult		= $params->get( 'OpenResult');
	$vSearchBox->vVersionText 		= $params->get( 'VersionText');
	$vSearchBox->vBorderSize 		= $params->get( 'BorderSize');
	// replaces the default value correctly
	if ($vSearchBox->vGoogleSite=="default") $vSearchBox->vGoogleSite = "www.google.com";
	$vOutput = $vSearchBox->getDisplayString();
	echo $vOutput;
	$vSearchBox = null;
?>