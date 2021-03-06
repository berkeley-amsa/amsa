<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

function ZooBuildRoute(&$query) {

	$app = App::getInstance('zoo');

	// init vars
	$segments = array();

	// frontpage
	$task = 'frontpage';

		if (@$query['task'] == $task || @$query['view'] == $task) {
			$segments[] = $task;
			unset($query['task']);
			unset($query['view']);

			// pagination
			if (isset($query['page'])) {
				$segments[] = $query['page'];
				unset($query['page']);
			}
		}

	// category
	$task = 'category';

		if ((@$query['task'] == $task || @$query['view'] == $task) && isset($query['category_id'])) {
			$segments[] = $task;
			if ($query['category_id']) {
				$segments[] = $app->category->translateIDToAlias((int) $query['category_id']);
			}
			unset($query['task']);
			unset($query['view']);
			unset($query['category_id']);

			// pagination
			if (isset($query['page'])) {
				$segments[] = $query['page'];
				unset($query['page']);
			}
		}

	// alpha index
	$task = 'alphaindex';

		if ((@$query['task'] == $task || @$query['view'] == $task) && isset($query['alpha_char']) && isset($query['app_id'])) {
			$segments[] = $task;
			$segments[] = $app->application->translateIDToAlias((int) $query['app_id']);
			$segments[] = $query['alpha_char'];
			unset($query['task']);
			unset($query['view']);
			unset($query['alpha_char']);
			unset($query['app_id']);

			// pagination
			if (isset($query['page'])) {
				$segments[] = $query['page'];
				unset($query['page']);
			}
		}

	// tag
	$task = 'tag';

		if ((@$query['task'] == $task || @$query['view'] == $task) && isset($query['tag']) && isset($query['app_id'])) {
			$segments[] = $task;
			$segments[] = $app->application->translateIDToAlias((int) $query['app_id']);
			$segments[] = $query['tag'];
			unset($query['task']);
			unset($query['view']);
			unset($query['tag']);
			unset($query['app_id']);

			// pagination
			if (isset($query['page'])) {
				$segments[] = $query['page'];
				unset($query['page']);
			}
		}

	// item
	$task = 'item';

		if ((@$query['task'] == $task || @$query['view'] == $task) && isset($query['item_id'])) {
			$segments[] = $task;
			$segments[] = $app->item->translateIDToAlias((int) $query['item_id']);
			unset($query['task']);
			unset($query['view']);
			unset($query['item_id']);

		}

	// feed
	$task = 'feed';

		if ((@$query['task'] == $task || @$query['view'] == $task) && isset($query['type']) && isset($query['app_id']) && isset($query['category_id'])) {
			$segments[] = $task;
			$segments[] = $query['type'];
			$segments[] = $app->application->translateIDToAlias((int) $query['app_id']);
			if ($query['category_id']) {
				$segments[] = $app->category->translateIDToAlias((int) $query['category_id']);
			}
			unset($query['task']);
			unset($query['view']);
			unset($query['type']);
			unset($query['app_id']);
			unset($query['category_id']);
		}

	// submission
	$task = 'submission';
    $layout = 'submission';

		if (((@$query['task'] == $task || @$query['view'] == $task) && @$query['layout'] == $layout)) {
			$segments[] = $task;
            $segments[] = $layout;
			$segments[] = $app->submission->translateIDToAlias((int) $query['submission_id']);
            $segments[] = $query['type_id'];
            $segments[] = $query['submission_hash'];
            $segments[] = $app->item->translateIDToAlias((int) @$query['item_id']);
			unset($query['task']);
			unset($query['view']);
			unset($query['layout']);
			unset($query['submission_id']);
            unset($query['type_id']);
            unset($query['submission_hash']);
            unset($query['item_id']);
		}

	// submission mysubmissions
	$task = 'submission';
    $layout = 'mysubmissions';

		if (((@$query['task'] == $task || @$query['view'] == $task) && @$query['layout'] == $layout)) {
			$segments[] = $task;
            $segments[] = $layout;
			$segments[] = $app->submission->translateIDToAlias((int) $query['submission_id']);
			unset($query['task']);
			unset($query['view']);
			unset($query['layout']);
			unset($query['submission_id']);
		}

	return $segments;
}

