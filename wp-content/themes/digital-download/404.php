<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Digital_Download
 */

get_header(); ?>

	<div class="error-holder">
        <h2 class="sub-title"><?php esc_html_e( 'Uh-Oh...', 'digital-download' ); ?></h2>
		<div class="error-content">
			<p><?php esc_html_e( 'The page you are looking for may have been removed, deleted, or possibly never existed.', 'digital-download' ); ?></p>					
		</div><!-- .error-content -->
		<h1 class="title"><?php esc_html_e( '404', 'digital-download' ); ?></h1>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Take me to the home page', 'digital-download' ); ?></a>
		<?php get_search_form(); ?>	
	</div><!-- .error-holder -->
    
    <?php
    /**
     * @see digital_download_latest_posts
    */
    do_action( 'digital_download_latest_posts' );

get_footer();