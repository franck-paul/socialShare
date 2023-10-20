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
use Dotclear\Core\Backend\Notices;
use Dotclear\Core\Backend\Page;
use Dotclear\Core\Process;
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
use Exception;

class Manage extends Process
{
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

                $settings->put('twitter', true, App::blogWorkspace()::NS_BOOL, 'Add Twitter button', false);
                $settings->put('facebook', true, App::blogWorkspace()::NS_BOOL, 'Add Facebook button', false);
                $settings->put('linkedin', true, App::blogWorkspace()::NS_BOOL, 'Add LinkedIn button', false);
                $settings->put('mastodon', true, App::blogWorkspace()::NS_BOOL, 'Add Mastodon button', false);
                $settings->put('mail', true, App::blogWorkspace()::NS_BOOL, 'Add mail button', false);

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

                $settings->put('twitter_account', '', App::blogWorkspace()::NS_STRING, 'Twitter account to use with Twitter button', false);

                App::blog()->triggerBlog();
                My::redirect();
            } catch (Exception $e) {
                App::error()->add($e->getMessage());
            }
        }

        if (!empty($_POST)) {
            try {
                $ssb_active = !empty($_POST['ssb_active']);

                $ssb_twitter  = !empty($_POST['ssb_twitter']);
                $ssb_facebook = !empty($_POST['ssb_facebook']);
                $ssb_linkedin = !empty($_POST['ssb_linkedin']);
                $ssb_mastodon = !empty($_POST['ssb_mastodon']);
                $ssb_mail     = !empty($_POST['ssb_mail']);

                $ssb_on_post = !empty($_POST['ssb_on_post']);
                $ssb_on_page = !empty($_POST['ssb_on_page']);

                $ssb_on_single_only = !empty($_POST['ssb_on_single_only']);

                $ssb_before_content = !empty($_POST['ssb_before_content']);
                $ssb_after_content  = !empty($_POST['ssb_after_content']);
                $ssb_template_tag   = !empty($_POST['ssb_template_tag']);

                $ssb_prefix    = trim(Html::escapeHTML($_POST['ssb_prefix']));
                $ssb_intro     = trim(Html::escapeHTML($_POST['ssb_intro']));
                $ssb_tags      = !empty($_POST['ssb_tags']);
                $ssb_use_style = abs((int) $_POST['ssb_use_style']);
                $ssb_style     = trim((string) $_POST['ssb_style']);

                $ssb_twitter_account = trim(ltrim(Html::escapeHTML($_POST['ssb_twitter_account']), '@'));

                // Everything's fine, save options

                $settings->put('active', $ssb_active, App::blogWorkspace()::NS_BOOL);

                $settings->put('twitter', $ssb_twitter, App::blogWorkspace()::NS_BOOL);
                $settings->put('facebook', $ssb_facebook, App::blogWorkspace()::NS_BOOL);
                $settings->put('linkedin', $ssb_linkedin, App::blogWorkspace()::NS_BOOL);
                $settings->put('mastodon', $ssb_mastodon, App::blogWorkspace()::NS_BOOL);
                $settings->put('mail', $ssb_mail, App::blogWorkspace()::NS_BOOL);

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

                Notices::addSuccessNotice(__('Settings have been successfully updated.'));
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

        $ssb_active = (bool) $settings->active;

        $ssb_twitter  = (bool) $settings->twitter;
        $ssb_facebook = (bool) $settings->facebook;
        $ssb_linkedin = (bool) $settings->linkedin;
        $ssb_mastodon = (bool) $settings->mastodon;
        $ssb_mail     = (bool) $settings->mail;

        $ssb_on_post = (bool) $settings->on_post;
        $ssb_on_page = (bool) $settings->on_page;

        $ssb_on_single_only = (bool) $settings->on_single_only;

        $ssb_before_content = (bool) $settings->before_content;
        $ssb_after_content  = (bool) $settings->after_content;
        $ssb_template_tag   = (bool) $settings->template_tag;

        $ssb_prefix    = (string) $settings->prefix;
        $ssb_intro     = (string) $settings->intro;
        $ssb_tags      = (bool) $settings->tags;
        $ssb_use_style = (int) $settings->use_style;
        $ssb_style     = (string) $settings->style;

        $ssb_twitter_account = (string) $settings->twitter_account;

        $ssb_use_styles = [
            0 => __('Use default CSS styles'),
            1 => __('Use theme\'s CSS styles'),
            2 => __('Use user-defined styles'),
        ];
        $radio_styles = [];
        $i            = 0;
        foreach ($ssb_use_styles as $k => $v) {
            $radio_styles[] = (new Para())->items([
                (new Radio(['ssb_use_style', 'ssb_use_style_' . $i], $ssb_use_style == $k))
                    ->value($k)
                    ->label((new Label($v, Label::INSIDE_TEXT_AFTER))),
            ]);
            $i++;
        }

        Page::openModule(My::name());

        echo Page::breadcrumb(
            [
                Html::escapeHTML(App::blog()->name()) => '',
                __('socialShare')                     => '',
            ]
        );
        echo Notices::getNotices();

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
                        ->label((new Label(__('Add Twitter sharing button'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Input('ssb_twitter_account'))
                        ->size(30)
                        ->maxlength(128)
                        ->value(Html::escapeHTML($ssb_twitter_account))
                        ->label((new Label(__('Twitter account:'), Label::INSIDE_TEXT_BEFORE))),
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
                    (new Checkbox('ssb_mail', $ssb_mail))
                        ->value(1)
                        ->label((new Label(__('Add Mail sharing button'), Label::INSIDE_TEXT_AFTER))),
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
                    (new Text(null, __('Only for Twitter and Mastodon buttons.'))),
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

        Page::closeModule();
    }
}
