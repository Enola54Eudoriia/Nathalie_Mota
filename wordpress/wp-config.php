<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Nathalie_Mota' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '<n3*a2,<7Tht2{TQQ<S/3cS$m4VSab4F8^!(>&D810gD^inat/%P PK*2yy);;Z1' );
define( 'SECURE_AUTH_KEY',  '7Z~5.H/-ryi: zz=F!~ +wqumJ*@Pu?]vy<Lu-~YrX(|]Y(W/?>Ld@>/OzgS@}0V' );
define( 'LOGGED_IN_KEY',    'JU.SH,^nLO{/-r)7G?)Zo`X=Fwp8ayS>%w?RT</*baQb1KwW|)7XNhkjFuX%uahg' );
define( 'NONCE_KEY',        '{wWJC=!{GLve%LfcNu2`.Fi]dcmWQG%}FWj5t-!/mfHML^7Tx$|Ao;Zp*>6g%eZ)' );
define( 'AUTH_SALT',        '2T>tRKZkMjpwV/8TTRV)xcKezrZ<F7f>XHhsk4_c@V: ~CFX/]%xZ1wo8Hb9!.I-' );
define( 'SECURE_AUTH_SALT', '?90uFfENzEatjEqo-r%]%8EYV U36)B]Pb-P!?Yd_M4=j 3)Q$YUD/Ijy)_|c,dN' );
define( 'LOGGED_IN_SALT',   'NL}9BG8,+jU aSB3h$XcZJ[[NtEK.,J|O[ur1Tum+5wa7p5)#@%yJlcE{HdQe?|M' );
define( 'NONCE_SALT',       '|3pwgl]W1Hb66 HF**O}UVfT#BM(ih#Ro3D##^3#uR0j`T{h0pDeJW2 <0Q;qSJk' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
