<?php
/**
 * The template for displaying all single downloads
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Digital_Download
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'download' );

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
        
        <?php
        /**
         * @hooked digital_download_navigation - 20
         * @hooked digital_download_comment    - 30
        */
        do_action( 'digital_download_after_download_content' );
        ?>
        
	</div><!-- #primary -->

<?php
get_sidebar( 'download' );
get_footer();
