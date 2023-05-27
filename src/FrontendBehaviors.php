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
use dcUtils;

class FrontendBehaviors
{
    public static function publicEntryBeforeContent()
    {
        $settings = dcCore::app()->blog->settings->get(My::id());
        if ($settings->active) {
            if ((dcCore::app()->ctx->posts->post_type == 'post' && $settings->on_post) || (dcCore::app()->ctx->posts->post_type == 'page' && $settings->on_page)) {
                if (((dcCore::app()->url->type == 'post' || dcCore::app()->url->type == 'page') && $settings->on_single_only) || (!$settings->on_single_only)) {
                    if ($settings->before_content) {
                        echo FrontendHelper::socialShare(
                            dcCore::app()->ctx->posts->getURL(),
                            dcCore::app()->ctx->posts->post_title,
                            (dcCore::app()->ctx->posts->post_lang ?: dcCore::app()->blog->settings->system->lang),
                            $settings->prefix,
                            $settings->twitter_account,
                            $settings->intro
                        );
                    }
                }
            }
        }
    }

    public static function publicEntryAfterContent()
    {
        $settings = dcCore::app()->blog->settings->get(My::id());
        if ($settings->active) {
            if ((dcCore::app()->ctx->posts->post_type == 'post' && $settings->on_post) || (dcCore::app()->ctx->posts->post_type == 'page' && $settings->on_page)) {
                if (((dcCore::app()->url->type == 'post' || dcCore::app()->url->type == 'page') && $settings->on_single_only) || (!$settings->on_single_only)) {
                    if ($settings->after_content) {
                        echo FrontendHelper::socialShare(
                            dcCore::app()->ctx->posts->getURL(),
                            dcCore::app()->ctx->posts->post_title,
                            (dcCore::app()->ctx->posts->post_lang ?: dcCore::app()->blog->settings->system->lang),
                            $settings->prefix,
                            $settings->twitter_account,
                            $settings->intro
                        );
                    }
                }
            }
        }
    }

    public static function publicHeadContent()
    {
        $settings = dcCore::app()->blog->settings->get(My::id());
        if ($settings->active) {
            switch ($settings->use_style) {
                case 0: // Default CSS styles
                    echo dcUtils::cssModuleLoad('socialShare/css/default.css');

                    break;
                case 1: // Blog's theme CSS styles

                    break;
                case 2: // User defined CSS styles
                    echo '<style type="text/css">' . "\n" . FrontendHelper::customStyle() . "\n" . "</style>\n";

                    break;
            }
        }
    }

    public static function publicFooterContent()
    {
        $settings = dcCore::app()->blog->settings->get(My::id());
        if ($settings->active) {
            echo dcUtils::jsModuleLoad('socialShare/js/popup.js');
        }
    }
}