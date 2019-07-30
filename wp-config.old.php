<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'britis22_1');

/** MySQL database username */
define('DB_USER', 'britis22_1');

/** MySQL database password */
define('DB_PASSWORD', 'britishl_dev');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

define('WP_HOME','https://britishlionfoodstorebalchik.com');
define('WP_SITEURL','https://britishlionfoodstorebalchik.com');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('WP_POST_REVISIONS', false );
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'WNHb^s}-,s~fiTQ6g~=qd4y[>Tv|czM@3S>w6mHihUSvxpGIlWW co/v$%Ec/_w+');
define('SECURE_AUTH_KEY',  ',.2|qqab6wG&?ip[~=.QKMkGWyf&vt7Bw2)s8y#?!p}T:-XL=^It9#ZZxNoutD=Z');
define('LOGGED_IN_KEY',    '%l8__rBXtCP!6$g< fM:xz|!{{HcR%?foet0+6O;.JkE8F&_ktN!p>]OK[.+`K]T');
define('NONCE_KEY',        '9>512hg$crq{(e5CEKi66#8H^#9i)?EwId3DAw*+8G#EaPQ$Pr>WIhc<$N4-~RQf');
define('AUTH_SALT',        '4~*>OdIk6(sML)U?N&3_|FPXH$gLJGG{}QRhq5qX Y(WPdb(?@z |kpC&.QF0gn-');
define('SECURE_AUTH_SALT', 'SC38)];1DSH1CrC:+vE8klK>Nv3N2u:N75hYxD!vU@vr>1.E[=1CSlr^&xo644o5');
define('LOGGED_IN_SALT',   'OQC>Y(jQ,B,[kh4P,LJe2{9E#rNc1J?iaQWGD#0^ZE$(Nw72(g-*znGr-Wg`Ww3<');
define('NONCE_SALT',       '#DQxbdFW*9e,wB_k+lWKu#]}nU=/B@S}5x5<W9 =Y<3U6an8HgaFXSVNu0ik},U?');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
#define('WP_DEBUG', TRUE);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
