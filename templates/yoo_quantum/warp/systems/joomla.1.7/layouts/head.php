<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// load mootools
JHtml::_('behavior.mootools');

// get html head data
$head = $this['system']->document->getHeadData();

// remove deprecated meta-data (html5)
unset($head['metaTags']['http-equiv']);
unset($head['metaTags']['standard']['title']);
unset($head['metaTags']['standard']['rights']);
unset($head['metaTags']['standard']['language']);

// load jQuery first, if not loaded before
if (!$this['system']->application->get('jquery')) {
	$this['system']->application->set('jquery', true);
	$head['scripts'] = array_merge(array($this['path']->url('lib:jquery/jquery.js') => array('mime' => 'text/javascript', 'defer' => false, 'async' => false)), $head['scripts']);
}

$this['system']->document->setHeadData($head);

// get styles and scripts
$styles  = $this['asset']->get('css');
$scripts = $this['asset']->get('js');

// compress styles and scripts
if ($compression = $this['config']->get('compression')) {

	$options = array();
	$filters = array('CSSImportResolver', 'CSSRewriteURL', 'CSSCompressor');

	// set options
	if ($compression == 3) {
		$options['Gzip'] = true;
	}

	// set filter
	if ($compression >= 2 && ($this['useragent']->browser() != 'msie' || version_compare($this['useragent']->version(), '8.0', '>='))) {
		$filters[] = 'CSSImageBase64';
	}

	// cache styles and check for remote styles
	if ($styles) {
		$styles = array($this['asset']->cache('template.css', $styles, $filters, $options));
		foreach ($styles[0] as $style) {
			if ($style->getType() == 'File' && !$style->getPath()) {
				$styles[] = $style;
			}
		}
	}

	// cache scripts and check for remote scripts
	if ($scripts) {
		$scripts = array($this['asset']->cache('template.js', $scripts, array('JSCompressor'), $options));
		foreach ($scripts[0] as $script) {
			if ($script->getType() == 'File' && !$script->getPath()) {
				$scripts[] = $script;
			}
		}
	}

	// compress joomla styles and scripts
	$head = $this['system']->document->getHeadData();
	$data = array('styleSheets' => array(), 'scripts' => array());
	
	foreach ($head['styleSheets'] as $style => $meta) {

		if (preg_match('/\.css$/i', $style)) {
			$asset = $this['asset']->createFile($style);
			if ($asset->getPath()) {
				$style = $this['asset']->cache(basename($style), $asset, array('CSSImportResolver', 'CSSRewriteURL', 'CSSCompressor'), $options)->getUrl();
			}
		}

		$data['styleSheets'][$style] = $meta;
	}

	foreach ($head['scripts'] as $script => $meta) {

		if (preg_match('/\.js$/i', $script)) {
			$asset = $this['asset']->createFile($script);
			if ($asset->getPath()) {
				$script = $this['asset']->cache(basename($script), $asset, array('JSCompressor'), $options)->getUrl();
			}
		}
	
		$data['scripts'][$script] = $meta;
	}
	
	$this['system']->document->setHeadData(array_merge($head, $data));
}

// add styles
if ($styles) {
	foreach ($styles as $style) {
		if ($url = $style->getUrl()) {
			$this['system']->document->addStyleSheet($url);
		} else {
			$this['system']->document->addStyleDeclaration($style->getContent());
		}
	}
}

// add scripts
if ($scripts) {
	foreach ($scripts as $script) {
		if ($url = $script->getUrl()) {
			$this['system']->document->addScript($url);
		} else {
			$this['system']->document->addScriptDeclaration($script->getContent());
		}
	}
}

?>
<meta charset="<?php echo $this['system']->document->getCharset(); ?>" />
<jdoc:include type="head" />
<?php $this->output('head'); ?>