<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Digital_Download
 */
    
    /**
     * After Content
     * 
     * @hooked digital_download_content_end - 20
    */
    do_action( 'digital_download_before_footer' );
    
    /**
     * Footer
     * 
     * @hooked digital_download_footer_start  - 20
     * @hooked digital_download_footer_top    - 30
     * @hooked digital_download_footer_bottom - 40
     * @hooked digital_download_footer_end    - 50
    */
    do_action( 'digital_download_footer' );
    
    /**
     * After Footer
     * 
     * @hooked digital_download_page_end - 20
    */
    do_action( 'digital_download_after_footer' );

    wp_footer(); ?>

</body>
</html>
