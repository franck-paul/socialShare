<?php

/**
 * @brief socialShare, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\socialShare;

use Dotclear\App;
use Dotclear\Core\Process;
use Exception;

class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        try {
            // Init
            $settings = My::settings();

            $settings->put('active', false, App::blogWorkspace()::NS_BOOL, 'Active', false, true);

            $settings->put('twitter', true, App::blogWorkspace()::NS_BOOL, 'Add Twitter/X button', false, true);
            $settings->put('facebook', true, App::blogWorkspace()::NS_BOOL, 'Add Facebook button', false, true);
            $settings->put('linkedin', true, App::blogWorkspace()::NS_BOOL, 'Add LinkedIn button', false, true);
            $settings->put('mastodon', true, App::blogWorkspace()::NS_BOOL, 'Add Mastodon button', false, true);
            $settings->put('bluesky', true, App::blogWorkspace()::NS_BOOL, 'Add Bluesky button', false, true);
            $settings->put('mail', true, App::blogWorkspace()::NS_BOOL, 'Add mail button', false, true);
            $settings->put('menu', true, App::blogWorkspace()::NS_BOOL, 'Add share menu button', false, true);

            $settings->put('on_post', true, App::blogWorkspace()::NS_BOOL, 'Add social sharing buttons on post', false, true);
            $settings->put('on_page', false, App::blogWorkspace()::NS_BOOL, 'Add social sharing buttons on page', false, true);

            $settings->put('on_single_only', true, App::blogWorkspace()::NS_BOOL, 'Display social sharing buttons on single display only (post or page)', false, true);

            $settings->put('before_content', false, App::blogWorkspace()::NS_BOOL, 'Display social sharing buttons before content', false, true);
            $settings->put('after_content', true, App::blogWorkspace()::NS_BOOL, 'Display social sharing buttons after content', false, true);
            $settings->put('template_tag', false, App::blogWorkspace()::NS_BOOL, 'Display social sharing buttons using template tag', false, true);

            $settings->put('prefix', __('Share this entry:'), App::blogWorkspace()::NS_STRING, 'Social sharing buttons prefix text', false, true);
            $settings->put('intro', '', App::blogWorkspace()::NS_STRING, 'Title introduction text', false, true);
            $settings->put('tags', true, App::blogWorkspace()::NS_BOOL, 'Use tags if any', false, true);
            $settings->put('use_style', 0, App::blogWorkspace()::NS_INT, 'CSS styles used', false, true);
            $settings->put('style', '', App::blogWorkspace()::NS_STRING, 'Social sharing buttons style', false, true);

            $settings->put('twitter_account', '', App::blogWorkspace()::NS_STRING, 'Twitter/X account to use with Twitter/X button', false, true);
        } catch (Exception $exception) {
            App::error()->add($exception->getMessage());
        }

        return true;
    }
}
