<?php

/**
 * Show review posts Synchronize tool API
 * I did it with direct addressing to the file
 */

function srp_post_handler()
{
    if (!isset($_POST['form_data_json']) && empty($_POST['form_data_json'])) {
        return;
    }

    $formDataJSON = $_POST['form_data_json'];
    $formDataJSON = json_decode(stripslashes($formDataJSON));
    $formDataLocation = intval(wp_strip_all_tags($formDataJSON->post_category));

    $customTitle = $formDataJSON->custom_title;
    $customDescription = $formDataJSON->custom_description;

    // construct array for creating post func
    $formDataArr = [
        'post_content' => $formDataJSON->post_content,
        'post_title' => wp_strip_all_tags($formDataJSON->post_title),
        //'post_date'    => wp_strip_all_tags( $formDataJSON->post_date ),
        'post_type' => 'srp_review_posts',
        'post_status' => 'publish',
        'post_date' => wp_strip_all_tags($formDataJSON->post_date),
        'meta_input' => [
            'srp_author_name_meta' => wp_strip_all_tags($formDataJSON->author_full_name),
            'srp_author_description_meta' => wp_strip_all_tags($formDataJSON->profession_title),
            'srp_review_link_meta' => wp_strip_all_tags($formDataJSON->review_link),
            'srp_review_link_text_meta' => wp_strip_all_tags($formDataJSON->review_link_text),
            //'srp_category_meta' =>  wp_strip_all_tags( $formDataJSON->post_category ),
            'srp_review_og_image_original' => wp_strip_all_tags($formDataJSON->og_fb),
            'srp_review_instagram_image_original' => wp_strip_all_tags($formDataJSON->og_inst),
            'entry_id' => wp_strip_all_tags($formDataJSON->entry_id),
            'entry_image' => wp_strip_all_tags($formDataJSON->entry_image),
        ],
        'sender_site_address' => wp_strip_all_tags($formDataJSON->sender_site_address),
        'sync_security_code' => wp_strip_all_tags($formDataJSON->sync_security_code),
    ];

    // get security data from plugin options
    $sender_site_address_val = get_option('srp_sync_site_address')['srp_sync_site_address'];
    $srp_sync_security_code = get_option('srp_sync_security_code')['srp_sync_security_code'];
    $origin_site_address = 'https://hapigood.com';

    // function for custom headers return (Cross Origin Policy)
    function applyCustomHeaders($origin_site_address)
    {
        header("Access-Control-Allow-Origin: $origin_site_address");
        header("Access-Control-Allow-Headers: Origin");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
    }

    // Apply Headers
    applyCustomHeaders($origin_site_address);

    function error_response($text){
        $responseJSON = [
            'postInsert' => 0,
            'errorResponse' => $text
        ];
        return json_encode($responseJSON);
    }

    // site address check
    if ($sender_site_address_val != $formDataArr['sender_site_address']) {
        echo error_response('The site address is different');
        exit;
    }

    // check security code and Die if need
    if ($srp_sync_security_code != $formDataArr['sync_security_code']) {
        echo error_response('Synchronization security code fail. Pls. check it!');
        exit;
    }

    // check if enable allow_url_open
    if (!ini_get('allow_url_fopen')) {
        echo error_response('allow_url_fopen is Disabled.');
        exit;
    }

    // execute insert post function
    $resultPostInsert = wp_insert_post($formDataArr, true);

    // check insert post state and return JSON
    if (!isset($resultPostInsert) && !is_int($resultPostInsert) && $resultPostInsert === 0) {
        echo error_response('Repeat later and if it does not work, contact the support team.');
        exit;
    }

    if (isset($formDataLocation) && is_int($formDataLocation)) {
        wp_set_object_terms($resultPostInsert, $formDataLocation, 'srp_review_tax_cat');
    }


    function upload_og($post_id, $file, $upload_file_name)
    {
        if (!file_exists(dirname((__FILE__)) . "/og/" . $post_id)) {
            mkdir(dirname((__FILE__)) . "/og/" . $post_id, 0777, true);
        }

        $uploadFile = dirname(__FILE__) . "/og/" . $post_id . "/" . $upload_file_name;
        file_put_contents($uploadFile, file_get_contents($file));
        return get_site_url() . '/' . str_replace(ABSPATH, '', $uploadFile);
    }

	function downloadFileToLibrary($fileUrl) {
		if (!function_exists('download_url')) {
			return false; // Функция download_url недоступна
		}

		$tmpFile = download_url($fileUrl);
		if (is_wp_error($tmpFile)) {
			return false; // Ошибка при загрузке файла
		}

		$fileArray = array(
			'name' => basename($fileUrl), // Имя файла
			'tmp_name' => $tmpFile, // Временный путь к файлу
		);

		$uploadedFile = wp_handle_sideload($fileArray, array('test_form' => false));
		if (is_wp_error($uploadedFile)) {
			return false; // Ошибка при перемещении файла
		}

		$attachment = array(
			'post_title' => preg_replace('/\.[^.]+$/', '', basename($fileUrl)),
			'post_content' => '',
			'post_status' => 'inherit',
		);

		$attachmentId = wp_insert_attachment($attachment, $uploadedFile['file']);
		if (is_wp_error($attachmentId)) {
			return false; // Ошибка при добавлении вложения
		}

		$attachmentData = wp_generate_attachment_metadata($attachmentId, $uploadedFile['file']);
		wp_update_attachment_metadata($attachmentId, $attachmentData);

		return wp_get_attachment_url($attachmentId);
	}


	$uploadedFileUrl = downloadFileToLibrary($formDataArr['meta_input']['entry_image']);

    $og_fb = upload_og($resultPostInsert, $formDataArr['meta_input']['srp_review_og_image_original'], "og-fb.png");
    $instagram_image = upload_og($resultPostInsert, $formDataArr['meta_input']['srp_review_instagram_image_original'], "instagram-image.png");

    $formDataUpdatePostArr = [
        'ID' => $resultPostInsert,
        'meta_input' => [
            'srp_review_og_image_original' => $og_fb,
            'srp_review_instagram_image_original' => $instagram_image
        ]
    ];

	if ($uploadedFileUrl) {
		$formDataUpdatePostArr['meta_input']['srp_review_testimonial_image'] = $uploadedFileUrl;
	}

    wp_update_post(wp_slash($formDataUpdatePostArr));
    update_option('hap_custom_title', $customTitle);
    update_option('hap_custom_description', $customDescription);

    $responseJSON = [
        'postInsert' => 1, // must be 1 for script.js
    ];

    $responseJSON = json_encode($responseJSON);
    echo $responseJSON;
}