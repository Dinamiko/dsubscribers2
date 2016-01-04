<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
* plugin activation
*/
function dsubscribers_activation() {

	// create database
	dsubscribers_database_install();
	
}

register_activation_hook( DSubscribers_PLUGIN_FILE, 'dsubscribers_activation' );