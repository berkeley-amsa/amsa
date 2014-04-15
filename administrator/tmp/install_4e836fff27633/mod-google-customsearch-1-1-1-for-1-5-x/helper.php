<?php
/*
	Joomler!.net Google Custom Search Module Helper - version 1.1.1 for 1.5.x
______________________________________________________________________
	License http://www.gnu.org/copyleft/gpl.html GNU/GPL
	Copyright(c) 2008 Joomler!.net All Rights Reserved.
	Comments & suggestions: http://www.joomler.net/
*/
defined('_JEXEC') or die('Restricted access');


class GWebSearch
{
	function execute($fields)
	{
		$lists = array();
		$lists['custom_css']			= trim( $fieldsfields->get('custom_css') );
		$lists['loading']			= trim( $fields->get('loading', 'Loading...') );
		$lists['controlOrder']			= trim( $fields->get('order', 'custom,local,web,video,blog,news,image,book') );
		$use['customsearchengine']		= intval( $fields->get('usecustomsearch', 0) );
		$expands['customexpand']		= intval( $fields->get('customexpand', 1) );
		$lists['Label']				= trim( $fields->get('label', 'Custom Search') );
		$lists['suffix']				= trim( $fields->get('suffix') );
		$lists['siteRestriction']			= trim( $fields->get('siteRestriction') );
		$use['localsearch']			= intval( $fields->get('uselocalsearch', 0) );
		$expands['localexpand']		= intval( $fields->get('localexpand', 1) );
		$use['videosearch']			= intval( $fields->get('usevideosearch', 1) );
		$expands['videoexpand']		= intval( $fields->get('videoexpand', 1) );
		$use['websearch']			= intval( $fields->get('usewebsearch', 1) );
		$expands['webexpand']		= intval( $fields->get('webexpand', 1) );
		$use['blogsearch']			= intval( $fields->get('useblogsearch', 1) );
		$expands['blogexpand']		= intval( $fields->get('blogexpand', 1) );
		$use['newssearch']			= intval( $fields->get('usenewssearch', 1) );
		$expands['newsexpand']		= intval( $fields->get('newsexpand', 1) );
		$use['imagesearch']			= intval( $fields->get('useimagesearch', 1) );
		$expands['imageexpand']		= intval( $fields->get('imageexpand', 1) );
		$use['booksearch']			= intval( $fields->get('usebooksearch', 1) );
		$expands['bookexpand']		= intval( $fields->get('bookexpand', 1) );
		$lists['centerpoint']			= $fields->get('centerpoint');
		$lists['maxkeyword']			= intval( $fields->get('maxkeyword', 5) );
		$lists['basewords']			= $fields->get('searchwords', 'Joomla');
		$lists['priorityOrder']			= trim( $fields->get('priority', 'content,title,page,default') );
		$lists['langJP']				= (JApplication::getCfg('language') == 'ja-JP');//for Japanese
		$lists['minlength']			= intval( $fields->get('minword', 5) );
		$lists['id']				= GWebSearch::isContent();

		$lists['use'] = $use;
		$lists['expands'] = $expands;

		$Related_Search		= intval( $fields->get('related_search', 0) );
		if($lists['id'] && $Related_Search){
			$lists['searchwords'] = GWebSearch::MakeSearchKeywords($lists);
		} else {
			$lists['searchwords']		= $lists['basewords'];
		}

		return $lists;
	}

	function makeLoading($loading)
	{
		$first = '<span class="gsearch_loading">';
		$last = '</span>';
		if(!empty($loading) && GWebSearch::isUrl($loading)){
			echo $first;
			?>
			<img src="<?php echo $loading; ?>" title="Loading" />
			<?php
			echo $last;
		} elseif(!empty($loading)) {
			echo $first. $loading. $last;
		}
	}

