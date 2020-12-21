=== SmartFormat feed for SmartNews ===
Contributors: smartnewsdev
Tags: smartnews, smartformat, rss
Requires at least: 4.0.26
Tested up to: 5.2
Stable tag: 0.0.1
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin will generate a valid SmartFormat Feed. SmartFormat is an extension of the RSS spec that provides feed elements to specify a publisher's analytics script and advertising tags that will run when a publisher's articles are displayed in native format on the SmartNews mobile app.

== Description ==

SmartNews (https://www.smartnews.com) is a free mobile news aggregation app for [iOS](https://itunes.apple.com/us/app/id579581125) and [Android](https://play.google.com/store/apps/details?id=jp.gocro.smartnews.android).

Publishing partners can distribute articles more rapidly with greater control over presentation if they provide a SmartFormat feed to SmartNews.

The SmartFormat varient of RSS feed has the following additional fields.

* Site logo
* Time To Live (TTL)
* Analytics script tag
* Advertisement script tag
* Sponsored links

It can be accessed using the following parameter `/?feed=smartformat` after the path. For example: https://www.domain.com/?feed=smartformat

[Official Documentation on SmartFormat](https://publishers.smartnews.com/hc/en-us/articles/360010977793)

== Frequently Asked Questions ==

Q: How do I become a SmartNews publishing partner?

A: To learn more, please visit [our publisher support page](https://about.smartnews.com/en/publishers/)

== Changelog ==

= 0.0.2 =

* Fix the description

= 0.0.1 =

* Initial release

