<?php

defined( 'ABSPATH' ) || exit;

/**
 * Check if the plugin is enabled.
 */
function cwfw_is_enabled() {

	return 'yes' === get_option( 'cwfw_enabled', 'yes' );

}

/**
 * Get withdrawal period.
 */
function cwfw_get_period() {

	return (int) get_option( 'cwfw_period', 14 );

}

/**
 * Get administrator email.
 */
function cwfw_get_admin_email() {

	return get_option( 'cwfw_admin_email', get_option( 'admin_email' ) );

}

/**
 * Check if guest requests are enabled.
 */
function cwfw_guest_requests_enabled() {

	return 'yes' === get_option( 'cwfw_guest_requests', 'yes' );

}