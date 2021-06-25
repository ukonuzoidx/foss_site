<?php
/**
 * Digital Download Theme Customizer
 *
 * @package Digital_Download
 */

/**
 * Requiring customizer panels & sections
*/
$digital_download_panels = array( 'info', 'site', 'appearance', 'layout', 'general', 'frontpage', 'footer' );

foreach( $digital_download_panels as $p ){
    require get_template_directory() . '/inc/customizer/' . $p . '.php';
}

/**
 * Sanitization Functions
*/
require get_template_directory() . '/inc/customizer/sanitization-functions.php';

/**
 * Active Callbacks
*/
require get_template_directory() . '/inc/customizer/active-callback.php';

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function digital_download_customize_preview_js() {
	wp_enqueue_script( 'digital-download-customizer', get_template_directory_uri() . '/inc/js/customizer.js', array( 'customize-preview' ), DIGITAL_DOWNLOAD_THEME_VERSION, true );
}
add_action( 'customize_preview_init', 'digital_download_customize_preview_js' );

function digital_download_customize_script(){
    $array = array( 'home' => get_permalink( get_option( 'page_on_front' ) ) );
    wp_enqueue_style( 'digital-download-customize', get_template_directory_uri() . '/inc/css/customize.css', array(), DIGITAL_DOWNLOAD_THEME_VERSION );
    wp_enqueue_script( 'digital-download-customize', get_template_directory_uri() . '/inc/js/customize.js', array( 'jquery', 'customize-controls' ), DIGITAL_DOWNLOAD_THEME_VERSION, true );
    wp_localize_script( 'digital-download-customize', 'digital_download_cdata', $array );

    wp_localize_script( 'digital-download-repeater', 'digital_download_customize',
		array(
			'nonce' => wp_create_nonce( 'digital_download_customize_nonce' )
		)
	);
}
add_action( 'customize_controls_enqueue_scripts', 'digital_download_customize_script' );