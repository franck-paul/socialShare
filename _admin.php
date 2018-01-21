<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of socialShare, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) {return;}

// dead but useful code, in order to have translations
__('socialShare') . __('Add social networks sharing buttons to your posts and pages');

$_menu['Blog']->addItem(__('socialShare'),
    'plugin.php?p=socialShare',
    urldecode(dcPage::getPF('socialShare/icon.png')),
    preg_match('/plugin.php\?p=socialShare(&.*)?$/', $_SERVER['REQUEST_URI']),
    $core->auth->check('admin', $core->blog->id));
