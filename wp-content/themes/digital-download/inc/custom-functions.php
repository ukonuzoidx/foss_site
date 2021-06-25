<?php
/**
 * Digital Download Custom functions and definitions
 *
 * @package Digital_Download
 */

if ( ! function_exists( 'digital_download_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function digital_download_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Digital Download, use a find and replace
	 * to change 'digital-download' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'digital-download', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'digital-download' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'digital_download_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo', array( 'header-text' => array( 'site-title', 'site-description' ) ) );
    
    /**
     * Add support for custom header.
    */
    add_theme_support( 'custom-header', apply_filters( 'digital_download_custom_header_args', array(
		'default-image' => get_template_directory_uri() . '/images/banner-img.jpg',
		'width'         => 1920, 
		'height'        => 612, 
		'header-text'   => false
	) ) );
    
    register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/images/banner-img.jpg',
			'thumbnail_url' => '%s/images/banner-img.jpg',
			'description'   => __( 'Default Header Image', 'digital-download' ),
		),
	) );
     
    /**
     * Add Custom Images sizes.
    */    
    add_image_size( 'digital-download-schema', 600, 60 );
    add_image_size( 'digital-download-fullwidth', 1170, 658, true );
    add_image_size( 'digital-download-related', 370, 280, true );
    
    /** Starter Content */
    $starter_content = array(
        // Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts' => array( 'home', 'blog' ),
		
        // Default to a static front page and assign the front and posts pages.
		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),
        
        // Set up nav menus for each of the two areas registered in the theme.
		'nav_menus' => array(
			// Assign a menu to the "top" location.
			'primary' => array(
				'name' => __( 'Primary', 'digital-download' ),
				'items' => array(
					'page_home',
					'page_blog'
				)
			)
		),
    );
    
    $starter_content = apply_filters( 'digital_download_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );
    
    // Add theme support for Responsive Videos.
    add_theme_support( 'jetpack-responsive-videos' );
}
endif;
add_action( 'after_setup_theme', 'digital_download_setup' );

if( ! function_exists( 'digital_download_content_width' ) ) :
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function digital_download_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'digital_download_content_width', 780 );
}
endif;
add_action( 'after_setup_theme', 'digital_download_content_width', 0 );

if( ! function_exists( 'digital_download_template_redirect_content_width' ) ) :
/**
* Adjust content_width value according to template.
*
* @return void
*/
function digital_download_template_redirect_content_width(){
	$sidebar = digital_download_sidebar();
    if( $sidebar ){	   
       $GLOBALS['content_width'] = 780;        
	}else{
        if( is_singular() ){
            if( digital_download_sidebar( true ) === 'full-width centered' ){
                $GLOBALS['content_width'] = 780; 
            }else{
                $GLOBALS['content_width'] = 1170;                
            }                
        }else{
            $GLOBALS['content_width'] = 1170; 
        }
	}
}
endif;
add_action( 'template_redirect', 'digital_download_template_redirect_content_width' );

if( ! function_exists( 'digital_download_scripts' ) ) :
/**
 * Enqueue scripts and styles.
 */
