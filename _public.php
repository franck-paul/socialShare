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

if (!defined('DC_RC_PATH')) { return; }

$core->addBehavior('publicHeadContent', array('dcSocialShare','publicHeadContent'));

$core->addBehavior('publicEntryBeforeContent', array('dcSocialShare','publicEntryBeforeContent'));
$core->addBehavior('publicEntryAfterContent', array('dcSocialShare','publicEntryAfterContent'));

$core->tpl->addValue('SocialShare',array('dcSocialShare','tplSocialShare'));

class dcSocialShare
{
	public static function publicEntryBeforeContent($core,$_ctx)
	{
		$ret = '';
		if ($core->blog->settings->socialShare->active) {
			if (($_ctx->posts->post_type == 'post' && $core->blog->settings->socialShare->on_post) ||
				($_ctx->posts->post_type == 'page' && $core->blog->settings->socialShare->on_page))
			{
				if ((($core->url->type == 'post' || $core->url->type == 'page') && $core->blog->settings->socialShare->on_single_only) ||
					(!$core->blog->settings->socialShare->on_single_only))
				{
					if ($core->blog->settings->socialShare->before_content) {
						echo self::socialShare(
							$_ctx->posts->getURL(),
							$_ctx->posts->post_title,
							($_ctx->posts->post_lang ? $_ctx->posts->post_lang : $core->blog->settings->system->lang),
							$core->blog->settings->socialShare->prefix,
							$core->blog->settings->socialShare->twitter_account);
					}
				}
			}
		}
	}

	public static function publicEntryAfterContent($core,$_ctx)
	{
		$ret = '';
		if ($core->blog->settings->socialShare->active) {
			if (($_ctx->posts->post_type == 'post' && $core->blog->settings->socialShare->on_post) ||
				($_ctx->posts->post_type == 'page' && $core->blog->settings->socialShare->on_page))
			{
				if ((($core->url->type == 'post' || $core->url->type == 'page') && $core->blog->settings->socialShare->on_single_only) ||
					(!$core->blog->settings->socialShare->on_single_only))
				{
					if ($core->blog->settings->socialShare->after_content) {
						echo self::socialShare(
							$_ctx->posts->getURL(),
							$_ctx->posts->post_title,
							($_ctx->posts->post_lang ? $_ctx->posts->post_lang : $core->blog->settings->system->lang),
							$core->blog->settings->socialShare->prefix,
							$core->blog->settings->socialShare->twitter_account);
					}
				}
			}
		}
	}

	public static function tplSocialShare($attr)
	{
		global $core,$_ctx;

		$ret = '';
		if ($core->blog->settings->socialShare->active && $core->blog->settings->socialShare->template_tag)
		{
			$f = $core->tpl->getFilters($attr);
			$ret = '<?php echo dcSocialShare::socialShare('.
				sprintf($f,'$_ctx->posts->getURL()').','.
				sprintf($f,'$_ctx->posts->post_title').','.
				sprintf($f,'($_ctx->posts->post_lang ? $_ctx->posts->post_lang : $core->blog->settings->system->lang)').','.
				'$core->blog->settings->socialShare->prefix'.','.
				'$core->blog->settings->socialShare->twitter_account'.
				'); ?>'."\n";
		}
		return $ret;
	}

	public static function publicHeadContent()
	{
		global $core;

		$core->blog->settings->addNamespace('socialShare');
		if ($core->blog->settings->socialShare->active)
		{
			switch ($core->blog->settings->socialShare->use_style) {
				case 0:		// Default CSS styles
					$ret = self::defaultStyle();
					break;
				case 1:		// Blog's theme CSS styles
					$ret = '';
					break;
				case 2:		// User defined CSS styles
					$ret = self::customStyle();
					break;
			}
			if ($ret != '') {
				echo '<style type="text/css">'."\n".$ret."\n"."</style>\n";
			}
		}
	}

	// Helpers

