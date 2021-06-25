<?php
/**
 * Digital Download Template Functions which enhance the theme by hooking into WordPress
 *
 * @package Digital_Download
 */

if( ! function_exists( 'digital_download_doctype' ) ) :
/**
 * Doctype Declaration
*/
function digital_download_doctype(){ ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <?php
}
endif;
add_action( 'digital_download_doctype', 'digital_download_doctype' );

if( ! function_exists( 'digital_download_head' ) ) :
/**
 * Before wp_head 
*/
function digital_download_head(){ ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php
}
endif;
add_action( 'digital_download_before_wp_head', 'digital_download_head' );

if( ! function_exists( 'digital_download_page_start' ) ) :
/**
 * Page Start
*/
function digital_download_page_start(){ ?>
    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#acc-content"><?php esc_html_e( 'Skip to content (Press Enter)', 'digital-download' ); ?></a>
    <?php
}
endif;
add_action( 'digital_download_before_header', 'digital_download_page_start', 20 );

if( ! function_exists( 'digital_download_header' ) ) :
/**
 * Header Start
*/
function digital_download_header(){ 
    $ed_cart   = get_theme_mod( 'ed_shopping_cart', true ); 
    $ed_search = get_theme_mod( 'ed_header_search', true ); ?>
	<div class="header-holder">
		<div class="overlay"></div>
		<div class="container">
			<header id="masthead" class="site-header" itemscope itemtype="https://schema.org/WPHeader">
				<?php digital_download_site_branding(); ?>
				<div class="right">
					<button id="toggle-button" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".close-main-nav-toggle"><span></span></button>

                    <div class="mobile-menu-wrapper">
                        <nav id="mobile-site-navigation" class="main-navigation mobile-navigation">        
                            <div class="primary-menu-list main-menu-modal cover-modal" data-modal-target-string=".main-menu-modal">
                                <button class="close close-main-nav-toggle" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".main-menu-modal"><i class="fas fa-times"></i></button>
                                <div class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile', 'digital-download' ); ?>">
                                    <?php
                                        wp_nav_menu( array(
                                            'theme_location' => 'primary',
                                            'menu_id'        => 'mobile-primary-menu',
                                            'menu_class'     => 'nav-menu main-menu-modal',
                                        ) );
                                    ?>
                                </div>
                            </div>
                        </nav><!-- #mobile-site-navigation -->
                    </div>

                    <nav id="site-navigation" class="main-navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
                        <?php
                            wp_nav_menu( array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'fallback_cb'    => 'digital_download_primary_menu_fallback',
                            ) );
                        ?>
                    </nav><!-- #site-navigation -->
					<div class="tools">						
						<?php 
                            if( $ed_search ) digital_download_header_search();
                            if( digital_download_is_edd_activated() ){
                                if( $ed_cart ) digital_download_header_shoping_cart();
                                digital_download_login_button();
                            }
                            
                        ?>						
					</div>
				</div>
			</header>
		</div>
	</div>
    <?php 
}
endif;
add_action( 'digital_download_header', 'digital_download_header', 20 );

if( ! function_exists( 'digital_download_content_start' ) ) :
/**
 * Content Start
*/
function digital_download_content_start(){ 
    echo '<div id="acc-content"><!-- for acccessibility purpose -->';
    $home_sections = digital_download_get_home_sections(); 
    
    if( !( is_front_page() && ! is_home() && $home_sections ) ){ 
        if( ! is_404() ){ ?>
            <div id="content" class="site-content">
            <?php 
        } 
        ?>
        <div class="container">            
        <?php
    }        
}
endif;
add_action( 'digital_download_content', 'digital_download_content_start' );

if( ! function_exists( 'digital_download_archive_header' ) ) :
/**
 * Archive Header
*/
function digital_download_archive_header(){
    if( ! is_front_page() ){ 
        global $wp_query; ?>    
        <header class="page-header">
        <?php 
            if( is_home() ){
                echo '<h1 class="page-title">';
                single_post_title();
                echo '</h1>';
            }
            
            if( is_archive() ){ 
                if( is_author() ){ ?>
                    <div class="img-holder"><?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?></div>
            		<div class="text-holder">
            		<?php
                        printf( __( '%1$sAll Posts by%2$s', 'digital-download' ), '<h1 class="author-archive-title"><span>', '</span> ' . get_the_author_meta( 'display_name' ) . '</h1>' );
                        the_archive_description( '<div class="author-archive-content">', '</div>' );
                    ?>
            		</div>            
                    <?php 				
                }else{ 
                    the_archive_title();
                    the_archive_description( '<div class="archive-description">', '</div>' );
                }
            }
            if( is_search() ){ ?>            
    			<h1 class="screen-reader-text"><?php esc_html_e( 'Search Result', 'digital-download' ); ?></h1>
                <span class="archive-type"><?php esc_html_e( 'You are looking for...', 'digital-download' ); ?></span>
                <?php
                get_search_form();
            }
        ?>
        </header><!-- .page-header -->
        <?php
        if( $wp_query->found_posts > 0 ){
            echo '<div class="post-count">';
            printf( esc_html__( 'Showing %1$s Result(s)%2$s', 'digital-download' ), '<em>' . number_format_i18n( $wp_query->found_posts ), '</em>' );
            echo '</div>';
        }
    }
}
endif;
add_action( 'digital_download_before_posts_content', 'digital_download_archive_header' );

