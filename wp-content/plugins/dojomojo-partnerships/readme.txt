=== DojoMojo Partnerships ===
Contributors: alexdovzhanyn
Tags: partnerships, marketing, dojo, dojomojo, emails, email acquisition, partnerships marketing
Requires at least: 4.4
Tested up to: 4.7.3
Stable tag: 1.0.13
License: GPLv3

== Hosting / Embedding your Giveaway on Wordpress

Integrates with the DojoMojo platform to allow giveaways to be hosted on your Wordpress website

== Installation ==

There are 2 main options for displaying your giveaway on your Wordpress website. You may choose to do one or both of these.

**OPTION 1 - FULL PAGE**
This option allows you to have the giveaway show full-screen on any URL you define.

Before you begin, you will want to define the URL you plan to use for your giveaway, i.e. http://mybrand.com/giveaway - where “giveaway” is the slug.

1. Install and activate the plugin.
2. Open the **Plugin Options**.
3. On the **Giveaway Slug** option, enter your giveaway slug (i.e. /giveaway). The slug is the address specific to the giveaway page, which is usually what comes after the "/".
4. Login to your [dojomojo.ninja](https://www.dojomojo.ninja) account and edit the campaign you'd like to integrate with Wordpress (**My Partnerships** > **Edit**).
5. Go to the **Hosting** tab of the **Campaign Manager**, and select the **Advanced** option.
6. When prompted for a URL, enter the URL of your wordpress website, along with the slug you defined in the plugin. (i.e. http://example.com/example-slug).
7. In the **Tracking** tab of the **Campaign Manager**, create and copy any tracking link and paste it into your browser.

The campaign should be loaded successfully into your website.

Note this will become the default option for future campaigns. To update the URL for your landing page, be sure to update your Giveaway Slug both 1) in Wordpress in the DojoMojo options and 2) in DojoMojo's Campaign Manager > Hosting > Advanced.

**OPTION 2 - SHORTCODE EMBED**
This option allows you to embed your giveaway into an existing post/page.

1. In Wordpress Admin, go to the edit section of the post/page where you would like to embed the DojoMojo giveaway.
2. Go to [DojoMojo](https://dojomojo.ninja) and edit your campaign. Go to the **Tracking** section of the **Campaign Builder**, and copy any Tracking Link.
3. Paste the Tracking Link where shown in this snippet and add it anywhere on your post/page:
```
  [dojomojo_giveaway tracking="**YOUR_TRACKING_LINK**"]
```
The final link should look something like this example:
```
  [dojomojo_giveaway tracking="https://dojomojo.ninja/promo-lookup/3i5649c5-c715-48ab-8905-d8616db88bv7"]
```
When viewing the post/page, you'll see the giveaway embedded.
