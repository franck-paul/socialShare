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

$this->registerModule(
    'socialShare',                                                 // Name
    'Add social networks sharing buttons to your posts and pages', // Description
    'Franck Paul, Kozlika',                                        // Author
    '1.1',                                                         // Version
    [
        'requires'    => [['core', '2.13']],                     // Dependencies
        'permissions' => 'admin',                                // Permissions
        'type'        => 'plugin',                               // Type

        'details'    => 'https://open-time.net/?q=socialShare',       // Details URL
        'support'    => 'https://github.com/franck-paul/socialShare', // Support URL
        'repository' => 'https://raw.githubusercontent.com/franck-paul/socialShare/main/dcstore.xml'
    ]
);
