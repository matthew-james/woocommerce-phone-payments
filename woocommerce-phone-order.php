<?php
/**
 * @package woocommerce_phone_order
 * @version 1.0
 */
/*
Plugin Name: Woocommerce Phone Order
Plugin URI:
Description: Adds the ability to add payments to Woocommerce orders from the order admin screen.
Author: Matt Allan
Version: 1.0
Author URI: http://mattallan.org
*/

/**
 * Add the meta box
 */
function wc_phone_order_render_meta( $post ) {

	add_thickbox();

	global $woocommerce;

	$status = get_post_status($post->ID);

	if ( $status == 'auto-draft' ) {
		echo 'Please save this order before adding a payment.';
		return;
	}

	$order = new WC_Order($post->ID);

	if ( empty( $order->get_total() ) ) {
		echo 'Please calculate totals and save before adding a payment.';
		return;
	}

	if ( !$order->has_status('pending') ) {
		echo "Order status must be 'pending payment' to take payment.";
		return;
	}

	$payment_url = $order->get_checkout_payment_url() . "&TB_iframe=true&width=900&height=600";

	echo "<a class='thickbox wc-phone-order-add-payment' href='{$payment_url}'>Add Payment</a>";

	return;
}

function wc_phone_order_add_meta_box() {
	add_meta_box( 'wc_phone_order', 'Phone Orders', 'wc_phone_order_render_meta', 'shop_order', 'side' );
}

add_action( 'add_meta_boxes', 'wc_phone_order_add_meta_box' );

/**
 * load styles and scripts
 */
function wc_phone_order_scripts() {
	wp_enqueue_script( 'wc-phone-order-script', plugin_dir_url( __FILE__ ) . 'assets/js/main.js' );
}

add_action( 'admin_enqueue_scripts', 'wc_phone_order_scripts' );
