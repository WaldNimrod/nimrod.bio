<?php
defined( 'ABSPATH' ) || exit;

add_action( 'admin_post_nopriv_nb_contact_submit', 'nb_handle_contact_submit' );
add_action( 'admin_post_nb_contact_submit', 'nb_handle_contact_submit' );

function nb_handle_contact_submit() {
	if ( ! isset( $_POST['nb_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nb_contact_nonce'] ) ), 'nb_contact_submit' ) ) {
		wp_safe_redirect( home_url( '/contact/?status=error' ) );
		exit;
	}

	if ( ! empty( $_POST['website'] ?? '' ) ) {
		wp_safe_redirect( home_url( '/contact/?status=ok' ) );
		exit;
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$topics  = array_intersect( array_keys( nb_get_worlds() ), array_map( 'sanitize_key', (array) ( $_POST['topics'] ?? array() ) ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( ! $name || ! $email || strlen( $message ) < 20 ) {
		wp_safe_redirect( home_url( '/contact/?status=invalid' ) );
		exit;
	}

	$to      = get_option( 'admin_email' );
	$subject = sprintf( '[nimrod.bio · contact] %s', $name );
	$body    = "From:    $name <$email>\n";
	$body   .= "Phone:   $phone\n";
	$body   .= 'Topics:  ' . implode( ', ', $topics ) . "\n";
	$body   .= "\n" . $message . "\n";

	$sent = wp_mail( $to, $subject, $body, array( 'Reply-To: ' . $email ) );

	wp_safe_redirect( home_url( '/contact/?status=' . ( $sent ? 'ok' : 'error' ) ) );
	exit;
}
