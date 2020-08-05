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
define( 'DB_NAME', 'CBTIS' );

/** MySQL database username */
define( 'DB_USER', 'willars' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'IRFcF~:KE;p)WAUaI+$;xQD6%;-&&66~?!];jW4fg[S?xK ]7|}tH6@[,g)>DCn~' );
define( 'SECURE_AUTH_KEY',  '`p3+mkRom{-fwo.,@7D{@4TBv23qL^GJ%*oV2Xri7X@;xXVmvu~e7+1vmMg.H;kb' );
define( 'LOGGED_IN_KEY',    'P?~K8:)VnaMi#EpX35i8X;Y+NsHl|a+DM3]EEdpUQb6V!awBv*6)(.aR^rO&QJ0K' );
define( 'NONCE_KEY',        'v(:!rz+?Kw$4YDs.$3![Dt;d!T?kj{TJLX.2(R{nP.BSH`d/2C57j5b(/v +2lv{' );
define( 'AUTH_SALT',        'EBb9et-XVO``V7c%d{HGzLot)u0_t eMh#5>D5#]nFb@6kbds|DAUVy+^wDXB+wo' );
define( 'SECURE_AUTH_SALT', 'Q8(OE4AM7:5STOgeE,71:nY8ArAJHDQEIOk<q{aH|9X8yvU2/$W9m.nkJzWqCvVm' );
define( 'LOGGED_IN_SALT',   'Vt;R0O9F;({I&o3gL9wW?K*9fS8rz{`LzaoM]9Fpuxiex&X1WZx}DAUGhrl6zo_=' );
define( 'NONCE_SALT',       'xja*BZYnI0>A&GJWC 2e`p{0XQC|1^Dc3U<K`7[nJDg5jM_ogp@c]f,#Njg@Gi#O' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
