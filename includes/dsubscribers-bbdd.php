<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

/**
* create dsubscribers table
*/
function dsubscribers_database_install() {
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'dsubscribers';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		email VARCHAR(200) DEFAULT '' NOT NULL,
		UNIQUE KEY id (id)
	);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	update_option( "dsubscribers_db_version", DSubscribers_BBDD_VERSION );

}

/**
* update table check 
*/
function dsubscribers_update_db_check() {

	$installed_ver = get_option( "dsubscribers_db_version", '1.0' );

    if ( $installed_ver != DSubscribers_BBDD_VERSION ) {

        dsubscribers_database_install();

    }

}

add_action( 'plugins_loaded', 'dsubscribers_update_db_check' );


