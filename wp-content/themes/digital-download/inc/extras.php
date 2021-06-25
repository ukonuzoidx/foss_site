<?php
/**
 * Digital Download Standalone Functions.
 *
 * @package Digital_Download
 */

if ( ! function_exists( 'digital_download_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time.
 */
function digital_download_posted_on() {
	$ed_updated_post_date = get_theme_mod( 'ed_post_update_date', true );
    
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		if( $ed_updated_post_date ){
            $time_string = '<time class="entry-date published updated" datetime="%3$s" itemprop="dateModified">%4$s</time></time><time class="updated" datetime="%1$s" itemprop="datePublished">%2$s</time>';		  
		}else{
            $time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';  
		}        
	}else{
	   $time_string = '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';   
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
    
    echo '<span class="posted-on"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a></span>';

}
endif;

if ( ! function_exists( 'digital_download_posted_by' ) ) :
/**
 * Prints HTML with meta information for the current author.
 */
function digital_download_posted_by() {
	$byline = '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_avatar( get_the_author_meta( 'ID' ), 24 ) . '</a>';
    $byline .= sprintf(
		/* translators: %s: post author. */
		esc_html_x( 'by %s', 'post author', 'digital-download' ),
		'<span itemprop="name"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" itemprop="url">' . esc_html( get_the_author() ) . '</a></span>' 
    );
	echo '<span class="byline" itemprop="author" itemscope itemtype="https://schema.org/Person">' . $byline . '</span>';
}
endif;

if ( ! function_exists( 'digital_download_category' ) ) :
/**
 * Prints categories
 */
function digital_download_category(){
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'digital-download' ) );
		if ( $categories_list ) {
			echo '<span class="cat-links" itemprop="about">' . $categories_list . '</span>';
		}
	}
}
endif;

if ( ! function_exists( 'digital_download_tag' ) ) :
/**
 * Prints tags
 */
function digital_download_tag(){
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', ' ' );
		if ( $tags_list ) {
			echo '<div class="tags" itemprop="about">' . $tags_list . '</div>';
		}
	}
}
endif;

if( ! function_exists( 'digital_download_get_posts_list' ) ) :
/**
 * Returns Latest, Related & Popular Posts
*/
function digital_download_get_posts_list( $status, $post_type = 'post' ){
    global $post;
    
    $args = array(
        'post_type'           => $post_type,
        'posts_status'        => 'publish',
        'ignore_sticky_posts' => true
    );
    
    switch( $status ){
        case 'latest':        
        $args['posts_per_page'] = 3;
        $title                  = __( 'Latest Posts', 'digital-download' );
        $class                  = 'latest-post';
        $image_size             = 'digital-download-related';
        break;
        
        case 'related':
        $args['posts_per_page'] = 4;
        $args['post__not_in']   = array( $post->ID );
        $args['orderby']        = 'rand';
        $title                  = get_theme_mod( 'related_post_title', __( 'You may also like...', 'digital-download' ) );
        $class                  = 'related-posts';
        $image_size             = 'digital-download-related';        
        $cats                   = get_the_category( $post->ID );        
        if( $cats ){
            $c = array();
            foreach( $cats as $cat ){
                $c[] = $cat->term_id; 
            }
            $args['category__in'] = $c;
        }        
        break;
    }
    
    $qry = new WP_Query( $args );
    
    if( $qry->have_posts() ){ ?>    
        <div class="<?php echo esc_attr( $class ); ?>">
    		<?php 
            if( $title ){
                if( $status == 'latest' ) echo '<header class="section-header">';
                echo '<h2 class="section-title">' . esc_html( $title ) . '</h2>'; 
                if( $status == 'latest' ) echo '</header>';   		  
    		}  
            ?>
			<div class="post-holder">
                <?php while( $qry->have_posts() ){ $qry->the_post(); ?>
                <div class="col">
    				<a href="<?php the_permalink(); ?>" class="post-thumbnail">
                        <?php
                            if( has_post_thumbnail() ){
                                the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
                            }else{ 
                                digital_download_get_fallback_svg( $image_size );//falback
                            }
                        ?>
                    </a>
                    <?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
                    <div class="entry-meta">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 20 ); ?></a>
                        <?php printf( __( 'by %1$s in %2$s', 'digital-download' ), '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a>', get_the_category_list( esc_html__( ', ', 'digital-download' ) ) ); ?>
					</div>
    			</div>
    			<?php } ?>
            </div><!-- .post-holder -->
    	</div>
        <?php
        wp_reset_postdata();
    }
}
endif;

