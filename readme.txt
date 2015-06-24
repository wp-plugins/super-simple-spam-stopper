=== Super Simple SPAM Stopper ===
Contributors: bgribaudo
Tags: comment, comments, spam, anti-spam, antispam, comment spam, block spam, spambot
Requires at least: 4.2.2
Tested up to: 4.2.2
Stable tag: 1.0.0
License: BSD_3Clause
License URI: http://directory.fsf.org/wiki/License:BSD_3Clause

Prevents automated SPAM by requiring visitors to answer a question of your choosing. The correct answer can be defined using plain text or a regular expression.

== Description ==

Attempts to prevent automated SPAM by requiring visitors to answer a question of your choosing in order for their comment to be accepted. Presumably, spambots will be unable to correctly answer this question and so comments they attempt to post will be rejected.
		
Only regular comments from non-logged-in users are filtered. Pingbacks, trackbacks and authenticated users will not be prompted to answer the verification question.

**Privacy Note:** This plugin does not call home or communicate with any external servers or services. All processing is done locally within your WordPress site.

== Installation ==

After downloading the plugin's zip file:

1. In WordPress's *Plugins* menu, choose *Add New* then *Upload Plugin*.
1. Follow the prompts to upload the plugin's zip file and then to active the plugin.
1. Configure the plugin through the *Settings* -> *Super Simple SPAM Stopper* menu in WordPress.

If you'd rather upload the plugin manually:

1. Extract and upload the contents of the plugin's zip file to the `/wp-content/plugins/` directory.
1. Activate the plugin through the *Plugins* menu in WordPress.
1. Configure the plugin through the *Settings* -> *Super Simple SPAM Stopper* menu in WordPress.

== Screenshots ==

1. Configuration options (in WordPress admin interface)
2. Question displayed on comment form (appearance will vary depending on your template/style sheet)