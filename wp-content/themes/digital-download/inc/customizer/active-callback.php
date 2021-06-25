<?php
/**
 * Active Callback
 * 
 * @package Digital_Download
*/

/**
 * Active Callback for Blog View All Button
*/
function digital_download_blog_view_all_ac(){
    $blog = get_option( 'page_for_posts' );
    if( $blog ) return true;
    
    return false; 
}