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
$this->registerModule(
    'socialShare',
    'Add social networks sharing buttons to your posts and pages',
    'Franck Paul, Kozlika',
    '7.5',
    [
        'date'     => '2025-04-19T16:52:31+0200',
        'requires' => [
            ['TemplateHelper'],
            ['core', '2.34'],
        ],
        'permissions' => 'My',
        'type'        => 'plugin',

        'details'    => 'https://open-time.net/?q=socialShare',
        'support'    => 'https://github.com/franck-paul/socialShare',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/socialShare/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
