<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// add js and css
$this->app->document->addScript('libraries:jquery/plugins/cookie/jquery.cookie.js');
$this->app->document->addScript('assets:js/comment.js');
$this->app->document->addStylesheet('assets:css/comments.css');

// css classes
$css[] = $params->get('max_depth', 5) > 1 ? 'nested' : null;
$css[] = $params->get('registered_users_only') && $active_author->isGuest() ? 'no-response' : null;

?>

<div id="comments">
	<div class="comments <?php echo implode("\n", $css); ?>">

		<h3 class="comments-meta">
			<span class="comments-count"><?php echo JText::_('Comments').' ('.(count($comments)-1).')'; ?></span>
		</h3>

			<ul class="level1">
				<?php
				foreach ($comments[0]->getChildren() as $comment) {
					echo $this->partial('comment', array('level' => 1, 'comment' => $comment, 'author' => $comment->getAuthor(), 'params' => $params));
				}
				?>
			</ul>

		<?php
		 	if($item->isCommentsEnabled()) :
				echo $this->partial('respond', compact('active_author', 'params', 'item'));
			endif;
		?>

	</div>
</div>

<script type="text/javascript">
	jQuery(function($) {
		$('#comments').Comment({ cookiePrefix: '<?php echo CommentHelper::COOKIE_PREFIX; ?>', cookieLifetime: '<?php echo CommentHelper::COOKIE_LIFETIME; ?>' });
	});
</script>