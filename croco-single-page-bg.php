<?php
/**
 * @package   croco_single_page_bg
 * @author    CrocoBlock
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:       Croco Single Page BG
 * Plugin URI:        http://www.crocoblock.com/
 * Description:       Setup Bakground for single page
 * Version:           1.0.0
 * Author:            Crocoblock
 * Author URI:        http://www.crocoblock.com/
 * Text Domain:       croco-single-page-bg
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * 
 * Setup Bakground for single page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// If class 'croco_single_page_bg' not exists.
if ( !class_exists('croco_single_page_bg') ) {

	/**
	 * Sets up and initializes the croco_single_page_bgplugin.
	 *
	 * @since 1.0.0
	 */
	class croco_single_page_bg {

		/**
		 * plugin slug (for text domains and options pages)
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $slug = 'croco-single-page-bg';

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );
			add_action( 'plugins_loaded', array( $this, 'includes' ), 3 );
			add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
			add_action( 'wp_head', array( $this, 'set_bg' ), 99 );
		}

		/**
		 * Include assets
		 *
		 * @since 1.0.0
		 */
		function assets() {
			wp_enqueue_style( $this->slug . '-style', CROCO_SPB_URI . 'css/style.css', '', CROCO_SPB_VERSION );
		}

		/**
		 * Defines constants for the plugin.
		 *
		 * @since 1.0.0
		 */
		function constants() {
			
			/**
			 * Set the version number of the plugin.
			 *
			 * @since 1.0.0
			 */
			define( 'CROCO_SPB_VERSION', '1.0.0' );

			/**
			 * Set constant path to the plugin directory.
			 *
			 * @since 1.0.0
			 */
			define( 'CROCO_SPB_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

			/**
			 * Set constant path to the plugin URI.
			 *
			 * @since 1.0.0
			 */
			define( 'CROCO_SPB_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		}

		/**
		 * Loads files from the '/inc' folder.
		 *
		 * @since 1.0.0
		 */
		function includes() {
			require_once( 'includes/class-admin-interface.php' );
			if ( isset($_GET['page']) && 'croco-single-page-bg' == $_GET['page'] ) {
				require_once( 'includes/class-croco-spb-media-uploader.php' );
				$c_uploader = new Croco_SPB_Media_Uploader;
				$c_uploader->init();
			}
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 */
		function lang() {
			load_plugin_textdomain( $this->slug, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		function set_bg() {
			$data = get_option( 'croco_spb' );

			if ( !$data || !is_array($data) || !isset($data['page']) || !$data['page'] || !isset($data['background']) ) {
				return;
			}

			if ( !is_page( $data['page'] ) ) {
				return;
			}

			$color = '';
			$image = '';

			if ( isset( $data['background']['color'] ) ) {
				$color = $data['background']['color'];
			}

			if ( isset( $data['background']['image'] ) ) {
				$image = $data['background']['image'];
			}

			if ( !$color && !$image ) {
				return;
			}

			?>
			<style type="text/css">

				body {
					background-color: <?php echo $color; ?>;
					<?php if ( $image ) { ?>
					background-image: url('<?php echo esc_url( $image ); ?>');
					background-repeat: <?php echo $data['background']['repeat']; ?>;
					background-position: <?php echo $data['background']['position']; ?>;
					background-attachment: <?php echo $data['background']['attachment']; ?>;
					<?php } ?>
				}
			</style>
			<?php

		}

		/**
		 * On plugin activation.
		 *
		 * @since 1.0.0
		 */
		function activation() {
			flush_rewrite_rules();
		}
		
		/**
		 * On plugin deactivation.
		 *
		 * @since 1.0.0
		 */
		function deactivation() {
			flush_rewrite_rules();
		}

	}

	// create class instance
	new croco_single_page_bg();
}