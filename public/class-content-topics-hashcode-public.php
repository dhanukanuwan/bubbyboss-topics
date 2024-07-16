<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://hashcodeab.se
 * @since      1.0.0
 *
 * @package    Content_Topics_Hashcode
 * @subpackage Content_Topics_Hashcode/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Content_Topics_Hashcode
 * @subpackage Content_Topics_Hashcode/public
 * @author     Dhanuka Gunarathna <dhanuka@hashcodeab.se>
 */
class Content_Topics_Hashcode_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function obliby_enqueue_scripts() {

		if ( is_singular( 'obliby_topics' ) ) {
			wp_enqueue_style( 'obliby-bootstrap-grid', plugin_dir_url( __FILE__ ) . 'css/bootstrap-grid.min.css', array(), '5.3.4', 'all' );
			wp_enqueue_style( 'obliby-css', plugin_dir_url( __FILE__ ) . 'css/content-topics.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/content-topics.css' ), 'all' );
			wp_enqueue_script( 'obliby-js', plugin_dir_url( __FILE__ ) . 'js/content-topics.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/content-topics.js' ), true );
		}

		$buddyboss_plugin_options = get_option( 'buddyboss_sap_plugin_options' );

		if ( ! empty( $buddyboss_plugin_options ) && isset( $buddyboss_plugin_options['create-new-post'] ) ) {

			$new_post_page_id = $buddyboss_plugin_options['create-new-post'];

			if ( is_page( $new_post_page_id ) ) {

				if ( isset( $_GET['nonce'] ) && isset( $_GET['category'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'obliby_nonce' ) ) {

					$category_id = sanitize_text_field( wp_unslash( $_GET['category'] ) );

					wp_enqueue_script( 'obliby-post-js', plugin_dir_url( __FILE__ ) . 'js/new-post.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/new-post.js' ), true );

					$ajax_object = array(
						'categoryID' => $category_id,
					);

					wp_localize_script( 'obliby-post-js', 'oblibyAjax', $ajax_object );

				}
			}
		}
	}

	/**
	 * Topic content data.
	 *
	 * @since    1.0.0
	 * @param array $data .
	 * @param array $topic .
	 * @param array $filter .
	 */
	public function obliby_topic_content_data( $data, $topic, $filter ) {

		$filter_slug = '';
		$topic_slug  = '';
		$topic_title = '';

		if ( ! empty( $filter ) && isset( $filter['slug'] ) ) {
			$filter_slug = $filter['slug'];
		}

		if ( ! empty( $topic ) && isset( $topic['slug'] ) ) {
			$topic_slug = $topic['slug'];
		}

		if ( ! empty( $topic ) && isset( $topic['title'] ) ) {
			$topic_title = $topic['title'];
		}

		if ( 'posts' === $filter_slug ) {
			$topic_posts = $this->obliby_get_topic_posts( $topic_slug );

			return array_merge( $data, $topic_posts );
		}

		if ( 'courses' === $filter_slug ) {
			$topic_courses = $this->obliby_get_topic_courses( $topic_slug );

			return array_merge( $data, $topic_courses );
		}

		if ( 'photos' === $filter_slug ) {
			$topic_images = $this->obliby_get_topic_media( $topic_title, 'photo' );

			return array_merge( $data, $topic_images );
		}

		if ( 'videos' === $filter_slug ) {
			$topic_videos = $this->obliby_get_topic_media( $topic_title, 'video' );

			return array_merge( $data, $topic_videos );
		}

		$all_content = $this->obliby_get_all_content_type_data( $topic, 1 );

		if ( ! empty( $all_content ) ) {
			$data = array_merge( $data, $all_content );
		}

		return $data;
	}

	/**
	 * Get posts related to given topic.
	 *
	 * @since    1.0.0
	 * @param string $topic .
	 * @param int    $page_number .
	 */
	private function obliby_get_all_content_type_data( $topic, $page_number ) {

		$content_data = array();

		$all_content = $this->obliby_get_all_content_types( $topic, $page_number );

		if ( ! empty( $all_content ) && ! is_wp_error( $all_content ) ) {

			foreach ( $all_content as $content_item ) {

				if ( isset( $content_item->type ) ) {

					if ( 'post' === $content_item->type ) {

						$content_data[] = $this->obliby_get_post_data( $content_item->ID, $content_item->type );
					}

					if ( 'courses' === $content_item->type ) {
						$content_data[] = $this->obliby_get_post_data( $content_item->ID, 'course' );
					}

					if ( 'photo' === $content_item->type || 'video' === $content_item->type ) {

						$content_data[] = $this->obliby_get_topic_media_content( $content_item, $content_item->type );
					}
				}
			}
		}

		return $content_data;
	}

	/**
	 * Get posts related to given topic.
	 *
	 * @since    1.0.0
	 * @param string $topic .
	 * @param int    $offset .
	 */
	private function obliby_get_topic_posts( $topic, $offset = 1 ) {

		$topic_posts = array();

		$offset_number = 15 * ( $offset - 1 );

		$category = get_category_by_slug( $topic );

		if ( empty( $category ) || is_wp_error( $category ) ) {
			return $topic_posts;
		}

		$post_ids = get_posts(
			array(
				'post_type'      => 'post',
				'category'       => $category->term_id,
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'posts_per_page' => 15,
				'offset'         => $offset_number,
			)
		);

		if ( ! empty( $post_ids ) && ! is_wp_error( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				$post_data     = $this->obliby_get_post_data( $post_id, __( 'post', 'content-topics-hashcode' ) );
				$topic_posts[] = $post_data;
			}
		}

		return $topic_posts;
	}

	/**
	 * Get courses related to given topic.
	 *
	 * @since    1.0.0
	 * @param string $topic .
	 * @param int    $offset .
	 */
	private function obliby_get_topic_courses( $topic, $offset = 1 ) {

		$topic_courses = array();

		$offset_number = 15 * ( $offset - 1 );

		$category = get_term_by( 'slug', $topic, 'course-category' );

		if ( empty( $category ) || is_wp_error( $category ) ) {
			return $topic_courses;
		}

		$post_ids = get_posts(
			array(
				'post_type'      => 'courses',
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'posts_per_page' => 15,
				'offset'         => $offset_number,
				'tax_query'      => array( //phpcs:ignore
					array(
						'taxonomy' => 'course-category',
						'field'    => 'slug',
						'terms'    => $category->slug,
					),
				),
			)
		);

		if ( ! empty( $post_ids ) && ! is_wp_error( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				$post_data       = $this->obliby_get_post_data( $post_id, __( 'course', 'content-topics-hashcode' ) );
				$topic_courses[] = $post_data;
			}
		}

		return $topic_courses;
	}

	/**
	 * Get only required post data.
	 *
	 * @since    1.0.0
	 * @param array $topic_data .
	 * @param int   $offset .
	 */
	private function obliby_get_all_content_types( $topic_data, $offset = 1 ) {

		global $wpdb;

		$topic_slug   = $topic_data['slug'];
		$topic_title  = $topic_data['title'];
		$albums_table = $wpdb->prefix . 'bp_media_albums';
		$media_table  = $wpdb->prefix . 'bp_media';

		if ( 1 > $offset ) {
			$offset = 1;
		}

		$offset_number = 15 * ( $offset - 1 );

		$topic_posts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT DISTINCT *
						FROM (
							SELECT wposts.ID,wposts.post_author AS user_id,wposts.post_title AS title,wposts.post_parent AS album_id,wposts.post_type AS type,wposts.menu_order AS activity_id,wposts.comment_count AS media_id FROM {$wpdb->posts} AS wposts
								LEFT JOIN {$wpdb->postmeta} AS wpostmeta ON (wposts.ID = wpostmeta.post_id)
								LEFT JOIN {$wpdb->term_relationships} AS tax_rel ON (wposts.ID = tax_rel.object_id)
								LEFT JOIN {$wpdb->term_taxonomy} AS term_tax ON (tax_rel.term_taxonomy_id = term_tax.term_taxonomy_id)
								LEFT JOIN {$wpdb->terms} AS terms ON (terms.term_id = term_tax.term_id)
							WHERE (wposts.post_status = 'publish' AND terms.slug = %s AND wposts.post_type = 'post' AND term_tax.taxonomy = 'category')
							OR (wposts.post_status = 'publish' AND terms.slug = %s AND wposts.post_type = 'courses' AND term_tax.taxonomy = 'course-category')
							UNION ALL
							SELECT m.attachment_id AS ID,m.user_id,m.title,m.album_id,m.type,m.activity_id,m.id AS media_id from $media_table AS m
								LEFT JOIN $albums_table AS a ON m.album_id = a.id WHERE a.title = %s AND a.privacy = %s
						) posts
				GROUP BY posts.ID ORDER BY posts.ID DESC LIMIT 15 OFFSET %d",
				$topic_slug,
				$topic_slug,
				$topic_title,
				'public',
				$offset_number
			),
			OBJECT
		);

		return $topic_posts;
	}

	/**
	 * Get only required post data.
	 *
	 * @since    1.0.0
	 * @param int    $post_id .
	 * @param string $type .
	 */
	private function obliby_get_post_data( $post_id, $type = '' ) {

		$post_title     = get_the_title( $post_id );
		$post_url       = get_permalink( $post_id );
		$post_thumbnail = wp_get_attachment_url( get_post_thumbnail_id( $post_id ), 'full' );
		$author_id      = get_post_field( 'post_author', $post_id );
		$author_avatar  = get_avatar_url( $author_id );
		$author_name    = get_the_author_meta( 'display_name', $author_id );
		$author_profile = bbp_get_user_profile_url( $author_id );
		$post_date      = get_the_date( 'F j, Y', $post_id );
		$time_diff      = human_time_diff( get_the_date( 'U', $post_id ) );

		$post_data = array(
			'post_title'     => $post_title,
			'post_url'       => $post_url,
			'author_avatar'  => $author_avatar,
			'author_name'    => $author_name,
			'author_profile' => $author_profile,
			'post_date'      => $post_date,
			'time_diff'      => $time_diff,
		);

		if ( ! empty( $post_thumbnail ) && ! is_wp_error( $post_thumbnail ) ) {
			$post_data['post_thumbnail'] = $post_thumbnail;
		}

		if ( ! empty( $type ) ) {
			$post_data['type'] = $type;
		}

		return $post_data;
	}

	/**
	 * Get only required post data.
	 *
	 * @since    1.0.0
	 * @param string $topic .
	 * @param string $type .
	 * @param int    $offset .
	 */
	private function obliby_get_topic_media( $topic, $type = '', $offset = 1 ) {

		global $wpdb;
		$albums_table = $wpdb->prefix . 'bp_media_albums';
		$media_table  = $wpdb->prefix . 'bp_media';

		$media_data = array();

		if ( 1 > $offset ) {
			$offset = 1;
		}

		$offset_number = 15 * ( $offset - 1 );

		$media_result = $wpdb->get_results( $wpdb->prepare( "SELECT m.attachment_id AS ID,m.user_id,m.title,m.album_id,m.type,m.activity_id,m.id AS media_id from $media_table AS m LEFT JOIN $albums_table AS a ON m.album_id = a.id WHERE a.title = %s AND a.privacy = %s AND m.type = %s ORDER BY m.attachment_id DESC LIMIT 15 OFFSET %d",  $topic, 'public', $type, $offset_number ) , OBJECT ); //phpcs:ignore

		if ( ! empty( $media_result ) && ! is_wp_error( $media_result ) ) {

			foreach ( $media_result as $media_item ) {

				$media_data[] = $this->obliby_get_topic_media_content( $media_item, $type );

			}
		}

		return $media_data;
	}

	/**
	 * Get media content.
	 *
	 * @since    1.0.0
	 * @param array  $media_item .
	 * @param string $type .
	 */
	private function obliby_get_topic_media_content( $media_item, $type ) {

		$media_url      = '';
		$author_name    = get_the_author_meta( 'display_name', $media_item->user_id );
		$author_profile = bbp_get_user_profile_url( $media_item->user_id );
		$post_date      = get_the_date( 'F j, Y', $media_item->ID );
		$video_data     = array();
		$author_avatar  = get_avatar_url( $media_item->user_id );
		$time_diff      = human_time_diff( get_the_date( 'U', $media_item->ID ) );

		if ( 'video' === $type ) {
			$video_data = bp_video_get_activity_video( $media_item->activity_id );
		}

		$activity_url = bp_activity_get_permalink( $media_item->activity_id );

		if ( 'photo' === $type ) {

			$media_template = new BP_Media_Template(
				array(
					'user_id' => $media_item->user_id,
					'include' => $media_item->media_id,
				)
			);

			if ( ! empty( $media_template ) && isset( $media_template->medias ) ) {
				$media_url = apply_filters( 'bp_get_media_attachment_image', $media_template->medias[0]->attachment_data->media_theatre_popup );
			}
		}

		$media_data = array(
			'media_url'      => $media_url,
			'author_name'    => $author_name,
			'author_profile' => $author_profile,
			'post_date'      => $post_date,
			'type'           => $type,
			'video_data'     => $video_data,
			'activity_id'    => $media_item->activity_id,
			'attachment_id'  => $media_item->ID,
			'album_id'       => $media_item->album_id,
			'author_avatar'  => $author_avatar,
			'time_diff'      => $time_diff,
			'activity_url'   => $activity_url,
		);

		return $media_data;
	}

	/**
	 * Topic content pagination endpoint.
	 *
	 * @since    1.0.0
	 */
	public function obliby_topic_pagination_endpoint() {
		register_rest_route(
			'oblibytopics/v1',
			'/getpagination',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'obliby_topic_pagination_endpoint_callback' ),
					'args'                => array(
						'type'      => array(
							'required' => true,
							'type'     => 'string',
						),
						'topicdata' => array(
							'required' => true,
							'type'     => 'string',
						),
						'offset'    => array(
							'required' => true,
							'type'     => 'number',
						),
					),
					'permission_callback' => '__return_true',
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
	public function obliby_topic_pagination_endpoint_callback( $request ) {

		$type       = $request->get_param( 'type' );
		$offset     = $request->get_param( 'offset' );
		$topic_data = $request->get_param( 'topicdata' );

		if ( ! empty( $topic_data ) ) {
			$topic_data = json_decode( wp_unslash( $topic_data ), true );
		}

		$new_offset = (int) $offset + 1;

		$data    = array();
		$success = false;
		$message = '';

		$topic_content = array();

		if ( 'all' === $type ) {
			$topic_content = $this->obliby_get_all_content_type_data( $topic_data, $new_offset );
		}

		if ( 'posts' === $type ) {
			$topic_content = $this->obliby_get_topic_posts( $topic_data['slug'], $new_offset );
		}

		if ( 'courses' === $type ) {
			$topic_content = $this->obliby_get_topic_courses( $topic_data['slug'], $new_offset );
		}

		if ( 'videos' === $type ) {
			$topic_content = $this->obliby_get_topic_media( $topic_data['title'], 'video', $new_offset );
		}

		if ( 'photos' === $type ) {
			$topic_content = $this->obliby_get_topic_media( $topic_data['title'], 'photo', $new_offset );
		}

		if ( ! empty( $topic_content ) && ! is_wp_error( $topic_content ) ) {

			foreach ( $topic_content as $topic_item ) {
				ob_start();
				include plugin_dir_path( __DIR__ ) . 'public/partials/template-parts/topic-' . $topic_item['type'] . '.php';
				$data[] = ob_get_contents();
				ob_end_clean();
			}

			$success = true;
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
	 * Serve topic post type single template from the plugin if it's not available in the theme.
	 *
	 * @param    string $template .
	 * @since    1.0.0
	 */
	public function obliby_topic_post_type_single_template( $template ) {

		global $post;

		if ( 'obliby_topics' === $post->post_type && locate_template( array( 'single-obliby_topics.php' ) ) !== $template ) {
			return plugin_dir_path( __FILE__ ) . 'partials/single-obliby_topics.php';
		}

		return $template;
	}

	/**
	 * Topic add content button data.
	 *
	 * @param    array  $btn_data .
	 * @param    string $active_filter .
	 * @param    array  $topic_filters .
	 * @param    array  $topic_data .
	 * @since    1.0.0
	 */
	public function obliby_topic_add_content_btn( $btn_data, $active_filter, $topic_filters, $topic_data ) {

		if ( empty( $active_filter ) ) {
			return $btn_data;
		}

		if ( empty( $topic_filters ) ) {
			return $btn_data;
		}

		if ( empty( $topic_data ) ) {
			return $btn_data;
		}

		$item_key = array_search( $active_filter, array_column( $topic_filters, 'slug' ), true );
		$user_id  = get_current_user_id();

		if ( false === $item_key ) {
			return $btn_data;
		}

		$btn_url  = '';
		$btn_text = sprintf( '%s %s', __( 'Add New', 'content-topics-hashcode' ), $topic_filters[ $item_key ]['single'] );

		if ( 'posts' === $active_filter ) {

			$buddyboss_plugin_options = get_option( 'buddyboss_sap_plugin_options' );

			if ( ! empty( $buddyboss_plugin_options ) && isset( $buddyboss_plugin_options['create-new-post'] ) ) {
				$btn_url = get_permalink( $buddyboss_plugin_options['create-new-post'] );
			}

			if ( ! empty( $btn_url ) ) {
				$category     = get_category_by_slug( $topic_data['slug'] );
				$obliby_nonce = wp_create_nonce( 'obliby_nonce' );

				if ( ! empty( $category ) && ! is_wp_error( $category ) ) {
					$btn_url = sprintf( '%s?category=%s&nonce=%s', $btn_url, $category->term_id, $obliby_nonce );
				}
			}
		} else {

			$filter_slug = $topic_filters[ $item_key ]['slug'];

			if ( 'videos' === $filter_slug ) {
				$filter_slug = 'photos';
			}

			$profile_url = bbp_get_user_profile_url( $user_id );
			$btn_url     = sprintf( '%s%s', $profile_url, $filter_slug );

			if ( 'photos' === $active_filter || 'videos' === $active_filter ) {

				global $wpdb;
				$albums_table = $wpdb->prefix . 'bp_media_albums';

				$media_result = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM $albums_table WHERE user_id = %d AND title = %s",  $user_id, $topic_data['title'] ) , OBJECT ); //phpcs:ignore

				if ( ! empty( $media_result ) && ! is_wp_error( $media_result ) ) {
					$album_id = $media_result[0]->id;
					$btn_url  = sprintf( '%s/albums/%s/', $btn_url, $album_id );
				}
			}
		}

		$btn_data = array(
			'btn_url'  => $btn_url,
			'btn_text' => $btn_text,
		);

		return $btn_data;
	}
}
