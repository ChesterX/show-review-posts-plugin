<?php

/**
 * Show review posts Synchronize tool API
 * I did it with direct addressing to the file
 */

function srp_post_handler() {
	if ( isset( $_POST['form_data_json'] ) && ! empty( $_POST['form_data_json'] ) ) {

		$formDataJSON     = $_POST['form_data_json'];
		$formDataJSON     = json_decode( stripslashes( $formDataJSON ) );
		$formDataLocation = intval( wp_strip_all_tags( $formDataJSON->post_category ) );

		$customTitle = $formDataJSON->custom_title;
		$customDescription = $formDataJSON->custom_description;
		// 'main_site_address' => 'https://localhost/hapigood-prod/',
		// 
		// construct array for creating post func
		$formDataArr = [
			'post_content'        => $formDataJSON->post_content,
			'post_title'          => wp_strip_all_tags( $formDataJSON->post_title ),
			//'post_date'    => wp_strip_all_tags( $formDataJSON->post_date ),
			'post_type'           => 'srp_review_posts',
			'post_status'         => 'publish',
			'post_date'           => wp_strip_all_tags( $formDataJSON->post_date ),
			'meta_input'          => [
				'srp_author_name_meta'         => wp_strip_all_tags( $formDataJSON->author_full_name ),
				'srp_author_description_meta'  => wp_strip_all_tags( $formDataJSON->profession_title ),
				'srp_review_link_meta'         => wp_strip_all_tags( $formDataJSON->review_link ),
				'srp_review_link_text_meta'    => wp_strip_all_tags( $formDataJSON->review_link_text ),
				//'srp_category_meta' =>  wp_strip_all_tags( $formDataJSON->post_category ),
				'srp_review_og_image_original' => wp_strip_all_tags( $formDataJSON->og_fb ),
				'srp_review_instagram_image_original' => wp_strip_all_tags( $formDataJSON->og_inst ),
				'entry_id' => wp_strip_all_tags( $formDataJSON->entry_id ),
			],
			'sender_site_address' => wp_strip_all_tags( $formDataJSON->sender_site_address ),
			'sync_security_code'  => wp_strip_all_tags( $formDataJSON->sync_security_code ),
		];


		// get security data from plugin options
		$sender_site_address_val = get_option( 'srp_sync_site_address' )['srp_sync_site_address'];
		$srp_sync_security_code  = get_option( 'srp_sync_security_code' )['srp_sync_security_code'];


		// function for custom headers return (Cross Origin Policy)
		$origin_site_address = 'https://hapigood.com';
		function applyCustomHeaders( $origin_site_address ) {
			header( "Access-Control-Allow-Origin: $origin_site_address" );
			header( "Access-Control-Allow-Headers: Origin" );
			header( "Access-Control-Allow-Methods: POST, GET, OPTIONS" );
			header( 'P3P: CP="CAO PSA OUR"' ); // Makes IE to support cookies
			header( "Content-Type: application/json; charset=utf-8" );
		}


		// site address check
		if ( $sender_site_address_val === $formDataArr['sender_site_address'] ) {

			// check security code and Die if need
			if ( $srp_sync_security_code != $formDataArr['sync_security_code'] ) {
				$responseJSON = [
					'postInsert'    => 0,
					'errorResponse' => 'Synchronization security code fail. Pls. check it!'
				];

				applyCustomHeaders( $origin_site_address );
				$responseJSON = json_encode( $responseJSON );

				echo $responseJSON;

				exit;
			}

			// execute insert post function
			$resultPostInsert = wp_insert_post( $formDataArr, true );

			// check insert post state and return JSON
			if ( isset( $resultPostInsert ) && is_int( $resultPostInsert ) && $resultPostInsert != 0 ) {

				if ( isset( $formDataLocation ) && is_int( $formDataLocation ) ) {
					wp_set_object_terms( $resultPostInsert, $formDataLocation, 'srp_review_tax_cat' );
				}

			// Upload OG and Instagram image

			function upload_og($post_id, $file, $uload_file_name){
				if (!file_exists(dirname(( __FILE__ )) . "/og/". $post_id)) {
				    mkdir(dirname(( __FILE__ )) . "/og/". $post_id, 0777, true);
				}

				$uploadFile = dirname( __FILE__ ) . "/og/". $post_id ."/" . $uload_file_name;
				file_put_contents($uploadFile, file_get_contents($file));
				return get_site_url() . '/' . str_replace( ABSPATH, '', $uploadFile);
			}

			$og_fb = upload_og($resultPostInsert, $formDataArr['meta_input']['srp_review_og_image_original'], "og-fb.png");
			$instagram_image = upload_og($resultPostInsert, $formDataArr['meta_input']['srp_review_instagram_image_original'], "instagram-image.png");
			
				$formDataUpdatePostArr = [
					'ID' => $resultPostInsert,
					'meta_input'          => [
						'srp_review_og_image_original' => $og_fb,
						'srp_review_instagram_image_original' => $instagram_image
					]
				];

				wp_update_post( wp_slash($formDataUpdatePostArr) );
				
				
				update_option( 'hap_custom_title', $customTitle );
				update_option( 'hap_custom_description', $customDescription );

				$responseJSON = [
					'postInsert' => 1, // must be 1 for script.js
				];

				applyCustomHeaders( $origin_site_address );
				$responseJSON = json_encode( $responseJSON );

				echo $responseJSON;
			} else { // if post wasn't created

				$responseJSON = [
					'postInsert'    => 0,
					'errorResponse' => 'Repeat later and if it does not work, contact the support team.'
				];

				applyCustomHeaders( $origin_site_address );
				$responseJSON = json_encode( $responseJSON );

				echo $responseJSON;
			}

		} else { // if address check is different
			// TODO: expediere raspuns cu eroare

			$responseJSON = [
				'postInsert'    => 0,
				'errorResponse' => 'The site address is different'
			];

			applyCustomHeaders( $origin_site_address );
			$responseJSON = json_encode( $responseJSON );

			echo $responseJSON;
		}
	}
}