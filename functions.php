<?php
add_action( 'init', 'ubcpeople_create_post_type' );
add_action('wp_ajax_ubcpeople_update_post', 'ubcpeople_update_post');

include 'include/receive-image.php';
include 'include/services/twitter.php';

function ubcpeople_create_post_type() {
	register_post_type( 'ubc_person',
		array(
			'labels' => array(
				'name' => __( 'People' ),
				'singular_name' => __( 'Person' ),
				'add_new' => _x( 'Add New', 'ubc_person' ),
				'add_new_item' => __( 'Add New Person', 'ubc_person' ),
				'edit_item' => __( 'Edit Person', 'ubc_person' ),
				'new_item' => __( 'New Person', 'ubc_person' ),
			),
		'public' => true,
		'has_archive' => true,
		)
	);
}


/**
 *	Update post via ajax from front-end
 */
function ubcpeople_update_post(){
	$id = $_POST['id'];
	$title = $_POST['title'];
	$content = $_POST['content'];
	$post_data = array(
		'ID' => $id,
		'post_modified' => date('Y-m-d H:i:s'),
		'post_title' => $title,
		'post_content' =>	$content,
	);
	
	wp_update_post( $post_data );
	update_post_meta($id, 'box_parameters', $_POST['box']);
	update_post_meta($id, 'background_parameters', $_POST['bg']);
	update_post_meta($id, 'images', $_POST['images']);
}