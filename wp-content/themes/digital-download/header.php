<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Digital_Download
 */
    /**
     * Doctype Hook
     * 
     * @hooked digital_download_doctype
    */
    do_action( 'digital_download_doctype' );
?>
<head itemscope itemtype="https://schema.org/WebSite">
	<?php 
    /**
     * Before wp_head
     * 
     * @hooked digital_download_head
    */
    do_action( 'digital_download_before_wp_head' );
    
    wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">

<?php
    wp_body_open();
    
    /**
     * Before Header
     * 
     * @hooked digital_download_page_start - 20 
    */
    do_action( 'digital_download_before_header' );
    
    /**
     * Header
     * 
     * @hooked digital_download_header - 20     
    */
    do_action( 'digital_download_header' );
    
    /**
     * After Header
    */
    do_action( 'digital_download_after_header' );
    
    /**
     * Content
     * 
     * @hooked digital_download_content_start
    */
    do_action( 'digital_download_content' );