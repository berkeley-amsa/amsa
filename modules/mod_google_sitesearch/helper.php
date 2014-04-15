<?php

#=========================================================================
#                                                                         
#  PROJECT:  GoogleSiteSearch                                              
#            Fairtec (www.fairtec.at)                                     
#            Joomla 1.0.x Module 
#                                                                         
#  COPYRIGHT Manfred Hofbauer FAIRTEC
#  LICENSE   GNU/PL
#                                                                         
#  AUTHORS:     MHO: Manfred Hofbauer (opensource@fairtec.at)
#  DESCRIPTION: helper class for the module
#  Version:    ok  1.0   base version
#			   ok  1.1   changed the code for better compatibility with 1.6, added parameter for tightness 
#  Subversion Tags							     
#                                                                         
#    $Author: Manfred.Hofbauer $                                      
#    $LastChangedDate: 2005-11-23 19:07:04 +0100 (Mi, 23 Nov 2005) $     
#    $LastChangedRevision: 849 $
#    $LastChangedBy: Manfred.Hofbauer $     
#    $Id: mod_google_sitesearch.php 849 2005-11-23 18:07:04Z Manfred.Hofbauer $
#=========================================================================
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
* helper class for the SearchBox
*/
class GoogleSiteSearchHelper{
	var $vLanguage 			= null;
	var $vGoogleSite 		= null;
	var $vSearchType		= null;
	var $vSearchDomainLabel	= null;
	var $vSearchDomain		= null;
	var $vBackgroundColor	= null;
	var $vFieldLength		= null;
	var $vSearchButtonText	= null;
	var $vPartnerId			= null;
	var $vPageCoding		= null;
	var $vChannelNo			= null;
	var $vBoxHtml			= null;
	var $vButtonPos			= null;
	var $vLogoPos			= null;
	var $vDefaultDomain		= null;
	var $vOpenResult		= null;
	var $vVersionText		= null;

   /**
	* Class constructor
	*/
	function SiteSearch(){
	}	
	
	/**
	 * displays the SearchBox
	 */
	function getDisplayString(){
		# get the HTML
		$this->vBoxHtml = $this->getContentHTML();
		# replace the parameter
		$this->replaceParameter();
		# add style
		$style = '<style type="text/css">#nobord { border: '.$this->vBorderSize.' transparent; }</style>';
		$this->vBoxHtml = $style . $this->vBoxHtml;
		# display the box
		return $this->vBoxHtml;
	}

	/**
	 * gets the right HTML-Content for the Classic-Searchbox	 
	 */
	function addModHTML(){
		if ($this->vVersionText=='YES'){
			$this->vBoxHtml =	'<left><table bgcolor="#ffffff" border=0 width=100%><tr id="nobord"></tr><tr id="nobord"><td id="nobord">'.
								$this->vBoxHtml.
								'<center><font size="0" color="#ffffff"><span style="color: #000000; background-color: #ffffff"><a href="http://www.fairtec.at">v1.7.0 by www.fairtec.at</a></span></font></left>'.
								'</td></tr></table></center>';
		}
	}

