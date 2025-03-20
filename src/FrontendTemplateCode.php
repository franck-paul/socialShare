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

class FrontendTemplateCode
{
    /**
     * PHP code for tpl:SocialShare value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function SocialShare(
        string $_prefix_,
        string $_twitter_account_,
        string $_intro_,
        array $_params_,
        string $_tag_,
    ): void {
        $socialshare_buffer = \Dotclear\Plugin\socialShare\FrontendHelper::socialShare(
            App::frontend()->context()->posts->getURL(),
            App::frontend()->context()->posts->post_title,
            $_prefix_,
            $_twitter_account_,
            $_intro_
        );
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            $socialshare_buffer,
            $_params_,
            $_tag_
        );
        unset($socialshare_buffer);
    }
}
