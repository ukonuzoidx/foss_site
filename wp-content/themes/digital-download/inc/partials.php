<?php
/**
 * Digital Download Customizer Partials
 *
 * @package Digital_Download
 */

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function digital_download_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function digital_download_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

if( ! function_exists( 'digital_download_get_banner_title' ) ) :
/**
 * Banner Title
*/
function digital_download_get_banner_title(){
    return esc_html( get_theme_mod( 'banner_title', __( 'Most Selective Market of Digital Products for Creatives.', 'digital-download' ) ) );
}
endif;

if( ! function_exists( 'digital_download_get_banner_sub_title' ) ) :
/**
 * Banner Sub Title
*/
function digital_download_get_banner_sub_title(){
    return wp_kses_post( get_theme_mod( 'banner_subtitle', __( 'Curated Design Resources to Energize Your Creative Workflow.', 'digital-download' ) ) );
}
endif;

if( ! function_exists( 'digital_download_get_banner_label' ) ) :
/**
 * Banner Label
*/
function digital_download_get_banner_label(){
    return esc_html( get_theme_mod( 'banner_label', __( 'View All Products', 'digital-download' ) ) );
}
endif;

if( ! function_exists( 'digital_download_get_second_label' ) ) :
/**
 * Second Label
*/
function digital_download_get_second_label(){
    return esc_html( get_theme_mod( 'second_label', __( 'See Our Pricing', 'digital-download' ) ) );
}
endif;

if( ! function_exists( 'digital_download_get_download_title' ) ) :
/**
 * Download Section Title
*/
function digital_download_get_download_title(){
    return esc_html( get_theme_mod( 'download_title', __( 'Recently Added Items', 'digital-download' ) ) );
}
endif;

if( ! function_exists( 'digital_download_get_download_subtitle' ) ) :
/**
 * Download Section Sub Title
*/
function digital_download_get_download_subtitle(){
    return esc_html( get_theme_mod( 'download_subtitle', __( 'Checkout the latest products to be added to the marketplace, or <a href="#">Click here</a> to view all items.', 'digital-download' ) ) );
}
endif;

if( ! function_exists( 'digital_download_get_download_more_label' ) ) :
/**
 * Download More Label
*/
function digital_download_get_download_more_label(){
    return esc_html( get_theme_mod( 'download_more_label', __( 'Browse All Products', 'digital-download' ) ) );        
}
endif;

if( ! function_exists( 'digital_download_get_read_more' ) ) :
/**
 * Display blog readmore button
*/
function digital_download_get_read_more(){
    return esc_html( get_theme_mod( 'read_more_text', __( 'Read More', 'digital-download' ) ) );
}
endif;

if( ! function_exists( 'digital_download_get_related_title' ) ) :
/**
 * Display blog readmore button
*/
function digital_download_get_related_title(){
    return esc_html( get_theme_mod( 'related_post_title', __( 'You may also like...', 'digital-download' ) ) );
}
endif;

if( ! function_exists( 'digital_download_get_footer_copyright' ) ) :
/**
 * Footer Copyright
*/
function digital_download_get_footer_copyright(){
    $copyright = get_theme_mod( 'footer_copyright' );
    echo '<span class="copyright">';
    if( $copyright ){
        echo wp_kses_post( $copyright );
    }else{
        esc_html_e( '&copy; Copyright ', 'digital-download' );
        echo date_i18n( esc_html__( 'Y', 'digital-download' ) );
        echo ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>. ';
        esc_html_e( 'All Rights Reserved. ', 'digital-download' );
    }
    echo '</span>'; 
}
endif;