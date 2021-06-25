<?php
/**
 * EDD related functions and definitions
 *
 * @package Digital_Download
 */

if( ! function_exists( 'digital_download_price' ) ) :
/**
 * EDD Download Price
*/
function digital_download_price(){
    if( edd_has_variable_prices( get_the_ID() ) ){ 
        echo '<span class="price">' . edd_price_range() . '</span>'; //Get the price range
    }elseif( edd_is_free_download( get_the_ID() ) ){
		echo '<span class="price free"><span class="edd_price">' .  esc_html__( 'Free', 'digital-download' ) . '</span></span>'; //Get free download text
	}else{ 
        echo '<span class="price">'; 
        edd_price( get_the_ID() ); //Get the standard price
        echo '</span>';
    }
}
endif;

if( ! function_exists( 'digital_download_header_shoping_cart' ) ) :
/**
 * Shoping Cart
*/
function digital_download_header_shoping_cart(){ ?>    
    <div class="cart" tabindex="0">
		<i class="fas fa-shopping-basket"></i><span class="count edd-cart-quantity"><?php echo edd_get_cart_quantity(); ?></span>
		<?php
            $widget_args = array(
        		'before_widget' => '<div class="product-holder widget_edd_cart_widget">',
        		'after_widget'  => '</div>',
        		'before_title'  => '',
        		'after_title'   => ''
        	);
            the_widget( 'edd_cart_widget', array( 'title' => '' ), $widget_args );
		?>
	</div>
    <?php
}
endif;

if( ! function_exists( 'digital_download_login_button' ) ) :
/**
 * Display Login Button 
*/
function digital_download_login_button(){    
    $class = ''; //Added for partial refresh.
    
    if( is_user_logged_in() ){
		$link  = get_theme_mod( 'dashboard_page' );
        $label = '';        
        
		if( $link ){
            if( is_page( $link ) ){
    			$label = get_theme_mod( 'logout_label', __( 'Logout', 'digital-download' ) );
    			$link  = wp_logout_url( get_permalink( $link ) );
                $class = ' btn-logout';				
    		}else{
    			$label = get_theme_mod( 'dashboard_label', __( 'Dashboard', 'digital-download' ) );
    			$link  = get_permalink( $link );
                $class = ' btn-dashboard';				
    		}
        }
    }else{
        $label = get_theme_mod( 'login_label', __( 'Login', 'digital-download' ) );
        $link  = get_permalink( get_theme_mod( 'login_page' ) );    
    }
    
    if( $label && $link ) echo '<a href="' . esc_url( $link ) . '" class="btn-login btn-primary' . esc_attr( $class ) . '">' . esc_html( $label ) . '</a>';
}
endif;

if( ! function_exists( 'digital_download_sidebar_download_wrap_start' ) ) :
/**
 * Sidebar Download Wrapper Start
*/
function digital_download_sidebar_download_wrap_start(){ ?>
    <div class="download-info-wrapper">
    <?php
}
endif;
add_action( 'digital_download_below_single_sidebar', 'digital_download_sidebar_download_wrap_start', 15 );

if( ! function_exists( 'digital_download_download_price' ) ) :
/**
 * Single Download Sidebar Price
*/
function digital_download_download_price(){ ?>
    <div class="download-price">
		<?php if ( edd_has_variable_prices( get_the_ID() ) ) { ?>
			<!-- Get the price range -->
			<div class="purchase-price price-range">
				<?php echo edd_price_range( get_the_ID() ); ?>
			</div>
		<?php } else if ( function_exists( 'edd_cp_has_custom_pricing' ) && edd_cp_has_custom_pricing( get_the_ID() ) ) { ?>
			<div class="purchase-price name-price">
				<?php _e( 'Name your price:', 'digital-download' ); ?>
			</div>
		<?php } else if ( edd_is_free_download( get_the_ID() ) ) { ?>
			<div class="purchase-price">
				<?php _e( 'Free', 'digital-download' ); ?>
			</div>
		<?php } else { ?>
			<!-- Get the single price -->
			<div class="purchase-price">
				<?php edd_price( get_the_ID() ); ?>
			</div>
		<?php } 
        
			// Get purchase button settings
			$behavior = get_post_meta( get_the_ID(), '_edd_button_behavior', true );

			$hide_button = get_post_meta( get_the_ID(), '_edd_hide_purchase_link', true ) ? 1 : 0;

			// If it's a direct purchase show this text
			if ( $behavior == 'direct' ) {
				$button_text = edd_get_option( 'buy_now_text', __( 'Buy Now', 'digital-download' ) );
			} else {
				// if it's an add to cart purchase, get the text from EDD options
				$button_text = edd_get_option( 'add_to_cart_text', __( 'Purchase', 'digital-download' ) );
			}
		
        	// Show the button unless set to not show
			if ( ! $hide_button ) {
				echo edd_get_purchase_link( array(
					'download_id' => get_the_ID(),
					'price'       => false,
					'direct'      => edd_get_download_button_behavior( get_the_ID() ) == 'direct' ? true : false,
					'text'        => $button_text
				) );
			}
		?>        
	</div><!-- .download-price -->
    <?php 
}
endif;
add_action( 'digital_download_below_single_sidebar', 'digital_download_download_price', 20 );