function digital_download_scripts(){
	// Use minified libraries if SCRIPT_DEBUG is false
    $build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
    
    if( digital_download_is_edd_activated() )
    wp_enqueue_script( 'digital-download-edd', get_template_directory_uri() . '/js' . $build . '/edd' . $suffix . '.js', array( 'jquery' ), DIGITAL_DOWNLOAD_THEME_VERSION, true );
    
    wp_enqueue_style( 'digital-download-google-fonts', digital_download_fonts_url(), array(), null );
    wp_enqueue_style( 'digital-download', get_stylesheet_uri(), array(), DIGITAL_DOWNLOAD_THEME_VERSION );

	wp_enqueue_script( 'all', get_template_directory_uri() . '/js' . $build . '/all' . $suffix . '.js', array( 'jquery' ), '5.6.3', true );
    wp_enqueue_script( 'v4-shims', get_template_directory_uri() . '/js' . $build . '/v4-shims' . $suffix . '.js', array( 'jquery', 'all' ), '5.6.3', true );
    wp_enqueue_script( 'digital-download-modal-accessibility', get_template_directory_uri() . '/js' . $build . '/modal-accessibility' . $suffix . '.js', array( 'jquery' ), DIGITAL_DOWNLOAD_THEME_VERSION, true );
	wp_enqueue_script( 'digital-download', get_template_directory_uri() . '/js' . $build . '/custom' . $suffix . '.js', array( 'jquery' ), DIGITAL_DOWNLOAD_THEME_VERSION, true );
    
    $array = array( 'is_rtc_active' => digital_download_is_rtc_activated() );
    
    wp_localize_script( 'digital-download', 'digital_download', $array );
            
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
endif;
add_action( 'wp_enqueue_scripts', 'digital_download_scripts' );

if( ! function_exists( 'digital_download_admin_scripts' ) ) :
/**
 * Enqueue admin scripts and styles.
*/
function digital_download_admin_scripts(){
    wp_enqueue_style( 'digital-download-admin', get_template_directory_uri() . '/inc/css/admin.css', '', DIGITAL_DOWNLOAD_THEME_VERSION );
}
endif; 
add_action( 'admin_enqueue_scripts', 'digital_download_admin_scripts' );

if( ! function_exists( 'digital_download_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function digital_download_body_classes( $classes ) {
	$blog_layout = get_theme_mod( 'blog_page_layout', 'classic' );
    $ed_banner   = get_theme_mod( 'ed_banner_section', true );
    
    // Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
    
    // Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color';
	}
    
    $classes[] = digital_download_sidebar( true );
    
    if( ( is_front_page() && ! is_home() ) && has_custom_header() && $ed_banner ){
        $classes[] = 'hasbanner';
    }
    
	return $classes;
}
endif;
add_filter( 'body_class', 'digital_download_body_classes' );

if( ! function_exists( 'digital_download_post_classes' ) ) :
/**
 * Add custom classes to the array of post classes.
*/
function digital_download_post_classes( $classes ){
    
    if( is_search() ){
        $classes[] = 'search-post';
    }
    
    return $classes;
}
endif;
add_filter( 'post_class', 'digital_download_post_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function digital_download_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'digital_download_pingback_header' );

if( ! function_exists( 'digital_download_change_comment_form_default_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function digital_download_change_comment_form_default_fields( $fields ){    
    // get the current commenter if available
    $commenter = wp_get_current_commenter();
 
    // core functionality
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $required = ( $req ? " required" : '' );
    $author   = ( $req ? __( 'Name*', 'digital-download' ) : __( 'Name', 'digital-download' ) );
    $email    = ( $req ? __( 'Email*', 'digital-download' ) : __( 'Email', 'digital-download' ) );
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><label class="screen-reader-text" for="author">' . esc_html__( 'Name', 'digital-download' ) . '<span class="required">*</span></label><input id="author" name="author" placeholder="' . esc_attr( $author ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $required . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><label class="screen-reader-text" for="email">' . esc_html__( 'Email', 'digital-download' ) . '<span class="required">*</span></label><input id="email" name="email" placeholder="' . esc_attr( $email ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . $required. ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><label class="screen-reader-text" for="url">' . esc_html__( 'Website', 'digital-download' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'digital-download' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'digital_download_change_comment_form_default_fields' );

if( ! function_exists( 'digital_download_change_comment_form_defaults' ) ) :
/**
 * Change Comment Form defaults
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function digital_download_change_comment_form_defaults( $defaults ){    
    $defaults['comment_field'] = '<p class="comment-form-comment"><label class="screen-reader-text" for="comment">' . esc_html__( 'Comment', 'digital-download' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'digital-download' ) . '" cols="45" rows="8" aria-required="true" required></textarea></p>';
    
    return $defaults;    
}
endif;
add_filter( 'comment_form_defaults', 'digital_download_change_comment_form_defaults' );

if ( ! function_exists( 'digital_download_excerpt_more' ) ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function digital_download_excerpt_more( $more ) {
	return is_admin() ? $more : ' &hellip; ';
}
endif;
add_filter( 'excerpt_more', 'digital_download_excerpt_more' );

if ( ! function_exists( 'digital_download_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function digital_download_excerpt_length( $length ) {
	$excerpt_length = get_theme_mod( 'excerpt_length', 55 );
    return is_admin() ? $length : absint( $excerpt_length );    
}
endif;
add_filter( 'excerpt_length', 'digital_download_excerpt_length', 999 );

if( ! function_exists( 'digital_download_get_the_archive_title' ) ) :
/**
 * Filter Archive Title
*/
function digital_download_get_the_archive_title( $title ){
    $ed_prefix = get_theme_mod( 'ed_prefix_archive', false );

    if( is_category() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . single_cat_title( '', false ) . '</h1>';
        }else{
            /* translators: Category archive title. 1: Category name */
            $title = sprintf( __( '%1$sBrowsing Category%2$s %3$s', 'digital-download' ), '<span class="archive-type">', '</span>', '<h1 class="page-title">' . single_cat_title( '', false ) . '</h1>' );
        }
    }elseif( is_tag() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . single_tag_title( '', false ) . '</h1>';    
        }else{
            /* translators: Tag archive title. 1: Tag name */
            $title = sprintf( __( '%1$sBrowsing Tag%2$s %3$s', 'digital-download' ), '<span class="archive-type">', '</span>', '<h1 class="page-title">' . single_tag_title( '', false ) . '</h1>' );
        }
    }elseif( is_year() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'digital-download' ) ) . '</h1>';
        }else{
            /* translators: Yearly archive title. 1: Year */
            $title = sprintf( __( '%1$sBrowsing Year%2$s %3$s', 'digital-download' ), '<span class="archive-type">', '</span>', '<h1 class="page-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'digital-download' ) ) . '</h1>' );
        }
    }elseif( is_month() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'digital-download' ) ) . '</h1>';
        }else{
            /* translators: Monthly archive title. 1: Month name and year */
            $title = sprintf( __( '%1$sBrowsing Month%2$s %3$s', 'digital-download' ), '<span class="archive-type">', '</span>', '<h1 class="page-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'digital-download' ) ) . '</h1>' );
        }
    }elseif( is_day() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'digital-download' ) ) . '</h1>';
        }else{
            /* translators: Daily archive title. 1: Date */
            $title = sprintf( __( '%1$sBrowsing Day%2$s %3$s', 'digital-download' ), '<span class="archive-type">', '</span>', '<h1 class="page-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'digital-download' ) ) . '</h1>' );
        }
    }elseif( is_post_type_archive() ) {        
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . post_type_archive_title( '', false ) . '</h1>';
        }else{
            /* translators: Post type archive title. 1: Post type name */
            $title = sprintf( __( '%1$sBrowsing Archives%2$s %3$s', 'digital-download' ), '<span class="archive-type">', '</span>', '<h1 class="page-title">' . post_type_archive_title( '', false ) . '</h1>' );
        }
    }elseif( is_tax() ) {
        $tax = get_taxonomy( get_queried_object()->taxonomy );
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . single_term_title( '', false ) . '</h1>';
        }else{                                                            
            /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
            $title = sprintf( '%1$s %2$s', '<span class="archive-type">' . esc_html( $tax->labels->singular_name ) . '</span>', '<h1 class="page-title">' . single_term_title( '', false ) . '</h1>' );
        }
    }else {
        $title = sprintf( __( '%1$sArchives%2$s', 'digital-download' ), '<h1 class="page-title">', '</h1>' );
    }
    return $title;
}
endif;
add_filter( 'get_the_archive_title', 'digital_download_get_the_archive_title' );

