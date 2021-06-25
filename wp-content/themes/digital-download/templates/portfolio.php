<?php
/**
 * Template Name: Portfolio Page
 * 
 * @package Digital_Download
 */

get_header(); ?> 

<section class="recent-items">
    <div class="container">
    <?php
        if( taxonomy_exists( 'rara_portfolio_categories' ) ){
            $label = get_theme_mod( 'portfolio_cat_select_label', __( 'Browse by :', 'digital-download' ) );
            $args  = array(
                'taxonomy'      => 'rara_portfolio_categories',
                'orderby'       => 'name', 
                'order'         => 'ASC',
            );                
            $terms = get_terms( $args );
            if( $terms ){ ?>
                <header class="section-header">        
                    <div class="button-group filter-button-group">
                        <button data-filter="*" class="button is-active"><?php echo esc_html_e( 'All', 'digital-download' ); ?></button><!-- This is HACK for reducing space between inline block elements.
                        --><?php
                            foreach( $terms as $t ){                            
                                echo '<button class="button" data-filter=".' . esc_attr( $t->slug ) .  '">' . esc_html( $t->name ) . '</button>';
                            } 
                        ?>
                    </div>
                </header><!-- .section-header -->            
                <?php
            }
        }
        
        $portfolio_qry = new WP_Query( array( 'post_type' => 'rara-portfolio', 'post_status' => 'publish', 'posts_per_page' => -1 ) );
        if( taxonomy_exists( 'rara_portfolio_categories' ) && $portfolio_qry->have_posts() ){ ?>
                            
            <div class="item-holder">
        		<?php
                while( $portfolio_qry->have_posts() ){
                    $portfolio_qry->the_post();
                    $terms = get_the_terms( get_the_ID(), 'rara_portfolio_categories' );
                    $string = '';
                    $i = 0;
                    if( $terms ){
                        foreach( $terms as $t ){
                            $i++;
                            $string .= $t->slug;
                            if( count( $terms ) > $i ){
                                $string .= ' ';
                            }
                        }
                    }
                    $term_list = get_the_term_list( get_the_ID(), 'rara_portfolio_categories', '', ', ' ); ?>
                    <div class="item <?php echo esc_attr( $string );?>">
        				<div class="img-holder">
        					<a href="<?php the_permalink(); ?>">
                            <?php 
                                if( has_post_thumbnail() ){
                                    the_post_thumbnail( 'digital-download-related', array( 'itemprop' => 'image' ) );    
                                }else{
                                    digital_download_get_fallback_svg( 'digital-download-related' );
                                }
                            ?>
                            </a>
        				</div>
                        <div class="text-holder">
    						<h3 class="product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <?php if( $term_list ) echo '<div class="category">' . $term_list . '</div>'; ?>
    					</div>              
        			</div>
        		    <?php
                }
                ?>
        	</div><!-- .item-holder -->
            <?php
            wp_reset_postdata(); 
        }
    ?>
    </div><!-- .container -->
</section><!-- .recent-items -->
<?php    
get_footer();