	/**
	 * gets the right HTML-Content for the Classic-Searchbox	 
	 */
	function getContentHTML(){
		if ($this->vSearchType == 'CLASSIC'){
			if (($this->vButtonPos =='BESIDE') and ($this->vLogoPos=='BESIDE')){  
				return '<!-- Search Google -->
						<center>
						<form method="get" action="http://www.google.com/custom" target="_top">
						<table bgcolor="#ffffff">
						<tr id="nobord"><td id="nobord" nowrap="nowrap" valign="top" align="left" height="32">
						<a href="http://www.google.com/">
						<img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" align="middle"></img></a>
						<input type="text" name="q" size="31" maxlength="255" value=""></input>
						<input type="submit" name="sa" value="Search"></input>
						<input type="hidden" name="client" value="pub-noaccount"></input>
						<input type="hidden" name="forid" value="1"></input>
						<input type="hidden" name="channel" value="pub-nochannel"></input>
						<input type="hidden" name="ie" value="ISO-8859-1"></input>
						<input type="hidden" name="oe" value="ISO-8859-1"></input>
						<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1;"></input>
						<input type="hidden" name="hl" value="de"></input>
						</td></tr></table>
						</form>
						</center>
						<!-- Search Google -->';
				}
			elseif (($this->vButtonPos =='BELOW') and ($this->vLogoPos=='BESIDE')){
				return	'<!-- Search Google -->
						<center>
						<form method="get" action="http://www.google.com/custom" target="_top">
						<table bgcolor="#ffffff">
						<tr id="nobord"><td id="nobord" nowrap="nowrap" valign="top" align="left" height="32">
						<a href="http://www.google.com/">
						<img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" align="middle"></img></a>
						<input type="text" name="q" size="31" maxlength="255" value=""></input>
						</td></tr>
						<tr id="nobord"><td id="nobord" valign="top" align="left">
						<input type="submit" name="sa" value="Search"></input>
						<input type="hidden" name="client" value="pub-noaccount"></input>
						<input type="hidden" name="forid" value="1"></input>
						<input type="hidden" name="channel" value="pub-nochannel"></input>
						<input type="hidden" name="ie" value="ISO-8859-1"></input>
						<input type="hidden" name="oe" value="ISO-8859-1"></input>
						<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1;"></input>
						<input type="hidden" name="hl" value="de"></input>
						</td></tr></table>
						</form>
						</center>
						<!-- Search Google -->';
				}
			elseif (($this->vButtonPos =='BELOW') and ($this->vLogoPos=='ABOVE')){
				return  '<!-- Search Google -->
						<center>
						<form method="get" action="http://www.google.com/custom" target="_top">
						<table bgcolor="#ffffff">
						<tr id="nobord"><td id="nobord" nowrap="nowrap" valign="top" align="left" height="32">
						<a href="http://www.google.com/">
						<img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" align="middle"></img></a>
						<br/>
						<input type="text" name="q" size="31" maxlength="255" value=""></input>
						</td></tr>
						<tr id="nobord"><td id="nobord" valign="top" align="left">
						<input type="submit" name="sa" value="Search"></input>
						<input type="hidden" name="client" value="pub-noaccount"></input>
						<input type="hidden" name="forid" value="1"></input>
						<input type="hidden" name="channel" value="pub-nochannel"></input>
						<input type="hidden" name="ie" value="ISO-8859-1"></input>
						<input type="hidden" name="oe" value="ISO-8859-1"></input>
						<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1;"></input>
						<input type="hidden" name="hl" value="de"></input>
						</td></tr></table>
						</form>
						</center>
						<!-- Search Google -->';
				}
			elseif (($this->vButtonPos =='BESIDE') and ($this->vLogoPos=='ABOVE')){
				return '<!-- Search Google -->
						<center>
						<form method="get" action="http://www.google.com/custom" target="_top">
						<table bgcolor="#ffffff">
						<tr id="nobord"><td id="nobord" nowrap="nowrap" valign="top" align="left" height="32">
						<a href="http://www.google.com/">
						<img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" align="middle"></img></a>
						<br/>
						<input type="text" name="q" size="31" maxlength="255" value=""></input>
						<input type="submit" name="sa" value="Search"></input>
						<input type="hidden" name="client" value="pub-noaccount"></input>
						<input type="hidden" name="forid" value="1"></input>
						<input type="hidden" name="channel" value="pub-nochannel"></input>
						<input type="hidden" name="ie" value="ISO-8859-1"></input>
						<input type="hidden" name="oe" value="ISO-8859-1"></input>
						<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1;"></input>
						<input type="hidden" name="hl" value="de"></input>
						</td></tr></table>
						</form>
						</center>
						<!-- Search Google -->';
				}
		}
		else {
			if (($this->vButtonPos =='BESIDE') and ($this->vLogoPos=='BESIDE')){  
				return '<!-- SiteSearch Google -->
						<form method="get" action="http://www.google.com/custom" target="_top">
						<table border="0" bgcolor="#ffffff">
						<tr id="nobord"><td id="nobord" nowrap="nowrap" valign="top" align="left" height="32">
						<a href="http://www.google.com/">
						<img src="http://www.google.com/logos/Logo_25wht.gif"
						border="0" alt="Google"></img></a>
						</td> 
						<td id="nobord" nowrap="nowrap">
						<input type="hidden" name="domains" value="www.fairtec.at"></input>
						<input type="text" name="q" size="31" maxlength="255" value=""></input>
						<input type="submit" name="sa" value="Search"></input>
						</td></tr>
						<tr id="nobord">
						<td id="nobord">&nbsp;</td>
						<td id="nobord" nowrap="nowrap">
						<table>
						<tr id="nobord">
						<td id="nobord">
						<input type="radio" name="sitesearch" value="" checked="checked"></input>
						<font size="-1" color="#000000">Web</font>
						</td>
						<td id="nobord">
						<input type="radio" name="sitesearch" value="www.fairtec.at"></input>
						<font size="-1" color="#000000">www.fairtec.at</font>
						</td>
						</tr>
						</table>
						<input type="hidden" name="client" value="pub-noaccount"></input>
						<input type="hidden" name="forid" value="1"></input>
						<input type="hidden" name="channel" value="pub-nochannel"></input>
						<input type="hidden" name="ie" value="ISO-8859-1"></input>
						<input type="hidden" name="oe" value="ISO-8859-1"></input>
						<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1;"></input>
						<input type="hidden" name="hl" value="de"></input>
						
						</td></tr></table>
						</form>
						<!-- SiteSearch Google -->';
				}
			elseif (($this->vButtonPos =='BELOW') and ($this->vLogoPos=='BESIDE')){
				return	'<!-- SiteSearch Google -->
						<form method="get" action="http://www.google.com/custom" target="_top">
						<table border="0" bgcolor="#ffffff">
						<tr id="nobord"><td id="nobord" nowrap="nowrap" valign="top" align="left" height="32">
						<a href="http://www.google.com/">
						<img src="http://www.google.com/logos/Logo_25wht.gif"
						border="0" alt="Google"></img></a>
						</td>
						<td nowrap="nowrap">
						<input type="hidden" name="domains" value="www.fairtec.at"></input>
						<input type="text" name="q" size="31" maxlength="255" value=""></input>
						</td></tr>
						<tr id="nobord">
						<td id="nobord">&nbsp;</td>
						<td id="nobord" nowrap="nowrap">
						<table>
						<tr id="nobord">
						<td id="nobord">
						<input type="radio" name="sitesearch" value="" checked="checked"></input>
						<font size="-1" color="#000000">Web</font>
						</td>
						<td id="nobord">
						<input type="radio" name="sitesearch" value="www.fairtec.at"></input>
						<font size="-1" color="#000000">www.fairtec.at</font>
						</td>
						</tr>
						</table>
						<input type="submit" name="sa" value="Search"></input>
						<input type="hidden" name="client" value="pub-noaccount"></input>
						<input type="hidden" name="forid" value="1"></input>
						<input type="hidden" name="channel" value="pub-nochannel"></input>
						<input type="hidden" name="ie" value="ISO-8859-1"></input>
						<input type="hidden" name="oe" value="ISO-8859-1"></input>
						<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1;"></input>
						<input type="hidden" name="hl" value="de"></input>
						
						</td></tr></table>
						</form>
						<!-- SiteSearch Google -->';
			}
			elseif (($this->vButtonPos =='BELOW') and ($this->vLogoPos=='ABOVE')){
				return '<!-- SiteSearch Google -->
						<form method="get" action="http://www.google.com/custom" target="_top">
						<table border="0" bgcolor="#ffffff">
						<tr id="nobord"><td id="nobord" nowrap="nowrap" valign="top" align="left" height="32">
						<a href="http://www.google.com/">
						<img src="http://www.google.com/logos/Logo_25wht.gif"
						border="0" alt="Google"></img></a>
						<br/>
						<input type="hidden" name="domains" value="www.fairtec.at"></input>
						<input type="text" name="q" size="31" maxlength="255" value=""></input>
						</td></tr>
						<tr id="nobord">
						<td id="nobord" nowrap="nowrap">
						<table>
						<tr id="nobord">
						<td id="nobord">
						<input type="radio" name="sitesearch" value="" checked="checked"></input>
						<font size="-1" color="#000000">Web</font>
						</td>
						<td id="nobord">
						<input type="radio" name="sitesearch" value="www.fairtec.at"></input>
						<font size="-1" color="#000000">www.fairtec.at</font>
						</td>
						</tr>
						</table>
						<input type="submit" name="sa" value="Search"></input>
						<input type="hidden" name="client" value="pub-noaccount"></input>
						<input type="hidden" name="forid" value="1"></input>
						<input type="hidden" name="channel" value="pub-nochannel"></input>
						<input type="hidden" name="ie" value="ISO-8859-1"></input>
						<input type="hidden" name="oe" value="ISO-8859-1"></input>
						<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1;"></input>
						<input type="hidden" name="hl" value="de"></input>
						
						</td></tr></table>
						</form>
						<!-- SiteSearch Google -->';
				}
			elseif (($this->vButtonPos =='BESIDE') and ($this->vLogoPos=='ABOVE')){
				return '<!-- SiteSearch Google -->
						<form method="get" action="http://www.google.com/custom" target="_top">
						<table border="0" bgcolor="#ffffff">
						<tr id="nobord"><td id="nobord" nowrap="nowrap" valign="top" align="left" height="32">
						<a href="http://www.google.com/">
						<img src="http://www.google.com/logos/Logo_25wht.gif"
						border="0" alt="Google"></img></a>
						<br/>
						<input type="hidden" name="domains" value="www.fairtec.at"></input>
						<input type="text" name="q" size="31" maxlength="255" value=""></input>
						<input type="submit" name="sa" value="Search"></input>
						</td></tr>
						<tr id="nobord">
						<td id="nobord" nowrap="nowrap">
						<table>
						<tr id="nobord">
						<td id="nobord">
						<input type="radio" name="sitesearch" value="" checked="checked"></input>
						<font size="-1" color="#000000">Web</font>
						</td>
						<td id="nobord">
						<input type="radio" name="sitesearch" value="www.fairtec.at"></input>
						<font size="-1" color="#000000">www.fairtec.at</font>
						</td>
						</tr>
						</table>
						<input type="hidden" name="client" value="pub-noaccount"></input>
						<input type="hidden" name="forid" value="1"></input>
						<input type="hidden" name="channel" value="pub-nochannel"></input>
						<input type="hidden" name="ie" value="ISO-8859-1"></input>
						<input type="hidden" name="oe" value="ISO-8859-1"></input>
						<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1;"></input>
						<input type="hidden" name="hl" value="de"></input>
						
						</td></tr></table>
						</form>
						<!-- SiteSearch Google -->';
			}
		}
	}
	/**
	 * replaces the parameter 
	 */
	function replaceParameter (){
		if (empty($this->vGoogleSite)==FALSE) 		$this->vBoxHtml = str_replace('www.google.com',$this->vGoogleSite,$this->vBoxHtml);
		if ($this->vSearchType == 'SITESEARCH'){
			if (empty($this->vSearchDomain)==FALSE){
				$this->vBoxHtml = str_replace('<input type="hidden" name="domains" value="www.fairtec.at"></input>',
											  '<input type="hidden" name="domains" value="'.$this->vSearchDomain.'"></input>',
											  $this->vBoxHtml);
				$this->vBoxHtml = str_replace('<input type="radio" name="sitesearch" value="www.fairtec.at"></input>',
											  '<input type="radio" name="sitesearch" value="'.$this->vSearchDomain.'"></input>',
											  $this->vBoxHtml);
						}
			if (empty($this->vSearchDomainLabel)==FALSE){
				$this->vBoxHtml = str_replace('www.fairtec.at</font>',
											  $this->vSearchDomainLabel.'</font>',
											  $this->vBoxHtml);
			}
			if ($this->vDefaultDomain == 'DOMAIN'){
				$this->vBoxHtml = str_replace('checked="checked"','',$this->vBoxHtml);
				$this->vBoxHtml = str_replace('<input type="radio" name="sitesearch" value="www.fairtec.at"','<input type="radio" name="sitesearch" value="www.fairtec.at" checked="checked"',$this->vBoxHtml);
			}
		}

		if (empty($this->vLanguage)==FALSE) 		$this->vBoxHtml = str_replace('name="hl" value="de"','name="hl" value="'.$this->vLanguage.'"',$this->vBoxHtml);

		$this->addModHTML();
		if (empty($this->vBackgroundColor)==FALSE) 	{
			if 		($this->vBackgroundColor=='Logo_25wht.gif'){
					 $this->vBoxHtml = str_replace('Logo_25wht.gif',$this->vBackgroundColor,$this->vBoxHtml);
					 $this->vBoxHtml = str_replace('bgcolor="#ffffff"','bgcolor="#ffffff"',$this->vBoxHtml);
					 $this->vBoxHtml = str_replace('color="#ffffff"','color="#ffffff"',$this->vBoxHtml);
					 $this->vBoxHtml = str_replace('background-color: #ffffff','background-color: #ffffff',$this->vBoxHtml);
			}
			elseif 	($this->vBackgroundColor=='Logo_25gry.gif'){
					 $this->vBoxHtml = str_replace('Logo_25wht.gif',$this->vBackgroundColor,$this->vBoxHtml);
					 $this->vBoxHtml = str_replace('bgcolor="#ffffff"','bgcolor="#cccccc"',$this->vBoxHtml);
					 $this->vBoxHtml = str_replace('color: #ffffff','color: #cccccc',$this->vBoxHtml);
					 $this->vBoxHtml = str_replace('background-color: #ffffff','background-color: #cccccc',$this->vBoxHtml);
			}
			elseif 	($this->vBackgroundColor=='Logo_25blk.gif'){
					 $this->vBoxHtml = str_replace('Logo_25wht.gif',$this->vBackgroundColor,$this->vBoxHtml);
					 $this->vBoxHtml = str_replace('bgcolor="#ffffff"','bgcolor="#000000"',$this->vBoxHtml);
					 $this->vBoxHtml = str_replace('<font size="-1" color="#000000"','<font size="-1" color="#ffffff"',$this->vBoxHtml);
					 $this->vBoxHtml = str_replace('color: #ffffff','color: #000000',$this->vBoxHtml);
					 $this->vBoxHtml = str_replace('background-color: #ffffff','background-color: #000000',$this->vBoxHtml);
			}
		}
		if (empty($this->vFieldLength)==FALSE) 		$this->vBoxHtml = str_replace("31",$this->vFieldLength,$this->vBoxHtml);
		if (empty($this->vSearchButtonText)==FALSE)	{
			$this->vSearchButtonText = 'value="'.$this->vSearchButtonText.'"';
			$this->vBoxHtml = str_replace('value="Search"',$this->vSearchButtonText,$this->vBoxHtml);
		} 
		else {
			$arrButtonDef = array("ar"=>"البحث","bg"=>"Търсене","zh-CN"=>"搜索","zh-TW"=>"搜尋","hr"=>"Traži","cs"=>"Vyhledávání","da"=>"Søg","nl"=>"Zoeken","en"=>"Search","fi"=>"Haku","fr"=>"Rechercher","de"=>"Suchen","el"=>"Αναζήτηση","iw"=>"חפש","hu"=>"Keresés","in"=>"Telusuri","it"=>"Cerca","ja"=>"検索","ko"=>"검색","lv"=>"Meklēt","lt"=>"Ieškoti","no"=>"Søk","pl"=>"Wyszukaj","pt"=>"Search","ro"=>"Căutare","ru"=>"Поиск","sr"=>"Претражи","sk"=>"Vyhľadávanie","es"=>"Búsqueda","sv"=>"Sök","th"=>"ค้นหา","tr"=>"Ara","uk"=>"Пошук","vi"=>"Tìm kiếm");
			$this->vSearchButtonText = 'value="'.$arrButtonDef[$this->vLanguage].'"';
			$this->vBoxHtml = str_replace('value="Search"',$this->vSearchButtonText,$this->vBoxHtml);
		}
			
		if (empty($this->vPartnerId)==FALSE){
			 		$this->vBoxHtml = str_replace('pub-noaccount',$this->vPartnerId,$this->vBoxHtml);
			 		if (empty($this->vChannelNo)==FALSE) 	{
			 			$this->vBoxHtml = str_replace('pub-nochannel',$this->vChannelNo,$this->vBoxHtml);
			 		}
					else {	
						$this->vBoxHtml = str_replace('<input type="hidden" name="channel" value="pub-nochannel"></input>','',$this->vBoxHtml);
					}	
		}
		if (empty($this->vPageEncoding)==FALSE) 	$this->vBoxHtml = str_replace('ISO-8859-1',$this->vPageEncoding,$this->vBoxHtml);
		if (empty($this->vOpenResult)==FALSE) 		$this->vBoxHtml = str_replace('_top',$this->vOpenResult,$this->vBoxHtml);
	}
}
	
?>