	function makeLocalSearch($uselocalsearch, $centerpoint)
	{
		$result = array();
		if($uselocalsearch && !empty($centerpoint)){
			$result[] = 'var localSearch = new GlocalSearch();';
			$result[] = 'searchControl.addSearcher(localSearch, options);';
			$result[] = 'localSearch.setCenterPoint("'. $centerpoint. '");';
		}
		if(!empty($result)){
			return implode("\n", $result);
		} else {
			return null;
		}
	}

	function SearchControl_Order($lists)
	{
		$use = $lists['use'];
		$expands = $lists['expands'];
		$siteRestriction = $lists['siteRestriction'];
		$centerpoint = $lists['centerpoint'];
		$Label = $lists['Label'];
		$suffix = $lists['suffix'];
		$order = explode(',', $lists['controlOrder']);
		$control = array();

		foreach ($order as $or){
			switch(trim($or)){
				case 'custom':
					if($use['customsearchengine']){
						$control[] = GWebSearch::makeExpandOptions($expands['customexpand']);
						$control[] = GWebSearch::makeCustomSearch($lists);
					}
					break;
				case 'local':
					if($use['localsearch']){
						$control[] = GWebSearch::makeExpandOptions($expands['localexpand']);
						$control[] = GWebSearch::makeLocalSearch($uselocalsearch, $centerpoint);
					}
					break;
				case 'web':
					if($use['websearch']){
						$control[] = GWebSearch::makeExpandOptions($expands['webexpand']);
						$control[] = 'searchControl.addSearcher(new GwebSearch(), options);';
					}
					break;
				case 'video':
					if($use['videosearch']){
						$control[] = GWebSearch::makeExpandOptions($expands['videoexpand']);
						$control[] = 'searchControl.addSearcher(new GvideoSearch(), options);';
					}
					break;
				case 'blog':
					if($use['blogsearch']){
						$control[] = GWebSearch::makeExpandOptions($expands['blogexpand']);
						$control[] = 'searchControl.addSearcher(new GblogSearch(), options);';
					}
					break;
				case 'news':
					if($use['newssearch']){
						$control[] = GWebSearch::makeExpandOptions($expands['newsexpand']);
						$control[] = 'searchControl.addSearcher(new GnewsSearch(), options);';
					}
					break;
				case 'image':
					if($use['imagesearch']){
						$control[] = GWebSearch::makeExpandOptions($expands['imageexpand']);
						$control[] = 'searchControl.addSearcher(new GimageSearch(), options);';
					}
					break;
				case 'book':
					if($use['booksearch']){
						$control[] = GWebSearch::makeExpandOptions($expands['bookexpand']);
						$control[] = 'searchControl.addSearcher(new GbookSearch(), options);';
					}
					break;

			}
		}

		if(empty($control)){
			echo '</script><span style="color:red; font-weight:700">ERROR:control setting!!</span><script>';
		}

		return implode("\n", $control);

	}

	function makeExpandOptions($mode)
	{
		$result = array();
		$result[] = 'options = new GsearcherOptions();';
		switch((int)$mode){
			case 0://EXPAND_MODE_CLOSED
				$result[] = 'options.setExpandMode(GSearchControl.EXPAND_MODE_CLOSED);';
				break;
			case 2://EXPAND_MODE_OPEN
				$result[] = 'options.setExpandMode(GSearchControl.EXPAND_MODE_OPEN);';
				break;
			default://EXPAND_MODE_PARTIAL
				$result[] = 'options.setExpandMode(GSearchControl.EXPAND_MODE_PARTIAL);';
				break;
		}
		return implode("\n", $result);
	}

	function makeCustomSearch($lists)
	{
		if(empty($lists['siteRestriction'])){
			return null;
		}
		$result = array();
		$result[] = 'var customSearch = new GwebSearch();';
		$result[] = 'customSearch.setUserDefinedLabel("'. $lists['Label']. '");';
		if(!empty($lists['suffix'])){
			$result[] = 'customSearch.setUserDefinedClassSuffix("'. $lists['suffix']. '");';
		}
		$result[] = 'customSearch.setSiteRestriction("'. $lists['siteRestriction']. '");';
		$result[] = 'searchControl.addSearcher(customSearch, options);';

		return implode("\n", $result);
	}

