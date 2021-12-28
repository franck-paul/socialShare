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
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

// dead but useful code, in order to have translations
__('socialShare') . __('Add social networks sharing buttons to your posts and pages');

$_menu['Blog']->addItem(
    __('socialShare'),
    'plugin.php?p=socialShare',
    urldecode(dcPage::getPF('socialShare/icon.png')),
    preg_match('/plugin.php\?p=socialShare(&.*)?$/', $_SERVER['REQUEST_URI']),
    $core->auth->check('admin', $core->blog->id)
);
