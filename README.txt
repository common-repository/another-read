=== AnotherRead ===
Contributors: lineindustries
Donate link:
Tags: Another Read, activity, books 
Requires at least: 6.2.2
Tested up to: 6.5.2
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create posts and pages with Another Read’s activity feeds and stacks.

== Description ==

Another Read helps authors and illustrators of children’s books connect with existing readers and reach new audiences. Use our suite of tools to manage your profile, connect your social media and add additional content.

The Another Read Activity plugin allows you to include an activity feed from Another Read and automatically generate posts within WordPress. Choose which posts you’d like to include by defining a keyword, contributor ID and publishers.

The plugin includes a Gutenberg block which allows you to add the activity feed to any of your pages or posts. Choose whether to display the jacket, keynote, author and book links.

The Another Read Activity plugin is reliant on, and retrieves content from a third party service, [Another Read](https://anotherread.com/?utm_source=wordpress.org&utm_medium=free%20plugin%20listing&utm_campaign=Another%Read%20Website). The plugin makes use of Another Read's API. Use of Another Read is subject to it's [Terms of Use](https://anotherread.com/terms-of-use?utm_source=wordpress.org&utm_medium=free%20plugin%20listing&utm_campaign=Another%Read%20Website) and [Privacy Policy](https://anotherread.com/privacy-policy?utm_source=wordpress.org&utm_medium=free%20plugin%20listing&utm_campaign=Another%Read%20Website).

**Connect to Another Read.**
Create a free account with Another Read and save your API key to the the settings page.

**Automatically generate posts.**
Include an activity feed from Another Read and automatically generate posts using this plugin.

**Add them anywhere.**
The plugin includes a Gutenberg block which allows you to add the activity feed to any of your pages or posts.

= Features =
* Post generation
* Gutenberg block

= Links =
* [Website](https://anotherread.com/?utm_source=wordpress.org&utm_medium=free%20plugin%20listing&utm_campaign=Another%Read%20Website)

= Create a free account =
You will need an API key to use the Another Read Activity plugin. Use the link below to register and create your account.

[Create an account](https://anotherread.com/register?utm_source=wordpress.org&utm_medium=free%20plugin%20listing&utm_campaign=Another%20Read%20Register)

== Installation ==

1. Upload plugin folder to ‘/wp-content/plugins/’ directory.
1.1 Alternatively, upload a .zip version to the plugin directory using uploader at ‘site-url/wp-admin/plugin-install’
2. Activate the plugin through the ‘Plugins’ menu in the WordPress admin dashboard.
3. Go to the Another Read admin page in WordPress and add your API key to the API key field.
3.1 If you don’t have an API key, make a free account following the link on the settings page to anotherread.com.
4. Once an API key is added and the rest of the settings are completed, press ‘save settings’, or ‘update posts’ to save the settings and gather posts.
4.1. Every 24 hours from the initial first press of the update posts button a cron job is made to automatically gather new activity posts.
5. Viewing the activity posts;
5.1. Add the new activity gutenberg block to a page to view a list of activity posts.
5.2. Go to the activity post type page to view individual activity posts.

== Frequently Asked Questions ==

= How do I get an API key =

Register for a free account at anotherread.com. Enter your API key on the plugin settings page.

= How do I define the content I want to pull in =

From within your WordPress Dashboard, navigate to “Another Read” > “Activity settings”, here you can enter an “Activity keyword”, Contributor ID and select a Publisher. You can also determine the number of posts to retrieve each time.

== Development ==
This plugin ships with the necessary package.json and build scripts required to compiile and compress the source code for the blocks within the project.
The source files for the compressed content is at ./blocks/src/*/index.js

== Screenshots ==

1. Admin screen.

== Changelog ==

= 1.0 =
* First release.

== Upgrade Notice ==

= 1.0 =
* First release.
