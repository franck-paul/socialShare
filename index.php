<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of socialShare, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) { return; }

$core->blog->settings->addNamespace('socialShare');
if (is_null($core->blog->settings->socialShare->active)) {
	try {
		// Add default settings values if necessary
		$core->blog->settings->socialShare->put('active',false,'boolean','Active',false);

		$core->blog->settings->socialShare->put('twitter',true,'boolean','Add Twitter button',false);
		$core->blog->settings->socialShare->put('facebook',true,'boolean','Add Facebook button',false);
		$core->blog->settings->socialShare->put('google',true,'boolean','Add Google+ button',false);
		$core->blog->settings->socialShare->put('mail',true,'boolean','Add mail button',false);

		$core->blog->settings->socialShare->put('on_post',true,'boolean','Add social sharing buttons on post',false);
		$core->blog->settings->socialShare->put('on_page',false,'boolean','Add social sharing buttons on page',false);

		$core->blog->settings->socialShare->put('on_single_only',true,'boolean','Display social sharing buttons on single display only (post or page)',false);

		$core->blog->settings->socialShare->put('before_content',false,'boolean','Display social sharing buttons before content',false);
		$core->blog->settings->socialShare->put('after_content',true,'boolean','Display social sharing buttons after content',false);
		$core->blog->settings->socialShare->put('template_tag',false,'boolean','Display social sharing buttons using template tag',false);

		$core->blog->settings->socialShare->put('prefix',__('Share this entry:'),'string','Social sharing buttons prefix text',false);
		$core->blog->settings->socialShare->put('use_style',0,'integer','CSS style used',false);
		$core->blog->settings->socialShare->put('style','','string','Social sharing buttons style',false);

		$core->blog->settings->socialShare->put('twitter_account','','string','Twitter account to use with Twitter button',false);

		$core->blog->triggerBlog();
		http::redirect($p_url);
	}
	catch (Exception $e) {
		$core->error->add($e->getMessage());
	}
}

$ssb_use_styles = array(
	0 => __('Use default CSS styles'),
	1 => __('Use theme\'s CSS styles'),
	2 => __('Use user-defined styles')
);


$ssb_active = (boolean) $core->blog->settings->socialShare->active;

$ssb_twitter = (boolean) $core->blog->settings->socialShare->twitter;
$ssb_facebook = (boolean) $core->blog->settings->socialShare->facebook;
$ssb_google = (boolean) $core->blog->settings->socialShare->google;
$ssb_mail = (boolean) $core->blog->settings->socialShare->mail;

$ssb_on_post = (boolean) $core->blog->settings->socialShare->on_post;
$ssb_on_page = (boolean) $core->blog->settings->socialShare->on_page;

$ssb_on_single_only = (boolean) $core->blog->settings->socialShare->on_single_only;

$ssb_before_content = (boolean) $core->blog->settings->socialShare->before_content;
$ssb_after_content = (boolean) $core->blog->settings->socialShare->after_content;
$ssb_template_tag = (boolean) $core->blog->settings->socialShare->template_tag;

$ssb_prefix = $core->blog->settings->socialShare->prefix;
$ssb_use_style = (integer) $core->blog->settings->socialShare->use_style;
$ssb_style = $core->blog->settings->socialShare->style;

$ssb_twitter_account = $core->blog->settings->socialShare->twitter_account;

if (!empty($_POST))
{
	try
	{
		$ssb_active = !empty($_POST['ssb_active']);

		$ssb_twitter = !empty($_POST['ssb_twitter']);
		$ssb_facebook = !empty($_POST['ssb_facebook']);
		$ssb_google = !empty($_POST['ssb_google']);
		$ssb_mail = !empty($_POST['ssb_mail']);

		$ssb_on_post = !empty($_POST['ssb_on_post']);
		$ssb_on_page = !empty($_POST['ssb_on_page']);

		$ssb_on_single_only = !empty($_POST['ssb_on_single_only']);

		$ssb_before_content = !empty($_POST['ssb_before_content']);
		$ssb_after_content = !empty($_POST['ssb_after_content']);
		$ssb_template_tag = !empty($_POST['ssb_template_tag']);

		$ssb_prefix = trim(html::escapeHTML($_POST['ssb_prefix']));
		$ssb_use_style = abs((integer)$_POST['ssb_use_style']);
		$ssb_style = trim($_POST['ssb_style']);

		$ssb_twitter_account = trim(html::escapeHTML($_POST['ssb_twitter_account']));

		# Everything's fine, save options
		$core->blog->settings->addNamespace('socialShare');

		$core->blog->settings->socialShare->put('active',$ssb_active);

		$core->blog->settings->socialShare->put('twitter',$ssb_twitter);
		$core->blog->settings->socialShare->put('facebook',$ssb_facebook);
		$core->blog->settings->socialShare->put('google',$ssb_google);
		$core->blog->settings->socialShare->put('mail',$ssb_mail);

		$core->blog->settings->socialShare->put('on_post',$ssb_on_post);
		$core->blog->settings->socialShare->put('on_page',$ssb_on_page);

		$core->blog->settings->socialShare->put('on_single_only',$ssb_on_single_only);

		$core->blog->settings->socialShare->put('before_content',$ssb_before_content);
		$core->blog->settings->socialShare->put('after_content',$ssb_after_content);
		$core->blog->settings->socialShare->put('template_tag',$ssb_template_tag);

		$core->blog->settings->socialShare->put('prefix',$ssb_prefix);
		$core->blog->settings->socialShare->put('use_style',$ssb_use_style);
		$core->blog->settings->socialShare->put('style',$ssb_style);

		$core->blog->settings->socialShare->put('twitter_account',$ssb_twitter_account);

		$core->blog->triggerBlog();

		dcPage::addSuccessNotice(__('Settings have been successfully updated.'));
		http::redirect($p_url);
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
	}
}