if ( ! function_exists( 'digital_download_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function digital_download_post_thumbnail() {
	global $wp_query;
    
    $ed_featured = get_theme_mod( 'ed_featured_image', true );
    $sidebar     = digital_download_sidebar();
    $image_size  = 'digital-download-fullwidth';
    
    if( is_home() || is_archive() || is_search() ){
        echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail" itemprop="thumbnailUrl">';
        if( has_post_thumbnail() ){
            the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
        }else{
            digital_download_get_fallback_svg( $image_size );//falback
        }
        echo '</a>';
    }elseif( is_singular() ){
        echo '<div class="post-thumbnail">';
        if( 'post' == get_post_type() ){
            if( $ed_featured ) the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
        }else{
            the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
        }
        echo '</div>';
    }
}
endif;
add_action( 'digital_download_before_page_entry_content', 'digital_download_post_thumbnail' );
add_action( 'digital_download_before_post_entry_content', 'digital_download_post_thumbnail' );
add_action( 'digital_download_before_download_entry_content', 'digital_download_post_thumbnail', 20 );

if( ! function_exists( 'digital_download_entry_header' ) ) :
/**
 * Entry Header
*/
function digital_download_entry_header(){ ?>
    <header class="entry-header">
		<?php 
            $ed_cat_single  = get_theme_mod( 'ed_category', false );
            $ed_post_date   = get_theme_mod( 'ed_post_date', false );
            $ed_post_author = get_theme_mod( 'ed_post_author', false );
            
            if( is_singular() ){
    			is_front_page() ? the_title( '<h2 class="entry-title">', '</h2>' ) : the_title( '<h1 class="entry-title">', '</h1>' );
                if( function_exists( 'the_subtitle' ) ) the_subtitle( '<p class="entry-subtitle">', '</p>' );
            }else{
    			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
    		} 
        
            if( 'post' === get_post_type() ){
                echo '<div class="entry-meta">';
                                
                if( is_single() ){
                    if( ! $ed_post_author ) digital_download_posted_by();
                    if( ! $ed_cat_single ) digital_download_category();
                    if( ! $ed_post_date ) digital_download_posted_on();
                }else{
                    digital_download_posted_by();
                    digital_download_category();
                    digital_download_posted_on();    
                }                
                echo '</div>';
            }		
		?>
	</header>         
    <?php    
}
endif;
add_action( 'digital_download_post_entry_content', 'digital_download_entry_header', 15 );
add_action( 'digital_download_page_entry_content', 'digital_download_entry_header', 15 );
add_action( 'digital_download_before_download_entry_content', 'digital_download_entry_header', 15 );

if( ! function_exists( 'digital_download_entry_content' ) ) :
/**
 * Entry Content
*/
function digital_download_entry_content(){ 
    $ed_excerpt = get_theme_mod( 'ed_excerpt', true ); ?>
    <div class="entry-content">
		<?php
			if( is_singular() || ! $ed_excerpt || ( get_post_format() != false ) ){
                the_content();    
    			wp_link_pages( array(
    				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'digital-download' ),
    				'after'  => '</div>',
    			) );
            }else{
                the_excerpt();
            }
		?>
	</div><!-- .entry-content -->
    <?php
}
endif;
add_action( 'digital_download_page_entry_content', 'digital_download_entry_content', 20 );
add_action( 'digital_download_post_entry_content', 'digital_download_entry_content', 20 );
add_action( 'digital_download_download_entry_content', 'digital_download_entry_content', 20 );

