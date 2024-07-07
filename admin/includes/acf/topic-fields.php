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
				'key'          => 'field_topic_icon',
				'label'        => 'Topic Icon',
				'name'         => 'topic_icon',
				'type'         => 'select',
				'instructions' => 'You can easily search icons from here. <a href="https://www.buddyboss.com/resources/font-cheatsheet/" target="_blank">https://www.buddyboss.com/resources/font-cheatsheet/</a>',
				'wrapper'      => array(
					'width' => '30',
				),
				'choices'      => $icon_choices,
				'ui'           => 1,
			),
			array(
				'key'     => 'field_topic_heading',
				'label'   => 'Topic Heading',
				'name'    => 'topic_heading',
				'type'    => 'text',
				'wrapper' => array(
					'width' => '70',
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
