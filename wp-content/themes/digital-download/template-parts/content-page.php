<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Digital_Download
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
        /**
         * Post Thumbnail
         * 
         * @hooked digital_download_post_thumbnail
        */
        do_action( 'digital_download_before_page_entry_content' );
    ?>
    
    <div class="text-holder">    
    <?php
        /**
         * Entry Content
         * @hooked digital_download_entry_header  - 15
         * @hooked digital_download_entry_content - 20
         * @hooked digital_download_entry_footer  - 25
        */
        do_action( 'digital_download_page_entry_content' );    
    ?>
    </div><!-- .text-holder -->    
</article><!-- #post-<?php the_ID(); ?> -->