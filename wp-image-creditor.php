<?php

/**
 * Plugin Name:       WP Image Creditor
 * Plugin URI:        https://github.com/Kcor555/wp-image-creditor
 * Description:      Give credit to author or photographers for images.
 * Author:            Kris Cochran
 * Author URI:        https://github.com/Kcor555
 * Version:           1.0
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Copyright 2022 by Kris Cochran - All rights reserved.
 */


//add field to image for to give credit to photographers

function wp_image_creditor_add_field( $img_fields, $post ) {
	$creditor_field               = get_post_meta( $post->ID, 'creditor_field', true );
	$img_fields['creditor_field'] =
		[
			'label' => __( 'Credit to', 'wp-image-creditor' ),
			'input' => 'text',
			'value' => $creditor_field,
		];

	return $img_fields;
}

add_filter( 'attachment_fields_to_edit', 'wp_image_creditor_add_field', 10, 2 );

//save field data

function wp_image_creditor_save_field( $post, $attachment ) {
	if ( isset( $attachment['creditor_field'] ) ) {
		update_post_meta( $post['ID'], 'creditor_field', sanitize_text_field( $attachment['creditor_field'] ) );
	} else {
		delete_post_meta( $post['ID'], 'creditor_field' );
	}

	return $post;
}

add_filter( 'attachment_fields_to_save', 'wp_image_creditor_save_field', 10, 2 );

//display credit under image

function wp_image_creditor_display( $markup, WP_Post $attachment ) {
	$creditor = get_post_meta( $attachment->ID, 'creditor_field', true );

	if ( ! empty( $creditor ) ) {
		return $markup . '<span class="wp-image-creditor">Credit to ' . $creditor . '</span>';

	}

	return $markup;
}

add_filter( 'mpress_image_refresh-markup', 'wp_image_creditor_display', 10, 2 );
