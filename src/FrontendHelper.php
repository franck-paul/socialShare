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
use Dotclear\Helper\Html\Html;

class FrontendHelper
{
    public static function socialShare($url, $title, $lang, $prefix, $twitter_account, $intro = '')
    {
        $ret = '';

        // Twitter does not like pipe in text, may be another characters?
        $filter = fn ($text) => str_replace(['|'], ['-'], $text);

        $settings = My::settings();
        if ($settings->twitter || $settings->facebook || $settings->linkedin || $settings->mastodon || $settings->mail) {
            $ret = '<div class="share">' . "\n";
            if ($prefix) {
                $ret .= '<p class="share-intro">' . $prefix . '</p>' . "\n";
            }
            $ret .= '<ul class="share-links">' . "\n";

            // Compose text
            $text = ($intro != '' ? $intro . '%20' : '') . $title;
            $a11y = __(' (new window)');

            // Lookup for tags on entry
            $tags = '';
            if ($settings->tags && isset(dcCore::app()->ctx->posts->post_meta)) {
                $meta = dcCore::app()->meta->getMetaRecordset(dcCore::app()->ctx->posts->post_meta, 'tag');
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
                $ret .= <<<TWITTER
                    <li><a class="share-twitter share-popup" target="_blank" rel="nofollow noopener noreferrer" title="$href_title$a11y" href="$share_url"><span>$href_text</span></a></li>
                    TWITTER;
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
                $ret .= <<<FACEBOOK
                    <li><a class="share-fb share-popup" target="_blank" rel="nofollow noopener noreferrer" title="$href_title$a11y" href="$share_url"><span>$href_text</span></a></li>
                    FACEBOOK;
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
                $ret .= <<<LINKEDIN
                    <li><a class="share-in share-popup" target="_blank" rel="nofollow noopener noreferrer" title="$href_title$a11y" href="$share_url"><span>$href_text</span></a></li>
                    LINKEDIN;
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
                $ret .= <<<MASTODON
                    <li><a class="share-mastodon share-popup" target="_blank" rel="nofollow noopener noreferrer" title="$href_title$a11y" href="$share_url"><span>$href_text</span></a></li>
                    MASTODON;
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
                $ret .= <<<MAILLINK
                    <li><a class="share-mail" target="_blank" rel="nofollow noopener noreferrer" title="$href_title$a11y" href="$share_url"><span>$href_text</span></a></li>
                    MAILLINK;
            }

            $ret .= '</ul>' . "\n" .
                '</div>' . "\n";
        }

        return $ret;
    }

    public static function customStyle()
    {
        $settings = My::settings();

        return $settings->style;
    }
}
