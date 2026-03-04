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
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Radio;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Form\Textarea;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Process\TraitProcess;
use Exception;

class Manage
{
    use TraitProcess;

    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        return self::status(My::checkContext(My::MANAGE));
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        $settings = My::settings();
        if (is_null($settings->active)) {
            try {
                // Add default settings values if necessary
                $settings->put('active', false, App::blogWorkspace()::NS_BOOL, 'Active', false);

                $settings->put('twitter', true, App::blogWorkspace()::NS_BOOL, 'Add Twitter/X button', false);
                $settings->put('facebook', true, App::blogWorkspace()::NS_BOOL, 'Add Facebook button', false);
                $settings->put('linkedin', true, App::blogWorkspace()::NS_BOOL, 'Add LinkedIn button', false);
                $settings->put('mastodon', true, App::blogWorkspace()::NS_BOOL, 'Add Mastodon button', false);
                $settings->put('bluesky', true, App::blogWorkspace()::NS_BOOL, 'Add Bluesky button', false);
                $settings->put('mail', true, App::blogWorkspace()::NS_BOOL, 'Add mail button', false);
                $settings->put('menu', true, App::blogWorkspace()::NS_BOOL, 'Add share menu button', false);

                $settings->put('on_post', true, App::blogWorkspace()::NS_BOOL, 'Add social sharing buttons on post', false);
                $settings->put('on_page', false, App::blogWorkspace()::NS_BOOL, 'Add social sharing buttons on page', false);

                $settings->put('on_single_only', true, App::blogWorkspace()::NS_BOOL, 'Display social sharing buttons on single display only (post or page)', false);

                $settings->put('before_content', false, App::blogWorkspace()::NS_BOOL, 'Display social sharing buttons before content', false);
                $settings->put('after_content', true, App::blogWorkspace()::NS_BOOL, 'Display social sharing buttons after content', false);
                $settings->put('template_tag', false, App::blogWorkspace()::NS_BOOL, 'Display social sharing buttons using template tag', false);

                $settings->put('prefix', __('Share this entry:'), App::blogWorkspace()::NS_STRING, 'Social sharing buttons prefix text', false);
                $settings->put('intro', '', App::blogWorkspace()::NS_STRING, 'Title introduction text', false);
                $settings->put('tags', true, App::blogWorkspace()::NS_BOOL, 'Use tags if any', false);
                $settings->put('use_style', 0, App::blogWorkspace()::NS_INT, 'CSS style used', false);
                $settings->put('style', '', App::blogWorkspace()::NS_STRING, 'Social sharing buttons style', false);

                $settings->put('twitter_account', '', App::blogWorkspace()::NS_STRING, 'Twitter/X account to use with Twitter/X button', false);

                App::blog()->triggerBlog();
                My::redirect();
            } catch (Exception $e) {
                App::error()->add($e->getMessage());
            }
        }