?>
<html>
<head>
	<title><?php echo __('socialShare'); ?></title>
</head>

<body>
<?php
echo dcPage::breadcrumb(
	array(
		html::escapeHTML($core->blog->name) => '',
		__('socialShare') => ''
	));
echo dcPage::notices();

echo
'<form action="'.$p_url.'" method="post">'.
'<p>'.form::checkbox('ssb_active',1,$ssb_active).' '.
'<label for="ssb_active" class="classic">'.__('Activate socialShare plugin').'</label></p>'.

'<h3>'.__('Buttons').'</h3>'.

'<div class="two-cols">'.
'<div class="col">'.
'<p>'.form::checkbox('ssb_twitter',1,$ssb_twitter).' '.
'<label for="ssb_twitter" class="classic">'.__('Add Twitter sharing button').'</label></p>'.
'<p>'.form::checkbox('ssb_facebook',1,$ssb_facebook).' '.
'<label for="ssb_facebook" class="classic">'.__('Add Facebook sharing button').'</label></p>'.
'<p>'.form::checkbox('ssb_google',1,$ssb_google).' '.
'<label for="ssb_google" class="classic">'.__('Add Google+ sharing button').'</label></p>'.
'<p>'.form::checkbox('ssb_mail',1,$ssb_mail).' '.
'<label for="ssb_mail" class="classic">'.__('Add Mail sharing button').'</label></p>'.
'</div>'.
'<div class="col">'.
'<p><label for="ssb_twitter_account" class="classic">'.__('Twitter account:').'</label> '.
form::field('ssb_twitter_account',30,128,html::escapeHTML($ssb_twitter_account)).'</p>'.
'<p class="form-note">'.__('This will be used as "via" in tweet rather than the blog name (if not empty).').'</p>'.
'</div>'.
'</div>'.
'<br class="clear" />'. //Opera sucks

'<h3>'.__('Options').'</h3>'.

'<p>'.form::checkbox('ssb_on_post',1,$ssb_on_post).' '.
'<label for="ssb_on_post" class="classic">'.__('Add social sharing buttons on posts').'</label></p>'.
'<p>'.form::checkbox('ssb_on_page',1,$ssb_on_page).' '.
'<label for="ssb_on_page" class="classic">'.__('Add social sharing buttons on pages').'</label></p>'.
'<p>'.form::checkbox('ssb_on_single_only',1,$ssb_on_single_only).' '.
'<label for="ssb_on_single_only" class="classic">'.__('Add social sharing buttons only on single display (post or page)').'</label></p>'.

'<h3>'.__('Position').'</h3>'.

'<p>'.form::checkbox('ssb_before_content',1,$ssb_before_content).' '.
'<label for="ssb_before_content" class="classic">'.__('Automatically add social sharing buttons before content').'</label></p>'.
'<p>'.form::checkbox('ssb_after_content',1,$ssb_after_content).' '.
'<label for="ssb_after_content" class="classic">'.__('Automatically add social sharing buttons after content').'</label></p>'.
'<p>'.form::checkbox('ssb_template_tag',1,$ssb_template_tag).' '.
'<label for="ssb_template_tag" class="classic">'.__('Add social sharing buttons using template tag').'</label></p>'.
'<p class="form-note">'.__('The {{tpl:SocialShare}} template tag must be present in your template\'s file(s).').'</p>'.

'<h3>'.__('Advanced options').'</h3>'.

'<p><label for="ssb_prefix">'.__('Social sharing buttons text prefix:').'</label> '.
form::field('ssb_prefix',30,128,html::escapeHTML($ssb_prefix)).'</p>'.
'<p class="form-note">'.__('This will be inserted before buttons (if not empty).').'</p>';

echo
'<div class="fieldset"><h4>'.__('Social sharing buttons CSS styles').'</h4>';
$i = 0;
foreach ($ssb_use_styles as $k => $v)
{
	echo '<p><label for="ssb_use_style_'.$i.'" class="classic">'.
	form::radio(array('ssb_use_style','ssb_use_style_'.$i),$k,$ssb_use_style == $k).' '.$v.'</label></p>';
	$i++;
}
echo
'<p class="area"><label for="ssb_style">'.__('User defined CSS styles:').'</label> '.
form::textarea('ssb_style',30,8,html::escapeHTML($ssb_style)).'</p>'.
'<p class="form-note">'.__('See the README.md file for HTML markup and example of CSS styles.').'</p>'.
'</div>';

echo
'<p>'.$core->formNonce().'<input type="submit" value="'.__('Save').'" /></p>'.
'</form>';

?>
</body>
</html>
