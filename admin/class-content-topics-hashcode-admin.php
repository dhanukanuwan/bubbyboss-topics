<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://hashcodeab.se
 * @since      1.0.0
 *
 * @package    Content_Topics_Hashcode
 * @subpackage Content_Topics_Hashcode/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Content_Topics_Hashcode
 * @subpackage Content_Topics_Hashcode/admin
 * @author     Dhanuka Gunarathna <dhanuka@hashcodeab.se>
 */
class Content_Topics_Hashcode_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register topics custom post type.
	 *
	 * @since    1.0.0
	 */
	public function obliby_content_topics_post_type() {

		$labels = array(
			'name'                  => _x( 'Topics', 'Post Type General Name', 'content-topics-hashcode' ),
			'singular_name'         => _x( 'Topic', 'Post Type Singular Name', 'content-topics-hashcode' ),
			'menu_name'             => __( 'Topics', 'content-topics-hashcode' ),
			'name_admin_bar'        => __( 'Topics', 'content-topics-hashcode' ),
			'archives'              => __( 'Topic Archives', 'content-topics-hashcode' ),
			'attributes'            => __( 'Topic Attributes', 'content-topics-hashcode' ),
			'parent_item_colon'     => __( 'Parent ItTopicm:', 'content-topics-hashcode' ),
			'all_items'             => __( 'All Topics', 'content-topics-hashcode' ),
			'add_new_item'          => __( 'Add New Item', 'content-topics-hashcode' ),
			'add_new'               => __( 'Add New Topic', 'content-topics-hashcode' ),
			'new_item'              => __( 'New Topic', 'content-topics-hashcode' ),
			'edit_item'             => __( 'Edit Topic', 'content-topics-hashcode' ),
			'update_item'           => __( 'Update Topic', 'content-topics-hashcode' ),
			'view_item'             => __( 'View Topic', 'content-topics-hashcode' ),
			'view_items'            => __( 'View Topic', 'content-topics-hashcode' ),
			'search_items'          => __( 'Search Topic', 'content-topics-hashcode' ),
			'not_found'             => __( 'Not found', 'content-topics-hashcode' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'content-topics-hashcode' ),
			'featured_image'        => __( 'Featured Image', 'content-topics-hashcode' ),
			'set_featured_image'    => __( 'Set featured image', 'content-topics-hashcode' ),
			'remove_featured_image' => __( 'Remove featured image', 'content-topics-hashcode' ),
			'use_featured_image'    => __( 'Use as featured image', 'content-topics-hashcode' ),
			'insert_into_item'      => __( 'Insert into item', 'content-topics-hashcode' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'content-topics-hashcode' ),
			'items_list'            => __( 'Items list', 'content-topics-hashcode' ),
			'items_list_navigation' => __( 'Items list navigation', 'content-topics-hashcode' ),
			'filter_items_list'     => __( 'Filter items list', 'content-topics-hashcode' ),
		);

		$rewrite = array(
			'slug'       => 'topic',
			'with_front' => false,
			'pages'      => false,
			'feeds'      => false,
		);

		$args = array(
			'label'               => __( 'Topic', 'content-topics-hashcode' ),
			'description'         => __( 'Obliby Content Topic', 'content-topics-hashcode' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-clipboard',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
			'show_in_rest'        => false,
		);

		register_post_type( 'obliby_topics', $args );
	}

	/**
	 * ACF field groups.
	 *
	 * @since 1.0.0
	 */
	public function obliby_acf_field_groups() {

		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		require_once plugin_dir_path( __DIR__ ) . 'admin/includes/acf/topic-fields.php';
	}

	/**
	 * Default topic content filters.
	 *
	 * @since 1.0.0
	 * @param  array $filters .
	 * @param  array $active_filter .
	 */
	public function obliby_default_topic_content_filters( $filters, $active_filter ) {

		$default_filters = array(
			array(
				'slug'    => 'posts',
				'name'    => __( 'Posts', 'content-topics-hashcode' ),
				'single'  => __( 'Post', 'content-topics-hashcode' ),
				'classes' => '',
			),
			array(
				'slug'    => 'photos',
				'name'    => __( 'Photos', 'content-topics-hashcode' ),
				'single'  => __( 'Photo', 'content-topics-hashcode' ),
				'classes' => '',
			),
			array(
				'slug'    => 'videos',
				'name'    => __( 'Videos', 'content-topics-hashcode' ),
				'single'  => __( 'Video', 'content-topics-hashcode' ),
				'classes' => '',
			),
			array(
				'slug'    => 'courses',
				'name'    => __( 'Courses', 'content-topics-hashcode' ),
				'single'  => __( 'Course', 'content-topics-hashcode' ),
				'classes' => '',
			),
		);

		foreach ( $default_filters as $default_filter ) {

			$item_key = array_search( $default_filter['slug'], array_column( $filters, 'slug' ), true );

			if ( $active_filter === $default_filter['slug'] ) {
				$default_filter['classes'] = 'active';
			}

			if ( false === $item_key ) {
				$filters[] = $default_filter;
			}
		}

		return $filters;
	}

	/**
	 * Topic content filters rewrite tags.
	 *
	 * @since 1.0.0
	 */
	public function obliby_topic_content_filter_rewrite_tags() {

		add_rewrite_tag( '%topicfilter%', '([^&]+)' );

		$content_topics = get_posts(
			array(
				'post_type' => 'obliby_topics',
				'nopaging'  => true,
			)
		);

		if ( ! empty( $content_topics ) && ! is_wp_error( $content_topics ) ) {
			foreach ( $content_topics as $content_topic ) {

				add_rewrite_rule( '^topic/' . $content_topic->post_name . '/([a-zA-Z0-9\-]+)/?', 'index.php?post_type=obliby_topics&p=' . $content_topic->ID . '&topicfilter=$matches[1]', 'top' );

			}
		}
	}

	/**
	 * Add new post and course categories when creating a new topic.
	 *
	 * @since 1.0.0
	 * @param  array   $post_id .
	 * @param  WP_Post $post .
	 */
	public function obliby_create_new_categories_on_save_topic( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// bail out if this is not an event item.
		if ( 'obliby_topics' !== $post->post_type ) {
			return;
		}

		$post_category   = get_term_by( 'name', $post->post_title, 'category' );
		$course_category = get_term_by( 'name', $post->post_title, 'course-category' );

		if ( empty( $post_category ) ) {
			wp_insert_term( $post->post_title, 'category' );
		}

		if ( empty( $course_category ) ) {
			wp_insert_term( $post->post_title, 'course-category' );
		}
	}

	/**
	 * Add new user media album endpoint.
	 *
	 * @since    1.0.0
	 */
	public function obliby_new_topic_user_media_album_endpoint() {
		register_rest_route(
			'oblibytopics/v1',
			'/newtopicalbum',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'obliby_new_topic_user_media_album_endpoint_callback' ),
					'args'                => array(
						'topic_name' => array(
							'required' => true,
							'type'     => 'string',
						),
					),
					'permission_callback' => array( $this, 'obliby_rest_api_user_permissions' ),
				),
			)
		);
	}

	/**
	 * Add new organization callback.
	 *
	 * @param    array $request request array.
	 * @since    1.0.0
	 */
	public function obliby_new_topic_user_media_album_endpoint_callback( $request ) {

		$topic_name = sanitize_text_field( $request->get_param( 'topic_name' ) );

		$data    = array();
		$success = false;
		$message = '';

		if ( ! empty( $topic_name ) ) {

			$user_id = get_current_user_id();

			global $wpdb;
			$albums_table = $wpdb->prefix . 'bp_media_albums';

			$inserted = $wpdb->insert( //phpcs:ignore
				$albums_table,
				array(
					'user_id'  => $user_id,
					'group_id' => 0,
					'title'    => $topic_name,
					'privacy'  => 'public',
				),
				array(
					'%d',
					'%d',
					'%s',
					'%s',
				)
			);

			if ( false !== $inserted ) {

				$success = true;

				if ( isset( $wpdb->insert_id ) ) {
					$data['album_id'] = $wpdb->insert_id;
				}
			}
		}

		$response = rest_ensure_response(
			array(
				'data'    => $data,
				'success' => $success,
				'message' => $message,
			)
		);

		return $response;
	}

	/**
	 * Check user permissions.
	 *
	 * @param    array $request request array.
	 * @since    1.0.0
	 */
	public function obliby_rest_api_user_permissions( $request ) { //phpcs:ignore
		return current_user_can( 'edit_posts' );
	}
}