        if ($_POST !== []) {
            try {
                // Post data helpers
                $_Bool = fn (string $name): bool => !empty($_POST[$name]);
                $_Int  = fn (string $name, int $default = 0): int => isset($_POST[$name]) && is_numeric($val = $_POST[$name]) ? (int) $val : $default;
                $_Str  = fn (string $name, string $default = ''): string => isset($_POST[$name]) && is_string($val = $_POST[$name]) ? $val : $default;

                $ssb_active = $_Bool('ssb_active');

                $ssb_twitter  = $_Bool('ssb_twitter');
                $ssb_facebook = $_Bool('ssb_facebook');
                $ssb_linkedin = $_Bool('ssb_linkedin');
                $ssb_mastodon = $_Bool('ssb_mastodon');
                $ssb_bluesky  = $_Bool('ssb_bluesky');
                $ssb_mail     = $_Bool('ssb_mail');
                $ssb_menu     = $_Bool('ssb_menu');

                $ssb_on_post = $_Bool('ssb_on_post');
                $ssb_on_page = $_Bool('ssb_on_page');

                $ssb_on_single_only = $_Bool('ssb_on_single_only');

                $ssb_before_content = $_Bool('ssb_before_content');
                $ssb_after_content  = $_Bool('ssb_after_content');
                $ssb_template_tag   = $_Bool('ssb_template_tag');

                $ssb_prefix    = trim(Html::escapeHTML($_Str('ssb_prefix')));
                $ssb_intro     = trim(Html::escapeHTML($_Str('ssb_intro')));
                $ssb_tags      = $_Bool('ssb_tags');
                $ssb_use_style = abs($_Int('ssb_use_style'));
                $ssb_style     = trim($_Str('ssb_style'));

                $ssb_twitter_account = trim(ltrim(Html::escapeHTML($_Str('ssb_twitter_account')), '@'));

                // Everything's fine, save options

                $settings->put('active', $ssb_active, App::blogWorkspace()::NS_BOOL);

                $settings->put('twitter', $ssb_twitter, App::blogWorkspace()::NS_BOOL);
                $settings->put('facebook', $ssb_facebook, App::blogWorkspace()::NS_BOOL);
                $settings->put('linkedin', $ssb_linkedin, App::blogWorkspace()::NS_BOOL);
                $settings->put('mastodon', $ssb_mastodon, App::blogWorkspace()::NS_BOOL);
                $settings->put('bluesky', $ssb_bluesky, App::blogWorkspace()::NS_BOOL);
                $settings->put('mail', $ssb_mail, App::blogWorkspace()::NS_BOOL);
                $settings->put('menu', $ssb_menu, App::blogWorkspace()::NS_BOOL);

                $settings->put('on_post', $ssb_on_post, App::blogWorkspace()::NS_BOOL);
                $settings->put('on_page', $ssb_on_page, App::blogWorkspace()::NS_BOOL);

                $settings->put('on_single_only', $ssb_on_single_only, App::blogWorkspace()::NS_BOOL);

                $settings->put('before_content', $ssb_before_content, App::blogWorkspace()::NS_BOOL);
                $settings->put('after_content', $ssb_after_content, App::blogWorkspace()::NS_BOOL);
                $settings->put('template_tag', $ssb_template_tag, App::blogWorkspace()::NS_BOOL);

                $settings->put('prefix', $ssb_prefix, App::blogWorkspace()::NS_STRING);
                $settings->put('intro', $ssb_intro, App::blogWorkspace()::NS_STRING);
                $settings->put('tags', $ssb_tags, App::blogWorkspace()::NS_BOOL);
                $settings->put('use_style', $ssb_use_style, App::blogWorkspace()::NS_INT);
                $settings->put('style', $ssb_style, App::blogWorkspace()::NS_STRING);

                $settings->put('twitter_account', $ssb_twitter_account, App::blogWorkspace()::NS_STRING);

                App::blog()->triggerBlog();

                App::backend()->notices()->addSuccessNotice(__('Settings have been successfully updated.'));
                My::redirect();
            } catch (Exception $e) {
                App::error()->add($e->getMessage());
            }
        }

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        $settings = My::settings();

        // Settings data helpers
        $_Bool = fn (mixed $setting): bool => (bool) $setting;
        $_Int  = fn (mixed $setting, int $default = 0): int => $setting !== null && is_numeric($val = $setting) ? (int) $val : $default;
        $_Str  = fn (mixed $setting, string $default = ''): string => $setting !== null && is_string($val = $setting) ? $val : $default;

        $ssb_active = $_Bool($settings->active);

        $ssb_twitter  = $_Bool($settings->twitter);
        $ssb_facebook = $_Bool($settings->facebook);
        $ssb_linkedin = $_Bool($settings->linkedin);
        $ssb_mastodon = $_Bool($settings->mastodon);
        $ssb_bluesky  = $_Bool($settings->bluesky);
        $ssb_mail     = $_Bool($settings->mail);
        $ssb_menu     = $_Bool($settings->menu);

        $ssb_on_post = $_Bool($settings->on_post);
        $ssb_on_page = $_Bool($settings->on_page);

        $ssb_on_single_only = $_Bool($settings->on_single_only);

        $ssb_before_content = $_Bool($settings->before_content);
        $ssb_after_content  = $_Bool($settings->after_content);
        $ssb_template_tag   = $_Bool($settings->template_tag);

        $ssb_prefix    = $_Str($settings->prefix);
        $ssb_intro     = $_Str($settings->intro);
        $ssb_tags      = $_Bool($settings->tags);
        $ssb_use_style = $_Int($settings->use_style);
        $ssb_style     = $_Str($settings->style);

        $ssb_twitter_account = $_Str($settings->twitter_account);

        $ssb_use_styles = [
            0 => __('Use default CSS styles'),
            1 => __('Use theme\'s CSS styles'),
            2 => __('Use user-defined styles'),
        ];
        $radio_styles = [];
        $i            = 0;
        foreach ($ssb_use_styles as $k => $v) {
            $radio_styles[] = (new Para())->items([
                (new Radio(['ssb_use_style', 'ssb_use_style_' . $i], $ssb_use_style === $k))
                    ->value($k)
                    ->label((new Label($v, Label::INSIDE_TEXT_AFTER))),
            ]);
            ++$i;
        }

        App::backend()->page()->openModule(My::name());

        echo App::backend()->page()->breadcrumb(
            [
                Html::escapeHTML(App::blog()->name()) => '',
                __('socialShare')                     => '',
            ]
        );
        echo App::backend()->notices()->getNotices();

