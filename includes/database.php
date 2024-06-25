<?php
/**
 * This file contains config functions used in the NJW Plugin Skeleton application.
 *
 * @package NjwMedia\Database
 * @since 1.0.0
 */

/**
 * Function named njw_create_link_log_table.
 *
 * @return void.
 */
function njw_create_link_log_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . njw_media_get_config( 'LINK_TABLE_LOG' );

	$charset_collate = $wpdb->get_charset_collate();

	$table_exists = wp_cache_get( $table_name, 'table_exists' );

	if ( false === $table_exists ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$table_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) === $table_name;
		wp_cache_set( $table_name, $table_exists, 'table_exists' );
	}

	if ( ! $table_exists ) {

		$sql = "CREATE TABLE $table_name (
            `id` int(11) NOT NULL auto_increment,
            `old_link` varchar(500),
            `new_link` varchar(500),
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP ,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}

/**
 * Function named njw_create_media_log_table.
 *
 * @return void.
 */
function njw_create_media_log_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . njw_media_get_config( 'ALT_TABLE_LOG' );

	$charset_collate = $wpdb->get_charset_collate();

	$table_exists = wp_cache_get( $table_name, 'table_exists' );

	if ( false === $table_exists ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$table_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) === $table_name;
		wp_cache_set( $table_name, $table_exists, 'table_exists' );
	}

	if ( ! $table_exists ) {

		$sql = "CREATE TABLE $table_name (
			`id` int(11) NOT NULL auto_increment,
			`media_id` int(11),
			`media_url` varchar(500),
			`new_alt_text` varchar(500),
			`old_alt` varchar(500),
			`created_at` DATETIME DEFAULT CURRENT_TIMESTAMP ,
			`updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}

/**
 * Retrieves the table name based on the provided log type.
 *
 * @param string $log The type of log for which to retrieve the table name.
 *                    Expected values are 'LINK' or 'MEDIA'.
 *
 * @return string|null The table name associated with the provided log type, or null if the log type is not recognized.
 *                     If the log type is 'LINK', it returns the value of 'LINK_TABLE_LOG' from the configuration.
 *                     If the log type is 'MEDIA', it returns the value of 'ALT_TABLE_LOG' from the configuration.
 */
function njw_get_table_name( $log ) {
	if ( $log == 'LINK' ) {
		$table_name = njw_media_get_config( 'LINK_TABLE_LOG' );
	} elseif ( $log == 'MEDIA' ) {
		$table_name = njw_media_get_config( 'ALT_TABLE_LOG' );
	} else {
		return null;
	}

	return $table_name;
}

/**
 * Function njw_query_db_log_table.
 *
 * @param  mixed $condition -> for select query.
 * @param  mixed $column_name_alias --> column alias if any default '*'.
 * @param  mixed $log --> It is a table name.
 * @return array | null return results.
 */
function njw_query_db_log_table( $condition, $column_name_alias = '*', $log = 'LINK' ) {
	global $wpdb;
	$table_name = njw_get_table_name( $log );
	$table      = $wpdb->prefix . $table_name;

	// Use $wpdb->prepare() to safely include the condition in the SQL query.
	// Note: This assumes that $condition is a string that does not contain any user-supplied data.
	$sql = $wpdb->prepare( 'SELECT %s FROM %s WHERE %s', $column_name_alias, $table, $condition );

	// phpcs:ignore
	$results = $wpdb->get_results( $sql );

	if ( ! empty( $results ) && is_array( $results ) ) {
		return count( $results ) > 0 ? $results : null;
	} else {
		return null;
	}
}


/**
 * Retrieves the column name based on table type.
 *
 * It sends the custom table column name .. helps on saving and retrieving column.
 *
 * @param string $log type to get the column name.
 * @return array get the column names.
 */
function njw_log_table_column_name( $log = 'LINK' ) {
	if ( $log == 'LINK' ) {
		return [
			'id',
			'old_link',
			'new_link',
		];
	} elseif ( $log == 'MEDIA' ) {
		return [
			'id',
			'media_id',
			'media_url',
			'generated_alt_text',
			'old_alt',
		];
	} else {
		return [];
	}
}

/**
 * Saves the log data to the database.
 *
 * @param  array  $data to insert on the table.
 * @param  string $by_column_name column name to check the data.
 * @param  string $log table name to save the data.
 * @return array success response
 */
function njw_media_save_log( $data, $by_column_name = 'unique_identifier', $log = 'LINK' ) {

	global $wpdb;
	$table_name = njw_get_table_name( $log );
	$table      = $wpdb->prefix . $table_name;

	$column_to_check = array_key_exists( $by_column_name, $data ) ? $data[ $by_column_name ] : null;
	$condition       = " $by_column_name =  '$column_to_check' ";

	$store_data_index = njw_log_table_column_name( $log );

	$store_data = [];

	// Storing only data that's available or give at the moment.
	foreach ( $store_data_index as $index ) {
		if ( array_key_exists( $index, $data ) ) {
			$store_data[ $index ] = $data[ $index ];
		}
	}

	$result  = njw_query_db_log_table( $condition );
	$success = false;

	if ( $result ) {
		// phpcs:ignore
		$success = $wpdb->update( $table, $store_data, [ $by_column_name => $column_to_check ] );

	} else {
		// phpcs:ignore
		$success = $wpdb->insert( $table, $store_data );
	}

	return [
		$by_column_name => $column_to_check,
		'success'       => $success,
		'result'        => $result,
		'store_data'    => $store_data,
	];
}
