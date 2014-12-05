<?php
/**
 * Add admin interface
 *
 * @package   cherry_wizard
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( !class_exists( 'croco_spb_interface' ) ) {

	/**
	 * Add admin interface
	 *
	 * @since 1.0.0
	 */
	class croco_spb_interface {
		
		function __construct() {
			// Add the withard page and menu item.
			add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
			add_action( 'croco_spb_save_options', array( $this, 'save_settings' ) );
		}
		
		/**
		 * Register the administration menu for this plugin into the WordPress Dashboard menu.
		 *
		 * @since 1.0.0
		 */
		public function add_menu_item() {
			global $cherry_wizard;
			add_options_page( 
				__( 'Single page BG', 'croco-single-page-bg' ),
				__( 'Single page BG', 'croco-single-page-bg' ),
				'manage_options',
				'croco-single-page-bg',
				array( $this, 'display_plugin_admin_page' )
			);
		}

		public function save_settings() {
			if ( !isset($_GET['action']) || 'save' != $_GET['action'] ) {
				return;
			}

			if ( !isset($_POST['croco_spb']) ) {
				return;
			}

			update_option( 'croco_spb', $_POST['croco_spb'] );
		}

		/**
		 * show wizard management page
		 * 
		 * @since 1.0.0
		 */
		public function display_plugin_admin_page() {
			include_once( 'views/settings-page.php' );
		}


		public function select_setting_interface() {

			$option_name = 'croco_spb';
			$value = get_option( 'croco_spb' );

			$page_id = isset($value['page']) ? $value['page'] : '';

			$background = isset($value['background']) ? $value['background'] : array();

			// Pull all the pages into an array
			$options_pages = array();
			$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
			$options_pages[''] = 'Select a page:';
			foreach ($options_pages_obj as $page) {
				$options_pages[$page->ID] = $page->post_title;
			}

			$output = '<div class="settings-wrap section">';
			$output .= '<select class="of-input" name="' . esc_attr( $option_name . '[page]' ) . '" id="page">';

			foreach ($options_pages as $key => $option ) {
				$output .= '<option'. selected( $page_id, $key, false ) .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
			}
			$output .= '</select>';	
			$output .= '</div>';
			return $output;
		}


		/**
		 * add setting interface
		 */
		public function bg_setting_interface() {

			$option_name = 'croco_spb';
			$value = get_option( 'croco_spb' );

			$background = isset($value['background']) ? $value['background'] : array();

			$background = wp_parse_args( $background, array(
				'color'      => '',
				'image'      => '',
				'repeat'     => '',
				'position'   => '',
				'attachment' => ''
			) );

			$default_color = '';

			$output = '<div class="settings-wrap section">';

			$output .= '<input name="' . esc_attr( $option_name . '[background][color]' ) . '" id="' . esc_attr( 'background_color' ) . '" placeholder="#ffffff" class="of-color of-background-color"  type="text" value="' . esc_attr( $background['color'] ) . '"' . $default_color .' />';

			// Background Image
			if ( !isset($background['image']) ) {
				$background['image'] = '';
			}

			$output .= Croco_SPB_Media_Uploader::uploader( 'background', $background['image'], null, esc_attr( $option_name . '[background][image]' ) );

			$class = 'background-properties';
			if ( '' == $background['image'] ) {
				$class .= ' hide';
			}
			$output .= '<div class="' . esc_attr( $class ) . '">';

			// Background Repeat
			$output .= '<select class="of-background of-background-repeat" name="' . esc_attr( $option_name . '[background][repeat]'  ) . '" id="' . esc_attr( 'background_repeat' ) . '">';
			$repeats = of_recognized_background_repeat();

			foreach ($repeats as $key => $repeat) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['repeat'], $key, false ) . '>'. esc_html( $repeat ) . '</option>';
			}
			$output .= '</select>';

			// Background Position
			$output .= '<select class="of-background of-background-position" name="' . esc_attr( $option_name . '[background][position]' ) . '" id="' . esc_attr( 'background_position' ) . '">';
			$positions = of_recognized_background_position();

			foreach ($positions as $key=>$position) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['position'], $key, false ) . '>'. esc_html( $position ) . '</option>';
			}
			$output .= '</select>';

			// Background Attachment
			$output .= '<select class="of-background of-background-attachment" name="' . esc_attr( $option_name . '[background][attachment]' ) . '" id="' . esc_attr( 'background_attachment' ) . '">';
			$attachments = of_recognized_background_attachment();

			foreach ($attachments as $key => $attachment) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['attachment'], $key, false ) . '>' . esc_html( $attachment ) . '</option>';
			}
			$output .= '</select>';
			$output .= '</div>';
			$output .= '</div>';

			return $output;
		}

	}

	$GLOBALS['croco_spb_interface'] = new croco_spb_interface();

}