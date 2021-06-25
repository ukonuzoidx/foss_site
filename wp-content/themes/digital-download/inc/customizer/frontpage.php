<?php
/**
 * Digital Download Front Page Settings
 *
 * @package Digital_Download
 */

function digital_download_customize_register_frontpage( $wp_customize ) {
	
    /** Front Page Settings */
    $wp_customize->add_panel( 
        'frontpage_settings',
         array(
            'priority'    => 60,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Front Page Settings', 'digital-download' ),
            'description' => __( 'Static Home Page settings.', 'digital-download' ),
        ) 
    );
    
    $wp_customize->get_section( 'header_image' )->panel              = 'frontpage_settings';
    $wp_customize->get_section( 'header_image' )->title              = __( 'Banner Section', 'digital-download' );
    $wp_customize->get_section( 'header_image' )->priority           = 10;
    $wp_customize->get_section( 'header_image' )->description        = '';                                               
    $wp_customize->get_setting( 'header_image' )->transport          = 'refresh';
    $wp_customize->get_setting( 'header_video' )->transport          = 'refresh';
    $wp_customize->get_setting( 'external_header_video' )->transport = 'refresh';
    
    /** Enable Download Section */
    $wp_customize->add_setting(
        'ed_banner_section',
        array(
            'default'           => true,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        )
    );

    $wp_customize->add_control(
        new Digital_Download_Toggle_Control(
            $wp_customize,
            'ed_banner_section',
            array(
                'label'       => __( 'Enable Banner Section', 'digital-download' ),
                'description' => __( 'Enable to show banner section.', 'digital-download' ),
                'section'     => 'header_image',
                'priority'    => 5
            )            
        )
    );
    
    /** Title */
    $wp_customize->add_setting(
        'banner_title',
        array(
            'default'           => __( 'Most Selective Market of Digital Products for Creatives.', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_title',
        array(
            'label'   => __( 'Title', 'digital-download' ),
            'section' => 'header_image',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_title', array(
        'selector'        => '.banner .banner-text .title',
        'render_callback' => 'digital_download_get_banner_title',
    ) );
    
    /** Sub Title */
    $wp_customize->add_setting(
        'banner_subtitle',
        array(
            'default'           => __( 'Curated Design Resources to Energize Your Creative Workflow.', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_subtitle',
        array(
            'label'   => __( 'Sub Title', 'digital-download' ),
            'section' => 'header_image',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_subtitle', array(
        'selector'        => '.banner .banner-text .banner-content',
        'render_callback' => 'digital_download_get_banner_sub_title',
    ) );
    
    /** Banner Label */
    $wp_customize->add_setting(
        'banner_label',
        array(
            'default'           => __( 'View All Products', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_label',
        array(
            'label'   => __( 'Banner Label', 'digital-download' ),
            'section' => 'header_image',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_label', array(
        'selector'        => '.banner .banner-text .btn-holder .btn-view-product',
        'render_callback' => 'digital_download_get_banner_label',
    ) );
    
    /** Banner Link */
    $wp_customize->add_setting(
        'banner_link',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'banner_link',
        array(
            'label'   => __( 'Banner Link', 'digital-download' ),
            'section' => 'header_image',
            'type'    => 'text',
        )
    );
    
    /** Second Label */
    $wp_customize->add_setting(
        'second_label',
        array(
            'default'           => __( 'See Our Pricing', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'second_label',
        array(
            'label'   => __( 'Second Label', 'digital-download' ),
            'section' => 'header_image',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'second_label', array(
        'selector'        => '.banner .banner-text .btn-holder .btn-view-pricing',
        'render_callback' => 'digital_download_get_second_label',
    ) );
    
    /** Second Link */
    $wp_customize->add_setting(
        'second_link',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'second_link',
        array(
            'label'   => __( 'Second Link', 'digital-download' ),
            'section' => 'header_image',
            'type'    => 'text',
        )
    );
    /** Banner Section Ends */
    
    /** Download Section */
    $wp_customize->add_section(
        'download_section',
        array(
            'title'           => __( 'Download Section', 'digital-download' ),
            'priority'        => 20,
            'panel'           => 'frontpage_settings',
            'active_callback' => 'digital_download_is_edd_activated'
        )
    );
    
    /** Enable Download Section */
    $wp_customize->add_setting(
        'ed_download_section',
        array(
            'default'           => true,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        )
    );

    $wp_customize->add_control(
        new Digital_Download_Toggle_Control(
            $wp_customize,
            'ed_download_section',
            array(
                'label'       => __( 'Enable Download Section', 'digital-download' ),
                'description' => __( 'Enable to show download section.', 'digital-download' ),
                'section'     => 'download_section',
            )            
        )
    );
    
    /** Title */
    $wp_customize->add_setting(
        'download_title',
        array(
            'default'           => __( 'Recently Added Items', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'download_title',
        array(
            'label'   => __( 'Title', 'digital-download' ),
            'section' => 'download_section',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'download_title', array(
        'selector'        => '.recent-items .section-header .section-title',
        'render_callback' => 'digital_download_get_download_title',
    ) );
    
    /** Sub Title */
    $wp_customize->add_setting(
        'download_subtitle',
        array(
            'default'           => __( 'Checkout the latest products to be added to the marketplace, or <a href="#">Click here</a> to view all items.', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'download_subtitle',
        array(
            'label'   => __( 'Sub Title', 'digital-download' ),
            'section' => 'download_section',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'download_subtitle', array(
        'selector'        => '.recent-items .section-header .section-header-content',
        'render_callback' => 'digital_download_get_download_subtitle',
    ) );
    
    /** No. of Downloads */
    $wp_customize->add_setting(
		'no_recent_download',
		array(
			'default'			=> '6',
			'sanitize_callback' => 'digital_download_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Digital_Download_Select_Control(
    		$wp_customize,
    		'no_recent_download',
    		array(
                'label'	  => __( 'No. of Downloads', 'digital-download' ),
    			'section' => 'download_section',
    			'choices' => array(
                    '3' => __( '3', 'digital-download' ),
                    '6' => __( '6', 'digital-download' ),
                    '9' => __( '9', 'digital-download' ),
                ),	
     		)
		)
	);
    
    /** Button label */
    $wp_customize->add_setting(
        'download_more_label',
        array(
            'default'           => __( 'Browse All Products', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'download_more_label',
        array(
            'label'   => __( 'Button Label', 'digital-download' ),
            'section' => 'download_section',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'download_more_label', array(
        'selector'        => '.recent-items .btn-holder .btn-primary',
        'render_callback' => 'digital_download_get_download_more_label',
    ) );
    
    /** Button Link */
    $wp_customize->add_setting(
		'download_more_link',
		array(
			'default'			=> '',
			'sanitize_callback' => 'digital_download_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Digital_Download_Select_Control(
    		$wp_customize,
    		'download_more_link',
    		array(
                'label'	  => __( 'Button Link', 'digital-download' ),
    			'section' => 'download_section',
    			'choices' => digital_download_get_posts( 'page' ),	
     		)
		)
	);
    /** Recent Download Ends */
    
    /** Newsletter Section */
    $wp_customize->add_section(
        'newsletter_section',
        array(
            'title'           => __( 'Newsletter Section', 'digital-download' ),
            'priority'        => 30,
            'panel'           => 'frontpage_settings',
            'active_callback' => 'digital_download_is_btnw_activated'
        )
    );
    
    /** Enable Download Section */
    $wp_customize->add_setting(
        'ed_newsletter_section',
        array(
            'default'           => true,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        )
    );

    $wp_customize->add_control(
        new Digital_Download_Toggle_Control(
            $wp_customize,
            'ed_newsletter_section',
            array(
                'label'       => __( 'Enable Newsletter Section', 'digital-download' ),
                'description' => __( 'Enable to show newsletter section.', 'digital-download' ),
                'section'     => 'newsletter_section',
            )            
        )
    );
    
    /** Newsletter Shortcode */
    $wp_customize->add_setting(
        'front_newsletter_shortcode',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'front_newsletter_shortcode',
        array(
            'type'        => 'text',
            'section'     => 'newsletter_section',
            'label'       => __( 'Newsletter Shortcode', 'digital-download' ),
            'description' => __( 'Enter the BlossomThemes Email Newsletters Shortcode. Ex. [BTEN id="356"]', 'digital-download' ),
        )
    );
    /** Newsletter Section Ends */    
}
add_action( 'customize_register', 'digital_download_customize_register_frontpage' );