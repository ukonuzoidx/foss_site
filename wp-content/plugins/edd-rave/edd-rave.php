<?php
/*
    Plugin Name:    Rave Easy Digital Downloads Payment Gateway
    Plugin URL:     https://wordpress.org/plugins/edd-rave
    Description:    Rave payment gateway for Easy Digital Downloads
    Version:        1.2.0
    Author:         Tunbosun Ayinla
    Author URI:     https://bosun.me
    License:        GPL-2.0+
    License URI:    http://www.gnu.org/licenses/gpl-2.0.txt
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if Easy Digital Downloads is active
if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
	return;
}

define( 'TBZ_EDD_RAVE_URL', plugin_dir_url( __FILE__ ) );

define( 'TBZ_EDD_RAVE_VERSION', '1.1.0' );

add_action( 'edd_rave_cc_form', '__return_false' );

function tbz_edd_rave_add_errors() {

	echo '<div id="edd-rave-payment-errors"></div>';

}
add_action( 'edd_after_cc_fields', 'tbz_edd_rave_add_errors', 999 );

function tbz_edd_rave_settings_section( $sections ) {

	$sections['rave-settings'] = 'Rave';

	return $sections;

}
add_filter( 'edd_settings_sections_gateways', 'tbz_edd_rave_settings_section' );

function tbz_edd_rave_settings( $settings ) {

	$rave_settings = array(
		array(
			'id'   => 'edd_rave_settings',
			'name' => '<strong>Rave Settings</strong>',
			'desc' => 'Configure the gateway settings',
			'type' => 'header',
		),
		array(
			'id'   => 'edd_rave_test_mode',
			'name' => 'Enable Test Mode',
			'desc' => 'Test mode enables you to test payments before going live. Once you are live uncheck this.',
			'type' => 'checkbox',
			'std'  => 0,
		),
		array(
			'id'   => 'edd_rave_test_public_key',
			'name' => 'Test Public Key',
			'desc' => 'Enter your Test Public Key here',
			'type' => 'text',
			'size' => 'regular',
		),
		array(
			'id'   => 'edd_rave_test_secret_key',
			'name' => 'Test Secret Key',
			'desc' => 'Enter your Test Secret Key here',
			'type' => 'text',
			'size' => 'regular',
		),
		array(
			'id'   => 'edd_rave_live_public_key',
			'name' => 'Live Public Key',
			'desc' => 'Enter your Live Public Key here',
			'type' => 'text',
			'size' => 'regular',
		),
		array(
			'id'   => 'edd_rave_live_secret_key',
			'name' => 'Live Secret Key',
			'desc' => 'Enter your Live Secret Key here',
			'type' => 'text',
			'size' => 'regular',
		),
		array(
			'id'   => 'edd_rave_title',
			'name' => 'Modal Title',
			'desc' => 'Text to be displayed as the title of the payment modal',
			'type' => 'text',
			'size' => 'regular',
		),
		array(
			'id'   => 'edd_rave_description',
			'name' => 'Modal Description',
			'desc' => 'Text to be displayed as a short modal description',
			'type' => 'text',
			'size' => 'regular',
		),
		array(
			'id'   => 'edd_rave_checkout_image',
			'name' => 'Checkout Logo',
			'desc' => 'Upload an image to be shown on the Rave Checkout modal window. Recommended minimum size is 128x128px. Leave blank to disable the image.',
			'type' => 'upload',
		),
		array(
			'id'   => 'edd_rave_webhook',
			'type' => 'descriptive_text',
			'name' => 'Webhook URL',
			'desc' => '<p><strong>Important:</strong> To avoid situations where bad network makes it impossible to verify transactions, set your webhook URL <a href="https://rave.flutterwave.com/dashboard/settings/webhooks" target="_blank">here</a> in your Rave account to the URL below.</p>' . '<p><strong><pre>' . home_url( 'index.php?edd-listener=raveipn' ) . '</pre></strong></p>',
		),
	);

	if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
		$rave_settings = array( 'rave-settings' => $rave_settings );
	}

	return array_merge( $settings, $rave_settings );

}
add_filter( 'edd_settings_gateways', 'tbz_edd_rave_settings', 1 );

function tbz_edd_register_rave_gateway( $gateways ) {

	if ( tbz_rave_edd_is_setup() ) {

		$gateways['rave'] = array(
			'admin_label'    => 'Rave',
			'checkout_label' => 'Rave',
		);

	}

	return $gateways;

}
add_filter( 'edd_payment_gateways', 'tbz_edd_register_rave_gateway' );

function tbz_edd_rave_check_config() {

	$is_enabled = edd_is_gateway_active( 'rave' );

	if ( ( ! $is_enabled || false === tbz_rave_edd_is_setup() ) && 'rave' == edd_get_chosen_gateway() ) {

		edd_set_error( 'rave_gateway_not_configured', 'There is an error with the Rave configuration.' );

	}

}
add_action( 'edd_pre_process_purchase', 'tbz_edd_rave_check_config', 1 );

function tbz_rave_edd_is_setup() {

	if ( edd_get_option( 'edd_rave_test_mode' ) ) {

		$secret_key = edd_get_option( 'edd_rave_test_secret_key', '' );
		$public_key = edd_get_option( 'edd_rave_test_public_key', '' );

	} else {

		$secret_key = edd_get_option( 'edd_rave_live_secret_key', '' );
		$public_key = edd_get_option( 'edd_rave_live_public_key', '' );

	}

	if ( empty( $public_key ) || empty( $secret_key ) ) {
		return false;
	}

	return true;

}

function tbz_edd_rave_get_payment_link( $rave_data ) {

	$ravepay_url = 'https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay';

	if ( edd_get_option( 'edd_rave_test_mode' ) ) {

		$public_key = edd_get_option( 'edd_rave_test_public_key', '' );

	} else {

		$public_key = edd_get_option( 'edd_rave_live_public_key', '' );

	}

	$headers = array(
		'Content-Type' => 'application/json',
	);

	$callback_url = add_query_arg( 'edd-listener', 'rave', home_url( 'index.php' ) );

	$body = array(
		'amount'             => $rave_data['amount'],
		'customer_firstname' => $rave_data['first_name'],
		'customer_lastname'  => $rave_data['last_name'],
		'customer_email'     => $rave_data['email'],
		'txref'              => $rave_data['reference'],
		'currency'           => edd_get_currency(),
		'custom_title'       => edd_get_option( 'edd_rave_title', '' ),
		'custom_description' => edd_get_option( 'edd_rave_description', '' ),
		'custom_logo'        => edd_get_option( 'edd_rave_checkout_image', '' ),
		'redirect_url'       => $callback_url,
		'PBFPubKey'          => $public_key,
	);

	$shop_country = edd_get_shop_country();

	if ( 'US' !== $shop_country ) {
		$body['country'] = $shop_country;
	}

	$args = array(
		'body'    => json_encode( $body ),
		'headers' => $headers,
		'timeout' => 60,
	);

	$request = wp_remote_post( $ravepay_url, $args );

	if ( ! is_wp_error( $request ) && 200 === wp_remote_retrieve_response_code( $request ) ) {

		$rave_response = json_decode( wp_remote_retrieve_body( $request ) );

	} else {

		$rave_response = json_decode( wp_remote_retrieve_body( $request ) );

	}

	return $rave_response;
}

function tbz_edd_rave_process_payment( $purchase_data ) {

	$payment_data = array(
		'price'        => $purchase_data['price'],
		'date'         => $purchase_data['date'],
		'user_email'   => $purchase_data['user_email'],
		'purchase_key' => $purchase_data['purchase_key'],
		'currency'     => edd_get_currency(),
		'downloads'    => $purchase_data['downloads'],
		'cart_details' => $purchase_data['cart_details'],
		'user_info'    => $purchase_data['user_info'],
		'status'       => 'pending',
		'gateway'      => 'rave',
	);

	$payment = edd_insert_payment( $payment_data );

	if ( ! $payment ) {

		edd_record_gateway_error( 'Payment Error', sprintf( 'Payment creation failed before sending buyer to Rave. Payment data: %s', json_encode( $payment_data ) ), $payment );

		edd_send_back_to_checkout( '?payment-mode=rave' );

	} else {

		$rave_data = array();

		$rave_data['amount']     = $purchase_data['price'];
		$rave_data['email']      = $purchase_data['user_email'];
		$rave_data['first_name'] = $purchase_data['user_info']['first_name'];
		$rave_data['last_name']  = $purchase_data['user_info']['last_name'];
		$rave_data['reference']  = 'EDD-' . $payment . '-' . uniqid();

		edd_set_payment_transaction_id( $payment, $rave_data['reference'] );

		$get_payment_url = tbz_edd_rave_get_payment_link( $rave_data );

		if ( 'success' === $get_payment_url->status ) {

			wp_redirect( $get_payment_url->data->link );

			exit;

		} else {

			edd_record_gateway_error( 'Payment Error', $get_payment_url->message );

			edd_set_error( 'rave_error', 'Can\'t connect to the gateway, Please try again.' );

			edd_send_back_to_checkout( '?payment-mode=rave' );

		}
	}

}
add_action( 'edd_gateway_rave', 'tbz_edd_rave_process_payment' );

function tbz_edd_rave_process_listener() {

	if ( isset( $_GET['edd-listener'] ) && $_GET['edd-listener'] == 'rave' ) {
		do_action( 'tbz_edd_rave_redirect_verify' );
	}

	if ( isset( $_GET['edd-listener'] ) && $_GET['edd-listener'] == 'raveip' ) {
		do_action( 'tbz_edd_rave_ipn_verify' );
	}

}
add_action( 'init', 'tbz_edd_rave_process_listener' );

function tbz_edd_rave_redirect_verify() {

	if ( isset( $_REQUEST['txref'] ) ) {

		$transaction_id = $_REQUEST['txref'];

		$payment_id = edd_get_purchase_id_by_transaction_id( $transaction_id );

		if ( $payment_id && get_post_status( $payment_id ) == 'publish' ) {

			edd_empty_cart();

			edd_send_to_success_page();
		}

		$rave_txn = tbz_edd_rave_verify_transaction( $transaction_id );

		$status              = $rave_txn->status;
		$response_code       = $rave_txn->data->chargecode;
		$valid_response_code = array( '0', '00' );

		if ( $payment_id && ! empty( $rave_txn->data ) && ( 'success' === $status ) && in_array( $response_code, $valid_response_code ) ) {

			$payment              = new EDD_Payment( $payment_id );
			$currency_symbol      = edd_currency_symbol( $payment->currency );
			$order_total          = edd_get_payment_amount( $payment_id );
			$rave_currency_symbol = edd_currency_symbol( $rave_txn->data->currency );
			$amount_paid          = $rave_txn->data->amount;
			$txn_ref              = $rave_txn->data->txref;
			$payment_ref          = $rave_txn->data->flwref;

			if ( $amount_paid < $order_total ) {

				$note = 'Look into this purchase. This order is currently revoked. Reason: Amount paid is less than the total order amount. Amount Paid was ' . $rave_currency_symbol . $amount_paid . ' while the total order amount is ' . $currency_symbol . $order_total . '. Rave Transaction ID: ' . $txn_ref . ' . Rave Payment Reference: ' . $payment_ref;

				$payment->status = 'revoked';

				$payment->add_note( $note );

				$payment->transaction_id = $txn_ref;

			} else {

				$note = 'Payment transaction was successful. Rave Transaction ID: ' . $txn_ref . ' . Rave Payment Reference: ' . $payment_ref;

				$payment = new EDD_Payment( $payment_id );

				$payment->status = 'publish';

				$payment->add_note( $note );

				$payment->transaction_id = $txn_ref;

			}

			$payment->save();

			edd_empty_cart();

			edd_send_to_success_page();

		} else {

			edd_set_error( 'failed_payment', 'Payment failed. Please try again.' );

			edd_send_back_to_checkout( '?payment-mode=rave' );

		}

	} else {

		edd_send_back_to_checkout( '?payment-mode=rave' );

	}

}
add_action( 'tbz_edd_rave_redirect_verify', 'tbz_edd_rave_redirect_verify' );

function tbz_edd_rave_ipn_verify() {

	if ( ( strtoupper( $_SERVER['REQUEST_METHOD'] ) != 'POST' ) ) {
		exit;
	}

	sleep( 10 );

	$body = @file_get_contents( 'php://input' );

	if ( tbz_edd_rave_is_json( $body ) ) {
		$webhook_body = (array) json_decode( $body );
	}

	if ( ! isset( $webhook_body['flwRef'] ) ) {
		exit;
	}

	if ( edd_get_option( 'edd_rave_test_mode' ) ) {

		$secret_key = edd_get_option( 'edd_rave_test_secret_key', '' );

	} else {

		$secret_key = edd_get_option( 'edd_rave_live_secret_key', '' );

	}

	$headers = array(
		'Content-Type' => 'application/json',
	);

	$body = array(
		'flwref'    => $webhook_body['flwRef'],
		'SECKEY'    => $secret_key,
		'normalize' => '1',
	);

	$args = array(
		'headers' => $headers,
		'body'    => json_encode( $body ),
		'timeout' => 60,
	);

	$rave_query_url = 'https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify';

	$request = wp_remote_post( $rave_query_url, $args );

	if ( ! is_wp_error( $request ) && 200 == wp_remote_retrieve_response_code( $request ) ) {
		$response = json_decode( wp_remote_retrieve_body( $request ) );

		$status              = $response->status;
		$response_code       = $response->data->chargecode;
		$valid_response_code = array( '0', '00' );

		if ( 'success' === $status && in_array( $response_code, $valid_response_code ) ) {

			$txn_ref    = $response->data->txref;
			$payment_id = edd_get_purchase_id_by_transaction_id( $txn_ref );

			if ( $payment_id ) {
				http_response_code( 200 );
			}

			if ( $payment_id && get_post_status( $payment_id ) == 'publish' ) {
				exit;
			}

			$payment_ref = $response->data->flwref;

			$payment = new EDD_Payment( $payment_id );

			$order_total = edd_get_payment_amount( $payment_id );

			$currency_symbol      = edd_currency_symbol( $payment->currency );
			$rave_currency_symbol = edd_currency_symbol( $response->data->currency );

			$amount_paid = $response->data->amount;

			if ( $amount_paid < $order_total ) {

				$note = 'Look into this purchase. This order is currently revoked. Reason: Amount paid is less than the total order amount. Amount Paid was ' . $rave_currency_symbol . $amount_paid . ' while the total order amount is ' . $currency_symbol . $order_total . '. Rave Transaction ID: ' . $txn_ref . ' . Rave Payment Reference: ' . $payment_ref;

				$payment->status = 'revoked';

				$payment->add_note( $note );

				$payment->transaction_id = $txn_ref;

			} else {

				$note = 'Payment transaction was successful. Rave Transaction ID: ' . $txn_ref . ' . Rave Payment Reference: ' . $payment_ref;

				$payment->status = 'publish';

				$payment->add_note( $note );

				$payment->transaction_id = $txn_ref;

			}

			$payment->save();

		}

	}

	exit;
}
add_action( 'tbz_edd_rave_ipn_verify', 'tbz_edd_rave_ipn_verify' );

function tbz_edd_rave_verify_transaction( $payment_token ) {

	if ( edd_get_option( 'edd_rave_test_mode' ) ) {

		$secret_key = edd_get_option( 'edd_rave_test_secret_key' );

	} else {

		$secret_key = edd_get_option( 'edd_rave_live_secret_key' );

	}

	$rave_verify_url = 'https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify';

	$headers = array(
		'Content-Type' => 'application/json',
	);

	$body = array(
		'txref'     => $payment_token,
		'SECKEY'    => $secret_key,
		'normalize' => '1',
	);

	$args = array(
		'headers' => $headers,
		'body'    => json_encode( $body ),
		'timeout' => 60,
	);

	$request = wp_remote_post( $rave_verify_url, $args );

	if ( ! is_wp_error( $request ) && 200 == wp_remote_retrieve_response_code( $request ) ) {

		$rave_response = json_decode( wp_remote_retrieve_body( $request ) );

	} else {

		$rave_response = json_decode( wp_remote_retrieve_body( $request ) );

	}

	return $rave_response;

}

function tbz_rave_paystack_testmode_notice() {

	if ( edd_get_option( 'edd_rave_test_mode' ) ) {
		?>
		<div class="error">
			<p>Rave testmode is still enabled for EDD, click <a href="<?php echo get_bloginfo( 'wpurl' ) ?>/wp-admin/edit.php?post_type=download&page=edd-settings&tab=gateways&section=rave-settings">here</a> to disable it when you want to start accepting live payment on your site.</p>
		</div>
		<?php
	}

}
add_action( 'admin_notices', 'tbz_rave_paystack_testmode_notice' );

function tbz_edd_rave_payment_icons( $icons ) {

	$icons[ TBZ_EDD_RAVE_URL . 'assets/images/powered-by-rave.png' ] = 'Rave';

	return $icons;

}
add_filter( 'edd_accepted_payment_icons', 'tbz_edd_rave_payment_icons' );

function tbz_edd_rave_extra_edd_currencies( $currencies ) {

	$currencies['NGN'] = 'Nigerian Naira (&#8358;)';
	$currencies['KES'] = 'Kenyan Shilling (KSh)';
	$currencies['GHS'] = 'Ghanaian Cedi (&#x20b5;)';
	$currencies['ZAR'] = 'South African Rand (&#82;)';
	$currencies['UGX'] = 'Ugandan Shilling (UGX)';
	$currencies['RWF'] = 'Rwandan Franc (Fr)';
	$currencies['TZS'] = 'Tanzanian Shilling (Sh)';
	$currencies['SLL'] = 'Sierra Leonean Leone (Le)';
	$currencies['XAF'] = 'Central African CFA franc (CFA)';
	$currencies['ZMW'] = 'Zambian Kwacha (ZK)';

	return $currencies;

}
add_filter( 'edd_currencies', 'tbz_edd_rave_extra_edd_currencies' );

function tbz_edd_rave_extra_currency_symbol( $symbol, $currency ) {

	switch ( $currency ) {

		case 'NGN':
			$symbol = '&#8358;';
			break;

		case 'KES':
			$symbol = 'KSh';
			break;

		case 'GHS':
			$symbol = '&#x20b5;';
			break;

		case 'ZAR':
			$symbol = '&#82;';
			break;

		case 'UGX':
			$symbol = 'UGX';
			break;

		case 'RWF':
			$symbol = 'Fr';
			break;

		case 'TZS':
			$symbol = 'Sh';
			break;

		case 'SLL':
			$symbol = 'Le';
			break;

		case 'XAF':
			$symbol = 'CFA';
			break;

		case 'ZMW':
			$symbol = 'ZK';
			break;

	}

	return $symbol;

}
add_filter( 'edd_currency_symbol', 'tbz_edd_rave_extra_currency_symbol', 10, 2 );

function tbz_edd_rave_plugin_action_links( $links ) {

	$settings_link = array(
		'settings' => '<a href="' . admin_url( 'edit.php?post_type=download&page=edd-settings&tab=gateways&section=rave-settings' ) . '" title="Settings">Settings</a>',
	);

	return array_merge( $settings_link, $links );

}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'tbz_edd_rave_plugin_action_links' );

function tbz_edd_rave_is_json( $string ) {
	return is_string( $string ) && is_array( json_decode( $string, true ) ) ? true : false;
}