socialShare plugin for Dotclear 2

[![Release](https://img.shields.io/github/v/release/franck-paul/socialShare)](https://github.com/franck-paul/socialShare/releases)
[![Date](https://img.shields.io/github/release-date/franck-paul/socialShare)](https://github.com/franck-paul/socialShare/releases)
[![Issues](https://img.shields.io/github/issues/franck-paul/socialShare)](https://github.com/franck-paul/socialShare/issues)
[![Dotclear](https://img.shields.io/badge/dotclear-v2.24-blue.svg)](https://fr.dotclear.org/download)
[![Dotaddict](https://img.shields.io/badge/dotaddict-official-green.svg)](https://plugins.dotaddict.org/dc2/details/socialShare)
[![License](https://img.shields.io/github/license/franck-paul/socialShare)](https://github.com/franck-paul/socialShare/blob/master/LICENSE)

=================================

Summary
-------

This plugin adds social share buttons to posts and/or pages, for:

Twitter, Facebook, Google+, LinkedIn, Mastodon social networks and by mail.

Markup
------

```html
<div class="share">
 <p class="share-intro"><PREFIX></p>
 <ul class="share-links">
  <li>
   <a  class="share-twitter"
    target="_blank"
    title="Share this post on Twitter (new window)"
    href="https://twitter.com/share?url=<URL>&amp;text=<TITRE>&amp;via="<TWITTER-ACCOUNT>"
    rel="nofollow">
     Twitter
   </a>
  </li>
  <li>
   <a
    class="share-fb"
    target="_blank"
    title="Share this post on Facebook (new window)"
    href="https://www.facebook.com/sharer.php?u=<URL>&amp;t=<TITRE>"
    rel="nofollow">
     Facebook
   </a>
  </li>
  <li>
   <a
    class="share-in"
    target="_blank"
    title="Share this post on LinkedIn (new window)"
    href="https://www.linkedin.com/shareArticle?mini=true&url=<URL>&amp;title=<TITRE>"
    rel="nofollow">
     LinkedIn
   </a>
  </li>
  <li>
   <a
    class="share-mastodon"
    target="_blank"
    title="Share this post on Mastodon (new window)"
    href="web+mastodon://share?text=<TITRE>+<URL>"
    rel="nofollow">
     Mastodon
   </a>
  </li>
  <li>
   <a
    class="share-mail"
    target="_blank"
    title="Share this post by email (new window)"
    href="mailto:?subject=<TITRE>&amp;body=<URL>"
    rel="nofollow">
     email
    </a>
  </li>
 </ul>
</div>
```

Default style for social share links
------------------------------------

```css
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
}

.share ul li:last-child a {
 margin-right: 0;
}

.share a:hover {
 color: #fff;
}

.share .share-twitter {
 background-image: url("index.php?pf=socialShare/img/icon-twitter.png");
 background-image: url("index.php?pf=socialShare/img/icon-twitter.svg"), none;
}
.share .share-twitter:hover {
 background-color: #78cbef;
}

.share .share-fb {
 background-image: url("index.php?pf=socialShare/img/icon-facebook.png");
 background-image: url("index.php?pf=socialShare/img/icon-facebook.svg"), none;
}
.share .share-fb:hover {
 background-color: #547bbc;
}

.share .share-in {
 background-image: url("index.php?pf=socialShare/img/icon-linkedin.png");
 background-image: url("index.php?pf=socialShare/img/icon-linkedin.svg"), none;
}
.share .share-in:hover {
 background-color: #1686b0;
}

.share .share-mastodon {
 background-image: url("index.php?pf=socialShare/img/icon-mastodon.png");
 background-image: url("index.php?pf=socialShare/img/icon-mastodon.svg"), none;
}
.share .share-mastodon:hover {
 background-color: #3088d4;
}

.share .share-mail {
 background-image: url("index.php?pf=socialShare/img/icon-email.png");
 background-image: url("index.php?pf=socialShare/img/icon-email.svg"), none;
}
.share .share-mail:hover {
 background-color: #99c122;
}
```

Notes
-----

- the SVG icons will not be functionnal before Dotclear 2.8 (rev 3014)
- the image URLs (png or svg) may vary depending on your configuration
- list protocol handlers in Chrome : <chrome://settings/handlers>
