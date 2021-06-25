<?php
/**
 * Digital Download Layout Settings
 *
 * @package Digital_Download
 */

function digital_download_customize_register_general_layout( $wp_customize ) {
	
    /** General Sidebar Layout */
    $wp_customize->add_section(
        'general_layout',
        array(
            'title'    => __( 'General Sidebar Layout', 'digital-download' ),
            'priority' => 55,
        )
    );
    
    /** Page Sidebar layout */
    $wp_customize->add_setting( 
        'page_sidebar_layout', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'digital_download_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Radio_Image_Control(
			$wp_customize,
			'page_sidebar_layout',
			array(
				'section'	  => 'general_layout',
				'label'		  => __( 'Page Sidebar Layout', 'digital-download' ),
				'description' => __( 'This is the general sidebar layout for pages. You can override the sidebar layout for individual page in repective page.', 'digital-download' ),
				'choices'	  => array(
					'no-sidebar'    => get_template_directory_uri() . '/images/1c.jpg',
                    'centered'      => get_template_directory_uri() . '/images/1cc.jpg',
					'left-sidebar'  => get_template_directory_uri() . '/images/2cl.jpg',
                    'right-sidebar' => get_template_directory_uri() . '/images/2cr.jpg',
				)
			)
		)
	);
    
    /** Post Sidebar layout */
    $wp_customize->add_setting( 
        'post_sidebar_layout', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'digital_download_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Radio_Image_Control(
			$wp_customize,
			'post_sidebar_layout',
			array(
				'section'	  => 'general_layout',
				'label'		  => __( 'Post Sidebar Layout', 'digital-download' ),
				'description' => __( 'This is the general sidebar layout for posts. You can override the sidebar layout for individual post in repective post.', 'digital-download' ),
				'choices'	  => array(
					'no-sidebar'    => get_template_directory_uri() . '/images/1c.jpg',
                    'centered'      => get_template_directory_uri() . '/images/1cc.jpg',
					'left-sidebar'  => get_template_directory_uri() . '/images/2cl.jpg',
                    'right-sidebar' => get_template_directory_uri() . '/images/2cr.jpg',
				)
			)
		)
	);
    
    /** Default Sidebar layout */
    $wp_customize->add_setting( 
        'layout_style', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'digital_download_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Radio_Image_Control(
			$wp_customize,
			'layout_style',
			array(
				'section'	  => 'general_layout',
				'label'		  => __( 'Default Sidebar Layout', 'digital-download' ),
				'description' => __( 'This is the general sidebar layout for whole site.', 'digital-download' ),
				'choices'	  => array(
					'no-sidebar'    => get_template_directory_uri() . '/images/1c.jpg',
                    'left-sidebar'  => get_template_directory_uri() . '/images/2cl.jpg',
                    'right-sidebar' => get_template_directory_uri() . '/images/2cr.jpg',
				)
			)
		)
	);
    
}
add_action( 'customize_register', 'digital_download_customize_register_general_layout' );