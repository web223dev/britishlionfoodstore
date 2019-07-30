=== Logo Manager ===

Contributors: 
Tags: logo, FTP, theme
Requires at least: 2.7
Tested up to: 3.5
Stable tag: trunk

Change your theme logo without using FTP. 

== Description ==

Enables you to change your theme logo without using FTP or the media library.

= Features =

*	Gets logo form your media library
*	Edit the image alt text and title
*	Option to link the logo to your homepage
*	Swap between uploaded logos

= Developers plugin page =

[logo uploader plugin](http://quick-plugins.com/logo-manager/).

== Installation ==

1.	Download the plugin.
2.	Login to your wordpress dashboard.
3.	Go to 'Plugins', 'Add New' then 'Upload'.
4.	Browse to the downloaded plugin then then 'Install Now'.
5.	Activate the plugin.
6.	Use the link on the 'appearances' page to access the plugin options.
7.	Add the code `<?php lm_display_logo(); ?>` to your header.php.

== Screenshots ==
1. This is the admin screen.

== Frequently Asked Questions ==

= What happens if I don't have a logo? =
All you will see on your site is the alt text.

= Can I use the logo URL instead of the lu_display_logo function? =
Yes. But you won't see the alt text or title.

= How can I revert to my original logo? =
Just replace `<?php lm_display_logo(); ?>` with the code from the original theme files.

== Changelog ==

= 2.4 =
*	Added Wordpress Media Uploader

= 2.3 =
*	Just a coding tweak - no change to the way the plugin works

= 2.1 & 2.2 =
*	Bug Fix in the way the array was called
*	Spelling mistakes

= 2.0 =
*	Major upgrade. Yo can now uploads as many logos as you like and select which one you want to display.
*	Cleaned out a whole load of code so it's super slick.

= 1.1 =
*	Option to make the logo responsive to the screen size

= 1.0 =
*	Initial Issue