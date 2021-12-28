<?php
/**
 * @brief socialShare, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

$core->blog->settings->addNamespace('socialShare');
if (is_null($core->blog->settings->socialShare->active)) {
    try {
        // Add default settings values if necessary
        $core->blog->settings->socialShare->put('active', false, 'boolean', 'Active', false);

        $core->blog->settings->socialShare->put('twitter', true, 'boolean', 'Add Twitter button', false);
        $core->blog->settings->socialShare->put('facebook', true, 'boolean', 'Add Facebook button', false);
        $core->blog->settings->socialShare->put('linkedin', true, 'boolean', 'Add LinkedIn button', false);
        $core->blog->settings->socialShare->put('mastodon', true, 'boolean', 'Add Mastodon button', false);
        $core->blog->settings->socialShare->put('mail', true, 'boolean', 'Add mail button', false);

        $core->blog->settings->socialShare->put('on_post', true, 'boolean', 'Add social sharing buttons on post', false);
        $core->blog->settings->socialShare->put('on_page', false, 'boolean', 'Add social sharing buttons on page', false);

        $core->blog->settings->socialShare->put('on_single_only', true, 'boolean', 'Display social sharing buttons on single display only (post or page)', false);

        $core->blog->settings->socialShare->put('before_content', false, 'boolean', 'Display social sharing buttons before content', false);
        $core->blog->settings->socialShare->put('after_content', true, 'boolean', 'Display social sharing buttons after content', false);
        $core->blog->settings->socialShare->put('template_tag', false, 'boolean', 'Display social sharing buttons using template tag', false);

        $core->blog->settings->socialShare->put('prefix', __('Share this entry:'), 'string', 'Social sharing buttons prefix text', false);
        $core->blog->settings->socialShare->put('intro', '', 'string', 'Title introduction text', false);
        $core->blog->settings->socialShare->put('tags', true, 'boolean', 'Use tags if any', false);
        $core->blog->settings->socialShare->put('use_style', 0, 'integer', 'CSS style used', false);
        $core->blog->settings->socialShare->put('style', '', 'string', 'Social sharing buttons style', false);

        $core->blog->settings->socialShare->put('twitter_account', '', 'string', 'Twitter account to use with Twitter button', false);

        $core->blog->triggerBlog();
        http::redirect($p_url);
    } catch (Exception $e) {
        $core->error->add($e->getMessage());
    }
}

$ssb_use_styles = [
    0 => __('Use default CSS styles'),
    1 => __('Use theme\'s CSS styles'),
    2 => __('Use user-defined styles'),
];

$ssb_active = (bool) $core->blog->settings->socialShare->active;

$ssb_twitter  = (bool) $core->blog->settings->socialShare->twitter;
$ssb_facebook = (bool) $core->blog->settings->socialShare->facebook;
$ssb_linkedin = (bool) $core->blog->settings->socialShare->linkedin;
$ssb_mastodon = (bool) $core->blog->settings->socialShare->mastodon;
$ssb_mail     = (bool) $core->blog->settings->socialShare->mail;

$ssb_on_post = (bool) $core->blog->settings->socialShare->on_post;
$ssb_on_page = (bool) $core->blog->settings->socialShare->on_page;

$ssb_on_single_only = (bool) $core->blog->settings->socialShare->on_single_only;

$ssb_before_content = (bool) $core->blog->settings->socialShare->before_content;
$ssb_after_content  = (bool) $core->blog->settings->socialShare->after_content;
$ssb_template_tag   = (bool) $core->blog->settings->socialShare->template_tag;

$ssb_prefix    = $core->blog->settings->socialShare->prefix;
$ssb_intro     = $core->blog->settings->socialShare->intro;
$ssb_tags      = (bool) $core->blog->settings->socialShare->tags;
$ssb_use_style = (int) $core->blog->settings->socialShare->use_style;
$ssb_style     = $core->blog->settings->socialShare->style;

$ssb_twitter_account = $core->blog->settings->socialShare->twitter_account;

if (!empty($_POST)) {
    try {
        $ssb_active = !empty($_POST['ssb_active']);

        $ssb_twitter  = !empty($_POST['ssb_twitter']);
        $ssb_facebook = !empty($_POST['ssb_facebook']);
        $ssb_linkedin = !empty($_POST['ssb_linkedin']);
        $ssb_mastodon = !empty($_POST['ssb_mastodon']);
        $ssb_mail     = !empty($_POST['ssb_mail']);

        $ssb_on_post = !empty($_POST['ssb_on_post']);
        $ssb_on_page = !empty($_POST['ssb_on_page']);

        $ssb_on_single_only = !empty($_POST['ssb_on_single_only']);

        $ssb_before_content = !empty($_POST['ssb_before_content']);
        $ssb_after_content  = !empty($_POST['ssb_after_content']);
        $ssb_template_tag   = !empty($_POST['ssb_template_tag']);

        $ssb_prefix    = trim(html::escapeHTML($_POST['ssb_prefix']));
        $ssb_intro     = trim(html::escapeHTML($_POST['ssb_intro']));
        $ssb_tags      = !empty($_POST['ssb_tags']);
        $ssb_use_style = abs((int) $_POST['ssb_use_style']);
        $ssb_style     = trim($_POST['ssb_style']);

        $ssb_twitter_account = trim(ltrim(html::escapeHTML($_POST['ssb_twitter_account']), '@'));

        # Everything's fine, save options
        $core->blog->settings->addNamespace('socialShare');

        $core->blog->settings->socialShare->put('active', $ssb_active);

        $core->blog->settings->socialShare->put('twitter', $ssb_twitter);
        $core->blog->settings->socialShare->put('facebook', $ssb_facebook);
        $core->blog->settings->socialShare->put('linkedin', $ssb_linkedin);
        $core->blog->settings->socialShare->put('mastodon', $ssb_mastodon);
        $core->blog->settings->socialShare->put('mail', $ssb_mail);

        $core->blog->settings->socialShare->put('on_post', $ssb_on_post);
        $core->blog->settings->socialShare->put('on_page', $ssb_on_page);

        $core->blog->settings->socialShare->put('on_single_only', $ssb_on_single_only);

        $core->blog->settings->socialShare->put('before_content', $ssb_before_content);
        $core->blog->settings->socialShare->put('after_content', $ssb_after_content);
        $core->blog->settings->socialShare->put('template_tag', $ssb_template_tag);

        $core->blog->settings->socialShare->put('prefix', $ssb_prefix);
        $core->blog->settings->socialShare->put('intro', $ssb_intro);
        $core->blog->settings->socialShare->put('tags', $ssb_tags);
        $core->blog->settings->socialShare->put('use_style', $ssb_use_style);
        $core->blog->settings->socialShare->put('style', $ssb_style);

        $core->blog->settings->socialShare->put('twitter_account', $ssb_twitter_account);

        $core->blog->triggerBlog();

        dcPage::addSuccessNotice(__('Settings have been successfully updated.'));
        http::redirect($p_url);
    } catch (Exception $e) {
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
    [
        html::escapeHTML($core->blog->name) => '',
        __('socialShare')                   => '',
    ]
);
echo dcPage::notices();

echo
'<form action="' . $p_url . '" method="post">' .
'<p>' . form::checkbox('ssb_active', 1, $ssb_active) . ' ' .
'<label for="ssb_active" class="classic">' . __('Activate socialShare plugin') . '</label></p>' .

'<h3>' . __('Buttons') . '</h3>' .

'<div class="two-cols clearfix">' .
'<div class="col">' .
'<p>' . form::checkbox('ssb_twitter', 1, $ssb_twitter) . ' ' .
'<label for="ssb_twitter" class="classic">' . __('Add Twitter sharing button') . '</label></p>' .
'<p>' . form::checkbox('ssb_facebook', 1, $ssb_facebook) . ' ' .
'<label for="ssb_facebook" class="classic">' . __('Add Facebook sharing button') . '</label></p>' .
'<p>' . form::checkbox('ssb_linkedin', 1, $ssb_linkedin) . ' ' .
'<label for="ssb_linkedin" class="classic">' . __('Add LinkedIn sharing button') . '</label></p>' .
'<p>' . form::checkbox('ssb_mastodon', 1, $ssb_mastodon) . ' ' .
'<label for="ssb_mastodon" class="classic">' . __('Add Mastodon sharing button') . '</label></p>' .
'<p>' . form::checkbox('ssb_mail', 1, $ssb_mail) . ' ' .
'<label for="ssb_mail" class="classic">' . __('Add Mail sharing button') . '</label></p>' .
'</div>' .
'<div class="col">' .
'<p><label for="ssb_twitter_account" class="classic">' . __('Twitter account:') . '</label> ' .
form::field('ssb_twitter_account', 30, 128, html::escapeHTML($ssb_twitter_account)) . '</p>' .
'<p class="form-note">' . __('This will be used as "via" in tweet rather than the blog name (if not empty).') . '</p>' .
'</div>' .
'</div>' .

'<h3>' . __('Options') . '</h3>' .

'<p>' . form::checkbox('ssb_on_post', 1, $ssb_on_post) . ' ' .
'<label for="ssb_on_post" class="classic">' . __('Add social sharing buttons on posts') . '</label></p>' .
'<p>' . form::checkbox('ssb_on_page', 1, $ssb_on_page) . ' ' .
'<label for="ssb_on_page" class="classic">' . __('Add social sharing buttons on pages') . '</label></p>' .
'<p>' . form::checkbox('ssb_on_single_only', 1, $ssb_on_single_only) . ' ' .
'<label for="ssb_on_single_only" class="classic">' . __('Add social sharing buttons only on single display (post or page)') . '</label></p>' .

'<h3>' . __('Position') . '</h3>' .

'<p>' . form::checkbox('ssb_before_content', 1, $ssb_before_content) . ' ' .
'<label for="ssb_before_content" class="classic">' . __('Automatically add social sharing buttons before content') . '</label></p>' .
'<p>' . form::checkbox('ssb_after_content', 1, $ssb_after_content) . ' ' .
'<label for="ssb_after_content" class="classic">' . __('Automatically add social sharing buttons after content') . '</label></p>' .
'<p>' . form::checkbox('ssb_template_tag', 1, $ssb_template_tag) . ' ' .
'<label for="ssb_template_tag" class="classic">' . __('Add social sharing buttons using template tag') . '</label></p>' .
'<p class="form-note">' . __('The {{tpl:SocialShare}} template tag must be present in your template\'s file(s).') . '</p>' .

'<h3>' . __('Advanced options') . '</h3>' .

'<p><label for="ssb_prefix">' . __('Social sharing buttons text prefix:') . '</label> ' .
form::field('ssb_prefix', 30, 128, html::escapeHTML($ssb_prefix)) . '</p>' .
'<p class="form-note">' . __('This will be inserted before buttons (if not empty).') . '</p>' .

'<p><label for="ssb_intro">' . __('Title introduction text:') . '</label> ' .
form::field('ssb_intro', 30, 128, html::escapeHTML($ssb_intro)) . '</p>' .
'<p class="form-note">' . __('This will be inserted before title (if not empty).') . '</p>';

echo
'<p>' . form::checkbox('ssb_tags', 1, $ssb_tags) . ' ' .
'<label for="ssb_tags" class="classic">' . __('Use tags if any') . '</label></p>' .
'<p class="form-note">' . __('Only for Twitter and Mastodon buttons.') . '</p>';

echo
'<div class="fieldset"><h4>' . __('Social sharing buttons CSS styles') . '</h4>';
$i = 0;
foreach ($ssb_use_styles as $k => $v) {
    echo '<p><label for="ssb_use_style_' . $i . '" class="classic">' .
    form::radio(['ssb_use_style', 'ssb_use_style_' . $i], $k, $ssb_use_style == $k) . ' ' . $v . '</label></p>';
    $i++;
}
echo
'<p class="area"><label for="ssb_style">' . __('User defined CSS styles:') . '</label> ' .
form::textarea('ssb_style', 30, 8, html::escapeHTML($ssb_style)) . '</p>' .
'<p class="form-note">' . __('See the README.md file for HTML markup and example of CSS styles.') . '</p>' .
    '</div>';

echo
'<p>' . $core->formNonce() . '<input type="submit" value="' . __('Save') . '" /></p>' .
    '</form>';

?>
</body>
</html>
