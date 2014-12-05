<?php do_action( 'croco_spb_save_options' ); ?>
<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form action="<?php echo add_query_arg( array( 'action' => 'save' ), menu_page_url( 'croco-single-page-bg', false ) ); ?>" method="post">
		<h4>Select page:</h4>
		<?php
			global $croco_spb_interface; 
			echo $croco_spb_interface->select_setting_interface();
		?>
		<h4>Set background:</h4>
		<?php
			echo $croco_spb_interface->bg_setting_interface();
		?>
		<input type="submit" class="button button-primary" value="<?php _e( 'Save', 'croco-single-page-bg' ); ?>">
	</form>

</div>
