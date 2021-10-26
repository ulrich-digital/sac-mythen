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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_ggafd' );

/** MySQL database username */
define( 'DB_USER', 'wp_dok2y' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Au^rnhfoOR22_U~8' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'ga;_7d#]!4vQg758B1)CZvQ~7#7_!&C6V4!)l5o0p9-5][6;&bvR|tSMRoZ0[-08');
define('SECURE_AUTH_KEY', '!9G2(UM:V+6gO0K/6h]K([0BZ01|-jAe~60IO([I|]uw7e*q+-637a(1C2K&m*@]');
define('LOGGED_IN_KEY', 'C4w;d;iQ)hL8x4l4;E/9594f-lUOj@H:[w6CJ%t7~U|2H)ZakiTT8V-089Z7757!');
define('NONCE_KEY', '0u27eg-3eS86XM:9j6P5bly[1#yC2-RMPe291+o(f+~2X1o95lNS[-H71pg*7!%b');
define('AUTH_SALT', 'JHk4dw#r|&c3%I%YH5vTFi*N3tK1GFp8j1U9p_+mudH7*W;zb7&|f(V6e4%%+m9#');
define('SECURE_AUTH_SALT', 'vWV3L13:6]9uX:1JBsuB%Cz+z7-5%-7w7A#8:~Y!_-N*48LcmH8s#Hjb@ek8s@[d');
define('LOGGED_IN_SALT', 'gs|j)SP6|53[s:m(D[5B1_6OtoKW1vM394boq3&t*F/S/[@o&9d)3o!()-DiAf&u');
define('NONCE_SALT', '%8Ym9qOR0LZH46%Ak(|5t]+Qlp*t77)R!L_H:8#*gXxZmH_i]r[4IZ@5vCd7M;P0');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '6I2gd_';


define('WP_ALLOW_MULTISITE', true);
define( 'WP_DEBUG', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
