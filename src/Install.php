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

use dcCore;
use dcNamespace;
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
            $settings = dcCore::app()->blog->settings->get(My::id());

            $settings->put('active', false, dcNamespace::NS_BOOL, 'Active', false, true);

            $settings->put('twitter', true, dcNamespace::NS_BOOL, 'Add Twitter button', false, true);
            $settings->put('facebook', true, dcNamespace::NS_BOOL, 'Add Facebook button', false, true);
            $settings->put('linkedin', true, dcNamespace::NS_BOOL, 'Add LinkedIn button', false, true);
            $settings->put('mastodon', true, dcNamespace::NS_BOOL, 'Add Mastodon button', false, true);
            $settings->put('mail', true, dcNamespace::NS_BOOL, 'Add mail button', false, true);

            $settings->put('on_post', true, dcNamespace::NS_BOOL, 'Add social sharing buttons on post', false, true);
            $settings->put('on_page', false, dcNamespace::NS_BOOL, 'Add social sharing buttons on page', false, true);

            $settings->put('on_single_only', true, dcNamespace::NS_BOOL, 'Display social sharing buttons on single display only (post or page)', false, true);

            $settings->put('before_content', false, dcNamespace::NS_BOOL, 'Display social sharing buttons before content', false, true);
            $settings->put('after_content', true, dcNamespace::NS_BOOL, 'Display social sharing buttons after content', false, true);
            $settings->put('template_tag', false, dcNamespace::NS_BOOL, 'Display social sharing buttons using template tag', false, true);

            $settings->put('prefix', __('Share this entry:'), dcNamespace::NS_STRING, 'Social sharing buttons prefix text', false, true);
            $settings->put('intro', '', dcNamespace::NS_STRING, 'Title introduction text', false, true);
            $settings->put('tags', true, dcNamespace::NS_BOOL, 'Use tags if any', false, true);
            $settings->put('use_style', 0, dcNamespace::NS_INT, 'CSS styles used', false, true);
            $settings->put('style', '', dcNamespace::NS_STRING, 'Social sharing buttons style', false, true);

            $settings->put('twitter_account', '', dcNamespace::NS_STRING, 'Twitter account to use with Twitter button', false, true);
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
