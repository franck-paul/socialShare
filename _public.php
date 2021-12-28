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

$core->addBehavior('publicHeadContent', ['dcSocialShare', 'publicHeadContent']);
$core->addBehavior('publicFooterContent', ['dcSocialShare', 'publicFooterContent']);

$core->addBehavior('publicEntryBeforeContent', ['dcSocialShare', 'publicEntryBeforeContent']);
$core->addBehavior('publicEntryAfterContent', ['dcSocialShare', 'publicEntryAfterContent']);

$core->tpl->addValue('SocialShare', ['dcSocialShare', 'tplSocialShare']);

class dcSocialShare
{
    public static function publicEntryBeforeContent($core, $_ctx)
    {
        if ($core->blog->settings->socialShare->active) {
            if (($_ctx->posts->post_type == 'post' && $core->blog->settings->socialShare->on_post) || ($_ctx->posts->post_type == 'page' && $core->blog->settings->socialShare->on_page)) {
                if ((($core->url->type == 'post' || $core->url->type == 'page') && $core->blog->settings->socialShare->on_single_only) || (!$core->blog->settings->socialShare->on_single_only)) {
                    if ($core->blog->settings->socialShare->before_content) {
                        echo self::socialShare(
                            $_ctx->posts->getURL(),
                            $_ctx->posts->post_title,
                            ($_ctx->posts->post_lang ?: $core->blog->settings->system->lang),
                            $core->blog->settings->socialShare->prefix,
                            $core->blog->settings->socialShare->twitter_account,
                            $core->blog->settings->socialShare->intro
                        );
                    }
                }
            }
        }
    }

    public static function publicEntryAfterContent($core, $_ctx)
    {
        if ($core->blog->settings->socialShare->active) {
            if (($_ctx->posts->post_type == 'post' && $core->blog->settings->socialShare->on_post) || ($_ctx->posts->post_type == 'page' && $core->blog->settings->socialShare->on_page)) {
                if ((($core->url->type == 'post' || $core->url->type == 'page') && $core->blog->settings->socialShare->on_single_only) || (!$core->blog->settings->socialShare->on_single_only)) {
                    if ($core->blog->settings->socialShare->after_content) {
                        echo self::socialShare(
                            $_ctx->posts->getURL(),
                            $_ctx->posts->post_title,
                            ($_ctx->posts->post_lang ?: $core->blog->settings->system->lang),
                            $core->blog->settings->socialShare->prefix,
                            $core->blog->settings->socialShare->twitter_account,
                            $core->blog->settings->socialShare->intro
                        );
                    }
                }
            }
        }
    }

    public static function tplSocialShare($attr)
    {
        global $core;

        $ret = '';
        if ($core->blog->settings->socialShare->active && $core->blog->settings->socialShare->template_tag) {
            $f   = $core->tpl->getFilters($attr);
            $ret = '<?php echo dcSocialShare::socialShare(' .
            sprintf($f, '$_ctx->posts->getURL()') . ',' .
            sprintf($f, '$_ctx->posts->post_title') . ',' .
            sprintf($f, '($_ctx->posts->post_lang ?: $core->blog->settings->system->lang)') . ',' .
                '$core->blog->settings->socialShare->prefix' . ',' .
                '$core->blog->settings->socialShare->twitter_account' . ',' .
                '$core->blog->settings->socialShare->intro' .
                '); ?>' . "\n";
        }

        return $ret;
    }

    public static function publicHeadContent()
    {
        global $core;

        $core->blog->settings->addNamespace('socialShare');
        if ($core->blog->settings->socialShare->active) {
            switch ($core->blog->settings->socialShare->use_style) {
                case 0: // Default CSS styles
                    echo dcUtils::cssLoad($core->blog->getPF('socialShare/css/default.css'));

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
        global $core;

        $core->blog->settings->addNamespace('socialShare');
        if ($core->blog->settings->socialShare->active) {
            echo dcUtils::jsLoad($core->blog->getPF('socialShare/js/popup.js'));
        }
    }

    // Helpers

    public static function socialShare($url, $title, $lang, $prefix, $twitter_account, $intro = '')
    {
        $ret = '';

        // Twitter does not like pipe in text, may be another characters?
        $filter = fn ($text) => str_replace(['|'], ['-'], $text);

        if ($GLOBALS['core']->blog->settings->socialShare->twitter || $GLOBALS['core']->blog->settings->socialShare->facebook || $GLOBALS['core']->blog->settings->socialShare->linkedin || $GLOBALS['core']->blog->settings->socialShare->mastodon || $GLOBALS['core']->blog->settings->socialShare->mail) {
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
            if ($GLOBALS['core']->blog->settings->socialShare->tags && isset($GLOBALS['_ctx']->posts->post_meta)) {
                $meta = $GLOBALS['core']->meta->getMetaRecordset($GLOBALS['_ctx']->posts->post_meta, 'tag');
                $meta->sort('meta_id_lower', 'asc');
                while ($meta->fetch()) {
                    $tags .= '%20%23' . $meta->meta_id; // space + # + tag
                }
            }

            // Twitter link
            if ($GLOBALS['core']->blog->settings->socialShare->twitter) {
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
            if ($GLOBALS['core']->blog->settings->socialShare->facebook) {
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
            if ($GLOBALS['core']->blog->settings->socialShare->linkedin) {
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
            if ($GLOBALS['core']->blog->settings->socialShare->mastodon) {
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
            if ($GLOBALS['core']->blog->settings->socialShare->mail) {
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
        $s = $GLOBALS['core']->blog->settings->socialShare->style;

        return $s;
    }
}
