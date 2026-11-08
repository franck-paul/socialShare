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
declare(strict_types=1);

if (isset($this) && is_object($this) && method_exists($this, 'registerModule') && isset($this->id) && is_string($this->id)) {
    $this->registerModule(
        'socialShare',
        'Add social networks sharing buttons to your posts and pages',
        'Franck Paul, Kozlika',
        '10.0',
        [
            'date'     => '2026-11-08T10:23:05+0100',
            'requires' => [
                ['TemplateHelper'],
                ['core', '2.40'],
            ],
            'permissions' => 'My',
            'type'        => 'plugin',

            'details'    => 'https://open-time.net/?q=socialShare',
            'support'    => 'https://github.com/franck-paul/socialShare',
            'repository' => 'https://raw.githubusercontent.com/franck-paul/socialShare/main/dcstore.xml',
            'license'    => 'gpl2',
        ]
    );
}
