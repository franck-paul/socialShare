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
if (!defined('DC_RC_PATH')) {
    return;
}

class dcSocialShare
{
    public static function publicEntryBeforeContent()
    {
        if (dcCore::app()->blog->settings->socialShare->active) {
            if ((dcCore::app()->ctx->posts->post_type == 'post' && dcCore::app()->blog->settings->socialShare->on_post) || (dcCore::app()->ctx->posts->post_type == 'page' && dcCore::app()->blog->settings->socialShare->on_page)) {
                if (((dcCore::app()->url->type == 'post' || dcCore::app()->url->type == 'page') && dcCore::app()->blog->settings->socialShare->on_single_only) || (!dcCore::app()->blog->settings->socialShare->on_single_only)) {
                    if (dcCore::app()->blog->settings->socialShare->before_content) {
                        echo self::socialShare(
                            dcCore::app()->ctx->posts->getURL(),
                            dcCore::app()->ctx->posts->post_title,
                            (dcCore::app()->ctx->posts->post_lang ?: dcCore::app()->blog->settings->system->lang),
                            dcCore::app()->blog->settings->socialShare->prefix,
                            dcCore::app()->blog->settings->socialShare->twitter_account,
                            dcCore::app()->blog->settings->socialShare->intro
                        );
                    }
                }
            }
        }
    }

    public static function publicEntryAfterContent()
    {
        if (dcCore::app()->blog->settings->socialShare->active) {
            if ((dcCore::app()->ctx->posts->post_type == 'post' && dcCore::app()->blog->settings->socialShare->on_post) || (dcCore::app()->ctx->posts->post_type == 'page' && dcCore::app()->blog->settings->socialShare->on_page)) {
                if (((dcCore::app()->url->type == 'post' || dcCore::app()->url->type == 'page') && dcCore::app()->blog->settings->socialShare->on_single_only) || (!dcCore::app()->blog->settings->socialShare->on_single_only)) {
                    if (dcCore::app()->blog->settings->socialShare->after_content) {
                        echo self::socialShare(
                            dcCore::app()->ctx->posts->getURL(),
                            dcCore::app()->ctx->posts->post_title,
                            (dcCore::app()->ctx->posts->post_lang ?: dcCore::app()->blog->settings->system->lang),
                            dcCore::app()->blog->settings->socialShare->prefix,
                            dcCore::app()->blog->settings->socialShare->twitter_account,
                            dcCore::app()->blog->settings->socialShare->intro
                        );
                    }
                }
            }
        }
    }

    public static function tplSocialShare($attr)
    {
        $ret = '';
        if (dcCore::app()->blog->settings->socialShare->active && dcCore::app()->blog->settings->socialShare->template_tag) {
            $f   = dcCore::app()->tpl->getFilters($attr);
            $ret = '<?php echo dcSocialShare::socialShare(' .
            sprintf($f, 'dcCore::app()->ctx->posts->getURL()') . ',' .
            sprintf($f, 'dcCore::app()->ctx->posts->post_title') . ',' .
            sprintf($f, '(dcCore::app()->ctx->posts->post_lang ?: dcCore::app()->blog->settings->system->lang)') . ',' .
                'dcCore::app()->blog->settings->socialShare->prefix' . ',' .
                'dcCore::app()->blog->settings->socialShare->twitter_account' . ',' .
                'dcCore::app()->blog->settings->socialShare->intro' .
                '); ?>' . "\n";
        }

        return $ret;
    }

    public static function publicHeadContent()
    {
        dcCore::app()->blog->settings->addNamespace('socialShare');
        if (dcCore::app()->blog->settings->socialShare->active) {
            switch (dcCore::app()->blog->settings->socialShare->use_style) {
                case 0: // Default CSS styles
                    echo dcUtils::cssModuleLoad('socialShare/css/default.css');

                    break;
                case 1: // Blog's theme CSS styles

                    break;
                case 2: // User defined CSS styles
                    echo '<style type="text/css">' . "\n" . self::customStyle() . "\n" . "</style>\n";

                    break;
            }
        }
    }

    public static function publicFooterContent()
    {
        dcCore::app()->blog->settings->addNamespace('socialShare');
        if (dcCore::app()->blog->settings->socialShare->active) {
            echo dcUtils::jsModuleLoad('socialShare/js/popup.js');
        }
    }

    // Helpers

