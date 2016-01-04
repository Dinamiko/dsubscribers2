<?php

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', 'dsubscribers_enqueue_styles', 15 );
add_action( 'wp_enqueue_scripts', 'dsubscribers_enqueue_scripts', 10 );
add_action( 'admin_enqueue_scripts', 'dsubscribers_admin_enqueue_styles', 10, 1 );
add_action( 'admin_enqueue_scripts', 'dsubscribers_admin_enqueue_scripts', 10, 1 );

function dsubscribers_enqueue_styles () {

	wp_register_style( 'dsubscribers-frontend', plugins_url( 'dsubscribers/assets/css/frontend.css' ), array(), DSubscribers_VERSION );
	wp_enqueue_style( 'dsubscribers-frontend' );

}

function dsubscribers_enqueue_scripts () {

	wp_register_script( 'dsubscribers-frontend', plugins_url( 'dsubscribers/assets/js/frontend.js' ), array( 'jquery' ), DSubscribers_VERSION, true );
	wp_enqueue_script( 'dsubscribers-frontend' );

}

function dsubscribers_admin_enqueue_styles ( $hook = '' ) {

	wp_register_style( 'dsubscribers-admin', plugins_url( 'dsubscribers/assets/css/admin.css' ), array(), DSubscribers_VERSION );
	wp_enqueue_style( 'dsubscribers-admin' );

}

function dsubscribers_admin_enqueue_scripts ( $hook = '' ) {

	wp_register_script( 'dsubscribers-admin', plugins_url( 'dsubscribers/assets/js/admin.js' ), array( 'jquery' ), DSubscribers_VERSION );
	wp_enqueue_script( 'dsubscribers-admin' );

	wp_register_script( 'dsubscribers-settings-admin', plugins_url( 'dsubscribers/assets/js/settings-admin.js' ), array( 'jquery' ), DSubscribers_VERSION );
	wp_enqueue_script( 'dsubscribers-settings-admin' );
				
}