<?php
/**
 * Topic custom fields.
 *
 * @link      https://hashcodeab.se/
 * @since      1.0.0
 *
 * @package    Content_Topics_Hashcode
 * @subpackage Content_Topics_Hashcode/admin
 */

$icon_choices = array(
	'' => 'Select Icon',
);
require plugin_dir_path( __FILE__ ) . 'bb-icons-list.php';

foreach ( $bb_icons_list as $bb_icon ) {
	$icon_choices[ $bb_icon ] = $bb_icon;
}

acf_add_local_field_group(
	array(
		'key'      => 'group_topic_content',
		'title'    => 'Topic Content',
		'fields'   => array(
			array(
				'key'           => 'field_topic_icon_type',
				'label'         => 'Icon Type',
				'name'          => 'topic_icon_type',
				'type'          => 'select',
				'wrapper'       => array(
					'width' => '25',
				),
				'choices'       => array(
					'buddyboss' => 'BuddyBoss',
					'custom'    => 'Custom',
				),
				'ui'            => 1,
				'default_value' => 'buddyboss',
			),
			array(
				'key'               => 'field_icon_image',
				'label'             => 'Icon Image',
				'name'              => 'icon_image',
				'type'              => 'image',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_topic_icon_type',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
				),
				'wrapper'           => array(
					'width' => '25',
				),
				'return_format'     => 'url',
			),
			array(
				'key'               => 'field_topic_icon',
				'label'             => 'Topic Icon',
				'name'              => 'topic_icon',
				'type'              => 'select',
				'instructions'      => 'You can easily search icons from here. <a href="https://www.buddyboss.com/resources/font-cheatsheet/" target="_blank">https://www.buddyboss.com/resources/font-cheatsheet/</a>',
				'wrapper'           => array(
					'width' => '25',
				),
				'choices'           => $icon_choices,
				'ui'                => 1,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_topic_icon_type',
							'operator' => '==',
							'value'    => 'buddyboss',
						),
					),
				),
			),
			array(
				'key'     => 'field_topic_heading',
				'label'   => 'Topic Heading',
				'name'    => 'topic_heading',
				'type'    => 'text',
				'wrapper' => array(
					'width' => '50',
				),
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'obliby_topics',
				),
			),
		),
		'active'   => true,
	)
);