if( ! function_exists( 'digital_download_entry_footer' ) ) :
/**
 * Entry Footer
*/
function digital_download_entry_footer(){ 
    $readmore = get_theme_mod( 'read_more_text', __( 'Read More', 'digital-download' ) ); ?>
	<footer class="entry-footer">
		<?php
			if( is_home() || is_archive() || is_search() ){
                echo '<a href="' . esc_url( get_the_permalink() ) . '" class="btn-readmore">' . esc_html( $readmore ) . '</a>';    
            }
            
            digital_download_tag();
			
            if( get_edit_post_link() ){
                edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'digital-download' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
            }
		?>
	</footer><!-- .entry-footer -->
	<?php 
}
endif;
add_action( 'digital_download_page_entry_content', 'digital_download_entry_footer', 25 );
add_action( 'digital_download_post_entry_content', 'digital_download_entry_footer', 25 );
add_action( 'digital_download_download_entry_content', 'digital_download_entry_footer', 25 );

if( ! function_exists( 'digital_download_author' ) ) :
/**
 * Author Section
*/
function digital_download_author(){ 
    $ed_author    = get_theme_mod( 'ed_author' );
    $author_title = get_the_author_meta( 'display_name' );
    if( ! $ed_author && get_the_author_meta( 'description' ) ){ ?>
    <div class="author-section">
		<div class="img-holder"><?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?></div>
		<div class="text-holder">
			<?php 
                if( $author_title ) echo '<h3 class="title">' . esc_html( $author_title ) . '</h3>';
                echo '<div class="author-content">' . wpautop( wp_kses_post( get_the_author_meta( 'description' ) ) ) . '</div>';
            ?>		
		</div>
	</div>
    <?php
    }
}
endif;
add_action( 'digital_download_after_post_content', 'digital_download_author', 15 );

if( ! function_exists( 'digital_download_navigation' ) ) :
/**
 * Navigation
*/
function digital_download_navigation(){
    if( is_single() ){
        $next_post = get_next_post();
        $prev_post = get_previous_post();
        
        if( $prev_post || $next_post ){?>            
            <nav class="navigation post-navigation" role="navigation">
    			<h2 class="screen-reader-text"><?php esc_html_e( 'Post Navigation', 'digital-download' ); ?></h2>
    			<div class="nav-links">
    				<?php if( $prev_post ){ ?>
                    <div class="nav-previous nav-holder">
						<div class="holder">
                            <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" rel="prev">
    							<span class="meta-nav"><?php esc_html_e( 'Previuos Article', 'digital-download' ); ?></span>
                                <?php
                                $prev_img = get_post_thumbnail_id( $prev_post->ID );
                                if( $prev_img ){
                                    $prev_url = wp_get_attachment_image_url( $prev_img, 'thumbnail' );
                                    echo '<img src="' . esc_url( $prev_url ) . '" alt="' . esc_attr( strip_tags( get_the_title( $prev_post->ID ) ) ) . '">';                                        
                                }else{
                                    digital_download_get_fallback_svg( 'thumbnail' );//falback
                                }
                                ?>
                                <span class="screen-reader-text"><?php esc_html_e( 'Previous Article:', 'digital-download' ); ?></span>
    							<span class="post-title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></span>
    						</a>
                        </div>
					</div>
					<?php } ?>
                    <?php if( $next_post ){ ?>
                    <div class="nav-next nav-holder">
						<div class="holder">
                            <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" rel="next">
    							<span class="meta-nav"><?php esc_html_e( 'Next Article', 'digital-download' ); ?></span>
                                <span class="post-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
                                <?php
                                $next_img = get_post_thumbnail_id( $next_post->ID );
                                if( $next_img ){
                                    $next_url = wp_get_attachment_image_url( $next_img, 'thumbnail' );
                                    echo '<img src="' . esc_url( $next_url ) . '" alt="' . esc_attr( strip_tags( get_the_title( $next_post->ID ) ) ) . '">';                                        
                                }else{
                                    digital_download_get_fallback_svg( 'thumbnail' );//falback
                                }
                                ?>    							
    						</a>
                        </div>
					</div>
                    <?php } ?>
    			</div>
    		</nav>        
            <?php
        }
    }else{
        the_posts_pagination( array(
            'prev_text'          => __( 'Previous', 'digital-download' ),
            'next_text'          => __( 'Next', 'digital-download' ),
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'digital-download' ) . ' </span>',
        ) );
    }
}
endif;
add_action( 'digital_download_after_posts_content', 'digital_download_navigation' );
add_action( 'digital_download_after_post_content', 'digital_download_navigation', 20 );
add_action( 'digital_download_after_download_content', 'digital_download_navigation', 20 );

