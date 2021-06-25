<?php
/**
 * Banner Section
 * 
 * @package Digital_Download
 */

$ed_banner       = get_theme_mod( 'ed_banner_section', true );
$banner_title    = get_theme_mod( 'banner_title', __( 'Most Selective Market of Digital Products for Creatives.', 'digital-download' ) );
$banner_subtitle = get_theme_mod( 'banner_subtitle', __( 'Curated Design Resources to Energize Your Creative Workflow.', 'digital-download' ) );
$banner_label    = get_theme_mod( 'banner_label', __( 'View All Products', 'digital-download' ) );
$banner_link     = get_theme_mod( 'banner_link', '#' );
$second_label    = get_theme_mod( 'second_label', __( 'See Our Pricing', 'digital-download' ) );
$second_link     = get_theme_mod( 'second_link', '#' );
$banner_image    = get_header_image();    
if( $banner_image ){ ?>
    <div id="banner_section" class="banner" style="background: url(<?php echo esc_url( $banner_image ); ?>) no-repeat;">
        <?php 
            if( $banner_title || $banner_subtitle || ( $banner_label && $banner_link ) || ( $second_label && $second_link ) ){
                echo '<div class="banner-text">';
                if( $banner_title ) echo '<h2 class="title">' . esc_html( $banner_title ) . '</h2>';
                if( $banner_subtitle ) echo '<div class="banner-content">' . wp_kses_post( $banner_subtitle ) . '</div>';
        		if( ( $banner_label && $banner_link ) || ( $second_label && $second_link ) ){
                    echo '<div class="btn-holder">';
                    if( $banner_label && $banner_link ) echo '<a href="' . esc_url( $banner_link ) . '" class="btn-primary btn-view-product">' . esc_html( $banner_label ) . '</a>';
                    if( $second_label && $second_link ) echo '<a href="' . esc_url( $second_link ) . '" class="btn-primary btn-view-pricing">' . esc_html( $second_label ) . '</a>';
                    echo '</div>';
                }
                echo '</div>';                   
            }
        ?>
    </div>
<?php
}