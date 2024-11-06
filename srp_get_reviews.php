<?php
add_action( 'admin_post_nopriv_hap_delete_review', 'hap_delete_review_callback' );
function hap_delete_review_callback(){
	$origin_site_address = 'https://hapigood.com';

	function applyCustomHeaders( $origin_site_address ) {
		header( "Access-Control-Allow-Origin: $origin_site_address" );
		header( "Access-Control-Allow-Headers: Origin" );
		header( "Access-Control-Allow-Methods: POST, GET, OPTIONS" );
		header( 'P3P: CP="CAO PSA OUR"' ); // Makes IE to support cookies
		header( "Content-Type: application/json; charset=utf-8" );
	}
	applyCustomHeaders( $origin_site_address );

	// $responseJSON = json_encode($_POST["post_id"]);
	// echo $responseJSON;
	wp_delete_post( intval($_POST['post_id']), true );

	//echo $_POST['post_id'];
	die();
}



add_action( 'admin_post_nopriv_hap_get_reviews', 'hap_get_reviews_callback' );
function hap_get_reviews_callback(){

	//function for custom headers return (Cross Origin Policy)
	$origin_site_address = 'https://hapigood.com';
	function applyCustomHeaders( $origin_site_address ) {
		header( "Access-Control-Allow-Origin: $origin_site_address" );
		header( "Access-Control-Allow-Headers: Origin" );
		header( "Access-Control-Allow-Methods: POST, GET, OPTIONS" );
		header( 'P3P: CP="CAO PSA OUR"' ); // Makes IE to support cookies
		header( "Content-Type: application/json; charset=utf-8" );
	}
	applyCustomHeaders( $origin_site_address );


	$args = array(
		'post_type' => 'srp_review_posts',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'date',
		// 'order' => 'ASC',
	);

	$loop = new WP_Query( $args );

	$responseJSON = [];

	while ( $loop->have_posts() ) : $loop->the_post();
		$post_id = get_the_ID();

		$html = '<tr id="'.get_the_ID().'">';
		$html .= '<td><a href="'.get_the_guid().'">'.get_the_title().'</a></td>';
		$html .= '<td>'.wp_trim_words( get_the_content(), 10, '...' ).'</td>';


		if( get_post_meta( $post_id, 'srp_review_og_image_original', true ) && get_post_meta( $post_id, 'srp_review_instagram_image_original', true )){
			$html .= '<td><a href="'.get_post_meta( $post_id, 'srp_review_og_image_original', true ).'">Og</a> / <a href="'.get_post_meta( $post_id, 'srp_review_instagram_image_original', true ).'">Instagram</a></td>';
		} else {
			$html .= '<td></td>';
		}

		$html .= '<td>'.get_the_time( 'j F Y' ).'</td>';
		$html .= '<td><a href="https://hapigood.com/wp-admin/admin.php?page=fs-poster-share&url='.get_permalink($post_id).'&content='.get_the_content().'&title='.get_the_title().'" class="share" target="_blank" style="color:green;">Share</a><br><a href="#" class="edit">Edit</a><br><a href="#" class="remove">Remove</a></td>';
		$html .= '</tr>';


		$responseJSON[$post_id] = [
			'html' => $html
		];

	endwhile;

	wp_reset_postdata();

	$responseJSON = json_encode( $responseJSON );
	echo $responseJSON;

	die();
}