        // Form
        echo (new Form('frmsettings'))
            ->action(App::backend()->getPageURL())
            ->method('post')
            ->fields([
                (new Para())->items([
                    (new Checkbox('ssb_active', $ssb_active))
                        ->value(1)
                        ->label((new Label(__('Activate socialShare plugin'), Label::INSIDE_TEXT_AFTER))),
                ]),

                (new Text('h3', __('Buttons'))),
                (new Para())->items([
                    (new Checkbox('ssb_twitter', $ssb_twitter))
                        ->value(1)
                        ->label((new Label(__('Add Twitter/X sharing button'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Input('ssb_twitter_account'))
                        ->size(30)
                        ->maxlength(128)
                        ->value(Html::escapeHTML($ssb_twitter_account))
                        ->label((new Label(__('Twitter/X account:'), Label::INSIDE_TEXT_BEFORE))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, __('This will be used as "via" in tweet rather than the blog name (if not empty).'))),
                ]),
                (new Para())->items([
                    (new Checkbox('ssb_facebook', $ssb_facebook))
                        ->value(1)
                        ->label((new Label(__('Add Facebook sharing button'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('ssb_linkedin', $ssb_linkedin))
                        ->value(1)
                        ->label((new Label(__('Add LinkedIn sharing button'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('ssb_mastodon', $ssb_mastodon))
                        ->value(1)
                        ->label((new Label(__('Add Mastodon sharing button'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('ssb_bluesky', $ssb_bluesky))
                        ->value(1)
                        ->label((new Label(__('Add Bluesky sharing button'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('ssb_mail', $ssb_mail))
                        ->value(1)
                        ->label((new Label(__('Add Mail sharing button'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('ssb_menu', $ssb_menu))
                        ->value(1)
                        ->label((new Label(__(' Add a button for the browser\'s share menu'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, __('This button may not be available depending on your browser capabilities and settings.'))),
                ]),

                (new Text('h3', __('Options'))),
                (new Para())->items([
                    (new Checkbox('ssb_on_post', $ssb_on_post))
                        ->value(1)
                        ->label((new Label(__('Add social sharing buttons on posts'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('ssb_on_page', $ssb_on_page))
                        ->value(1)
                        ->label((new Label(__('Add social sharing buttons on pages'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('ssb_on_single_only', $ssb_on_single_only))
                        ->value(1)
                        ->label((new Label(__('Add social sharing buttons only on single display (post or page)'), Label::INSIDE_TEXT_AFTER))),
                ]),

                (new Text('h3', __('Position'))),
                (new Para())->items([
                    (new Checkbox('ssb_before_content', $ssb_before_content))
                        ->value(1)
                        ->label((new Label(__('Automatically add social sharing buttons before content'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('ssb_after_content', $ssb_after_content))
                        ->value(1)
                        ->label((new Label(__('Automatically add social sharing buttons after content'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('ssb_template_tag', $ssb_template_tag))
                        ->value(1)
                        ->label((new Label(__('Add social sharing buttons using template tag'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, __('The {{tpl:SocialShare}} template tag must be present in your template\'s file(s).'))),
                ]),

                (new Text('h3', __('Advanced options'))),
                (new Para())->items([
                    (new Input('ssb_prefix'))
                        ->size(30)
                        ->maxlength(128)
                        ->value(Html::escapeHTML($ssb_prefix))
                        ->label((new Label(__('Social sharing buttons text prefix:'), Label::OUTSIDE_TEXT_BEFORE))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, __('This will be inserted before buttons (if not empty).'))),
                ]),
                (new Para())->items([
                    (new Input('ssb_intro'))
                        ->size(30)
                        ->maxlength(128)
                        ->value(Html::escapeHTML($ssb_intro))
                        ->label((new Label(__('Title introduction text:'), Label::OUTSIDE_TEXT_BEFORE))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, __('This will be inserted before title (if not empty).'))),
                ]),
                (new Para())->items([
                    (new Checkbox('ssb_tags', $ssb_tags))
                        ->value(1)
                        ->label((new Label(__('Use tags if any'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, __('Only for Twitter/X, Bluesky and Mastodon buttons.'))),
                ]),

                (new Text('h3', __('Social sharing buttons CSS styles'))),
                ...$radio_styles,
                (new Para())->class('area')->items([
                    (new Textarea('ssb_style'))
                        ->cols(30)
                        ->rows(8)
                        ->value(Html::escapeHTML($ssb_style))
                        ->label((new Label(__('User defined CSS styles:'), Label::OUTSIDE_TEXT_BEFORE))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, __('See the README.md file for HTML markup and example of CSS styles.'))),
                ]),

                (new Para())->items([
                    (new Submit(['frmsubmit']))
                        ->value(__('Save')),
                    ... My::hiddenFields(),
                ]),
            ])
        ->render();

        App::backend()->page()->closeModule();
    }
}
