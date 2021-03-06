<?php
/**
 * Export Users
 *
 * @author   WPEverest
 * @category Admin
 * @package  UserRegistration/Admin
 * @since    1.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * UR_Admin_Export_Users Class.
 */
class UR_Admin_Export_Users {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'export_csv' ) );
	}

	/**
	 * Outputs Export Users Page
	 *
	 * @return void
	 */
	public static function output() {
		$all_forms = ur_get_all_user_registration_form();
		include_once dirname( __FILE__ ) . '/views/html-admin-page-export-users.php';
	}

	/**
	 * Exports users data along with extra information in CSV format.
	 *
	 * @return void
	 */
	public function export_csv( $form_id ) {

		// Check for non empty $_POST.
		if ( ! isset( $_POST['user_registration_export_users'] ) ) {
			return;
		}

		// Nonce check.
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'user-registration-settings' ) ) {
			die( __( 'Action failed. Please refresh the page and retry.', 'user-registration' ) );
		}

		$form_id = isset( $_POST['export_users'] ) ? $_POST['export_users'] : 0;

		// Return if form id is not set and current user doesnot have export capability.
		if ( ! isset( $form_id ) || ! current_user_can( 'export' ) ) {
			return;
		}

		$users = get_users(
			array(
				'ur_form_id' => $form_id,
			)
		);

		if ( count( $users ) === 0 ) {
			echo '<div id="message" class="updated inline notice notice-error"><p><strong>' . __( 'No users found with this form id.', 'user-registration' ) . '</strong></p></div>';
			return;
		}

		$columns = $this->generate_columns( $form_id );
		$rows    = $this->generate_rows( $users, $form_id );

		$form_name = strtolower( str_replace( ' ', '-', get_the_title( $form_id ) ) );
		$file_name = $form_name . '-' . current_time( 'Y-m-d_H:i:s' ) . '.csv';

		if ( ob_get_contents() ) {
			ob_clean();
		}

		 // Force download
		header( 'Content-Type: application/force-download' );
		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Type: application/download' );

		// Disposition / Encoding on response body
		header( "Content-Disposition: attachment;filename={$file_name}" );
		header( 'Content-Transfer-Encoding: binary' );

		$handle = fopen( 'php://output', 'w' );

		// Handle UTF-8 chars conversion for CSV
		fprintf( $handle, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

		// Put the column headers
		fputcsv( $handle, array_values( $columns ) );

		// Put the row values
		foreach ( $rows as $row ) {
			fputcsv( $handle, $row );
		}

		fclose( $handle );

		exit;
	}

	/**
	 * Generate Column for CSV export.
	 *
	 * @param  int $form_id  Form ID.
	 * @return array    $columns  CSV Export Columns.
	 */
	public function generate_columns( $form_id ) {

		// Default Columns.
		$default_columns = apply_filters(
			'user_registration_csv_export_default_columns',
			array(
				'user_role'        => __( 'User Role', 'user-registration' ),
				'date_created'     => __( 'User Registered', 'user-registration' ),
				'date_created_gmt' => __( 'User Registered GMT', 'user-registration' ),
			)
		);

		// User ID Column.
		$user_id_column = array(
			'user_id' => __( 'User ID', 'user-registration' ),
		);

		$columns = ur_get_meta_key_label( $form_id );

		$exclude_columns = apply_filters(
			'user_registration_csv_export_exclude_columns',
			array(
				'user_pass',
				'user_confirm_password',
			)
		);

		foreach ( $exclude_columns as $exclude_column ) {
			unset( $columns[ $exclude_column ] );
		}

		$columns = array_merge( $user_id_column, $columns );
		$columns = array_merge( $columns, $default_columns );

		return apply_filters( 'user_registration_csv_export_columns', $columns );
	}

	/**
	 * Generate rows for CSV export
	 *
	 * @param  obj $users   Users Data
	 * @return array    $rows    CSV export rows.
	 */
	public function generate_rows( $users, $form_id ) {

		$rows = array();

		foreach ( $users as $user ) {

			if ( ! isset( $user->data->ID ) ) {
				continue;
			}

			$user_form_id = get_user_meta( $user->data->ID, 'ur_form_id', true );

			// If the user is not submitted by selected registration form.
			if ( $user_form_id !== $form_id ) {
				continue;
			}

			$user_id_row    = array( 'user_id' => $user->data->ID );
			$user_extra_row = ur_get_user_extra_fields( $user->data->ID );

			foreach ( $user_extra_row as $user_extra_data ) {
				$columns = $this->generate_columns( $form_id );

				if ( ! isset( $columns[ $user_extra_data ] ) ) {

					// Remove the rows value that are not in columns.
					unset( $user_extra_row[ $user_extra_data ] );
				}
			}

			$user_table_data     = ur_get_user_table_fields();
			$user_table_data_row = array();

			// Get user table data that are on column.
			foreach ( $user_table_data as $data ) {
				$columns = $this->generate_columns( $form_id );

				if ( isset( $columns[ $data ] ) ) {
					$user_table_data_row = array_merge( $user_table_data_row, array( $data => $user->$data ) );
				}
			}

			$user_meta_data     = ur_get_registered_user_meta_fields();
			$user_meta_data_row = array();

			// Get user meta table data that are on column.
			foreach ( $user_meta_data as $meta_data ) {
				$columns = $this->generate_columns( $form_id );

				if ( isset( $columns[ $meta_data ] ) ) {
					$user_meta_data_row = array_merge( $user_meta_data_row, array( $meta_data => get_user_meta( $user->data->ID, $meta_data, true ) ) );
				}
			}

			$user_extra_row = array_merge( $user_extra_row, $user_table_data_row );
			$user_extra_row = array_merge( $user_extra_row, $user_meta_data_row );

			// Get user default row.
			$user_default_row = array(
				'user_role'        => is_array( $user->roles ) ? implode( ',', $user->roles ) : $user->roles,
				'date_created'     => $user->data->user_registered,
				'date_created_gmt' => get_gmt_from_date( $user->data->user_registered ),
			);

			$user_row = array_merge( $user_id_row, $user_extra_row );
			$user_row = array_merge( $user_row, $user_default_row );

			/**
			 * Reorder rows according to the values in column.
			 *
			 * @see https://stackoverflow.com/a/44774818/9520912
			 */
			$user_row = array_merge( array_fill_keys( array_keys( $this->generate_columns( $form_id ) ), '' ), $user_row );

			$rows[] = $user_row;
		}

		return apply_filters( 'user_registration_csv_export_rows', $rows, $users );
	}
}

new UR_Admin_Export_Users();
