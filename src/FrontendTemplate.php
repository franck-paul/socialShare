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

use ArrayObject;
use Dotclear\Plugin\TemplateHelper\Code;

class FrontendTemplate
{
    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr   The attribute
     */
    public static function SocialShare(array|ArrayObject $attr): string
    {
        $settings = My::settings();
        if ($settings->getBool('active') && $settings->getBool('template_tag')) {
            return Code::getPHPTemplateValueCode(
                FrontendTemplateCode::SocialShare(...),
                [
                    $settings->getStr('prefix', false),
                    $settings->getStr('twitter_account', false),
                    $settings->getStr('intro', false),
                ],
                $attr,
            );
        }

        return '';
    }
}
