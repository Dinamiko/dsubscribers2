<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class DSubscribers_Settings {

	private static $_instance = null;
	public $parent = null;
	public $_token;
	public $base = '';
	public $settings = array();

	public function __construct ( $parent ) {

		$this->parent = $parent;

		$this->base = 'dsubscribers_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// sanitizes dsubscribers options
		add_action( 'init', array( $this, 'dsubscribers_sanitize_options' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page	
		add_filter( 'plugin_action_links_' . plugin_basename( DSubscribers_PLUGIN_FILE ) , array( $this, 'add_settings_link' ) );
		
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Adds DSubscribers admin menu
	 * @return void
	 */
	public function add_menu_item () {
		
		// main menu
		$page = add_menu_page( 'DSubscribers', 'DSubscribers', 'manage_options', 'dsubscribers' . '_settings',  array( $this, 'settings_page' ) );	

		// support
		add_submenu_page( 'dsubscribers' . '_settings', 'Support', 'Support', 'manage_options', 'dsubscribers-support', array( $this, 'dsubscribers_support_screen' ));

		// settings assets
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );

	}

	public function dsubscribers_support_screen() { ?>
		
		<div class="wrap">
			<h2>DSubscribers Support</h2>

			<div class="dsubscribers-item">			
				<h3>Documentation</h3>
				<p>Everything you need to know for getting DSubscribers up and running.</p>
				<p><a href="http://wp.dinamiko.com/demos/dsubscribers/documentation/" target="_blank">Go to Documentation</a></p>
			</div>

			<div class="dsubscribers-item">			
				<h3>Support</h3>
				<p>Having trouble? don't worry, create a ticket in the support forum.</p>
				<p><a href="https://wordpress.org/support/plugin/dk-pdf" target="_blank">Go to Support</a></p>
			</div>

		</div>

	<?php }

	/**
	 * Load settings JS & CSS
	 * @return void
	 */
	public function settings_assets () {
		wp_enqueue_style( 'farbtastic' );
    	wp_enqueue_script( 'farbtastic' );
    	wp_enqueue_media();   	
    	wp_register_script( 'dsubscribers' . '-settings-js', plugins_url( 'dk-pdf/assets/js/settings-admin.js' ), array( 'farbtastic', 'jquery' ), '1.0.0' );
    	wp_enqueue_script( 'dsubscribers' . '-settings-js' ); 	   	
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="admin.php?page=' . 'dsubscribers' . '_settings">' . __( 'Settings', 'dsubscribers' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {

		$settings['subscriber_email'] = array(

			'title'					=> __( 'Subscriber E-mail', 'dsubscribers' ),
			'description'			=> '',
			'fields'				=> array(

				array(
					'id' 			=> 'send_checkbox',
					'label'			=> __( 'Send E-mail to subscriber', 'dsubscribers' ),
					'description'	=> '',
					'type'			=> 'checkbox',
					'default'		=> ''
				),

				array(
					'id' 			=> 'message_block',
					'label'			=> __( 'Message' , 'dsubscribers' ),
					'description'	=> __( 'This box accepts HTML tags', 'dsubscribers' ),
					'type'			=> 'textarea',
					'default'		=> 'Thank you for subscribing!',
					'placeholder'	=> ''
				)

			)
		);

		$settings['messages'] = array(

			'title'					=> __( 'Form Messages', 'dsubscribers' ),
			'description'			=> '',
			'fields'				=> array(

				array(
					'id' 			=> 'subscribed_msg',
					'label'			=> __( 'Subscribed' , 'dsubscribers' ),
					'description'	=> '',
					'type'			=> 'text',
					'default'		=> 'Thank you for subscribing!',
					'placeholder'	=> __( 'Subscribed message', 'dsubscribers' )
				),

				array(
					'id' 			=> 'exists_msg',
					'label'			=> __( 'Exists' , 'dsubscribers' ),
					'description'	=> '',
					'type'			=> 'text',
					'default'		=> 'Sorry, this e-mail already exists',
					'placeholder'	=> __( 'Email already exists', 'dsubscribers' )
				),

				array(
					'id' 			=> 'unsubscribed_msg',
					'label'			=> __( 'Unsubscribed' , 'dsubscribers' ),
					'description'	=> '',
					'type'			=> 'text',
					'default'		=> 'Unsubscribed correctly',
					'placeholder'	=> __( 'Unsubscribed correctly', 'dsubscribers' )
				),

				array(
					'id' 			=> 'dont_exists_msg',
					'label'			=> __( 'Subscriber don\'t exists' , 'dsubscribers' ),
					'description'	=> '',
					'type'			=> 'text',
					'default'		=> 'Sorry, subscriber don\'t exists',
					'placeholder'	=> __( 'Subscriber don\'t exists', 'dsubscribers' )
				)

			)
		);

		$settings = apply_filters( 'dsubscribers' . '_settings_fields', $settings );

		return $settings;

	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) continue;

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), 'dsubscribers' . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( 'dsubscribers' . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), 'dsubscribers' . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				if ( ! $current_section ) break;
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . 'dsubscribers' . '_settings">' . "\n";
			$html .= '<h2>' . __( 'DSubscribers Settings' , 'dsubscribers' ) . '</h2>' . "\n";

			$tab = '';
			if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
				$tab .= $_GET['tab'];
			}

			// Show page tabs
			if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

				$html .= '<h2 class="nav-tab-wrapper">' . "\n";

				$c = 0;
				foreach ( $this->settings as $section => $data ) {

					// Set tab class
					$class = 'nav-tab';
					if ( ! isset( $_GET['tab'] ) ) {
						if ( 0 == $c ) {
							$class .= ' nav-tab-active';
						}
					} else {
						if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
							$class .= ' nav-tab-active';
						}
					}

					// Set tab link
					$tab_link = add_query_arg( array( 'tab' => $section ) );
					if ( isset( $_GET['settings-updated'] ) ) {
						$tab_link = remove_query_arg( 'settings-updated', $tab_link );
					}

					// Output tab
					$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

					++$c;
				}

				$html .= '</h2>' . "\n";
			}

			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Get settings fields
				ob_start();
				settings_fields( 'dsubscribers' . '_settings' );
				do_settings_sections( 'dsubscribers' . '_settings' );
				$html .= ob_get_clean();

				$html .= '<p class="submit">' . "\n";
					$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'dsubscribers' ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";

		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	* adds filter pre_update_option_{option}
	*/
	public function dsubscribers_sanitize_options() {

		add_filter( 'pre_update_option_dsubscribers_send_checkbox', array( $this, 'dsubscribers_update_field_dsubscribers_send_checkbox'), 10, 2 );
		add_filter( 'pre_update_option_dsubscribers_message_block', array( $this, 'dsubscribers_update_field_dsubscribers_message_block'), 10, 2 );
		add_filter( 'pre_update_option_dsubscribers_subscribed_msg', array( $this, 'dsubscribers_update_field_dsubscribers_subscribed_msg'), 10, 2 );
		add_filter( 'pre_update_option_dsubscribers_exists_msg', array( $this, 'dsubscribers_update_field_dsubscribers_exists_msg'), 10, 2 );
		add_filter( 'pre_update_option_dsubscribers_unsubscribed_msg', array( $this, 'dsubscribers_update_field_dsubscribers_unsubscribed_msg'), 10, 2 );
		add_filter( 'pre_update_option_dsubscribers_dont_exists_msg', array( $this, 'dsubscribers_update_field_dsubscribers_dont_exists_msg'), 10, 2 );
		
	}

	/**
	* sanitizes dsubscribers_send_checkbox option
	*/
	public function dsubscribers_update_field_dsubscribers_send_checkbox( $new_value, $old_value ) {
		$new_value = sanitize_text_field( $new_value );	
		return $new_value;
	}

	/**
	* sanitizes dsubscribers_message_block option
	*/
	public function dsubscribers_update_field_dsubscribers_message_block( $new_value, $old_value ) {
	
		$arr = array(
		    'a' => array(
		        'href' => array(),
		        'title' => array()
		    ),
		    'br' => array(),
		    'em' => array(),
		    'strong' => array(),
		    'p' => array(),
		    'h1' => array(),
		    'h2' => array(),
		    'h3' => array(),
		    'h4' => array(),
		);

		$new_value = wp_kses( $new_value, $arr );		

		return $new_value;
	}

	/**
	* sanitizes dsubscribers_subscribed_msg option
	*/
	public function dsubscribers_update_field_dsubscribers_subscribed_msg( $new_value, $old_value ) {
		$new_value = sanitize_text_field( $new_value );	
		return $new_value;
	}

	/**
	* sanitizes dsubscribers_exists_msg option
	*/
	public function dsubscribers_update_field_dsubscribers_exists_msg( $new_value, $old_value ) {
		$new_value = sanitize_text_field( $new_value );	
		return $new_value;
	}

	/**
	* sanitizes dsubscribers_unsubscribed_msg option
	*/
	public function dsubscribers_update_field_dsubscribers_unsubscribed_msg( $new_value, $old_value ) {
		$new_value = sanitize_text_field( $new_value );	
		return $new_value;
	}

	/**
	* sanitizes dsubscribers_dont_exists_msg option
	*/
	public function dsubscribers_update_field_dsubscribers_dont_exists_msg( $new_value, $old_value ) {
		$new_value = sanitize_text_field( $new_value );	
		return $new_value;
	}

	/**
	 * Main DSubscribers_Settings Instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}