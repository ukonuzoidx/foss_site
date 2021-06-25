<?php
/**
 * Digital Download General Settings
 *
 * @package Digital_Download
 */

function digital_download_customize_register_general( $wp_customize ) {
	
    /** General Settings */
    $wp_customize->add_panel( 
        'general_settings',
         array(
            'priority'    => 85,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'General Settings', 'digital-download' ),
            'description' => __( 'Customize Header, Social, SEO, Post/Page settings.', 'digital-download' ),
        ) 
    );    
    
    /** Header Settings */
    $wp_customize->add_section(
        'header_settings',
        array(
            'title'    => __( 'Header Settings', 'digital-download' ),
            'priority' => 20,
            'panel'    => 'general_settings',
        )
    );
    
    /** Header Search */
    $wp_customize->add_setting( 
        'ed_header_search', 
        array(
            'default'           => true,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Toggle_Control( 
			$wp_customize,
			'ed_header_search',
			array(
				'section'     => 'header_settings',
				'label'	      => __( 'Header Search', 'digital-download' ),
                'description' => __( 'Enable to show Search button in header.', 'digital-download' ),
			)
		)
	);
    
    /** Shopping Cart */
    $wp_customize->add_setting( 
        'ed_shopping_cart', 
        array(
            'default'           => true,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Toggle_Control( 
			$wp_customize,
			'ed_shopping_cart',
			array(
				'section'         => 'header_settings',
				'label'	          => __( 'Shopping Cart', 'digital-download' ),
                'description'     => __( 'Enable to show Shopping Cart in header.', 'digital-download' ),
                'active_callback' => 'digital_download_is_edd_activated'
			)
		)
	);
    
    /** Login Label */
    $wp_customize->add_setting(
        'login_label',
        array(
            'default'           => __( 'Login', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'login_label',
        array(
            'type'            => 'text',
            'section'         => 'header_settings',
            'label'           => __( 'Login Label', 'digital-download' ),
            'description'     => __( 'Change the "Login" label in the header, when the user has not logged in.', 'digital-download' ),
            'active_callback' => 'digital_download_is_edd_activated'
        )
    );
    
    /** Login Page */
    $wp_customize->add_setting(
		'login_page',
		array(
			'default'			=> '',
			'sanitize_callback' => 'digital_download_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Digital_Download_Select_Control(
    		$wp_customize,
    		'login_page',
    		array(
                'label'	          => __( 'Login Page', 'digital-download' ),
    			'section'         => 'header_settings',
    			'choices'         => digital_download_get_posts( 'page' ),
                'active_callback' => 'digital_download_is_edd_activated'	
     		)
		)
	);
    
    /** Logout Label */
    $wp_customize->add_setting(
        'logout_label',
        array(
            'default'           => __( 'Logout', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'logout_label',
        array(
            'type'            => 'text',
            'section'         => 'header_settings',
            'label'           => __( 'Logout Label', 'digital-download' ),
            'description'     => __( 'Change the "Logout" label in the header, when the user is in dashboard page.', 'digital-download' ),
            'active_callback' => 'digital_download_is_edd_activated'
        )
    );
    
    /** Dashboard Label */
    $wp_customize->add_setting(
        'dashboard_label',
        array(
            'default'           => __( 'Dashboard', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'dashboard_label',
        array(
            'type'            => 'text',
            'section'         => 'header_settings',
            'label'           => __( 'Dashboard Label', 'digital-download' ),
            'description'     => __( 'Change the "Dashboard" label in the header, when the user has logged in.', 'digital-download' ),
            'active_callback' => 'digital_download_is_edd_activated'
        )
    );
    
    /** Dashboard Page */
    $wp_customize->add_setting(
		'dashboard_page',
		array(
			'default'			=> '',
			'sanitize_callback' => 'digital_download_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Digital_Download_Select_Control(
    		$wp_customize,
    		'dashboard_page',
    		array(
                'label'	          => __( 'Dashboard Page', 'digital-download' ),
    			'section'         => 'header_settings',
    			'choices'         => digital_download_get_posts( 'page' ),
                'active_callback' => 'digital_download_is_edd_activated'	
     		)
		)
	);
    /** Header Settings Ends */
    
    /** Social Media Settings */
    $wp_customize->add_section(
        'social_media_settings',
        array(
            'title'    => __( 'Social Media Settings', 'digital-download' ),
            'priority' => 30,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_social_links', 
        array(
            'default'           => true,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Toggle_Control( 
			$wp_customize,
			'ed_social_links',
			array(
				'section'     => 'social_media_settings',
				'label'	      => __( 'Enable Social Links', 'digital-download' ),
                'description' => __( 'Enable to show social links at footer.', 'digital-download' ),
			)
		)
	);
    
    $wp_customize->add_setting( 
        new Digital_Download_Repeater_Setting( 
            $wp_customize, 
            'social_links', 
            array(
                'default' => '',
                'sanitize_callback' => array( 'Digital_Download_Repeater_Setting', 'sanitize_repeater_setting' ),
            ) 
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Control_Repeater(
			$wp_customize,
			'social_links',
			array(
				'section' => 'social_media_settings',				
				'label'	  => __( 'Social Links', 'digital-download' ),
				'fields'  => array(
                    'font' => array(
                        'type'        => 'font',
                        'label'       => __( 'Font Awesome Icon', 'digital-download' ),
                        'description' => __( 'Example: fab fa-facebook-f', 'digital-download' ),
                    ),
                    'link' => array(
                        'type'        => 'url',
                        'label'       => __( 'Link', 'digital-download' ),
                        'description' => __( 'Example: http://facebook.com', 'digital-download' ),
                    )
                ),
                'row_label' => array(
                    'type' => 'field',
                    'value' => __( 'links', 'digital-download' ),
                    'field' => 'link'
                ),
                'choices'   => array(
                    'limit' => 10
                )                        
			)
		)
	);
    /** Social Media Settings Ends */
    
    /** SEO Settings */
    $wp_customize->add_section(
        'seo_settings',
        array(
            'title'    => __( 'SEO Settings', 'digital-download' ),
            'priority' => 40,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_post_update_date', 
        array(
            'default'           => true,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Toggle_Control( 
			$wp_customize,
			'ed_post_update_date',
			array(
				'section'     => 'seo_settings',
				'label'	      => __( 'Enable Last Update Post Date', 'digital-download' ),
                'description' => __( 'Enable to show last updated post date on listing as well as in single post.', 'digital-download' ),
			)
		)
	);      
    /** SEO Settings Ends */
    
    /** Posts(Blog) & Pages Settings */
    $wp_customize->add_section(
        'post_page_settings',
        array(
            'title'    => __( 'Posts(Blog) & Pages Settings', 'digital-download' ),
            'priority' => 50,
            'panel'    => 'general_settings',
        )
    );
    
    /** Prefix Archive Page */
    $wp_customize->add_setting( 
        'ed_prefix_archive', 
        array(
            'default'           => false,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Toggle_Control( 
			$wp_customize,
			'ed_prefix_archive',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Hide Prefix in Archive Page', 'digital-download' ),
                'description' => __( 'Enable to hide prefix in archive page.', 'digital-download' ),
			)
		)
	);
    
    /** Blog Excerpt */
    $wp_customize->add_setting( 
        'ed_excerpt', 
        array(
            'default'           => true,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Toggle_Control( 
			$wp_customize,
			'ed_excerpt',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Enable Blog Excerpt', 'digital-download' ),
                'description' => __( 'Enable to show excerpt or disable to show full post content.', 'digital-download' ),
			)
		)
	);
    
    /** Excerpt Length */
    $wp_customize->add_setting( 
        'excerpt_length', 
        array(
            'default'           => 55,
            'sanitize_callback' => 'digital_download_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Slider_Control( 
			$wp_customize,
			'excerpt_length',
			array(
				'section'	  => 'post_page_settings',
				'label'		  => __( 'Excerpt Length', 'digital-download' ),
				'description' => __( 'Automatically generated excerpt length (in words).', 'digital-download' ),
                'choices'	  => array(
					'min' 	=> 10,
					'max' 	=> 100,
					'step'	=> 5,
				)                 
			)
		)
	);
    
    /** Read More Text */
    $wp_customize->add_setting(
        'read_more_text',
        array(
            'default'           => __( 'Read More', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'read_more_text',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Read More Text', 'digital-download' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'read_more_text', array(
        'selector' => '.entry-footer .btn-readmore',
        'render_callback' => 'digital_download_get_read_more',
    ) );
    
    /** Note */
    $wp_customize->add_setting(
        'post_note_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Digital_Download_Note_Control( 
			$wp_customize,
			'post_note_text',
			array(
				'section'	  => 'post_page_settings',
				'description' => __( '<hr/>These options affect your individual posts.', 'digital-download' ),
			)
		)
    );
    
    /** Hide Author */
    $wp_customize->add_setting( 
        'ed_author', 
        array(
            'default'           => false,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Toggle_Control( 
			$wp_customize,
			'ed_author',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Hide Author Section', 'digital-download' ),
                'description' => __( 'Enable to hide author section below the post.', 'digital-download' ),
			)
		)
	);
    
    /** Show Related Posts */
    $wp_customize->add_setting( 
        'ed_related', 
        array(
            'default'           => true,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Toggle_Control( 
			$wp_customize,
			'ed_related',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Show Related Posts', 'digital-download' ),
                'description' => __( 'Enable to show related posts in single page.', 'digital-download' ),
			)
		)
	);
    
    /** Related Posts section title */
    $wp_customize->add_setting(
        'related_post_title',
        array(
            'default'           => __( 'You may also like...', 'digital-download' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'related_post_title',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Related Posts Section Title', 'digital-download' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'related_post_title', array(
        'selector'        => '.related-posts .section-title',
        'render_callback' => 'digital_download_get_related_title',
    ) );
    
    /** Hide Category */
    $wp_customize->add_setting( 
        'ed_category', 
        array(
            'default'           => false,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Toggle_Control( 
			$wp_customize,
			'ed_category',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Hide Category', 'digital-download' ),
                'description' => __( 'Enable to hide category.', 'digital-download' ),
			)
		)
	);
    
    /** Hide Post Author */
    $wp_customize->add_setting( 
        'ed_post_author', 
        array(
            'default'           => false,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Digital_Download_Toggle_Control( 
            $wp_customize,
            'ed_post_author',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Post Author', 'digital-download' ),
                'description' => __( 'Enable to hide post author.', 'digital-download' ),
            )
        )
    );
    
    /** Hide Posted Date */
    $wp_customize->add_setting( 
        'ed_post_date', 
        array(
            'default'           => false,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Toggle_Control( 
			$wp_customize,
			'ed_post_date',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Hide Posted Date', 'digital-download' ),
                'description' => __( 'Enable to hide posted date.', 'digital-download' ),
			)
		)
	);
    
    /** Show Featured Image */
    $wp_customize->add_setting( 
        'ed_featured_image', 
        array(
            'default'           => true,
            'sanitize_callback' => 'digital_download_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
		new Digital_Download_Toggle_Control( 
			$wp_customize,
			'ed_featured_image',
			array(
				'section'     => 'post_page_settings',
				'label'	      => __( 'Show Featured Image', 'digital-download' ),
                'description' => __( 'Enable to show featured image in post detail (single post).', 'digital-download' ),
			)
		)
	);
    /** Posts(Blog) & Pages Settings Ends */
}
add_action( 'customize_register', 'digital_download_customize_register_general' );