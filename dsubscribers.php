<?php
/*
 * Plugin Name: DSubscribers
 * Version: 2.0
 * Plugin URI: http://wp.dinamiko.com/demos/dsubscribers 
 * Description: Manage subscribers from your site with ease
 * Author: Emili Castells
 * Author URI: http://www.dinamiko.com
 * Requires at least: 3.9
 * Tested up to: 4.4
 *
 * Text Domain: dsubscribers
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'DSubscribers' ) ) {

	final class DSubscribers {

		private static $instance;

		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof DSubscribers ) ) {

				self::$instance = new DSubscribers;
				self::$instance->setup_constants();

				add_action( 'plugins_loaded', array( self::$instance, 'dsubscribers_load_textdomain' ) );				
				
				self::$instance->includes();	

			}

			return self::$instance;

		}

		public function test() {

			return 'hello';

		}

		public function dsubscribers_load_textdomain() {

			load_plugin_textdomain( 'dsubscribers', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 

		}

		private function setup_constants() {

			if ( ! defined( 'DSubscribers_VERSION' ) ) { define( 'DSubscribers_VERSION', '2.0' ); }
			if ( ! defined( 'DSubscribers_BBDD_VERSION' ) ) { define( 'DSubscribers_BBDD_VERSION', '1.0' ); }
			if ( ! defined( 'DSubscribers_PLUGIN_DIR' ) ) { define( 'DSubscribers_PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); }
			if ( ! defined( 'DSubscribers_PLUGIN_URL' ) ) { define( 'DSubscribersPLUGIN_URL', plugin_dir_url( __FILE__ ) ); }
			if ( ! defined( 'DSubscribers_PLUGIN_FILE' ) ) { define( 'DSubscribers_PLUGIN_FILE', __FILE__ ); }			

		}

		private function includes() {

			// enqueue css / js
			require_once DSubscribers_PLUGIN_DIR . 'includes/dsubscribers-load-js-css.php';	

			/*
			// upgrade dashboard screen
			// TODO not worked when upgrading in plugins list page
			//require_once DSubscribers_PLUGIN_DIR . 'includes/dsubscribers-upgrades.php';

			// settings / metaboxes
			if ( is_admin() ) {

				require_once DSubscribers_PLUGIN_DIR . 'includes/class-dsubscribers-settings.php';
				$settings = new DSubscribers_Settings( $this );

				require_once DSubscribers_PLUGIN_DIR . 'includes/class-dsubscribers-admin-api.php';
				$this->admin = new DSubscribers_Admin_API();

				require_once DSubscribers_PLUGIN_DIR . 'includes/dsubscribers-metaboxes.php';

			}					

			// functions
			require_once DSubscribers_PLUGIN_DIR . 'includes/dsubscribers-functions.php';

			// shortcodes
			require_once DSubscribers_PLUGIN_DIR . 'includes/class-dsubscribers-template-loader.php';
			require_once DSubscribers_PLUGIN_DIR . 'includes/dsubscribers-shortcodes.php';
			*/

		}

		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'dsubscribers' ), DSubscribers_VERSION );
		}

		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'dsubscribers' ), DSubscribers_VERSION );
		}

	}

}

function DSubscribers() {

	return DSubscribers::instance();

}

DSubscribers();