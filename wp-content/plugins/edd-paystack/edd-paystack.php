<?php
/*
	Plugin Name:       Paystack Easy Digital Downloads Payment Gateway
	Plugin URL:        https://paystack.com
	Description:       Paystack payment gateway for Easy Digital Downloads
	Version:           1.4.0
	Author:            Tunbosun Ayinla
	Author URI:        https://bosun.me
	License:           GPL-2.0+
	License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if Easy Digital Downloads is active
if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
	return;
}


function tbz_edd_paystack_add_errors() {
	echo '<div id="edd-paystack-payment-errors"></div>';
}
add_action( 'edd_after_cc_fields', 'tbz_edd_paystack_add_errors', 999 );

add_action( 'edd_paystack_cc_form', '__return_false' );

define( 'TBZ_EDD_PAYSTACK_URL', plugin_dir_url( __FILE__ ) );

define( 'TBZ_EDD_PAYSTACK_VERSION', '1.4.0' );


function tbz_edd_paystack_settings_section( $sections ) {

	$sections['paystack-settings'] = 'Paystack';

	return $sections;

}
add_filter( 'edd_settings_sections_gateways', 'tbz_edd_paystack_settings_section' );


function tbz_edd_paystack_settings( $settings ) {

	$paystack_settings = array(
		array(
			'id'   => 'edd_paystack_settings',
			'name' => '<strong>Paystack Settings</strong>',
			'desc' => 'Configure the gateway settings',
			'type' => 'header',
		),
		array(
			'id'   => 'edd_paystack_test_mode',
			'name' => 'Enable Test Mode',
			'desc' => 'Test mode enables you to test payments before going live. Once the LIVE MODE is enabled on your Paystack account uncheck this',
			'type' => 'checkbox',
			'std'  => 0,
		),
		array(
			'id'   => 'edd_paystack_test_secret_key',
			'name' => 'Test Secret Key',
			'desc' => 'Enter your Test Secret Key here',
			'type' => 'text',
			'size' => 'regular',
		),
		array(
			'id'   => 'edd_paystack_test_public_key',
			'name' => 'Test Public Key',
			'desc' => 'Enter your Test Public Key here',
			'type' => 'text',
			'size' => 'regular',
		),
		array(
			'id'   => 'edd_paystack_live_secret_key',
			'name' => 'Live Secret Key',
			'desc' => 'Enter your Live Secret Key here',
			'type' => 'text',
			'size' => 'regular',
		),
		array(
			'id'   => 'edd_paystack_live_public_key',
			'name' => 'Live Public Key',
			'desc' => 'Enter your Live Public Key here',
			'type' => 'text',
			'size' => 'regular',
		),
		array(
			'id'   => 'edd_paystack_webhook',
			'type' => 'descriptive_text',
			'name' => 'Webhook URL',
			'desc' => '<p><strong>Important:</strong> To avoid situations where bad network makes it impossible to verify transactions, set your webhook URL <a href="https://dashboard.paystack.com/#/settings/developer" target="_blank">here</a> in your Paystack account to the URL below.</p>' . '<p><strong><pre>' . home_url( 'index.php?edd-listener=paystackipn' ) . '</pre></strong></p>',
		),
	);

	if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
		$paystack_settings = array( 'paystack-settings' => $paystack_settings );
	}

	return array_merge( $settings, $paystack_settings );

}
add_filter( 'edd_settings_gateways', 'tbz_edd_paystack_settings', 1 );


function tbz_edd_register_paystack_gateway( $gateways ) {

	if ( tbz_paystack_edd_is_setup() ) {

		$gateways['paystack'] = array(
			'admin_label'    => 'Paystack',
			'checkout_label' => 'Paystack',
		);

	}

	return $gateways;

}
add_filter( 'edd_payment_gateways', 'tbz_edd_register_paystack_gateway' );


function tbz_edd_paystack_check_config() {

	$is_enabled = edd_is_gateway_active( 'paystack' );

	if ( ( ! $is_enabled || false === tbz_paystack_edd_is_setup() ) && 'paystack' == edd_get_chosen_gateway() ) {
		edd_set_error( 'paystack_gateway_not_configured', 'There is an error with the Paystack configuration.' );
	}

	if ( ! in_array( edd_get_currency(), array(
			'GHS',
			'NGN',
			'USD',
			'ZAR',
		) ) && 'paystack' == edd_get_chosen_gateway() ) {
		edd_set_error( 'paystack_gateway_invalid_currency', 'Set your store currency to either NGN (&#8358), GHS (&#x20b5;), USD (&#36;) or ZAR (R)' );
	}

}
add_action( 'edd_pre_process_purchase', 'tbz_edd_paystack_check_config', 1 );


function tbz_paystack_edd_is_setup() {

	if ( edd_get_option( 'edd_paystack_test_mode' ) ) {

		$secret_key = trim( edd_get_option( 'edd_paystack_test_secret_key' ) );
		$public_key = trim( edd_get_option( 'edd_paystack_test_public_key' ) );

	} else {

		$secret_key = trim( edd_get_option( 'edd_paystack_live_secret_key' ) );
		$public_key = trim( edd_get_option( 'edd_paystack_live_public_key' ) );

	}

	if ( empty( $public_key ) || empty( $secret_key ) ) {
		return false;
	}

	return true;

}


function tbz_paystack_edd_get_payment_link( $paystack_data ) {

	$paystack_url = 'https://api.paystack.co/transaction/initialize/';

	if ( edd_get_option( 'edd_paystack_test_mode' ) ) {

		$secret_key = trim( edd_get_option( 'edd_paystack_test_secret_key' ) );

	} else {

		$secret_key = trim( edd_get_option( 'edd_paystack_live_secret_key' ) );

	}

	$headers = array(
		'Authorization' => 'Bearer ' . $secret_key,
	);

	$callback_url = add_query_arg( 'edd-listener', 'paystack', home_url( 'index.php' ) );

	$body = array(
		'amount'       => $paystack_data['amount'],
		'email'        => $paystack_data['email'],
		'reference'    => $paystack_data['reference'],
		'currency'     => edd_get_currency(),
		'callback_url' => $callback_url,
	);

	$args = array(
		'body'    => $body,
		'headers' => $headers,
		'timeout' => 60,
	);

	$request = wp_remote_post( $paystack_url, $args );

	if ( ! is_wp_error( $request ) && 200 === wp_remote_retrieve_response_code( $request ) ) {

		$paystack_response = json_decode( wp_remote_retrieve_body( $request ) );

	} else {

		$paystack_response = json_decode( wp_remote_retrieve_body( $request ) );

	}

	return $paystack_response;

}


function tbz_edd_paystack_process_payment( $purchase_data ) {

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
		'gateway'      => 'paystack',
	);

	$payment = edd_insert_payment( $payment_data );

	if ( ! $payment ) {

		edd_record_gateway_error( 'Payment Error', sprintf( 'Payment creation failed before sending buyer to Paystack. Payment data: %s', json_encode( $payment_data ) ), $payment );

		edd_send_back_to_checkout( '?payment-mode=paystack' );

	} else {

		$paystack_data = array();

		$paystack_data['amount']    = $purchase_data['price'] * 100;
		$paystack_data['email']     = $purchase_data['user_email'];
		$paystack_data['reference'] = 'EDD-' . $payment . '-' . uniqid();

		edd_set_payment_transaction_id( $payment, $paystack_data['reference'] );

		$get_payment_url = tbz_paystack_edd_get_payment_link( $paystack_data );

		if ( $get_payment_url->status ) {

			wp_redirect( $get_payment_url->data->authorization_url );

			exit;

		} else {

			edd_record_gateway_error( 'Payment Error', $get_payment_url->message );

			edd_set_error( 'paystack_error', 'Can\'t connect to the gateway, Please try again.' );

			edd_send_back_to_checkout( '?payment-mode=paystack' );

		}
	}

}
add_action( 'edd_gateway_paystack', 'tbz_edd_paystack_process_payment' );


function tbz_edd_paystack_redirect() {

	if ( isset( $_GET['edd-listener'] ) && $_GET['edd-listener'] == 'paystack' ) {
		do_action( 'tbz_edd_paystack_redirect_verify' );
	}

	if ( isset( $_GET['edd-listener'] ) && $_GET['edd-listener'] == 'paystackipn' ) {
		do_action( 'tbz_edd_paystack_ipn_verify' );
	}

}
add_action( 'init', 'tbz_edd_paystack_redirect' );


function tbz_edd_paystack_redirect_verify() {

	if ( isset( $_REQUEST['trxref'] ) ) {

		$transaction_id = $_REQUEST['trxref'];

		$the_payment_id = edd_get_purchase_id_by_transaction_id( $transaction_id );

		if ( $the_payment_id && get_post_status( $the_payment_id ) == 'publish' ) {

			edd_empty_cart();

			edd_send_to_success_page();
		}

		$paystack_txn = tbz_edd_paystack_verify_transaction( $transaction_id );

		$order_info = explode( '-', $transaction_id );

		$payment_id = $order_info[1];

		if ( $payment_id && ! empty( $paystack_txn->data ) && ( $paystack_txn->data->status === 'success' ) ) {

			$payment = new EDD_Payment( $payment_id );

			$order_total = edd_get_payment_amount( $payment_id );

			$currency_symbol = edd_currency_symbol( $payment->currency );

			$amount_paid = $paystack_txn->data->amount / 100;

			$paystack_txn_ref = $paystack_txn->data->reference;

			if ( $amount_paid < $order_total ) {

				$note = 'Look into this purchase. This order is currently revoked. Reason: Amount paid is less than the total order amount. Amount Paid was ' . $currency_symbol . $amount_paid . ' while the total order amount is ' . $currency_symbol . $order_total . '. Paystack Transaction Reference: ' . $paystack_txn_ref;

				$payment->status = 'revoked';

				$payment->add_note( $note );

				$payment->transaction_id = $paystack_txn_ref;

			} else {

				$note = 'Payment transaction was successful. Paystack Transaction Reference: ' . $paystack_txn_ref;

				$payment->status = 'publish';

				$payment->add_note( $note );

				$payment->transaction_id = $paystack_txn_ref;

			}

			$payment->save();

			edd_empty_cart();

			edd_send_to_success_page();

		} else {

			edd_set_error( 'failed_payment', 'Payment failed. Please try again.' );

			edd_send_back_to_checkout( '?payment-mode=paystack' );

		}
	}

}
add_action( 'tbz_edd_paystack_redirect_verify', 'tbz_edd_paystack_redirect_verify' );


function tbz_edd_paystack_ipn_verify() {

	if ( ( strtoupper( $_SERVER['REQUEST_METHOD'] ) != 'POST' ) || ! array_key_exists( 'HTTP_X_PAYSTACK_SIGNATURE', $_SERVER ) ) {
		exit;
	}

	$json = file_get_contents( 'php://input' );

	if ( edd_get_option( 'edd_paystack_test_mode' ) ) {

		$secret_key = trim( edd_get_option( 'edd_paystack_test_secret_key' ) );

	} else {

		$secret_key = trim( edd_get_option( 'edd_paystack_live_secret_key' ) );

	}

	// validate event do all at once to avoid timing attack
	if ( $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac( 'sha512', $json, $secret_key ) ) {
		exit;
	}

	$event = json_decode( $json );

	if ( 'charge.success' == $event->event ) {

		http_response_code( 200 );

		$transaction_id = $event->data->reference;

		$the_payment_id = edd_get_purchase_id_by_transaction_id( $transaction_id );

		if ( $the_payment_id && get_post_status( $the_payment_id ) == 'publish' ) {
			exit;
		}

		$order_info = explode( '-', $transaction_id );

		$payment_id = $order_info[1];

		$saved_txn_ref = edd_get_payment_transaction_id( $payment_id );

		if ( $event->data->reference != $saved_txn_ref ) {
			exit;
		}

		$payment = new EDD_Payment( $payment_id );

		$order_total = edd_get_payment_amount( $payment_id );

		$currency_symbol = edd_currency_symbol( $payment->currency );

		$amount_paid = $event->data->amount / 100;

		$paystack_txn_ref = $event->data->reference;

		if ( $amount_paid < $order_total ) {

			$note = 'Look into this purchase. This order is currently revoked. Reason: Amount paid is less than the total order amount. Amount Paid was ' . $currency_symbol . $amount_paid . ' while the total order amount is ' . $currency_symbol . $order_total . '. Paystack Transaction Reference: ' . $paystack_txn_ref;

			$payment->status = 'revoked';

			$payment->add_note( $note );

			$payment->transaction_id = $paystack_txn_ref;

		} else {

			$note = 'Payment transaction was successful. Paystack Transaction Reference: ' . $paystack_txn_ref;

			$payment->status = 'publish';

			$payment->add_note( $note );

			$payment->transaction_id = $paystack_txn_ref;

		}

		$payment->save();

		exit;
	}

	exit;
}
add_action( 'tbz_edd_paystack_ipn_verify', 'tbz_edd_paystack_ipn_verify' );


function tbz_edd_paystack_verify_transaction( $payment_token ) {

	$paystack_url = 'https://api.paystack.co/transaction/verify/' . $payment_token;

	if ( edd_get_option( 'edd_paystack_test_mode' ) ) {

		$secret_key = trim( edd_get_option( 'edd_paystack_test_secret_key' ) );

	} else {

		$secret_key = trim( edd_get_option( 'edd_paystack_live_secret_key' ) );

	}

	$headers = array(
		'Authorization' => 'Bearer ' . $secret_key,
	);

	$args = array(
		'headers' => $headers,
		'timeout' => 60,
	);

	$request = wp_remote_get( $paystack_url, $args );

	if ( ! is_wp_error( $request ) && 200 === wp_remote_retrieve_response_code( $request ) ) {

		$paystack_response = json_decode( wp_remote_retrieve_body( $request ) );

	} else {

		$paystack_response = json_decode( wp_remote_retrieve_body( $request ) );

	}

	return $paystack_response;

}


function tbz_edd_paystack_testmode_notice() {

	if ( edd_get_option( 'edd_paystack_test_mode' ) ) {
		?>
		<div class="error">
			<p>Paystack testmode is still enabled for EDD, click <a href="<?php echo get_bloginfo( 'wpurl' ); ?>/wp-admin/edit.php?post_type=download&page=edd-settings&tab=gateways&section=paystack-settings">here</a> to disable it when you want to start accepting live payment on your site.</p>
		</div>
		<?php
	}

}
add_action( 'admin_notices', 'tbz_edd_paystack_testmode_notice' );


function tbz_edd_paystack_payment_icons( $icons ) {

	$icons[ TBZ_EDD_PAYSTACK_URL . 'assets/images/paystack.png' ] = 'Paystack';

	return $icons;

}
add_filter( 'edd_accepted_payment_icons', 'tbz_edd_paystack_payment_icons' );


function tbz_edd_paystack_extra_edd_currencies( $currencies ) {

	$currencies['GHS'] = 'Ghanaian Cedi (&#x20b5;)';
	$currencies['NGN'] = 'Nigerian Naira (&#8358;)';

	return $currencies;

}
add_filter( 'edd_currencies', 'tbz_edd_paystack_extra_edd_currencies' );


function tbz_edd_paystack_extra_currency_symbol( $symbol, $currency ) {

	switch ( $currency ) {
		case 'GHS':
			$symbol = '&#x20b5;';
			break;

		case 'NGN':
			$symbol = '&#8358;';
			break;
	}

	return $symbol;

}
add_filter( 'edd_currency_symbol', 'tbz_edd_paystack_extra_currency_symbol', 10, 2 );


function tbz_edd_paystack_format_ngn_currency_before( $formatted, $currency, $price ) {

	$symbol = edd_currency_symbol( $currency );

	return $symbol . $price;
}
add_filter( 'edd_ngn_currency_filter_before', 'tbz_edd_paystack_format_ngn_currency_before', 10, 3 );


function tbz_edd_paystack_format_ngn_currency_after( $formatted, $currency, $price ) {

	$symbol = edd_currency_symbol( $currency );

	return $price . $symbol;
}
add_filter( 'edd_ngn_currency_filter_after', 'tbz_edd_paystack_format_ngn_currency_after', 10, 3 );


function tbz_edd_paystack_plugin_action_links( $links ) {

	$settings_link = array(
		'settings' => '<a href="' . admin_url( 'edit.php?post_type=download&page=edd-settings&tab=gateways&section=paystack-settings' ) . '" title="Settings">Settings</a>',
	);

	return array_merge( $settings_link, $links );

}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'tbz_edd_paystack_plugin_action_links' );
