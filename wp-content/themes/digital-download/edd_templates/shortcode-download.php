<?php
/**
 * A single download inside of the [downloads] shortcode.
 * excerpt, full_content & price parameters are overridden.
 *
 * @package EDD
 */

global $edd_download_shortcode_item_atts, $edd_download_shortcode_item_i;
?>

<?php $schema = edd_add_schema_microdata() ? 'itemscope itemtype="https://schema.org/Product" ' : ''; ?>

<div <?php echo $schema; ?>class="<?php echo esc_attr( apply_filters( 'edd_download_class', 'edd_download item', get_the_ID(), $edd_download_shortcode_item_atts, $edd_download_shortcode_item_i ) ); ?>" id="edd_download_<?php the_ID(); ?>">

	<div class="<?php echo esc_attr( apply_filters( 'edd_download_inner_class', 'edd_download_inner', get_the_ID(), $edd_download_shortcode_item_atts, $edd_download_shortcode_item_i ) ); ?>">

		<?php
			do_action( 'edd_download_before' );

			if ( 'false' !== $edd_download_shortcode_item_atts['thumbnails'] ) :
				edd_get_template_part( 'shortcode', 'content-image' );
				do_action( 'edd_download_after_thumbnail' );
			endif;

			if ( 'yes' === $edd_download_shortcode_item_atts['buy_button'] ) :
				edd_get_template_part( 'shortcode', 'content-cart-button' );
			endif;

			edd_get_template_part( 'shortcode', 'content-title' );

			do_action( 'edd_download_after_title' );
			
			edd_get_template_part( 'shortcode', 'content-price' );
			
            do_action( 'edd_download_after_price' );

			do_action( 'edd_download_after' );
		?>

	</div>

</div>
