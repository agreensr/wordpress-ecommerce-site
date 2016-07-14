<?php

/**
 * KT API KEY CLASS
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class kt_api_manager_key {

	// API Key URL
	public function create_software_api_url( $args ) {

		$api_url = add_query_arg( $args, kt_api_m()->upgrade_url );

		return $api_url;
	}

	public function activate( $args ) {

		$defaults = array(
			'wc-api'			=> 'am-software-api',
			'request' 			=> 'activation',
			'product_id' 		=> kt_api_m()->kt_product_id,
			'instance' 			=> kt_api_m()->kt_instance_id,
			'platform' 			=> kt_api_m()->kt_domain,
			'software_version' 	=> kt_api_m()->kt_software_version
			);

		$args = wp_parse_args( $defaults, $args );

		$target_url = $this->create_software_api_url( $args );

		$request = wp_remote_get( $target_url );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

	public function deactivate( $args ) {

		$defaults = array(
			'wc-api'		=> 'am-software-api',
			'request' 		=> 'deactivation',
			'product_id' 	=> kt_api_m()->kt_product_id,
			'instance' 		=> kt_api_m()->kt_instance_id,
			'platform' 		=> kt_api_m()->kt_domain
			);

		$args = wp_parse_args( $defaults, $args );

		$target_url = $this->create_software_api_url( $args );

		$request = wp_remote_get( $target_url );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		// Request failed
			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

	/**
	 * Checks if the software is activated or deactivated
	 */
	public function status( $args ) {

		$defaults = array(
			'wc-api'		=> 'am-software-api',
			'request' 		=> 'status',
			'product_id' 	=> kt_api_m()->kt_product_id,
			'instance' 		=> kt_api_m()->kt_instance_id,
			'platform' 		=> kt_api_m()->kt_domain,
			);

		$args = wp_parse_args( $defaults, $args );

		$target_url = $this->create_software_api_url( $args );

		$request = wp_remote_get( $target_url );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

}

// Class is instantiated as an object by other classes on-demand