if( ! function_exists( 'digital_download_site_branding' ) ) :
/**
 * Site Branding
*/
function digital_download_site_branding(){ ?>
    <div class="site-branding" itemscope itemtype="https://schema.org/Organization">
		<?php if( function_exists( 'has_custom_logo' ) && has_custom_logo() ) the_custom_logo(); ?>
        <div class="text-logo">
            <?php
            if( is_front_page() ){ ?>
                <h1 class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>
				<?php 
            }else{ ?>
                <p class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>
            <?php
            }
            $description = get_bloginfo( 'description', 'display' );
            if ( $description || is_customize_preview() ){ ?>
                <p class="site-description"><?php echo $description; ?></p>
            <?php                
            }
            ?>
        </div><!-- .text-logo -->
	</div><!-- .site-branding -->
    <?php
}
endif;

if( ! function_exists( 'digital_download_header_search' ) ) :
/**
 * Header Search
*/
function digital_download_header_search(){ ?>
    <div class="form-section">
        <button id="btn-search" class="search-btn" data-toggle-target=".header-search-modal" data-toggle-body-class="showing-search-modal" aria-expanded="false" data-set-focus=".header-search-modal .search-field">
		  <span id="btn-search-icon" class="fas fa-search"></span>
        </button>
		<div class="form-holder search header-searh-wrap header-search-modal cover-modal" data-modal-target-string=".header-search-modal">
			<?php get_search_form(); ?>
            <button class="btn-form-close" data-toggle-target=".header-search-modal" data-toggle-body-class="showing-search-modal" aria-expanded="false" data-set-focus=".header-search-modal">
                 <i class="fas fa-times"></i>
            </button>
		</div>
	</div>
    <?php
}
endif;

if( ! function_exists( 'digital_download_primary_menu_fallback' ) ) :
/**
 * Fallback for primary menu
*/
function digital_download_primary_menu_fallback(){
    if( current_user_can( 'manage_options' ) ){
        echo '<ul id="primary-menu" class="menu">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Click here to add a menu', 'digital-download' ) . '</a></li>';
        echo '</ul>';
    }
}
endif;

if( ! function_exists( 'digital_download_social_links' ) ) :
/**
 * Social Links 
*/
function digital_download_social_links( $echo = true ){ 
    $social_links = get_theme_mod( 'social_links' );
    $ed_social    = get_theme_mod( 'ed_social_links', true ); 
    
    if( $ed_social && $social_links && $echo ){ ?>
    <ul class="social-networks">
    <?php 
        foreach( $social_links as $link ){
    	   if( $link['link'] ){ ?>
            <li>
                <a href="<?php echo esc_url( $link['link'] ); ?>" target="_blank" rel="nofollow noopener">
                    <i class="<?php echo esc_attr( $link['font'] ); ?>"></i>
                </a>
            </li>    	   
            <?php
            } 
        } 
        ?>
	</ul>
    <?php    
    }elseif( $ed_social && $social_links ){
        return true;
    }else{
        return false;
    }
    ?>
    <?php                                
}
endif;

if( ! function_exists( 'digital_download_theme_comment' ) ) :
/**
 * Callback function for Comment List
 * 
 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments 
 */
function digital_download_theme_comment( $comment, $args, $depth ){
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	
    <?php if ( 'div' != $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body" itemscope itemtype="https://schema.org/UserComments">
	<?php endif; ?>
    	
        <footer class="comment-meta">
            <div class="comment-author vcard">
        	   <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
        	</div><!-- .comment-author vcard -->
        </footer>
        
        <div class="text-holder">
        	<div class="top">
                <div class="left">
                    <?php if ( $comment->comment_approved == '0' ) : ?>
                		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'digital-download' ); ?></em>
                		<br />
                	<?php endif; ?>
                    <?php printf( __( '<b class="fn" itemprop="creator" itemscope itemtype="https://schema.org/Person">%s</b> <span class="says">says:</span>', 'digital-download' ), get_comment_author_link() ); ?>
                	<div class="comment-metadata">
                        <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
                    		<time itemprop="commentTime" datetime="<?php echo esc_attr( get_gmt_from_date( get_comment_date() . get_comment_time(), 'Y-m-d H:i:s' ) ); ?>"><?php printf( esc_html__( '%1$s at %2$s', 'digital-download' ), get_comment_date(),  get_comment_time() ); ?></time>
                        </a>
                	</div>
                </div>
                <div class="reply">
                    <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            	</div>
            </div>            
            <div class="comment-content" itemprop="commentText"><?php comment_text(); ?></div>        
        </div><!-- .text-holder -->
        
	<?php if ( 'div' != $args['style'] ) : ?>
    </div><!-- .comment-body -->
	<?php endif; ?>
    
<?php
}
endif;