if( ! function_exists( 'digital_download_single_post_schema' ) ) :
/**
 * Single Post Schema
 *
 * @return string
 */
function digital_download_single_post_schema() {
    if ( is_singular( 'post' ) ) {
        global $post;
        $custom_logo_id = get_theme_mod( 'custom_logo' );

        $site_logo   = wp_get_attachment_image_src( $custom_logo_id , 'digital-download-schema' );
        $images      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        $excerpt     = digital_download_escape_text_tags( $post->post_excerpt );
        $content     = $excerpt === "" ? mb_substr( digital_download_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;
        $schema_type = ! empty( $custom_logo_id ) && has_post_thumbnail( $post->ID ) ? "BlogPosting" : "Blog";

        $args = array(
            "@context"  => "https://schema.org",
            "@type"     => $schema_type,
            "mainEntityOfPage" => array(
                "@type" => "WebPage",
                "@id"   => esc_url( get_permalink( $post->ID ) )
            ),
            "headline"      => esc_html( get_the_title( $post->ID ) ),
            "datePublished" => esc_html( get_the_time( DATE_ISO8601, $post->ID ) ),
            "dateModified"  => esc_html( get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ) ),
            "author"        => array(
                "@type"     => "Person",
                "name"      => digital_download_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
            ),
            "description" => ( class_exists('WPSEO_Meta') ? WPSEO_Meta::get_value( 'metadesc' ) : $content )
        );

        if ( has_post_thumbnail( $post->ID ) ) :
            $args['image'] = array(
                "@type"  => "ImageObject",
                "url"    => $images[0],
                "width"  => $images[1],
                "height" => $images[2]
            );
        endif;

        if ( ! empty( $custom_logo_id ) ) :
            $args['publisher'] = array(
                "@type"       => "Organization",
                "name"        => esc_html( get_bloginfo( 'name' ) ),
                "description" => wp_kses_post( get_bloginfo( 'description' ) ),
                "logo"        => array(
                    "@type"   => "ImageObject",
                    "url"     => $site_logo[0],
                    "width"   => $site_logo[1],
                    "height"  => $site_logo[2]
                )
            );
        endif;

        echo '<script type="application/ld+json">' , PHP_EOL;
        if ( version_compare( PHP_VERSION, '5.4.0' , '>=' ) ) {
            echo wp_json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) , PHP_EOL;
        } else {
            echo wp_json_encode( $args ) , PHP_EOL;
        }
        echo '</script>' , PHP_EOL;
    }
}
endif;
add_action( 'wp_head', 'digital_download_single_post_schema' );

if( ! function_exists( 'digital_download_get_comment_author_link' ) ) :
/**
 * Filter to modify comment author link
 * @link https://developer.wordpress.org/reference/functions/get_comment_author_link/
 */