	public static function socialShare($url,$title,$lang,$prefix,$twitter_account)
	{
		$ret = '';

		if ($GLOBALS['core']->blog->settings->socialShare->twitter ||
			$GLOBALS['core']->blog->settings->socialShare->facebook ||
			$GLOBALS['core']->blog->settings->socialShare->google ||
			$GLOBALS['core']->blog->settings->socialShare->mail)
		{
			$ret =
				'<div class="share">'."\n";
			if ($prefix) {
				$ret .= '<p class="share-intro">'.$prefix.'</p>'."\n";
			}
			$ret .= '<ul class="share-links">'."\n";

			// Twitter link
			if ($GLOBALS['core']->blog->settings->socialShare->twitter)
			{
				$share_url = sprintf('https://twitter.com/share?url=%s&amp;text=%s',
					html::sanitizeURL($url),
					html::escapeHTML($title));
				if ($twitter_account != '') {
					$share_url .= '&amp;via='.html::escapeHTML($twitter_account);
				}
				$ret .=
					'<li>'."\n".
					'<a class="share-twitter" target="_blank" rel="nofollow" '.
						'title="'.__('Share this on Twitter').'" '.
						'href="'.$share_url.'" '.
						'onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700\');return false;">'."\n".
					'<span>'.__('Twitter').'</span>'."\n".
					'</a>'."\n".
					'</li>'."\n";
			}

			// Facebook link
			if ($GLOBALS['core']->blog->settings->socialShare->facebook)
			{
				$share_url = sprintf('https://www.facebook.com/sharer.php?u=%s&amp;t=%s',
					html::sanitizeURL($url),
					html::escapeHTML($title));
				$ret .=
					'<li>'."\n".
					'<a class="share-fb" target="_blank" rel="nofollow" '.
						'title="'.__('Share this on Facebook').'" '.
						'href="'.$share_url.'" '.
						'onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700\');return false;">'."\n".
					'<span>'.__('Facebook').'</span>'."\n".
					'</a>'."\n".
					'</li>'."\n";
			}

			// Google+ link
			if ($GLOBALS['core']->blog->settings->socialShare->google)
			{
				$share_url = sprintf('https://plus.google.com/share?url=%s&amp;hl=%s',
					html::sanitizeURL($url),
					html::escapeHTML($lang));
				$ret .=
					'<li>'."\n".
					'<a class="share-gp" target="_blank" rel="nofollow" '.
						'title="'.__('Share this on Google+').'" '.
						'href="'.$share_url.'" '.
						'onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650\');return false;">'."\n".
					'<span>'.__('Google+').'</span>'."\n".
					'</a>'."\n".
					'</li>'."\n";
			}

			// Mail link
			if ($GLOBALS['core']->blog->settings->socialShare->mail)
			{
				$share_url = sprintf('mailto:?subject=%s&amp;body=%s',
					html::escapeHTML($title),
					html::sanitizeURL($url));
				$ret .=
					'<li>'."\n".
					'<a class="share-mail" target="_blank" rel="nofollow" '.
						'title="'.__('Share this by mail').'" '.
						'href="'.$share_url.'">'."\n".
					'<span>'.__('Mail').'</span>'."\n".
					'</a>'."\n".
					'</li>'."\n";
			}

			$ret .=
				'</ul>'."\n".
				'</div>'."\n";

		}
		return $ret;
	}

	public static function customStyle()
	{
		$s = $GLOBALS['core']->blog->settings->socialShare->style;
		return $s;
	}

	public static function defaultStyle()
	{
		$ret = <<<EOT1
.share {
    font-size: 0.875em;
    margin-top: 1.5em;
    margin-bottom: 1.5em;
    padding: 0.5em 0px;
    text-align: right;
    clear: both;
}

.share p, .share ul, .share li {
    display: inline-block;
    margin: 0px;
    padding: 0px;
}

.share p {
    padding-right: 1.5em;
}

.share a {
    padding: 0.25em 0.5em 0.25em 2em;
    margin-right: 0.5em;
    background-position: 0.25em center;
    background-repeat: no-repeat;
    background-size: 1.5em auto;
    text-decoration: none;
    border-bottom: none;
}

.share ul li:last-child a {
    margin-right: 0;
}

.share a:hover {
	color: #fff;
}

.share .share-twitter {
	background-image: url("PF-PATH/icon-twitter.png");
}
.share .share-fb {
	background-image: url("PF-PATH/icon-facebook.png");
}

.share .share-gp {
	background-image: url("PF-PATH/icon-gplus.png");
}
.share .share-mail {
	background-image: url("PF-PATH/icon-email.png");
}

.share .share-twitter:hover {
	background-color: #78cbef;
}
.share .share-fb:hover {
	background-color: #547bbc;
}
.share .share-gp:hover {
	background-color: #d30e60;
}
.share .share-mail:hover {
	background-color: #99c122;
}
EOT1;
		if (version_compare($GLOBALS['core']->getVersion('core'),'2.8-r3014','>=')) {
			$ret .= "\n".'/* Dotclear 2.8 and later specific */'."\n";
			$ret .= <<<EOT2
.share .share-twitter {
    background-image: url("PF-PATH/icon-twitter.svg"), none;
}
.share .share-fb {
    background-image: url("PF-PATH/icon-facebook.svg"), none;
}
.share .share-gp {
    background-image: url("PF-PATH/icon-gplus.svg"), none;
}
.share .share-mail {
    background-image: url("PF-PATH/icon-email.svg"), none;
}
EOT2;
		}
		$base = html::stripHostURL($GLOBALS['core']->blog->getQmarkURL().'pf=socialShare/img');
		$s = str_replace('PF-PATH', $base, $ret);

		return $s;
	}
}
