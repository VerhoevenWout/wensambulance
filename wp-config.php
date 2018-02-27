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


if( stristr( $_SERVER['SERVER_NAME'], "loc" ) || stristr( $_SERVER['SERVER_NAME'], "xip.io" ) ) {

	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define('DB_NAME', 'wensambulance_wp');

	/** MySQL database username */
	define('DB_USER', 'root');

	/** MySQL database password */
	define('DB_PASSWORD', 'root');

	/** MySQL hostname */
	define('DB_HOST', 'localhost');

	/** Database Charset to use in creating database tables. */
	define('DB_CHARSET', 'utf8');

	/** The Database Collate type. Don't change this if in doubt. */
	define('DB_COLLATE', '');

} else{

	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define('DB_NAME', 'wensambulance_be_wensambulance');

	/** MySQL database username */
	define('DB_USER', 'wensambulance_be_wensambulance');

	/** MySQL database password */
	define('DB_PASSWORD', 'nIanITXR0goQqHT7V4V3');

	/** MySQL hostname */
	define('DB_HOST', 'wensambulance.be.mysql');

	/** Database Charset to use in creating database tables. */
	define('DB_CHARSET', 'utf8');

	/** The Database Collate type. Don't change this if in doubt. */
	define('DB_COLLATE', '');

}



/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'n/Y?_+gEr-; l|ywB%ug6=(|eZ{K n5<PgR!Qz6tm}<%wjw4(I]14Y{z&&lUiXw{');
define('SECURE_AUTH_KEY',  '5xao#PDwlb-73,CS^6:XLt{E&A`R=un`E3vC d+Z~M#1-{eoke|0g3,>--1PF9-R');
define('LOGGED_IN_KEY',    '<haOs_w8-q)+.<uKLrrmwrm?$+g->&`JqUbWz|&y6?(?+P;}1ks`ug6HV5K+cn+K');
define('NONCE_KEY',        'nU(|TcZ5`H]+-+-ds|f,9.1rW8tOfSq*r_CB/7{-21M:!RH.J&8eCyFBH$ndy~ph');
define('AUTH_SALT',        's4-1LU3%FtI,2-cBmo3|kx!:B9533{PakEN&u8B|}^|M5!;DhPj-|?E`l6B;>;U|');
define('SECURE_AUTH_SALT', 'jGsUT}<D]I?FbJ>93S*Ps]n%)uejnEQ,-:0 6~(IVY|.h+WIMQr+hH5P&ABnuM>|');
define('LOGGED_IN_SALT',   'aFxo{IM?-K+0@ue_v(a+OD@w1wPNg`L6>]~Z*Bq{zt+|dG%GE~HbANuoK@a8nWeF');
define('NONCE_SALT',       '.+S:cCPJe3EAr:!_g:_|e+k#BfA2P590q=n:MZZSzahOIN#>iA/uS^lk=M#r0$bv');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
