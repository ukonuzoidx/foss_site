<?php
/**
 * About Section
 * 
 * @package Digital_Download
 */

if( digital_download_is_edd_activated() ){
    $section_title    = get_theme_mod( 'download_title', __( 'Recently Added Items', 'digital-download' ) );
    $section_subtitle = get_theme_mod( 'download_subtitle', __( 'Checkout the latest products to be added to the marketplace, or <a href="#">Click here</a> to view all items.', 'digital-download' ) );
    $btn_label        = get_theme_mod( 'download_more_label', __( 'Browse All Products', 'digital-download' ) );
    $btn_link         = get_theme_mod( 'download_more_link' );
    $posts_per_page   = ( int ) get_theme_mod( 'no_recent_download', '6' );
    
    $args = array(
        'post_type'      => 'download',
        'posts_per_page' => $posts_per_page
    );
    
    $qry = new WP_Query( $args );
    
    if( $qry->have_posts() || $section_title || $section_subtitle ){ ?>
        <section id="recent_items" class="recent-items">
        	<div class="container">		
            <?php
                if( $section_title || $section_subtitle ){
                    echo '<header class="section-header">';
                    if( $section_title ) echo '<h2 class="section-title">' . esc_html( $section_title ) . '</h2>';
                    if( $section_subtitle ) echo '<div class="section-header-content">' . wpautop( wp_kses_post( $section_subtitle ) ) . '</div>';
                    echo '</header>';
                }
                
                if( $qry->have_posts() ){ ?>
                    <div class="item-holder">
        			<?php
                        while( $qry->have_posts() ){
                            $qry->the_post(); 
                            get_template_part( 'template-parts/content', 'downloads' );
            			}
                        wp_reset_postdata();
        			?>
            		</div>
            		<?php 
                }
                
                if( $btn_label && $btn_link ){
                    echo '<div class="btn-holder"><a href="' . esc_url( get_permalink( $btn_link ) ) . '" class="btn-primary">' . esc_html( $btn_label ) . '</a></div>';
                }
            ?>  
        	</div>
        </section>
        <?php
    }
}