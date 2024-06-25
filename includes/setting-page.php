<?php
/**
 * This file contains setting page used in the NJW Media application.
 *
 * @package Aremedia\Trackonomics\SettingPage
 * @since 1.0.0
 */

add_action( 'admin_menu', 'njw_media__add_admin_menu' );
add_action( 'admin_init', 'njw_media__settings_init' );


/**
 * Adds the admin menu for the NJW Media settings page.
 *
 * This function is responsible for adding the NJW Media settings page to the WordPress admin menu.
 * It is called during the `admin_menu` action hook.
 *
 * @since 1.0.0
 */
function njw_media__add_admin_menu() {
	add_options_page( 'NJW Media', 'NJW Media', 'manage_options', 'njw-media-option', 'njw_media__options_page' );
}


/**
 * Initializes the settings for the NJW Media plugin.
 *
 * This function is responsible for initializing the settings for the NJW Media plugin.
 * It is called during the plugin's initialization process.
 *
 * @since 1.0.0
 */
function njw_media__settings_init() {

	register_setting( 'njw_media_Plugin_page', njw_media_get_config( 'SETTINGS_NAME' ) );
	register_setting( 'njw_media_Plugin_page', 'njw_media__api-settings' );


	add_settings_section(
		'njw_media_Plugin_page_section',
		__( 'NJW Media - Settings ', 'njw-media' ),
		'njw_media__settings_section_callback',
		'njw_media_Plugin_page',
	);
}

/**
 * Callback function for the settings section in the njw_media plugin.
 *
 * This function is responsible for rendering the settings section on the settings page.
 * It is called when the settings page is being displayed.
 *
 * @since 1.0.0
 */
function njw_media__settings_section_callback() {
	njw_media_options_settings_field();
}

/**
 * Renders the options settings field for the NJW Media plugin.
 *
 * This function is responsible for rendering the options settings field in the plugin's settings page.
 * It can be used to display and manage various options related to the plugin's functionality.
 *
 * @since 1.0.0
 */
function njw_media_options_settings_field() {

	$config_value = njw_media_api_config();

	$api_config_constant_name = [
		'API_PROXY_URL' => 'API Proxy URl',
		'SECRET_KEY'    => 'Secret Key ( GPT4Vision or Replicate API key )',
		'ACCESS_KEY'    => 'Access Key to access secure API',
	];

	?>
	<div class="njw-media-config">
		<?php foreach ( $api_config_constant_name as $constant => $constant_label ) : ?>
			<?php
				$setting_name = njw_media_get_config( 'SETTINGS_NAME' );
				$current_name = $setting_name . '[' . $constant . ']';
				$op_value     = $config_value[ $constant ];
			if ( $constant === 'ACCESS_KEY' ) {
				$op_value = njw_get_single_option( 'ACCESS_KEY', njw_generate_random_string() );
			}
			?>
			<div class="njw-media-field <?php echo esc_attr( $constant ); ?>">
				<label><?php echo esc_html( $constant_label ); ?> </label> &nbsp; &nbsp;
				<input type="text" name="<?php echo esc_attr( $current_name ); ?>" value="<?php echo esc_attr( $op_value ); ?>"/>
			</div>
			<br/>
			<br/>
		<?php endforeach; ?>
	</div>
	<?php
}


/**
 * Renders the options page for the NJW Media plugin.
 *
 * This function is responsible for rendering the settings page for the plugin.
 * It is called when the user navigates to the options page in the WordPress admin area.
 *
 * @since 1.0.0
 */
function njw_media__options_page() {
	?>
		<div class="njw-media-setting-page">
			<form action='options.php' method='post'>

				<?php
				settings_fields( 'njw_media_Plugin_page' );
				do_settings_sections( 'njw_media_Plugin_page' );
				submit_button();
				?>

			</form>
		</div>
		<?php
}


