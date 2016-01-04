<?php

class DSubscribersTest extends WP_UnitTestCase {

	protected $object;

	public function setUp() {
		parent::setUp();
		$this->object = DSubscribers();
	}
	public function tearDown() {
		parent::tearDown();
	}
	public function test_dsubscribers_instance() {
		$this->assertClassHasStaticAttribute( 'instance', 'DSubscribers' );
	}

	public function test_constants() {
		
		$this->assertSame( DSubscribers_VERSION, '2.0');
		$this->assertSame( DSubscribers_BBDD_VERSION, '1.0');

		// DSubscribers_PLUGIN_DIR
		$path = str_replace( 'tests/', '', plugin_dir_path( __FILE__ ) );
		$this->assertSame( DSubscribers_PLUGIN_DIR, $path );

		// TODO DSubscribers_PLUGIN_URL
		// TODO DSubscribers_PLUGIN_FILE

	}

	public function test_includes() {

		// includes
		$this->assertFileExists( DSubscribers_PLUGIN_DIR . 'includes/dsubscribers-load-js-css.php' );
		$this->assertFileExists( DSubscribers_PLUGIN_DIR . 'includes/dsubscribers-functions.php' );
		$this->assertFileExists( DSubscribers_PLUGIN_DIR . 'includes/dsubscribers-bbdd.php' );
		$this->assertFileExists( DSubscribers_PLUGIN_DIR . 'includes/dsubscribers-install.php' );

		// assets
		$this->assertFileExists( DSubscribers_PLUGIN_DIR . 'assets/css/admin.css' );
		$this->assertFileExists( DSubscribers_PLUGIN_DIR . 'assets/css/frontend.css' ); 
		$this->assertFileExists( DSubscribers_PLUGIN_DIR . 'assets/js/admin.js' );
		$this->assertFileExists( DSubscribers_PLUGIN_DIR . 'assets/js/settings-admin.js' );
		$this->assertFileExists( DSubscribers_PLUGIN_DIR . 'assets/js/frontend.js' );
		$this->assertFileExists( DSubscribers_PLUGIN_DIR . 'assets/js/jquery.validate.min.js' );

	}

}

