<?php
/**
 * Template part for displaying single download
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Digital_Download
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php 
        /**
         * @hooked digital_download_entry_header   - 15
         * @hooked digital_download_post_thumbnail - 20
        */
        do_action( 'digital_download_before_download_entry_content' );
    ?>
    
    <div class="text-holder">
    <?php
        /**
         * @hooked digital_download_entry_content - 20
         * @hooked digital_download_entry_footer  - 25
        */
        do_action( 'digital_download_download_entry_content' );
    ?>
    </div><!-- .text-holder -->    
</article><!-- #post-<?php the_ID(); ?> -->