if( ! function_exists( 'digital_download_related_posts' ) ) :
/**
 * Related Posts 
*/
function digital_download_related_posts(){ 
    $ed_related_post = get_theme_mod( 'ed_related', true );    
    if( $ed_related_post ) digital_download_get_posts_list( 'related' );
}
endif;                                                                               
add_action( 'digital_download_after_post_content', 'digital_download_related_posts', 30 );

if( ! function_exists( 'digital_download_latest_posts' ) ) :
/**
 * Latest Posts
*/
function digital_download_latest_posts(){ 
    digital_download_get_posts_list( 'latest' );
}
endif;
add_action( 'digital_download_latest_posts', 'digital_download_latest_posts' );

if( ! function_exists( 'digital_download_comment' ) ) :
/**
 * Comments Template 
*/
function digital_download_comment(){
    // If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
}
endif;
add_action( 'digital_download_after_page_content', 'digital_download_comment' );
add_action( 'digital_download_after_post_content', 'digital_download_comment', 40 );
add_action( 'digital_download_after_download_content', 'digital_download_comment', 30 );

if( ! function_exists( 'digital_download_content_end' ) ) :
/**
 * Content End
*/
function digital_download_content_end(){ 
    $home_sections = digital_download_get_home_sections();
    
    if( !( is_front_page() && ! is_home() && $home_sections ) ){ ?>            
            </div><!-- .container -->        
            <?php if( ! is_404() ){ ?>
        </div><!-- .site-content -->
        <?php
        }
    }

}
endif;
add_action( 'digital_download_before_footer', 'digital_download_content_end', 20 );

if( ! function_exists( 'digital_download_footer_start' ) ) :
/**
 * Footer Start
*/
function digital_download_footer_start(){
    ?>
    <footer id="colophon" class="site-footer" itemscope itemtype="https://schema.org/WPFooter">
    <?php
}
endif;
add_action( 'digital_download_footer', 'digital_download_footer_start', 20 );

if( ! function_exists( 'digital_download_footer_top' ) ) :
/**
 * Footer Top
*/
function digital_download_footer_top(){    
    $footer_sidebars = array( 'footer-one', 'footer-two', 'footer-three', 'footer-four' );
    $active_sidebars = array();
    $sidebar_count   = 0;
    
    foreach ( $footer_sidebars as $sidebar ) {
        if( is_active_sidebar( $sidebar ) ){
            array_push( $active_sidebars, $sidebar );
            $sidebar_count++ ;
        }
    }
    
    if( $active_sidebars ){ ?>
        <div class="footer-t">
    		<div class="container">
    			<div class="column-<?php echo esc_attr( $sidebar_count ); ?>">
                <?php foreach( $active_sidebars as $active ){ ?>
    				<div class="col">
    				   <?php dynamic_sidebar( $active ); ?>	
    				</div>
                <?php } ?>
                </div>
    		</div>
    	</div>
        <?php 
    }   
}
endif;
add_action( 'digital_download_footer', 'digital_download_footer_top', 30 );

if( ! function_exists( 'digital_download_footer_bottom' ) ) :
/**
 * Footer Bottom
*/
function digital_download_footer_bottom(){ ?>
    <div class="footer-b">
		<div class="container">
			<div class="site-info">            
            <?php
                digital_download_get_footer_copyright();
                esc_html_e( 'Digital Download | Developed by ', 'digital-download' );
                echo '<a href="' . esc_url( 'https://rarathemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Rara Theme', 'digital-download' ) . '</a>.';
                printf( esc_html__( ' Powered by %s', 'digital-download' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'digital-download' ) ) .'" target="_blank">WordPress</a>.' );
                if ( function_exists( 'the_privacy_policy_link' ) ) {
                    the_privacy_policy_link();
                }
            ?>               
            </div>
            <?php digital_download_social_links(); ?>
		</div>
	</div>
    <?php
}
endif;
add_action( 'digital_download_footer', 'digital_download_footer_bottom', 40 );

if( ! function_exists( 'digital_download_footer_end' ) ) :
/**
 * Footer End 
*/
function digital_download_footer_end(){ ?>
    </footer><!-- #colophon -->
    <?php
}
endif;
add_action( 'digital_download_footer', 'digital_download_footer_end', 50 );

if( ! function_exists( 'digital_download_page_end' ) ) :
/**
 * Page End
*/
function digital_download_page_end(){ ?>
        </div><!-- #acc-content -->
    </div><!-- #page -->
    <?php
}
endif;
add_action( 'digital_download_after_footer', 'digital_download_page_end', 20 );
