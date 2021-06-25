<?php
/**
 * Subtitles functions and definitions
 *
 * @package Digital_Download
 */

/**
 * Subtitle support to downloads items
 */
function digital_download_subtitles_support() {
    add_post_type_support( 'download', 'subtitles' );
    remove_post_type_support( 'post', 'subtitles' );
    remove_action( 'wp_head', array( Subtitles::getInstance(), 'subtitle_styling' ) );
}
add_action( 'init', 'digital_download_subtitles_support' );

/**
 * Remove automatic output of subtitles
 */
add_filter( 'subtitle_view_supported', '__return_false' );