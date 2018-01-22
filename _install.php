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

if (!defined('DC_CONTEXT_ADMIN')) {return;}

$new_version = $core->plugins->moduleInfo('socialShare', 'version');
$old_version = $core->getVersion('socialShare');

if (version_compare($old_version, $new_version, '>=')) {
    return;
}

try
{
    $core->blog->settings->addNamespace('socialShare');

    $core->blog->settings->socialShare->put('active', false, 'boolean', 'Active', false, true);

    $core->blog->settings->socialShare->put('twitter', true, 'boolean', 'Add Twitter button', false, true);
    $core->blog->settings->socialShare->put('facebook', true, 'boolean', 'Add Facebook button', false, true);
    $core->blog->settings->socialShare->put('google', true, 'boolean', 'Add Google+ button', false, true);
    $core->blog->settings->socialShare->put('linkedin', true, 'boolean', 'Add LinkedIn button', false, true);
    $core->blog->settings->socialShare->put('mastodon', true, 'boolean', 'Add Mastodon button', false, true);
    $core->blog->settings->socialShare->put('mail', true, 'boolean', 'Add mail button', false, true);

    $core->blog->settings->socialShare->put('on_post', true, 'boolean', 'Add social sharing buttons on post', false, true);
    $core->blog->settings->socialShare->put('on_page', false, 'boolean', 'Add social sharing buttons on page', false, true);

    $core->blog->settings->socialShare->put('on_single_only', true, 'boolean', 'Display social sharing buttons on single display only (post or page)', false, true);

    $core->blog->settings->socialShare->put('before_content', false, 'boolean', 'Display social sharing buttons before content', false, true);
    $core->blog->settings->socialShare->put('after_content', true, 'boolean', 'Display social sharing buttons after content', false, true);
    $core->blog->settings->socialShare->put('template_tag', false, 'boolean', 'Display social sharing buttons using template tag', false, true);

    $core->blog->settings->socialShare->put('prefix', __('Share this entry:'), 'string', 'Social sharing buttons prefix text', false, true);
    $core->blog->settings->socialShare->put('use_style', 0, 'integer', 'CSS styles used', false, true);
    $core->blog->settings->socialShare->put('style', '', 'string', 'Social sharing buttons style', false, true);

    $core->blog->settings->socialShare->put('twitter_account', '', 'string', 'Twitter account to use with Twitter button', false, true);

    $core->setVersion('socialShare', $new_version);

    return true;
} catch (Exception $e) {
    $core->error->add($e->getMessage());
}
return false;
