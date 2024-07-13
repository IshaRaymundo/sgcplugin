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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'id22373657_wordpress' );

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
define( 'AUTH_KEY',         'yghB$(.a.Z(`e8@(GIi:cb1:@-=d*)9LJvcI51&6EF:E+V|rw9*0,z@wwU!3q)][' );
define( 'SECURE_AUTH_KEY',  '{H8GlFk98KDms5u<e9WlII{ypFM=^4KAYn8VTq{@/}DKM-#5RkwB!*zIr0>! *e,' );
define( 'LOGGED_IN_KEY',    'T_:F(v!DG?PQ)#C989z}fr>G*vjBOy3.Q9{dMP)OV{,evT}<.RM&bD,!,=2lxL)j' );
define( 'NONCE_KEY',        '_DhS&e=m^IboI/ky~O8J-BmmLd=KXeP(!8iPvKW$t*AOGQTVdFU1M6p5PY}z $m~' );
define( 'AUTH_SALT',        'kQeGz|)&PXZGE~eGSj>bv%P4pYLGko+1`u@=F;KC>l7ho7P*w3SqBuEJL<i?),x*' );
define( 'SECURE_AUTH_SALT', 'zCj?.;t?v}rC:m s&ie#48:n|u8[s00/+S^r,.%p!*qA6p2y1{xu7-jiDBI^}:D#' );
define( 'LOGGED_IN_SALT',   'SQgP`{7UI>%L iAG0JpMJGU;]7sL,3]t>i{ikS1(xAIse#V|G<tj(<aEpC4ViSF}' );
define( 'NONCE_SALT',       '7qd]qfCNde.A*5roNI4+e;B_{dEU;i)hplG0OstY*=@Y!I<OIU^mGm4fQ+Przgaq' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
