=== WP Clone by WP Academy ===
Contributors: wpacademy
Donate link: http://wpacademy.com/software
Tags: wp academy, wpacademy, move wordpress, copy wordpress, clone wordpress, install wordpress, wordpress hosting, backup, restore
Author URI: http://wpacademy.com
Plugin URI: http://wpacademy.com/software
Requires at least: 3.0
Tested up to: 3.9
Stable tag: 2.1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Move or copy a WordPress site to another server or to another domain name, move to/from local server hosting, and backup sites.

== Description ==

WP Clone is the easiest, fastest and most secure way to move or copy a WordPress site to another domain or hosting server.  You can also use it to move your site to/from local server hosting, to create copies of your site for development or testing purposes, to backup your site, and to install pre-configured versions of WordPress.  

WP Clone is a superior solution to even commercial WordPress cloning plugins for the following reasons: 

* Does not require FTP access to either the source or destination site &ndash; just install a new WordPress on the destination site, upload and activate WP Clone plugin, and follow the prompts
* It does not backup or restore the WordPress system files (just the user content and database) &ndash; reducing upload time and improving security of your site
* It fetches the site backup via your host&apos;s direct http connection, which saves you from needing to upload large files through your internet connection
* It uses the WordPress internal zip archive function, which makes it compatible with virtually 100% of hosts that support WordPress (no special PHP libraries are required)

<blockquote>
= Update April 2015 =
WP Clone fails to restore in about 10-20% of installations. This appears to be related to the hosting platform rather than the WordPress version. We are attempting to isolate this problem and determine the types of installations that fail in order to update the plugin.  Under no circumstances should the source (origin) site ever be damaged or destroyed.
Feel free to leave a negative review and details of your failure on the support forum, but please be polite as we do not receive any remuneration for writing and supporting this plugin. We will investigate as we can. As a reminder also to never overwrite an installation for which you do not have a backup -- in normal usage this plugin would be used only to overwrite a new (or blank) WordPress installation.  Thanks -- WP Academy Support
</blockquote>

= Help Video =
[youtube http://www.youtube.com/watch?v=xN5Ffhyn4Ao]

= New features (July 2013): Exclude directories and Database-only backup =
We have implemented exclude-directories on the backup, and also database-only backup!  By transferring the contents of 'wp-content' directory with FTP from your old site to new site, you should now be able to migrate sites of any size.

= Support and Disclaimer =
No WordPress backup plugin will work reliably on all hosts.  If you have any problems, try doing a "Database Only" backup (use "Advanced Options"), transfer the wp-content directory over with FTP, and then restore new site.  You should also deactivate and delete any page caching plugins (i.e. W3 Total Cache) before backup. 
If you still have an issue, please post to the WordPress.org support forum where we support this plugin for free, and we'll respond on a  "best-effort" basis.  You can also try the Duplicator plugin http://wordpress.org/plugins/duplicator/ or All-in-One WP Migration https://wordpress.org/plugins/all-in-one-wp-migration/, both of which work pretty good, but are not as fast as WP Clone to migrate sites; or use the manual method described here http://members.wpacademy.com/ww3.htm?moving-wordpress.htm

= Please donate to support plugin development & ongoing support =
WP Clone is provided free of charge to the community and supported through the plugin forums on WordPress.org. Please help defray our development expenses and help with support costs through the [Donations Page](http://wpacademy.com/software "Donations page")
Donation page.

= Additional documentation =
Additional documentation, including supported hosts, at the [WP Clone FAQ Page](http://members.wpacademy.com/wpclone-faq "WP Clone FAQ")

= Other contributors =
WP Clone uses functions from the "Safe Search and Replace on Database with Serialized Data" script first written by David Coveney of Interconnect IT Ltd (UK) http://www.davidcoveney.com or http://www.interconnectit.com and 
released under the WTFPL http://sam.zoy.org/wtfpl/. Partial script with full changelog is placed inside 'lib/files' directory.


== Installation ==

1. Navigate to Plugins > Add New
2. Search for "WP Clone"
3. Install and activate the plugin
4. Follow remaining instructions in the help video

== Frequently Asked Questions ==
Review FAQ's and Help Video at the [WP Clone FAQ Page](http://members.wpacademy.com/wpclone-faq "WP Clone FAQ")

== Changelog ==
= 2.1.8 - 2014-09-18 =
* Updated: Readme description.
= 2.1.7 - 2014-07-30 =
* Changed: Admin page links.
= 2.1.6 - 2013-07-07 =
* Added: An option to exclude specific directories during backup.
* Added: An option to only backup the database.
* Added: An admin notice for multisite users.
* Changed: File operations during backup are now handled directly instead of using the WP filesystem abstraction class.

= 2.1.5 - 2013-06-05 =
* Changed: UI Twitter feed from rss to the official twitter widget.
* Changed: UI Sidebar link attributes.

= 2.1.4 - 2013-03-18 =
* Fixed: When javascript is disabled,submit button shows "Create Backup" but the plugin attempts to do a restore.
* Changed: The temporary directory location during the restore process from '/wp-content/' to '/wp-content/wpclone-temp/'.

= 2.1.3 - 2013-03-17 =
* Fixed: The 'copy' link in the 'backup successful' screen which stopped working after the 2.1.2 update.
* Added: An option to backup the database using WordPress' WPDB class.
* Removed: The need to keep the original backup names intact.
* Removed: 'lib/DirectoryTree.php' and 'lib/class.php'.
* Changed: The backup name structure.
* Changed: Backup file downloads are now handled using WP core functions.

= 2.1.2 - 2013-03-07 =
* Fixed: An XSS vulnerability caused by an older version of the ZeroClipboard library.

= 2.1.1 - 2013-02-16 =
* Fixed: a missing nonce action which was causing a wp_nonce_ays loop on some hosts.
* Fixed: a couple of UI issues.

= 2.1 - 2012-12-25 =
* Added: WP Academy sidebar.

= 2.0.6 - 2012-08-05 =
* Added: WP Filesystem integration
* Added: Alternate zip method for better compatibility with hosts that haven't enabled PHP's zip extension
	
= 2.0.5 - 2012-06-25 =
* Fixed: A secondary search and replace that was corrupting serialized entries

= 2.0.3 - 2012-05-16 =
* Fixed: ignoring trailing slashes in the site URLs

= 2.0.2 -	2012-04-12 =
* Initial release