if( ! function_exists( 'digital_download_sidebar' ) ) :
/**
 * Return sidebar layouts for pages/posts
*/
function digital_download_sidebar( $class = false ){
    global $post;
    $return        = false;
    $page_layout   = get_theme_mod( 'page_sidebar_layout', 'right-sidebar' ); //Default Layout Style for Pages
    $post_layout   = get_theme_mod( 'post_sidebar_layout', 'right-sidebar' ); //Default Layout Style for Posts
    $layout        = get_theme_mod( 'layout_style', 'right-sidebar' ); //Default Layout Style for Styling Settings
    $home_sections = digital_download_get_home_sections();
    
    
    if( is_singular( array( 'page', 'post' ) ) ){         
        if( get_post_meta( $post->ID, '_digital_download_sidebar_layout', true ) ){
            $sidebar_layout = get_post_meta( $post->ID, '_digital_download_sidebar_layout', true );
        }else{
            $sidebar_layout = 'default-sidebar';
        }
        
        if( is_page() ){
            if( ( is_front_page() && $home_sections ) || is_page_template( array( 'templates/portfolio.php', 'templates/dashboard.php', 'templates/login.php' ) ) ){
                $return = $class ? 'full-width' : false;        
            }elseif( is_active_sidebar( 'sidebar' ) ){
                if( $sidebar_layout == 'no-sidebar' || ( $sidebar_layout == 'default-sidebar' && $page_layout == 'no-sidebar' ) ){
                    $return = $class ? 'full-width' : false;
                }elseif( $sidebar_layout == 'centered' || ( $sidebar_layout == 'default-sidebar' && $page_layout == 'centered' ) ){
                    $return = $class ? 'full-width centered' : false;
                }elseif( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ){
                    $return = $class ? 'rightsidebar' : 'sidebar';
                }elseif( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ){
                    $return = $class ? 'leftsidebar' : 'sidebar';
                }
            }else{
                $return = $class ? 'full-width' : false;
            }
        }elseif( is_single() ){
            if( is_active_sidebar( 'sidebar' ) ){
                if( $sidebar_layout == 'no-sidebar' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'no-sidebar' ) ){
                    $return = $class ? 'full-width' : false;
                }elseif( $sidebar_layout == 'centered' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'centered' ) ){
                    $return = $class ? 'full-width centered' : false;
                }elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ){
                    $return = $class ? 'rightsidebar' : 'sidebar';
                }elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ){
                    $return = $class ? 'leftsidebar' : 'sidebar';
                }
            }else{
                $return = $class ? 'full-width' : false;
            }
        }
    }else{
        if( $layout == 'no-sidebar' ){
            $return = $class ? 'full-width' : false;
        }elseif( is_active_sidebar( 'sidebar' ) ){            
            if( $class ){
                if( $layout == 'right-sidebar' ) $return = 'rightsidebar'; //With Sidebar
                if( $layout == 'left-sidebar' ) $return = 'leftsidebar';
            }else{
                $return = 'sidebar';    
            }                         
        }else{
            $return = $class ? 'full-width' : false;
        } 
    }    
    return $return; 
}
endif;

if( ! function_exists( 'digital_download_fonts_url' ) ) :
/**
 * Register google font.
 */
function digital_download_fonts_url() {
    $fonts_url = '';

    /* Translators: If there are characters in your language that are not
    * supported by respective fonts, translate this to 'off'. Do not translate
    * into your own language.
    */
    $nunito_font = _x( 'on', 'Roboto: on or off', 'digital-download' );

    if ( 'off' !== $nunito_font ) {
        $font_families = array();

        $font_families[] = 'Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i';

        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) )
        );

        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    return esc_url( $fonts_url );
}
endif;

if( ! function_exists( 'digital_download_get_posts' ) ) :
/**
 * Fuction to list Custom Post Type
*/
function digital_download_get_posts( $post_type = 'post' ){    
    $args = array(
    	'posts_per_page'   => -1,
    	'post_type'        => $post_type,
    	'post_status'      => 'publish',
    	'suppress_filters' => true 
    );
    $posts_array = get_posts( $args );
    
    // Initate an empty array
    $post_options = array();
    $post_options[''] = __( ' -- Choose -- ', 'digital-download' );
    if ( ! empty( $posts_array ) ) {
        foreach ( $posts_array as $posts ) {
            $post_options[ $posts->ID ] = $posts->post_title;
        }
    }
    return $post_options;
    wp_reset_postdata();
}
endif;

