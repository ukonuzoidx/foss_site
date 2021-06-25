<?php
/**
 * Blog Section
 * 
 * @package Digital_Download
 */

$newsletter = get_theme_mod( 'front_newsletter_shortcode' ); 

if( $newsletter ){ ?>
    <div id="subscribe_section" class="subscrib-section">
    	<div class="newsletter-holder">
            <?php echo do_shortcode( wp_kses_post( $newsletter ) ); ?>
    	</div>
    </div>
    <?php
}