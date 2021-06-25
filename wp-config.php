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
define('DB_NAME', 'fossa_site');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'cEVXC T|<zw_.fjE~Gg~U3]yS{KX,#21,3Q=e8?b=fXud->4XY9[_Chg.BfYczlo');
define('SECURE_AUTH_KEY',  'Yl$YLlXt1Uvd9q$s%t)#?mzvg8r|G+e!gUZ)K]Nbc2Z^5hzihmcWATc;BAP5! {=');
define('LOGGED_IN_KEY',    '!i1c2njeCS{Q|e.D<f,C5Qm2(o}0f[Kz@< [XfvY7+:.,Fk_lx{DcCziBSH@ufi ');
define('NONCE_KEY',        'bPvHsc@9+yL_my.%4g}vqewv)?,u+JApy{HD~zI&q~~~UgcF(}I)WG$zt=z;$,wR');
define('AUTH_SALT',        '<{,e92c#LVMD7B`^^oI.zkT=(_.ZLtoLCt-}8+e,T23^30RMu/wPn8ZsY>&CdE8p');
define('SECURE_AUTH_SALT', '5g$<dT]#)Xs5k(;xn4hNmV_)xxCIC,<F^T_sl[8uOvQ-(*7K3vPWz0uIElGI@Y#;');
define('LOGGED_IN_SALT',   '0O@G/-nX/m5!TW4<aR[)IISnB 3g{:;#dIt6/O{R/PA-W}t(Y$^~1J;],IVj)K~?');
define('NONCE_SALT',       'wi*3FpRTPvulz--^HKb,lH&m<g-ZWLmc$|RBMT >J |+A*X0P}R#Tgcb1e->iEh}');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'foosa_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
