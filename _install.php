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

$new_version = dcCore::app()->plugins->moduleInfo('socialShare', 'version');
$old_version = dcCore::app()->getVersion('socialShare');

if (version_compare((string) $old_version, $new_version, '>=')) {
    return;
}

try {
    dcCore::app()->blog->settings->addNamespace('socialShare');

    dcCore::app()->blog->settings->socialShare->put('active', false, 'boolean', 'Active', false, true);

    dcCore::app()->blog->settings->socialShare->put('twitter', true, 'boolean', 'Add Twitter button', false, true);
    dcCore::app()->blog->settings->socialShare->put('facebook', true, 'boolean', 'Add Facebook button', false, true);
    dcCore::app()->blog->settings->socialShare->put('linkedin', true, 'boolean', 'Add LinkedIn button', false, true);
    dcCore::app()->blog->settings->socialShare->put('mastodon', true, 'boolean', 'Add Mastodon button', false, true);
    dcCore::app()->blog->settings->socialShare->put('mail', true, 'boolean', 'Add mail button', false, true);

    dcCore::app()->blog->settings->socialShare->put('on_post', true, 'boolean', 'Add social sharing buttons on post', false, true);
    dcCore::app()->blog->settings->socialShare->put('on_page', false, 'boolean', 'Add social sharing buttons on page', false, true);

    dcCore::app()->blog->settings->socialShare->put('on_single_only', true, 'boolean', 'Display social sharing buttons on single display only (post or page)', false, true);

    dcCore::app()->blog->settings->socialShare->put('before_content', false, 'boolean', 'Display social sharing buttons before content', false, true);
    dcCore::app()->blog->settings->socialShare->put('after_content', true, 'boolean', 'Display social sharing buttons after content', false, true);
    dcCore::app()->blog->settings->socialShare->put('template_tag', false, 'boolean', 'Display social sharing buttons using template tag', false, true);

    dcCore::app()->blog->settings->socialShare->put('prefix', __('Share this entry:'), 'string', 'Social sharing buttons prefix text', false, true);
    dcCore::app()->blog->settings->socialShare->put('intro', '', 'string', 'Title introduction text', false, true);
    dcCore::app()->blog->settings->socialShare->put('tags', true, 'boolean', 'Use tags if any', false, true);
    dcCore::app()->blog->settings->socialShare->put('use_style', 0, 'integer', 'CSS styles used', false, true);
    dcCore::app()->blog->settings->socialShare->put('style', '', 'string', 'Social sharing buttons style', false, true);

    dcCore::app()->blog->settings->socialShare->put('twitter_account', '', 'string', 'Twitter account to use with Twitter button', false, true);

    dcCore::app()->setVersion('socialShare', $new_version);

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
