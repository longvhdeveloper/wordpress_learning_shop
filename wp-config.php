<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress_shop');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '4ch/=oNSaO|Q!.:tSrettEK>K0=h=j+PW6eX&Fz1T-^;-g4wqS?NKaOrj]t)SH2N');
define('SECURE_AUTH_KEY',  'bg/<#,Z0nb-5*^m6ack:S?5{2*1]-+&SOw$#J9@)mo ~U-4 ~DvF>xhC~54Btm$e');
define('LOGGED_IN_KEY',    '|68)lIa-xAp+!urFsk 6vK?kmc88m/QS?+Av}Y`|0>[1:SaI2 nR%,LR(&p:_r%u');
define('NONCE_KEY',        '%wvYbgZV=K9->409^(iD3-Sp+!||iq-fKK]g>$6|{:L8Ru)}/5{Px:?5l+LO*0=X');
define('AUTH_SALT',        '|p2B-RRY+6^e{RaOj?+H*mRP|%o2p CP/Y3~`-1jYzaC+$w&++C ur#kB.|K|!_+');
define('SECURE_AUTH_SALT', 'JC1b6HM -74YEUSNsF#K@iM}SDDx0vV$@.2_FD,}h&jMn_r+I.y^$(_Tekt9` 1.');
define('LOGGED_IN_SALT',   '<tF$Hln`cpp$;L8R$[*7}2-mcMT>a_#?/I{.p`T-v|as6@|b*|&B2WLP(3@uJ-WK');
define('NONCE_SALT',       '&QdfSsfoWZ5aS(h-#MO(i0THi8?^[/&;(g+BZ#xYF.BP`RnfPxid:#H7|u(AbW]X');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('FS_METHOD', 'direct');
