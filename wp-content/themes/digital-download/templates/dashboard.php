<?php
/**
 * Template Name: Dashboard Page
 * 
 * @package Digital_Download
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                	
                    <?php
                        digital_download_entry_header(); 
                        
                        if( is_user_logged_in() ){ ?>
                            <div class="page-content">
								<div class="post-text">
									<h2 class="account-title"><?php esc_html_e( 'Downloads', 'digital-download' ); ?></h2>
									<?php echo do_shortcode( '[download_history]' ); ?>
								</div><!-- .post-text -->
							</div>
							
                            <div class="page-content">
								<div class="post-text">
									<h2 class="account-title"><?php esc_html_e( 'Purchase History', 'digital-download' ); ?></h2>
									<?php echo do_shortcode( '[purchase_history]' ); ?>
								</div><!-- .post-text -->
							</div>
							
    						<div class="page-content">
								<div class="post-text">
									<h2 class="account-title"><?php esc_html_e( 'Account Info', 'digital-download' ); ?></h2>
									<?php echo do_shortcode( '[edd_profile_editor]' ); ?>
								</div><!-- .post-text -->
							</div>
                            <?php    
                        }else{
                            echo do_shortcode( '[edd_login]' );    
                        }                                                              
                    ?>
                      
                </article><!-- #post-<?php the_ID(); ?> -->

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();