if( ! function_exists( 'digital_download_sidebar_download_wrap_end' ) ) :
/**
 * Sidebar Download Wrapper End 
*/
function digital_download_sidebar_download_wrap_end(){ ?>
    </div><!-- .download-info-wrapper -->
    <?php    
}
endif;
add_action( 'digital_download_below_single_sidebar', 'digital_download_sidebar_download_wrap_end', 40 );

if( ! function_exists( 'digital_download_edd_meta' ) ) :
/**
 * Single Download Meta
*/
function digital_download_edd_meta(){
    // Get the download tags
	$download_tags = get_the_term_list( get_the_ID(), 'download_tag', '', ' ', '' );

	// Get the download categories
	$download_cats = get_the_term_list( get_the_ID(), 'download_category', '', ' ', '' );
    
    // If the details exist, show them on the single download sidebar
	if ( $download_cats || $download_tags ) { ?>
		<div class="download-post-meta">
			<!-- Get the download categories -->
			<?php if ( $download_cats ) { ?>
				<div class="meta-cat">
					<h2><i class="fas fa-folder-open"></i><?php esc_html_e( 'Categories', 'digital-download' ); ?></h2>
					<?php echo $download_cats; ?>
				</div>
			<?php } ?>

			<!-- Get the download tags -->
			<?php if ( $download_tags ) { ?>
				<div class="meta-tag">
					<h2><i class="fas fa-tags"></i><?php esc_html_e( 'Tags', 'digital-download' ); ?></h2>
					<?php echo $download_tags; ?>
				</div>
			<?php } ?>
		</div><!-- .download-post-meta -->
	<?php 
	}       
}
endif;
add_action( 'digital_download_below_single_sidebar', 'digital_download_edd_meta', 55 );

if( ! function_exists( 'digital_download_download_image' ) ) :
/**
 * Download Image
*/
function digital_download_download_image(){ 
	if( has_post_thumbnail() ){
		the_post_thumbnail( 'digital-download-related', array( 'itemprop' => 'image' ) );
	}else{ 
		digital_download_get_fallback_svg( 'digital-download-related' );//falback
	}?>
	<div class="download-image-overlay">        
		<div class="download-cart-view">
			<a href="<?php the_permalink(); ?>" class="download-view-btn" title="<?php esc_attr_e( 'View', 'digital-download' ); ?>"></a>
			<button data-id="<?php the_ID(); ?>" class="download-cart-btn" title="<?php esc_attr_e( 'Cart', 'digital-download' ); ?>"></button>            
		</div>		
	</div>
	<?php
}
endif;

if( ! function_exists( 'digital_download_remove_variable_pricing' ) ) :
/**
 * Remove default variable pricing output and add our own 
*/
function digital_download_remove_variable_pricing(){
    remove_action( 'edd_purchase_link_top', 'edd_purchase_variable_pricing', 10, 2 );
}
endif;
add_action( 'init', 'digital_download_remove_variable_pricing' );

if( ! function_exists( 'digital_download_variable_pricing' ) ) :
/**
 * Variable price output
 */
