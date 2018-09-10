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

if (!defined('DC_RC_PATH')) {return;}

$this->registerModule(
    "socialShare",                                                 // Name
    "Add social networks sharing buttons to your posts and pages", // Description
    "Franck Paul, Kozlika",                                        // Author
    '0.9',                                                         // Version
    array(
        'requires'    => array(array('core', '2.9')),            // Dependencies
        'permissions' => 'admin',                                // Permissions
        'support'     => 'https://open-time.net/?q=socialShare', // Support URL
        'type'        => 'plugin'                                // Type
    )
);
