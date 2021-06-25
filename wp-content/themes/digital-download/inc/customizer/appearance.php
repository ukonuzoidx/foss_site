<?php
/**
 * Digital Download Appearance Settings
 *
 * @package Digital_Download
 */

function digital_download_customize_register_appearance( $wp_customize ) {
    
    /** Appearance Settings */
    $wp_customize->add_panel( 
        'appearance_settings',
         array(
            'priority'    => 50,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Appearance Settings', 'digital-download' ),
            'description' => __( 'Customize Typography, Background Image & Color.', 'digital-download' ),
        ) 
    );
    
    /** Move Background Image section to appearance panel */
    $wp_customize->get_section( 'colors' )->panel              = 'appearance_settings';
    $wp_customize->get_section( 'colors' )->priority           = 10;
    $wp_customize->get_section( 'background_image' )->panel    = 'appearance_settings';
    $wp_customize->get_section( 'background_image' )->priority = 15;
    
}
add_action( 'customize_register', 'digital_download_customize_register_appearance' );