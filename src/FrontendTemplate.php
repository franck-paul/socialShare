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

class FrontendTemplate
{
    public static function tplSocialShare($attr)
    {
        $ret      = '';
        $settings = My::settings();
        if ($settings->active && $settings->template_tag) {
            $f   = dcCore::app()->tpl->getFilters($attr);
            $ret = '<?php echo ' . FrontendHelper::class . '::socialShare(' .
            sprintf($f, 'dcCore::app()->ctx->posts->getURL()') . ',' .
            sprintf($f, 'dcCore::app()->ctx->posts->post_title') . ',' .
            sprintf($f, '(dcCore::app()->ctx->posts->post_lang ?: dcCore::app()->blog->settings->system->lang)') . ',' .
                'dcCore::app()->blog->settings->' . My::id() . '->prefix' . ',' .
                'dcCore::app()->blog->settings->' . My::id() . '->twitter_account' . ',' .
                'dcCore::app()->blog->settings->' . My::id() . '->intro' .
                '); ?>' . "\n";
        }

        return $ret;
    }
}
