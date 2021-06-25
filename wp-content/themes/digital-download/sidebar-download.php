<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Digital_Download
 */
?>

<aside id="secondary" class="widget-area" itemscope itemtype="https://schema.org/WPSideBar">
	<?php 
        /**
         * @hooked digital_download_sidebar_download_wrap_start - 15
         * @hooked digital_download_download_price              - 20
         * @hooked digital_download_sidebar_download_wrap_end   - 40
         * @hooked digital_download_edd_meta                    - 55 
        */
        do_action( 'digital_download_below_single_sidebar' );
    ?>     
</aside><!-- #secondary -->