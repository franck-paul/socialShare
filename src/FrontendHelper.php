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
use Dotclear\Helper\Html\Form\Div;
use Dotclear\Helper\Html\Form\Li;
use Dotclear\Helper\Html\Form\Link;
use Dotclear\Helper\Html\Form\Span;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Form\Ul;
use Dotclear\Helper\Html\Html;

class FrontendHelper
{
    public static function socialShare(string $url, string $title, string $prefix, string $twitter_account, string $intro = ''): string
    {
        // Twitter does not like pipe in text, may be another characters?
        $filter = static fn (string $text): string => str_replace(['|'], ['-'], $text);

        $settings = My::settings();
        if ($settings->twitter || $settings->facebook || $settings->linkedin || $settings->mastodon || $settings->mail || $settings->menu) {
            // Compose text
            $text = ($intro !== '' ? $intro . '%20' : '') . $title;
            $a11y = __(' (new window)');

            // Lookup for tags on entry
            $tags     = '';
            $tag_list = [];
            if ($settings->tags && isset(App::frontend()->context()->posts->post_meta)) {
                $meta = App::meta()->getMetaRecordset(App::frontend()->context()->posts->post_meta, 'tag');
                $meta->sort('meta_id_lower', 'asc');
                while ($meta->fetch()) {
                    $tag_list[] = $meta->meta_id;
                    $tags .= '%20%23' . $meta->meta_id; // space + # + tag
                }
            }

            // Compose links
            $links = [];
            if ($prefix !== '') {
                $links[] = (new Li())
                    ->class('share-intro')
                    ->text($prefix);
            }

            // Twitter link
            if ($settings->twitter) {
                $share_url = sprintf(
                    'https://x.com/share?url=%s&amp;text=%s',
                    Html::sanitizeURL($url),
                    Html::escapeHTML($filter($text) . $tags)
                );
                if ($twitter_account !== '') {
                    $share_url .= '&amp;via=' . Html::escapeHTML($twitter_account);
                }

                $href_text  = __('Twitter/X');
                $href_title = __('Share this on Twitter/X');

                $links[] = (new Li())
                    ->items([
                        (new Link())
                            ->href($share_url)
                            ->class(['share-twitter', 'share-popup'])
                            ->extra([
                                'target="_blank"',
                                'rel="nofollow noopener noreferrer"',
                            ])
                            ->title($href_title . $a11y)
                            ->items([
                                (new Span($href_text)),
                            ]),
                    ]);
            }

            // Facebook link
            if ($settings->facebook) {
                $share_url = sprintf(
                    'https://www.facebook.com/sharer.php?u=%s&amp;t=%s',
                    Html::sanitizeURL($url),
                    Html::escapeHTML($text)
                );
                $href_text  = __('Facebook');
                $href_title = __('Share this on Facebook');

                $links[] = (new Li())
                    ->items([
                        (new Link())
                            ->href($share_url)
                            ->class(['share-fb', 'share-popup'])
                            ->extra([
                                'target="_blank"',
                                'rel="nofollow noopener noreferrer"',
                            ])
                            ->title($href_title . $a11y)
                            ->items([
                                (new Span($href_text)),
                            ]),
                    ]);
            }

            // LinkedIn link
            if ($settings->linkedin) {
                $share_url = sprintf(
                    'https://www.linkedin.com/shareArticle?mini=true&url=%s&title=%s',
                    Html::sanitizeURL($url),
                    Html::escapeHTML($text)
                );
                $href_text  = __('LinkedIn');
                $href_title = __('Share this on LinkedIn');

                $links[] = (new Li())
                    ->items([
                        (new Link())
                            ->href($share_url)
                            ->class(['share-in', 'share-popup'])
                            ->extra([
                                'target="_blank"',
                                'rel="nofollow noopener noreferrer"',
                            ])
                            ->title($href_title . $a11y)
                            ->items([
                                (new Span($href_text)),
                            ]),
                    ]);
            }

            // Mastodon link
            if ($settings->mastodon) {
                $share_url = sprintf(
                    'https://mastodonshare.com/?text=%s+%s',    // was 'web+mastodon://share?text=%s+%s',
                    str_replace('&amp;', '%26', Html::escapeHTML($text . $tags)),
                    Html::sanitizeURL($url)
                );
                $href_text  = __('Mastodon');
                $href_title = __('Share this on Mastodon');

                $links[] = (new Li())
                    ->items([
                        (new Link())
                            ->href($share_url)
                            ->class(['share-mastodon', 'share-popup'])
                            ->extra([
                                'target="_blank"',
                                'rel="nofollow noopener noreferrer"',
                            ])
                            ->title($href_title . $a11y)
                            ->items([
                                (new Span($href_text)),
                            ]),
                    ]);
            }

            // Bluesky link
            if ($settings->bluesky) {
                $share_url = sprintf(
                    'https://bsky.app/intent/compose?text=%s (%s)',
                    Html::escapeHTML($filter($text) . $tags),
                    Html::sanitizeURL($url)
                );

                $href_text  = __('Bluesky');
                $href_title = __('Share this on Bluesky');

                $links[] = (new Li())
                    ->items([
                        (new Link())
                            ->href($share_url)
                            ->class(['share-bluesky', 'share-popup'])
                            ->extra([
                                'target="_blank"',
                                'rel="nofollow noopener noreferrer"',
                            ])
                            ->title($href_title . $a11y)
                            ->items([
                                (new Span($href_text)),
                            ]),
                    ]);
            }

            // Mail link
            if ($settings->mail) {
                $share_url = sprintf(
                    'mailto:?subject=%s&amp;body=%s',
                    Html::escapeHTML($text),
                    Html::sanitizeURL($url)
                );
                $href_text  = __('Mail');
                $href_title = __('Share this by mail');

                $links[] = (new Li())
                    ->items([
                        (new Link())
                            ->href($share_url)
                            ->class('share-mail')
                            ->extra([
                                'target="_blank"',
                                'rel="nofollow noopener noreferrer"',
                            ])
                            ->title($href_title . $a11y)
                            ->items([
                                (new Span($href_text)),
                            ]),
                    ]);
            }

            // Share menu link
            if ($settings->menu) {
                $share_url  = '#';
                $href_text  = __('Share menu');
                $href_title = __('Share menu');
                $tags       = implode(' ', array_map(fn ($tag): string => '#' . $tag, $tag_list));

                $links[] = (new Li())
                    ->items([
                        (new Link())
                            ->href($share_url)
                            ->class('share-menu')
                            ->extra('hidden')
                            ->data([
                                'title' => implode(' ', [$text, $tags]),
                                'url'   => $url,
                            ])
                            ->title($href_title)
                            ->items([
                                (new Span($href_text)),
                            ]),
                    ]);
            }

            return (new Div())
                ->class('share')
                ->items([
                    (new Ul())
                        ->class('share-links')
                        ->items($links),
                ])
            ->render();
        }

        return '';
    }

    public static function customStyle(): string
    {
        $settings = My::settings();

        return $settings->style ?? '';
    }
}
