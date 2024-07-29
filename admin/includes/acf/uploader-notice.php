<?php
/**
 * Uploader notice custom fields.
 *
 * @link      https://hashcodeab.se/
 * @since      1.0.0
 *
 * @package    Content_Topics_Hashcode
 * @subpackage Content_Topics_Hashcode/admin
 */

$bp_pages      = get_option( 'bp-pages' );
$media_page_id = null;

if ( ! empty( $bp_pages ) && isset( $bp_pages['media'] ) ) {

	$media_page_id = $bp_pages['media'];
}

if ( ! empty( $media_page_id ) ) {

	acf_add_local_field_group(
		array(
			'key'      => 'group_uploader_notice',
			'title'    => 'Uploader Notice',
			'fields'   => array(
				array(
					'key'   => 'field_uploader_notice',
					'label' => 'Uploader Notice',
					'name'  => 'uploader_notice',
					'type'  => 'textarea',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page',
						'operator' => '==',
						'value'    => $media_page_id,
					),
				),
			),
			'active'   => true,
		)
	);

}