function digital_download_variable_pricing( $download_id = 0, $args = array() ){
	global $edd_displayed_form_ids;

	// If we've already generated a form ID for this download ID, append -#
	$form_id = '';
	if ( $edd_displayed_form_ids[ $download_id ] > 1 ) {
		$form_id .= '-' . $edd_displayed_form_ids[ $download_id ];
	}

	$variable_pricing = edd_has_variable_prices( $download_id );

	if ( ! $variable_pricing ) {
		return;
	}

	$prices = apply_filters( 'edd_purchase_variable_prices', edd_get_variable_prices( $download_id ), $download_id );

	// If the price_id passed is found in the variable prices, do not display all variable prices.
	if ( false !== $args['price_id'] && isset( $prices[ $args['price_id'] ] ) ) {
		return;
	}

	$type   = edd_single_price_option_mode( $download_id ) ? 'checkbox' : 'radio';
	$mode   = edd_single_price_option_mode( $download_id ) ? 'multi' : 'single';
	$schema = edd_add_schema_microdata() ? ' itemprop="offers" itemscope itemtype="https://schema.org/Offer"' : '';

	// Filter the class names for the edd_price_options div
	$css_classes_array = apply_filters( 'edd_price_options_classes', array(
		'edd_price_options',
		'edd_' . esc_attr( $mode ) . '_mode'
	), $download_id );

	// Sanitize those class names and form them into a string
	$css_classes_string = implode( ' ', array_map( 'sanitize_html_class', $css_classes_array ) );

	if ( edd_item_in_cart( $download_id ) && ! edd_single_price_option_mode( $download_id ) ) {
		return;
	}

	do_action( 'edd_before_price_options', $download_id ); ?>
	<div class="<?php echo esc_attr( rtrim( $css_classes_string ) ); ?>">
		<ul>
			<?php
			if ( $prices ) :
				$checked_key = isset( $_GET['price_option'] ) ? absint( $_GET['price_option'] ) : edd_get_default_variable_price( $download_id );
				foreach ( $prices as $key => $price ) :
					echo '<li id="edd_price_option_' . $download_id . '_' . sanitize_key( $price['name'] ) . $form_id . '"' . $schema . '>';
						echo '<label for="' . esc_attr( 'edd_price_option_' . $download_id . '_' . $key . $form_id ) . '">';
							echo '<input type="' . $type . '" ' . checked( apply_filters( 'edd_price_option_checked', $checked_key, $download_id, $key ), $key, false ) . ' name="edd_options[price_id][]" id="' . esc_attr( 'edd_price_option_' . $download_id . '_' . $key . $form_id ) . '" class="' . esc_attr( 'edd_price_option_' . $download_id ) . '" value="' . esc_attr( $key ) . '" data-price="' . edd_get_price_option_amount( $download_id, $key ) .'"/>&nbsp;';

							echo '<span class="check-mark"></span>';

							$item_prop = edd_add_schema_microdata() ? ' itemprop="description"' : '';

							// Construct the default price output.
							$price_output = '<span class="edd_price_option_name"' . $item_prop . '>' . esc_html( $price['name'] ) . '</span><span class="edd_price_option_sep">&nbsp;&ndash;&nbsp;</span><span class="edd_price_option_price">' . edd_currency_filter( edd_format_amount( $price['amount'] ) ) . '</span>';

							// Filter the default price output
							$price_output = apply_filters( 'edd_price_option_output', $price_output, $download_id, $key, $price, $form_id, $item_prop );

							// Output the filtered price output
							echo $price_output;

							if( edd_add_schema_microdata() ) {
								echo '<meta itemprop="price" content="' . esc_attr( $price['amount'] ) .'" />';
								echo '<meta itemprop="priceCurrency" content="' . esc_attr( edd_get_currency() ) .'" />';
							}

						echo '</label>';
						do_action( 'edd_after_price_option', $key, $price, $download_id );
					echo '</li>';
				endforeach;
			endif;
			do_action( 'edd_after_price_options_list', $download_id, $prices, $type );
			?>
		</ul>
	</div><!--end .edd_price_options-->
<?php
	do_action( 'edd_after_price_options', $download_id );
}
endif;	
add_action( 'edd_purchase_link_top', 'digital_download_variable_pricing', 10, 2 );

if( ! function_exists( 'digital_download_empty_cart_template' ) ) :
/**
 * Show the list of products when the cart is empty
 */
function digital_download_empty_cart_template(){
	echo do_shortcode( '[downloads orderby="random"]' );
}
endif;
add_filter( 'edd_cart_empty', 'digital_download_empty_cart_template' );

if( ! function_exists( 'digital_download_filter_edd_downloads_list_wrapper_class' ) ) :
/**
 * Added "item-holder" class for download shortcode 
*/
function digital_download_filter_edd_downloads_list_wrapper_class( $wrapper_class, $atts ){
    // make filter magic happen here...
    $wrapper_class .= ' item-holder';
     
    return $wrapper_class;
}
endif;
add_filter( 'edd_downloads_list_wrapper_class', 'digital_download_filter_edd_downloads_list_wrapper_class', 10, 2 );

if( ! function_exists( 'digital_download_edd_download_list_before' ) ) :
/**
 * Wrapper start for downloads in download shortcode
*/
function digital_download_edd_download_list_before( $atts ){
	echo '<div class="recent-items">';
}
endif; 
if( ! is_admin() ) add_action( 'edd_downloads_list_before', 'digital_download_edd_download_list_before' ); 

if( ! function_exists( 'digital_download_edd_download_list_after' ) ) :
/**
 * Wrapper end for downloads in download shortcode
*/
function digital_download_edd_download_list_after( $atts, $downloads, $query ){
    echo '</div><!-- .recent-items -->';
}
endif;
add_action( 'edd_downloads_list_after', 'digital_download_edd_download_list_after', 10, 3 );
