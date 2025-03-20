# socialShare plugin for Dotclear 2

[![Release](https://img.shields.io/github/v/release/franck-paul/socialShare)](https://github.com/franck-paul/socialShare/releases)
[![Date](https://img.shields.io/github/release-date/franck-paul/socialShare)](https://github.com/franck-paul/socialShare/releases)
[![Issues](https://img.shields.io/github/issues/franck-paul/socialShare)](https://github.com/franck-paul/socialShare/issues)
[![Dotaddict](https://img.shields.io/badge/dotaddict-official-green.svg)](https://plugins.dotaddict.org/dc2/details/socialShare)
[![License](https://img.shields.io/github/license/franck-paul/socialShare)](https://github.com/franck-paul/socialShare/blob/master/LICENSE)

## Summary

This plugin adds social share buttons to posts and/or pages, for:

Twitter, Facebook, Google+, LinkedIn, Mastodon, Bluesky social networks, mail and if possible with browser's sharing menu.

## Default style for social share links

```css
.share {
  font-size: 0.875em;
  margin-top: 1.5em;
  margin-bottom: 1.5em;
  padding: 0.5em 0;
  clear: both;
}

.share ul,
.share li {
  margin: 0;
  padding: 0;
}

.share ul {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-end;
  gap: 0.25em;
}
.share ul li {
  list-style: none;
}

.share a {
  padding: 0.25em 0.5em 0.25em 2em;
  background-position: 0.25em center;
  background-repeat: no-repeat;
  background-size: 1.5em auto;
  text-decoration: none;
  border-bottom: none;
}

.share a:hover {
  color: #fff;
}

.share .share-twitter:hover {
  background-color: #78cbef;
}
.share .share-fb:hover {
  background-color: #547bbc;
}
.share .share-in:hover {
  background-color: #1686b0;
}
.share .share-mastodon:hover {
  background-color: #3088d4;
}
.share .share-bluesky:hover {
  background-color: #1185fe;
}
.share .share-mail:hover {
  background-color: #99c122;
}
.share .share-menu:hover {
  background-color: #666666;
}

.share .share-twitter {
  background-image: url('index.php?pf=socialShare/img/icon-twitter.svg');
}
.share .share-fb {
  background-image: url('index.php?pf=socialShare/img/icon-facebook.svg');
}
.share .share-in {
  background-image: url('index.php?pf=socialShare/img/icon-linkedin.svg');
}
.share .share-mastodon {
  background-image: url('index.php?pf=socialShare/img/icon-mastodon.svg');
}
.share .share-bluesky {
  background-image: url('index.php?pf=socialShare/img/icon-bluesky.svg');
}
.share .share-mail {
  background-image: url('index.php?pf=socialShare/img/icon-email.svg');
}
.share .share-menu {
  background-image: url('index.php?pf=socialShare/img/icon-menu.svg');
}
```