function digital_download_get_comment_author_link( $return, $author, $comment_ID ){
    $comment = get_comment( $comment_ID );
    $url     = get_comment_author_url( $comment );
    $author  = get_comment_author( $comment );
 
    if ( empty( $url ) || 'http://' == $url )
        $return = '<span itemprop="name">'. esc_html( $author ) .'</span>';
    else
        $return = '<span itemprop="name"><a href=' . esc_url( $url ) . ' rel="external nofollow noopener" class="url" itemprop="url">' . esc_html( $author ) . '</a></span>';

    return $return;
}
endif;
add_filter( 'get_comment_author_link', 'digital_download_get_comment_author_link', 10, 3 );

if( ! function_exists( 'digital_download_filter_page_templates' ) ) :
/**
 * Removes certain page templates from the page template
 * dropdown if certain EDD isn't activated.
*/
function digital_download_filter_page_templates( $page_templates ){
    if( ! digital_download_is_edd_activated() ){
		unset( $page_templates['templates/dashboard.php'] );
		unset( $page_templates['templates/login.php'] );
	}

	return $page_templates;
}
endif;
add_filter( 'theme_page_templates', 'digital_download_filter_page_templates' );

if( ! function_exists( 'digital_download_btnw_bg_color' ) ) :
/**
 * Filter to modify default background color of btnw
 */
function digital_download_btnw_bg_color(){
    return '#f1f5f8';
}
endif; 
add_filter ( 'bt_newsletter_bg_color', 'digital_download_btnw_bg_color' );

if( ! function_exists( 'digital_download_admin_notice' ) ) :
/**
 * Addmin notice for getting started page
*/
function digital_download_admin_notice(){
    global $pagenow;
    $theme_args      = wp_get_theme();
    $meta            = get_option( 'digital_download_admin_notice' );
    $name            = $theme_args->__get( 'Name' );
    $current_screen  = get_current_screen();
    
    if( 'themes.php' == $pagenow && !$meta ){
        
        if( $current_screen->id !== 'dashboard' && $current_screen->id !== 'themes' ){
            return;
        }

        if( is_network_admin() ){
            return;
        }

        if( ! current_user_can( 'manage_options' ) ){
            return;
        } ?>

        <div class="welcome-message notice notice-info">
            <div class="notice-wrapper">
                <div class="notice-text">
                    <h3><?php esc_html_e( 'Congratulations!', 'digital-download' ); ?></h3>
                    <p><?php printf( __( '%1$s is now installed and ready to use. Click below to see theme documentation, plugins to install and other details to get started.', 'digital-download' ), esc_html( $name ) ) ; ?></p>
                    <p><a href="<?php echo esc_url( admin_url( 'themes.php?page=digital-download-getting-started' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Go to the getting started.', 'digital-download' ); ?></a></p>
                    <p class="dismiss-link"><strong><a href="?digital_download_admin_notice=1"><?php esc_html_e( 'Dismiss', 'digital-download' ); ?></a></strong></p>
                </div>
            </div>
        </div>
    <?php }
}
endif;
add_action( 'admin_notices', 'digital_download_admin_notice' );

if( ! function_exists( 'digital_download_update_admin_notice' ) ) :
/**
 * Updating admin notice on dismiss
*/
function digital_download_update_admin_notice(){
    if ( isset( $_GET['digital_download_admin_notice'] ) && $_GET['digital_download_admin_notice'] = '1' ) {
        update_option( 'digital_download_admin_notice', true );
    }
}
endif;
add_action( 'admin_init', 'digital_download_update_admin_notice' );

if ( ! function_exists( 'digital_download_get_fontawesome_ajax' ) ) :
/**
 * Return an array of all icons.
 */
function digital_download_get_fontawesome_ajax() {
    // Bail if the nonce doesn't check out
    if ( ! isset( $_POST['digital_download_customize_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['digital_download_customize_nonce'] ), 'digital_download_customize_nonce' ) ) {
        wp_die();
    }

    // Do another nonce check
    check_ajax_referer( 'digital_download_customize_nonce', 'digital_download_customize_nonce' );

    // Bail if user can't edit theme options
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        wp_die();
    }

    // Get all of our fonts
    $fonts = digital_download_get_fontawesome_list();
    
    ob_start();
    if( $fonts ){ ?>
        <ul class="font-group">
            <?php 
                foreach( $fonts as $font ){
                    echo '<li data-font="' . esc_attr( $font ) . '"><i class="' . esc_attr( $font ) . '"></i></li>';                        
                }
            ?>
        </ul>
        <?php
    }
    echo ob_get_clean();

    // Exit
    wp_die();
}
endif;
add_action( 'wp_ajax_digital_download_get_fontawesome_ajax', 'digital_download_get_fontawesome_ajax' );