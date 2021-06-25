<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Digital_Download
 */

get_header(); ?>

	<div id="primary" class="content-area">
        
        <?php 
        /**
         * Before Posts hook
         * @hooked digital_download_archive_header
        */
        do_action( 'digital_download_before_posts_content' );
        ?>
        
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) : 
        
			/* Start the Loop */
			while ( have_posts() ) : the_post();
                if ( is_post_type_archive( 'download' ) || is_tax( array( 'download_category', 'download_tag' ) ) ){
					get_template_part( 'template-parts/content', 'downloads' );	
				}else{
    				/*
    				 * Include the Post-Format-specific template for the content.
    				 * If you want to override this in a child theme, then include a file
    				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
    				 */
    				get_template_part( 'template-parts/content', get_post_format() );
                }
			endwhile;

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
        
        <?php
        /**
         * After Posts hook
         * @hooked digital_download_navigation - 15
        */
        do_action( 'digital_download_after_posts_content' );
        ?>
        
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