if( ! function_exists( 'digital_download_get_categories' ) ) :
/**
 * Function to list post categories in customizer options
*/
function digital_download_get_categories( $select = true, $taxonomy = 'category', $slug = false ){    
    /* Option list of all categories */
    $categories = array();
    
    $args = array( 
        'hide_empty' => false,
        'taxonomy'   => $taxonomy 
    );
    
    $catlists = get_terms( $args );
    if( $select ) $categories[''] = __( 'Choose Category', 'digital-download' );
    foreach( $catlists as $category ){
        if( $slug ){
            $categories[$category->slug] = $category->name;
        }else{
            $categories[$category->term_id] = $category->name;    
        }        
    }
    
    return $categories;
}
endif;

if( ! function_exists( 'digital_download_get_home_sections' ) ) :
/**
 * Returns Home Sections 
*/
function digital_download_get_home_sections(){
    $ed_banner = get_theme_mod( 'ed_banner_section', true );
    $sections = array( 
        'download'    => array( 'section' => 'download' ),
        'feature'     => array( 'sidebar' => 'feature' ),
        'newsletter'  => array( 'section' => 'newsletter' ), 
        'testimonial' => array( 'sidebar' => 'testimonial' ),
        'cta'         => array( 'sidebar' => 'cta' ),
    );
    
    $enabled_section = array();
    
    if( $ed_banner ) array_push( $enabled_section, 'banner' );
    
    foreach( $sections as $k => $v ){
        if( array_key_exists( 'sidebar', $v ) ){
            if( is_active_sidebar( $v['sidebar'] ) ) array_push( $enabled_section, $v['sidebar'] );
        }else{
            if( get_theme_mod( 'ed_' . $v['section'] . '_section', true ) ) array_push( $enabled_section, $v['section'] );
        }
    }  
    
    return apply_filters( 'digital_download_home_sections', $enabled_section );
}
endif;

if( ! function_exists( 'digital_download_get_image_sizes' ) ) :
/**
 * Get information about available image sizes
 */
function digital_download_get_image_sizes( $size = '' ) {
 
    global $_wp_additional_image_sizes;
 
    $sizes = array();
    $get_intermediate_image_sizes = get_intermediate_image_sizes();
 
    // Create the full array with sizes and crop info
    foreach( $get_intermediate_image_sizes as $_size ) {
        if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
            $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
            $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
            $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
            $sizes[ $_size ] = array( 
                'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
            );
        }
    } 
    // Get only 1 size if found
    if ( $size ) {
        if( isset( $sizes[ $size ] ) ) {
            return $sizes[ $size ];
        } else {
            return false;
        }
    }
    return $sizes;
}
endif;

if ( ! function_exists( 'digital_download_get_fallback_svg' ) ) :    
/**
 * Get Fallback SVG
*/
function digital_download_get_fallback_svg( $post_thumbnail ) {
    if( ! $post_thumbnail ){
        return;
    }
    
    $image_size = digital_download_get_image_sizes( $post_thumbnail );
     
    if( $image_size ){ ?>
        <div class="svg-holder">
             <svg class="fallback-svg" viewBox="0 0 <?php echo esc_attr( $image_size['width'] ); ?> <?php echo esc_attr( $image_size['height'] ); ?>" preserveAspectRatio="none">
                    <rect width="<?php echo esc_attr( $image_size['width'] ); ?>" height="<?php echo esc_attr( $image_size['height'] ); ?>" style="fill:#f2f2f2;"></rect>
            </svg>
        </div>
        <?php
    }
}
endif;

if( ! function_exists( 'digital_download_escape_text_tags' ) ) :
/**
 * Remove new line tags from string
 *
 * @param $text
 * @return string
 */
function digital_download_escape_text_tags( $text ) {
    return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}
endif;

if( ! function_exists( 'wp_body_open' ) ) :
/**
 * Fire the wp_body_open action.
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
*/
function wp_body_open() {
	/**
	 * Triggered after the opening <body> tag.
    */
	do_action( 'wp_body_open' );
}
endif;

/**
 * Is BlossomThemes Email Newsletters active or not
*/
function digital_download_is_btnw_activated(){
    return class_exists( 'Blossomthemes_Email_Newsletter' ) ? true : false;        
}

/**
 * Is Easy Digital Download active or not
 */
function digital_download_is_edd_activated() {
	return class_exists( 'Easy_Digital_Downloads' ) ? true : false;
}

/**
 * Is Subtitles active or not
 */
function digital_download_is_subtitles_activated() {
	return class_exists( 'Subtitles' ) ? true : false;
}

/**
 * Is Rara Theme Companion active or not
*/
function digital_download_is_rtc_activated(){
    return class_exists( 'Raratheme_Companion' ) ? true : false;
}