	function makeCustomCSS($custom_css)
	{
		if(!empty($custom_css)){
			?>
			<style type="text/css">
			<?php echo $custom_css; ?>
			</style>
			<?php
		}
	}

	function MakeSearchKeywords($lists)
	{
		$priorities = GWebSearch::strTrimArray( explode(',', $lists['priorityOrder']) );
		$keywords = '';
		if(count($priorities)){
			$results = array();
			foreach($priorities as $pr){
				switch($pr){
					case 'content':
						$results[] = GWebSearch::ContentKeywords($lists['id']);
						break;
					case 'title':
						$results[] = GWebSearch::titleKeywords();
						break;
					case 'page':
						$results[] = GWebSearch::pageKeywords();
						break;
					case 'default':
						$results[] = $lists['basewords'];
						break;
				}
			}
			if(count($results)){
				$keywords = implode(' ', $results);
			}
		}
		if(empty($keywords)){
			return $lists['basewords'];
		} else {
			return GWebSearch::OptimizeWords($keywords, $lists);
		}

	}

	function ContentKeywords($id)
	{
		$db	=& JFactory::getDBO();

		$keyword = '';
		if ($id)
		{
			$query = 'SELECT metakey' .
			' FROM #__content' .
			' WHERE id = '.(int) $id;
			$db->setQuery($query);
			$metakey = trim($db->loadResult());
			if ($metakey)
			{
				$keys = explode(',', $metakey);

				if (count($keys))
				{
					$keys = explode( ',', $metakey );

					$likes = GWebSearch::strTrimArray($keys);

					if(count($likes)){
						$keyword = implode(' ', $likes);
					}
				}
			}
		}
		return $keyword;
	}

	function pageKeywords()
	{
		//page metakey
		$docs	=& JFactory::getDocument();
		$keyword = '';
		$metaTags = $docs->_metaTags;
		if(isset($metaTags['standard']['keywords']) && !empty($metaTags['standard']['keywords'])){
			$keyword = $metaTags['standard']['keywords'];
		}
		return str_replace(',', '', $keyword);
	}

	function titleKeywords()
	{
		return  JApplication::getPageTitle();
	}

	function isContent()
	{
		$option			= JRequest::getCmd('option');
		$view				= JRequest::getCmd('view');
		$temp				= JRequest::getString('id');
		$temp				= explode(':', $temp);
		$id				= (int)$temp[0];
		if ($option == 'com_content' && $view == 'article' && $id){
			return $id;
		} else {
			return 0;
		}
	}

	function OptimizeWords($keywords, $lists)
	{
		$keywords = explode(' ', $keywords);
		$results = array();

		if($lists['langJP']){
			$regex = '/^(あ-ん|\d|mosimage)+$/i';
		} else {
			$regex = '/^(\d|mosimage)+$/i';
		}

		if(count($keywords) < $lists['maxkeyword']){
			$keywords = array_merge($keywords, explode(' ', $lists['basewords']));
		}

		foreach( $keywords as $word ){
			if(!preg_match($regex, $word) && JString::strlen($word) >= $lists['minlength']){
				$word = strtolower($word);
				if(!in_array($word, $results)){
					$results[] = $word;
				}
				if(count($results) == $lists['maxkeyword']){
					break;
				}
			}
		}
		if(empty($results)){
			return $lists['basewords'];
		}
		return implode(' ', $results);
	}

	function isUrl($str)
	{
		if (preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $str)) {
		    return true;
		} else {
		    return false;
		}
	}

	function strTrimArray($array)
	{
		if(!is_array($array) || empty($array)){
			return array();
		}
		$results = array();
		foreach($array as $ar){
			$ar = trim($ar);
			if(!empty($ar)){
				$results[] = $ar;
			}
		}
		return $results;
	}

}
?>