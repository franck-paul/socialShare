<?php

/**
 * @brief socialShare, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul contact@open-time.net
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\socialShare;

use Dotclear\App;
use Dotclear\Database\MetaRecord;

class FrontendBehaviors
{
    public static function publicEntryBeforeContent(): string
    {
        $settings = My::settings();
        if ($settings->getBool('active')
            && App::frontend()->context()->posts instanceof MetaRecord
            && (App::frontend()->context()->posts->strField('post_type')    === 'post' && $settings->getBool('on_post')
                || App::frontend()->context()->posts->strField('post_type') === 'page' && $settings->getBool('on_page'))
            && ((App::url()->isType(['post', 'page']) && $settings->getBool('on_single_only')
                || !$settings->getBool('on_single_only')) && $settings->getBool('before_content'))
        ) {
            echo FrontendHelper::socialShare(
                App::frontend()->context()->posts->getURL(),
                App::frontend()->context()->posts->strField('post_title'),
                $settings->getStr('prefix', false),
                $settings->getStr('twitter_account', false),
                $settings->getStr('intro', false)
            );
        }

        return '';
    }

    public static function publicEntryAfterContent(): string
    {
        $settings = My::settings();
        if ($settings->getBool('active')
            && App::frontend()->context()->posts instanceof MetaRecord
            && (App::frontend()->context()->posts->strField('post_type')    === 'post' && $settings->getBool('on_post')
                || App::frontend()->context()->posts->strField('post_type') === 'page' && $settings->getBool('on_page'))
            && ((App::url()->isType(['post', 'page']) && $settings->getBool('on_single_only')
                || !$settings->getBool('on_single_only')) && $settings->getBool('after_content'))
        ) {
            echo FrontendHelper::socialShare(
                App::frontend()->context()->posts->getURL(),
                App::frontend()->context()->posts->strField('post_title'),
                $settings->getStr('prefix', false),
                $settings->getStr('twitter_account', false),
                $settings->getStr('intro', false)
            );
        }

        return '';
    }

    public static function publicHeadContent(): string
    {
        $settings = My::settings();
        if ($settings->getBool('active')) {
            switch ($settings->getInt('use_style', false)) {
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
        if ($settings->getBool('active')) {
            echo My::jsLoad('popup.js');
        }

        return '';
    }
}
