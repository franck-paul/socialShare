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

class FrontendBehaviors
{
    public static function publicEntryBeforeContent(): string
    {
        $settings = My::settings();
        if ($settings->active && (App::frontend()->context()->posts->post_type == 'post' && $settings->on_post || App::frontend()->context()->posts->post_type == 'page' && $settings->on_page) && (((App::url()->getType() == 'post' || App::url()->getType() == 'page') && $settings->on_single_only || !$settings->on_single_only) && $settings->before_content)) {
            echo FrontendHelper::socialShare(
                App::frontend()->context()->posts->getURL(),
                App::frontend()->context()->posts->post_title,
                $settings->prefix,
                $settings->twitter_account,
                $settings->intro
            );
        }

        return '';
    }

    public static function publicEntryAfterContent(): string
    {
        $settings = My::settings();
        if ($settings->active && (App::frontend()->context()->posts->post_type == 'post' && $settings->on_post || App::frontend()->context()->posts->post_type == 'page' && $settings->on_page) && (((App::url()->getType() == 'post' || App::url()->getType() == 'page') && $settings->on_single_only || !$settings->on_single_only) && $settings->after_content)) {
            echo FrontendHelper::socialShare(
                App::frontend()->context()->posts->getURL(),
                App::frontend()->context()->posts->post_title,
                $settings->prefix,
                $settings->twitter_account,
                $settings->intro
            );
        }

        return '';
    }

    public static function publicHeadContent(): string
    {
        $settings = My::settings();
        if ($settings->active) {
            switch ($settings->use_style) {
                case 0: // Default CSS styles
                    echo My::cssLoad('default.css');

                    break;
                case 1: // Blog's theme CSS styles

                    break;
                case 2: // User defined CSS styles
                    echo '<style type="text/css">' . "\n" . FrontendHelper::customStyle() . "\n" . "</style>\n";

                    break;
            }
        }

        return '';
    }

    public static function publicFooterContent(): string
    {
        $settings = My::settings();
        if ($settings->active) {
            echo My::jsLoad('popup.js');
        }

        return '';
    }
}
