<?php
/**
 * Companion Filters
 * 
 * @package Digital_Download
*/

if( ! function_exists( 'digital_download_cta_bg_color_filter' ) ) :
/**
 * Filter to add background color of CTA widget.
 */    
function digital_download_cta_bg_color_filter(){
	return '#f1f5f8';
}
endif;
add_filter( 'rrtc_cta_bg_color', 'digital_download_cta_bg_color_filter' );

if( ! function_exists( 'digital_download_testimonial_widget_filter' ) ) :
/**
 * Filter Testimonial Widget
*/
function digital_download_testimonial_widget_filter( $html, $args, $instance ){
    $name        = ! empty( $instance['name'] ) ? $instance['name'] : '' ;        
    $designation = ! empty( $instance['designation'] ) ? $instance['designation'] : '' ;        
    $testimonial = ! empty( $instance['testimonial'] ) ? $instance['testimonial'] : '';
    $image       = ! empty( $instance['image'] ) ? $instance['image'] : '';
    
    ob_start(); ?>    
    <div class="rtc-testimonial-holder">
        <div class="rtc-testimonial-inner-holder">
            <div class="img-holder">
            <?php 
            if( $image ){
                echo wp_get_attachment_image( $image, 'thumbnail', false, array( 'alt' => esc_attr( $name ), 'itemprop' => 'image' ) );
            }else{
                digital_download_get_fallback_svg( 'thumbnail' );//falback                
            }?>
            </div>

            <div class="text-holder">
                <div class="testimonial-meta">
                   <?php 
                        if( $name ) echo '<span class="name">' . esc_html( $name ) . '</span>';
                        if( isset( $designation ) && $designation!='' ){
                            echo '<span class="designation">' . esc_html( $designation ) . '</span>';
                        }
                    ?>
                </div>                              
                <?php if( $testimonial ) echo '<div class="testimonial-content">' . wpautop( wp_kses_post( $testimonial ) ) . '</div>'; ?>
            </div>
        </div>
    </div>
    <?php 
    $html = ob_get_clean();
    return $html;
}
endif;
add_filter( 'raratheme_companion_testimonial_widget_filter', 'digital_download_testimonial_widget_filter', 10, 3 );