add_action( 'admin_post_nopriv_hap_get_post', 'hap_get_post_callback' );
function hap_get_post_callback(){

	$origin_site_address = 'https://hapigood.com';
	function applyCustomHeaders( $origin_site_address ) {
		header( "Access-Control-Allow-Origin: $origin_site_address" );
		header( "Access-Control-Allow-Headers: Origin" );
		header( "Access-Control-Allow-Methods: POST, GET, OPTIONS" );
		header( 'P3P: CP="CAO PSA OUR"' ); // Makes IE to support cookies
		header( "Content-Type: application/json; charset=utf-8" );
	}
	applyCustomHeaders( $origin_site_address );

	$post_id = $_POST['post_id'];
	if(!isset($post_id)){
		return;
	}

	$post = get_post( $post_id );

	$responseJSON = [
		'title' => $post->post_title,
		'content' => $post->post_content,
		'date' => $post->post_date,
		'srp_author_name_meta' => get_post_meta( $post_id, 'srp_author_name_meta', true ),
		'srp_author_description_meta' => get_post_meta( $post_id, 'srp_author_description_meta', true ),
		'srp_review_link_meta' => get_post_meta( $post_id, 'srp_review_link_meta', true ),
		'srp_review_link_text_meta' => get_post_meta( $post_id, 'srp_review_link_text_meta', true ),
	];

	$responseJSON = json_encode( $responseJSON );
	echo $responseJSON;

	die();

}


add_action( 'admin_post_nopriv_hap_edit_post', 'hap_edit_post_callback' );
function hap_edit_post_callback(){

	$origin_site_address = 'https://hapigood.com';
	function applyCustomHeaders( $origin_site_address ) {
		header( "Access-Control-Allow-Origin: $origin_site_address" );
		header( "Access-Control-Allow-Headers: Origin" );
		header( "Access-Control-Allow-Methods: POST, GET, OPTIONS" );
		header( 'P3P: CP="CAO PSA OUR"' ); // Makes IE to support cookies
		header( "Content-Type: application/json; charset=utf-8" );
	}
	applyCustomHeaders( $origin_site_address );
	$formDataArr = [
		'ID' 				  => $_POST['post_id'],
		'post_content'        => stripcslashes($_POST['content']),
		'post_title'          => stripcslashes(wp_strip_all_tags( $_POST['title'] )),
		'post_date'           => wp_strip_all_tags( $_POST['date'] ),
		'meta_input'          => [
			'srp_author_name_meta'         => stripcslashes(wp_strip_all_tags( $_POST['srp_author_name_meta'] )),
			//'srp_author_description_meta'  => wp_strip_all_tags( $_POST['srp_author_description_meta'] ),
			'srp_review_link_meta'         => stripcslashes(wp_strip_all_tags( $_POST['srp_review_link_meta'] )),
			'srp_review_link_text_meta'    => stripcslashes(wp_strip_all_tags( $_POST['srp_review_link_text_meta'] )),
			//'srp_category_meta' =>  wp_strip_all_tags( $formDataJSON->post_category ),
			//'srp_review_og_image_original' => wp_strip_all_tags( $formDataJSON->og_fb ),
			// 'srp_review_meta_description' => wp_strip_all_tags( $_POST['srp_review_meta_description'] ),
			// 'srp_review_meta_keywords' => wp_strip_all_tags( $_POST['srp_review_meta_keywords'] ),
		]
	];

	function upload_og($post_id, $file, $uload_file_name){
		if (!file_exists(dirname(( __FILE__ )) . "/og/". $post_id)) {
			mkdir(dirname(( __FILE__ )) . "/og/". $post_id, 0777, true);
		}

		$uploadFile = dirname( __FILE__ ) . "/og/". $post_id ."/" . $uload_file_name;
		file_put_contents($uploadFile, file_get_contents($file));
		return get_site_url() . '/' . str_replace( ABSPATH, '', $uploadFile);
	}

	if(!empty($_POST['og_fb']) && !empty($_POST['og_inst'])){
		$og_fb = upload_og($_POST['post_id'], $_POST['og_fb'], "og-fb.png");
		$instagram_image = upload_og($_POST['post_id'], $_POST['og_inst'], "instagram-image.png");


		$formDataArr['meta_input'] += ['srp_review_og_image_original' => $og_fb];
		$formDataArr['meta_input'] += ['srp_review_instagram_image_original' => $instagram_image];
	}
	//var_dump($formDataArr);
	// die();
	// }

	$update = wp_update_post( wp_slash($formDataArr) );
	echo $update;

	die();
}