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

use ArrayObject;
use dcCore;

class FrontendTemplate
{
    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr   The attribute
     *
     * @return     string
     */
    public static function tplSocialShare(array|ArrayObject $attr): string
    {
        $ret      = '';
        $settings = My::settings();
        if ($settings->active && $settings->template_tag) {
            $f   = dcCore::app()->tpl->getFilters($attr);
            $ret = '<?php echo ' . FrontendHelper::class . '::socialShare(' .
            sprintf($f, 'dcCore::app()->ctx->posts->getURL()') . ',' .
            sprintf($f, 'dcCore::app()->ctx->posts->post_title') . ',' .
            sprintf($f, '(dcCore::app()->ctx->posts->post_lang ?: App::blog()->settings()->system->lang)') . ',' .
                'App::blog()->settings()->' . My::id() . '->prefix' . ',' .
                'App::blog()->settings()->' . My::id() . '->twitter_account' . ',' .
                'App::blog()->settings()->' . My::id() . '->intro' .
                '); ?>' . "\n";
        }

        return $ret;
    }
}
