<?php

/**
 * @brief socialShare, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul
 *
 * @copyright Franck Paul contact@open-time.net
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
$this->registerModule(
    'socialShare',
    'Add social networks sharing buttons to your posts and pages',
    'Franck Paul, Kozlika',
    '9.0',
    [
        'date'     => '2026-08-03T10:11:14+0200',
        'requires' => [
            ['TemplateHelper'],
            ['core', '2.39'],
        ],
        'permissions' => 'My',
        'type'        => 'plugin',

        'details'    => 'https://open-time.net/?q=socialShare',
        'support'    => 'https://github.com/franck-paul/socialShare',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/socialShare/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
