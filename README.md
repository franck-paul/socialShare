socialShare plugin for Dotclear 2
=================================

Summary
-------

This plugin adds social share buttons to posts and/or pages, for Twitter, Facebook, Google+ social networks and by mail.

Default style for social share links
------------------------------------

.share {
    font-size: 0.875em;
    margin-top: 1.5em;
    padding: 0.5em 0px;
    text-align: right;
    clear: both;
}

.share p {
    padding-right: 1.5em;
}

.share p, .share ul, .share li {
    display: inline-block;
    margin: 0px;
    padding: 0px;
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

.share .share-gp {
	background-image: url("index.php?pf=socialShare/img/icon-gplus.png");
    background-image: url("index.php?pf=socialShare/img/icon-gplus.svg"), none;
}
.share .share-gp:hover {
	background-color: #d30e60;
}

.share .share-mail {
	background-image: url("index.php?pf=socialShare/img/icon-email.png");
    background-image: url("index.php?pf=socialShare/img/icon-email.svg"), none;
}
.share .share-mail {
	background-color: #99c122;
}
