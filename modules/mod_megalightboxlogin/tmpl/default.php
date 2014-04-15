<?php
/*----------------------------------------------------------------------
 # mod_megalightboxlogin - Mega Lightbox Login Module For Joomla! 1.6
 #----------------------------------------------------------------------
 # author OmegaTheme.com
 # copyright Copyright(C) 2011 - OmegaTheme.com. All Rights Reserved.
 # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Website: http://omegatheme.com
 # Technical support: Forum - http://omegatheme.com/forum/
------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$doc =& JFactory::getDocument();
$doc->addStyleSheet('modules/mod_megalightboxlogin/assets/css/mega_lightbox_login.css');
?>
<?php if ($type == 'logout') : ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="mega-logout-form">
	<div class="mega-logout-wrap">
		<?php if ($params->get('greeting')) : ?>
		<div class="mega-greeting">
			<div class="mega-logged-greeting">
				<div class="mega-logged-greeting-inner">
				<?php if($params->get('name') == 0) : {
					echo JText::sprintf('MOD_MEGALIGHTBOXLOGIN_HINAME', $user->get('name'));
				} else : {
					echo JText::sprintf('MOD_MEGALIGHTBOXLOGIN_HINAME', $user->get('username'));
				} endif; ?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<?php
			$logoutTitle = trim($params->get('logout_title'));
			if ($logoutTitle == '') {
				$logoutTitle = JText::_('JLOGOUT');
			}
		?>
		<div class="mega-logout-button">
			<div class="mega-logout-button-inner">
				<input type="submit" name="Submit" class="button" value="<?php echo $logoutTitle; ?>" />
			</div>
		</div>
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="user.logout" />
			<input type="hidden" name="return" value="<?php echo $return; ?>" />
			<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<div style="clear: both;"></div>
<?php else : ?>
<?php
	JHTML::_('behavior.mootools');
	$doc->addScript('modules/mod_megalightboxlogin/assets/js/megascript.js');
?>

<div id="mega-login-popup-wrap">
	<span id="mega-login-popup-link" class="modal mega-login-button" title="<?php echo JText::_('MEGA_LOGIN_TITLE') ?>">
		<span class="mega-login-popup-inner1">
			<span class="mega-login-popup-inner2">
			<?php
				$loginTitle = trim($params->get('login_title'));
				
				if (empty($loginTitle)) {
					echo JText::_('JLOGIN');
				} else {
					$loginTitle = explode('/', $loginTitle);
					echo '<span id="mega-login-label">'.$loginTitle[0].'</span>&nbsp;/&nbsp;<span id="mega-register-label">'.$loginTitle[1].'</span>';
				}
			?>
			</span>
		</span>
	</span>
</div>
<div style="clear: both;"></div>
<div id="mega-lightbox-wrapper" style="display:none;">
	<div class="mega-lightbox-tl">
		<div class="mega-lightbox-tr">
			<div class="mega-lightbox-tm">&nbsp;</div>
		</div>
	</div>
	<div id="mega-lightbox-wrap" style="display: none;clear: both;">
		<div class="mega-tab-wrap">
			<div class="mega-tab-area">
				<span id ="mega-login-tab" class="mega-tab"><?php echo JText::_('JLOGIN') ?></span>
				<span id ="mega-signup-tab" class="mega-tab"><?php echo JText::_('JREGISTER') ?></span>
			</div>
			<div id="mega-tab-login-main" class="mega-tab-content">
				<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="mega-login-form" >
				<div class="mega-login-form-custom">
					<div class="userdata">
						<p class="form-login-username">
							<label for="modlgn-username"><?php echo JText::_('MOD_MEGALIGHTBOXLOGIN_VALUE_USERNAME') ?></label><br />
							<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
						</p>
						<p class="form-login-password">
							<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label><br />
							<input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
						</p>
						<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
						<p class="form-login-remember">
							<input id="modlgn-remember" type="checkbox" name="remember" value="yes"/>
							<label for="modlgn-remember"><?php echo JText::_('MOD_MEGALIGHTBOXLOGIN_REMEMBER_ME') ?></label>
						</p>
						<?php endif; ?>
						<p class="mega-submit">
							<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
							<input type="hidden" name="option" value="com_users" />
							<input type="hidden" name="task" value="user.login" />
							<input type="hidden" name="return" value="<?php echo $return; ?>" />
							<?php echo JHtml::_('form.token'); ?>
						</p>
					</div>
					<ul>
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
								<?php echo JText::_('MOD_MEGALIGHTBOXLOGIN_FORGOT_YOUR_PASSWORD'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
								<?php echo JText::_('MOD_MEGALIGHTBOXLOGIN_FORGOT_YOUR_USERNAME'); ?>
							</a>
						</li>
					</ul>
				</div>
				</form>
			</div>
			<div id="mega-tab-signup-main" class="mega-tab-content">
				<?php
				$usersConfig = JComponentHelper::getParams('com_users');
				if ($usersConfig->get('allowUserRegistration')) : ?>
				<div class="mega-registration">
				<form id="mega-member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate">
					<div class="mega-user-signup">
						<p style="margin-top: 0;"><strong class="red">*</strong> <?php echo JText::_('MOD_MEGALIGHTBOXLOGIN_REQUIRED'); ?></p>
						<p class="form-signup form-signup-name">
							<label class="required" for="jform_name" id="jform_name-lbl">Name:<span class="star">&nbsp;*</span></label><br />
							<input type="text" size="30" class="required" value="" id="jform_name" name="jform[name]" />
						</p>
						<p class="form-signup form-signup-username">
							<label class="required" for="jform_username" id="jform_username-lbl">Username:<span class="star">&nbsp;*</span></label><br />
							<input type="text" size="30" class="validate-username required" value="" id="jform_username" name="jform[username]" />
						</p>
						<p class="form-signup form-signup-password">
							<label class="required" for="jform_password1" id="jform_password1-lbl">Password:<span class="star">&nbsp;*</span></label><br />
							<input type="password" size="30" class="validate-password required" autocomplete="off" value="" id="jform_password1" name="jform[password1]" />
						</p>
						<p class="form-signup form-signup-confirm-password">
							<label class="required" for="jform_password2" id="jform_password2-lbl">Confirm Password:<span class="star">&nbsp;*</span></label><br />
							<input type="password" size="30" class="validate-password required" autocomplete="off" value="" id="jform_password2" name="jform[password2]" />
						</p>
						<p class="form-signup form-signup-email">
							<label class="required" for="jform_email1" id="jform_email1-lbl">Email Address:<span class="star">&nbsp;*</span></label><br />
							<input type="text" size="30" value="" id="jform_email1" class="validate-email required" name="jform[email1]" />
						</p>
						<p class="form-signup form-signup-confirm-email">
							<label class="required" for="jform_email2" id="jform_email2-lbl">Confirm email Address:<span class="star">&nbsp;*</span></label><br />
							<input type="text" size="30" value="" id="jform_email2" class="validate-email required" name="jform[email2]" />
						</p>
					</div>
					<div>
						<button type="submit" class="validate"><?php echo JText::_('JREGISTER');?></button>
						<?php echo JText::_('COM_USERS_OR');?>
						<a href="<?php echo JRoute::_('');?>" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>
						<input type="hidden" name="option" value="com_users" />
						<input type="hidden" name="task" value="registration.register" />
						<?php echo JHtml::_('form.token');?>
					</div>
				</form>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="mega-lightbox-bl">
		<div class="mega-lightbox-br">
			<div class="mega-lightbox-bm">&nbsp;</div>
		</div>
	</div>
</div>
<?php endif; ?>
