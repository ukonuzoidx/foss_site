<?php
/**
 * Digital Download Widget Areas
 * 
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * @package Digital_Download
 */

function digital_download_widgets_init(){    
    $sidebars = array(
        'sidebar'   => array(
            'name'        => __( 'Sidebar', 'digital-download' ),
            'id'          => 'sidebar', 
            'description' => __( 'Default Sidebar', 'digital-download' ),
        ),
        'feature'   => array(
            'name'        => __( 'Features Section', 'digital-download' ),
            'id'          => 'feature', 
            'description' => __( 'Add "Text" & "Rara: Icon Text" widgets for feature section.', 'digital-download' ),
        ),
        'testimonial' => array(
            'name'        => __( 'Testimonial Section', 'digital-download' ),
            'id'          => 'testimonial', 
            'description' => __( 'Add "Text" & "Rara: Testimonial" widgets for testimonial section.', 'digital-download' ),
        ),
        'cta' => array(
            'name'        => __( 'Call To Action Section', 'digital-download' ),
            'id'          => 'cta', 
            'description' => __( 'Add "Rara: Call To Action" widget for Call to Action section.', 'digital-download' ),
        ),
        'footer-one'=> array(
            'name'        => __( 'Footer One', 'digital-download' ),
            'id'          => 'footer-one', 
            'description' => __( 'Add footer one widgets here.', 'digital-download' ),
        ),
        'footer-two'=> array(
            'name'        => __( 'Footer Two', 'digital-download' ),
            'id'          => 'footer-two', 
            'description' => __( 'Add footer two widgets here.', 'digital-download' ),
        ),
        'footer-three'=> array(
            'name'        => __( 'Footer Three', 'digital-download' ),
            'id'          => 'footer-three', 
            'description' => __( 'Add footer three widgets here.', 'digital-download' ),
        ),
        'footer-four'=> array(
            'name'        => __( 'Footer Four', 'digital-download' ),
            'id'          => 'footer-four', 
            'description' => __( 'Add footer four widgets here.', 'digital-download' ),
        )
    );
    
    foreach( $sidebars as $sidebar ){
        register_sidebar( array(
    		'name'          => esc_html( $sidebar['name'] ),
    		'id'            => esc_attr( $sidebar['id'] ),
    		'description'   => esc_html( $sidebar['description'] ),
    		'before_widget' => '<section id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</section>',
    		'before_title'  => '<h2 class="widget-title" itemprop="name">',
    		'after_title'   => '</h2>',
    	) );
    }
}
add_action( 'widgets_init', 'digital_download_widgets_init' );