    public static function socialShare($url, $title, $lang, $prefix, $twitter_account, $intro = '')
    {
        $ret = '';

        // Twitter does not like pipe in text, may be another characters?
        $filter = fn ($text) => str_replace(['|'], ['-'], $text);

        if (dcCore::app()->blog->settings->socialShare->twitter || dcCore::app()->blog->settings->socialShare->facebook || dcCore::app()->blog->settings->socialShare->linkedin || dcCore::app()->blog->settings->socialShare->mastodon || dcCore::app()->blog->settings->socialShare->mail) {
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
            if (dcCore::app()->blog->settings->socialShare->tags && isset(dcCore::app()->ctx->posts->post_meta)) {
                $meta = dcCore::app()->meta->getMetaRecordset(dcCore::app()->ctx->posts->post_meta, 'tag');
                $meta->sort('meta_id_lower', 'asc');
                while ($meta->fetch()) {
                    $tags .= '%20%23' . $meta->meta_id; // space + # + tag
                }
            }

            // Twitter link
            if (dcCore::app()->blog->settings->socialShare->twitter) {
                $share_url = sprintf(
                    'https://twitter.com/share?url=%s&amp;text=%s',
                    html::sanitizeURL($url),
                    html::escapeHTML($filter($text) . $tags)
                );
                if ($twitter_account != '') {
                    $share_url .= '&amp;via=' . html::escapeHTML($twitter_account);
                }
                $href_text  = __('Twitter');
                $href_title = __('Share this on Twitter');
                $ret .= <<<TWITTER
                    <li><a class="share-twitter share-popup" target="_blank" rel="nofollow noopener noreferrer" title="$href_title$a11y" href="$share_url"><span>$href_text</span></a></li>
                    TWITTER;
            }

            // Facebook link
            if (dcCore::app()->blog->settings->socialShare->facebook) {
                $share_url = sprintf(
                    'https://www.facebook.com/sharer.php?u=%s&amp;t=%s',
                    html::sanitizeURL($url),
                    html::escapeHTML($text)
                );
                $href_text  = __('Facebook');
                $href_title = __('Share this on Facebook');
                $ret .= <<<FACEBOOK
                    <li><a class="share-fb share-popup" target="_blank" rel="nofollow noopener noreferrer" title="$href_title$a11y" href="$share_url"><span>$href_text</span></a></li>
                    FACEBOOK;
            }

            // LinkedIn link
            if (dcCore::app()->blog->settings->socialShare->linkedin) {
                $share_url = sprintf(
                    'https://www.linkedin.com/shareArticle?mini=true&url=%s&title=%s',
                    html::sanitizeURL($url),
                    html::escapeHTML($text)
                );
                $href_text  = __('LinkedIn');
                $href_title = __('Share this on LinkedIn');
                $ret .= <<<LINKEDIN
                    <li><a class="share-in share-popup" target="_blank" rel="nofollow noopener noreferrer" title="$href_title$a11y" href="$share_url"><span>$href_text</span></a></li>
                    LINKEDIN;
            }

            // Mastodon link
            if (dcCore::app()->blog->settings->socialShare->mastodon) {
                $share_url = sprintf(
                    'web+mastodon://share?text=%s+%s',
                    str_replace('&amp;', '%26', html::escapeHTML($text . $tags)),
                    html::sanitizeURL($url)
                );
                $href_text  = __('Mastodon');
                $href_title = __('Share this on Mastodon');
                $ret .= <<<MASTODON
                    <li><a class="share-mastodon share-popup" target="_blank" rel="nofollow noopener noreferrer" title="$href_title$a11y" href="$share_url"><span>$href_text</span></a></li>
                    MASTODON;
            }

            // Mail link
            if (dcCore::app()->blog->settings->socialShare->mail) {
                $share_url = sprintf(
                    'mailto:?subject=%s&amp;body=%s',
                    html::escapeHTML($text),
                    html::sanitizeURL($url)
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
        return dcCore::app()->blog->settings->socialShare->style;
    }
}

dcCore::app()->addBehavior('publicHeadContent', [dcSocialShare::class, 'publicHeadContent']);
dcCore::app()->addBehavior('publicFooterContent', [dcSocialShare::class, 'publicFooterContent']);

dcCore::app()->addBehavior('publicEntryBeforeContent', [dcSocialShare::class, 'publicEntryBeforeContent']);
dcCore::app()->addBehavior('publicEntryAfterContent', [dcSocialShare::class, 'publicEntryAfterContent']);

dcCore::app()->tpl->addValue('SocialShare', [dcSocialShare::class, 'tplSocialShare']);