function ZooParseRoute($segments) {

	$app = App::getInstance('zoo');

	// init vars
	$vars  = array();
	$count = count($segments);

	// fix segments (see JRouter::_decodeSegments)
	foreach (array_keys($segments) as $key) {
		$segments[$key] = str_replace(':', '-', $segments[$key]);
	}

	// frontpage (with optional pagination)
	$task = 'frontpage';

		if ($count == 1 && $segments[0] == $task) {
			$vars['task'] = $task;
		}

		if ($count == 2 && $segments[0] == $task) {
			$vars['task'] = $task;
			$vars['page'] = (int) $segments[1];
		}

	// category (with optional pagination)
	$task = 'category';

		if ($count == 2 && $segments[0] == $task) {
			$vars['task']        = $task;
			$vars['category_id'] = (int) $app->category->translateAliasToID($segments[1]);
		}

		if ($count == 3 && $segments[0] == $task) {
			$vars['task']        = $task;
			$vars['category_id'] = (int) $app->category->translateAliasToID($segments[1]);
			$vars['page']        = (int) $segments[2];
		}

	// alpha index (with optional pagination)
	$task = 'alphaindex';

		if ($count == 3 && $segments[0] == $task) {
			$vars['task']       = $task;
			$vars['app_id']		= (int) $app->application->translateAliasToID($segments[1]);
			$vars['alpha_char'] = (string) $segments[2];
		}

		if ($count == 4 && $segments[0] == $task) {
			$vars['task']       = $task;
			$vars['app_id']		= (int) $app->application->translateAliasToID($segments[1]);
			$vars['alpha_char'] = (string) $segments[2];
			$vars['page']       = (int) $segments[3];
		}

	// tag (with optional pagination)
	$task = 'tag';

		if ($count == 3 && $segments[0] == $task) {
			$vars['task']   = $task;
			$vars['app_id']	= (int) $app->application->translateAliasToID($segments[1]);
			$vars['tag']    = (string) $segments[2];
		}

		if ($count == 4 && $segments[0] == $task) {
			$vars['task']   = $task;
			$vars['app_id']	= (int) $app->application->translateAliasToID($segments[1]);
			$vars['tag']    = (string) $segments[2];
			$vars['page']   = (int) $segments[3];
		}

	// item
	$task = 'item';

		if ($count == 2 && $segments[0] == $task) {
			$vars['task']    = $task;
			$vars['item_id'] = (int) $app->item->translateAliasToID($segments[1]);
		}

	// feed
	$task = 'feed';

		if ($count == 3 && $segments[0] == $task) {
			$vars['task'] = $task;
			$vars['type'] = (string) $segments[1];
			$vars['app_id'] = (int) $app->application->translateAliasToID($segments[2]);
		}

		if ($count == 4 && $segments[0] == $task) {
			$vars['task']        = $task;
			$vars['type']        = (string) $segments[1];
			$vars['app_id']		 = (int) $app->application->translateAliasToID($segments[2]);
			$vars['category_id'] = (int) $app->category->translateAliasToID($segments[3]);
		}

	// submission
	$task = 'submission';
    $layout = 'submission';

		if ($count == 2 && $segments[0] == $task && $segments[1] == $layout) {
			$vars['task']   = $task;
			$vars['layout'] = (string) $segments[1];
		}

		if ($count == 5 && $segments[0] == $task && $segments[1] == $layout) {
			$vars['task']            = $task;
			$vars['layout']          = (string) $segments[1];
			$vars['submission_id']   = (int) $app->submission->translateAliasToID($segments[2]);
            $vars['type_id']         = (string) $segments[3];
            $vars['submission_hash'] = (string) $segments[4];
		}

		if ($count == 6 && $segments[0] == $task && $segments[1] == $layout) {
			$vars['task']            = $task;
			$vars['layout']          = (string) $segments[1];
			$vars['submission_id']   = (int) $app->submission->translateAliasToID($segments[2]);
            $vars['type_id']         = (string) $segments[3];
            $vars['submission_hash'] = (string) $segments[4];
            $vars['item_id']         = (int) $app->item->translateAliasToID($segments[5]);
		}

	// submission mysubmissions
	$task = 'submission';
    $layout = 'mysubmissions';

		if ($count == 2 && $segments[0] == $task && $segments[1] == $layout) {
			$vars['task']   = $task;
			$vars['layout'] = (string) $segments[1];
		}

		if ($count == 3 && $segments[0] == $task && $segments[1] == $layout) {
			$vars['task']          = $task;
			$vars['layout']        = (string) $segments[1];
			$vars['submission_id'] = (int) $app->submission->translateAliasToID($segments[2]);
		}

	return $vars;
}