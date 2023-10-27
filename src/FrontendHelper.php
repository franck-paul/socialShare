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
use Dotclear\Helper\Html\Html;

class FrontendHelper
{
    public static function socialShare(string $url, string $title, string $lang, string $prefix, string $twitter_account, string $intro = ''): string
    {
        $ret = '';

        // Twitter does not like pipe in text, may be another characters?
        $filter = static fn($text) => str_replace(['|'], ['-'], $text);

        $settings = My::settings();
        if ($settings->twitter || $settings->facebook || $settings->linkedin || $settings->mastodon || $settings->mail) {
            $ret = '<div class="share">' . "\n";
            if ($prefix !== '' && $prefix !== '0') {
                $ret .= '<p class="share-intro">' . $prefix . '</p>' . "\n";
            }

            $ret .= '<ul class="share-links">' . "\n";

            // Compose text
            $text = ($intro != '' ? $intro . '%20' : '') . $title;
            $a11y = __(' (new window)');

            // Lookup for tags on entry
            $tags = '';
            if ($settings->tags && isset(App::frontend()->context()->posts->post_meta)) {
                $meta = App::meta()->getMetaRecordset(App::frontend()->context()->posts->post_meta, 'tag');
                $meta->sort('meta_id_lower', 'asc');
                while ($meta->fetch()) {
                    $tags .= '%20%23' . $meta->meta_id; // space + # + tag
                }
            }

            // Twitter link
            if ($settings->twitter) {
                $share_url = sprintf(
                    'https://twitter.com/share?url=%s&amp;text=%s',
                    Html::sanitizeURL($url),
                    Html::escapeHTML($filter($text) . $tags)
                );
                if ($twitter_account != '') {
                    $share_url .= '&amp;via=' . Html::escapeHTML($twitter_account);
                }

                $href_text  = __('Twitter');
                $href_title = __('Share this on Twitter');
                $ret .= self::link($href_title, $a11y, $share_url, $href_text, 'share-twitter share-popup');
            }

            // Facebook link
            if ($settings->facebook) {
                $share_url = sprintf(
                    'https://www.facebook.com/sharer.php?u=%s&amp;t=%s',
                    Html::sanitizeURL($url),
                    Html::escapeHTML($text)
                );
                $href_text  = __('Facebook');
                $href_title = __('Share this on Facebook');
                $ret .= self::link($href_title, $a11y, $share_url, $href_text, 'share-fb share-popup');
            }

            // LinkedIn link
            if ($settings->linkedin) {
                $share_url = sprintf(
                    'https://www.linkedin.com/shareArticle?mini=true&url=%s&title=%s',
                    Html::sanitizeURL($url),
                    Html::escapeHTML($text)
                );
                $href_text  = __('LinkedIn');
                $href_title = __('Share this on LinkedIn');
                $ret .= self::link($href_title, $a11y, $share_url, $href_text, 'share-in share-popup');
            }

            // Mastodon link
            if ($settings->mastodon) {
                $share_url = sprintf(
                    'web+mastodon://share?text=%s+%s',
                    str_replace('&amp;', '%26', Html::escapeHTML($text . $tags)),
                    Html::sanitizeURL($url)
                );
                $href_text  = __('Mastodon');
                $href_title = __('Share this on Mastodon');
                $ret .= self::link($href_title, $a11y, $share_url, $href_text, 'share-mastodon share-popup');
            }

            // Mail link
            if ($settings->mail) {
                $share_url = sprintf(
                    'mailto:?subject=%s&amp;body=%s',
                    Html::escapeHTML($text),
                    Html::sanitizeURL($url)
                );
                $href_text  = __('Mail');
                $href_title = __('Share this by mail');
                $ret .= self::link($href_title, $a11y, $share_url, $href_text, 'share-mail');
            }

            $ret .= '</ul>' . "\n" .
                '</div>' . "\n";
        }

        return $ret;
    }

    public static function customStyle(): string
    {
        $settings = My::settings();

        return $settings->style ?? '';
    }

    private static function link(string $href_title, string $a11y, string $share_url, string $href_text, string $class): string
    {
        // Tricky code to avoid xgettext bug on indented end heredoc identifier (see https://savannah.gnu.org/bugs/?62158)
        // Warning: don't use <<< if there is some __() l10n calls after as xgettext will not find them
        return <<<LINK
            <li><a class="{$class}" target="_blank" rel="nofollow noopener noreferrer" title="{$href_title}$a11y" href="{$share_url}"><span>{$href_text}</span></a></li>
            LINK;
    }
}
