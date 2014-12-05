<?php

class Croco_SPB_Media_Uploader {

	/**
	 * Initialize the media uploader class
	 *
	 * @since 1.7.0
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'media_scripts' ) );
	}

	/**
	 * Media Uploader Using the WordPress Media Library.
	 *
	 * Parameters:
	 *
	 * string $_id - A token to identify this field (the name).
	 * string $_value - The value of the field, if present.
	 * string $_desc - An optional description of the field.
	 *
	 */

	static function uploader( $_id, $_value, $_desc = '', $_name = '' ) {

		// Gets the unique option id
		$option_name = 'croco_spb';

		$output = '';
		$id = '';
		$class = '';
		$int = '';
		$value = '';
		$name = '';

		$id = strip_tags( strtolower( $_id ) );

		// If a value is passed and we don't have a stored value, use the value that's passed through.
		if ( $_value != '' && $value == '' ) {
			$value = $_value;
		}

		if ($_name != '') {
			$name = $_name;
		}
		else {
			$name = $option_name.'['.$id.']';
		}

		if ( $value ) {
			$class = ' has-file';
		}
		$output .= '<input id="' . $id . '" class="upload' . $class . '" type="text" name="'.$name.'" value="' . $value . '" placeholder="' . __('No file chosen', 'textdomain') .'" />' . "\n";
		if ( function_exists( 'wp_enqueue_media' ) ) {
			if ( ( $value == '' ) ) {
				$output .= '<input id="upload-' . $id . '" class="upload-button button" type="button" value="' . __( 'Upload', 'textdomain' ) . '" />' . "\n";
			} else {
				$output .= '<input id="remove-' . $id . '" class="remove-file button" type="button" value="' . __( 'Remove', 'textdomain' ) . '" />' . "\n";
			}
		} else {
			$output .= '<p><i>' . __( 'Upgrade your version of WordPress for full media support.', 'textdomain' ) . '</i></p>';
		}

		if ( $_desc != '' ) {
			$output .= '<span class="of-metabox-desc">' . $_desc . '</span>' . "\n";
		}

		$output .= '<div class="screenshot" id="' . $id . '-image">' . "\n";

		if ( $value != '' ) {
			$remove = '<a class="remove-image">Remove</a>';
			$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
			if ( $image ) {
				$output .= '<img src="' . $value . '" alt="" />' . $remove;
			} else {
				$parts = explode( "/", $value );
				for( $i = 0; $i < sizeof( $parts ); ++$i ) {
					$title = $parts[$i];
				}

				// No output preview if it's not an image.
				$output .= '';

				// Standard generic output if it's not an image.
				$title = __( 'View File', 'textdomain' );
				$output .= '<div class="no-image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">'.$title.'</a></span></div>';
			}
		}
		$output .= '</div>' . "\n";
		return $output;
	}

	/**
	 * Enqueue scripts for file uploader
	 */
	function media_scripts() {

		if ( function_exists( 'wp_enqueue_media' ) )
			wp_enqueue_media();

		wp_register_script( 'croco-spb-media-uploader', CROCO_SPB_URI .'js/media-uploader.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'croco-spb-media-uploader' );
		wp_localize_script( 'croco-spb-media-uploader', 'croco_spb_l10n', array(
			'upload' => __( 'Upload', 'textdomain' ),
			'remove' => __( 'Remove', 'textdomain' )
		) );
	}
}