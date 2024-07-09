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

		if ( 'pictures' === $filter_slug ) {
			$topic_images = $this->obliby_get_topic_media( $topic_title, 'photo' );

			return array_merge( $data, $topic_images );
		}

		if ( 'videos' === $filter_slug ) {
			$topic_videos = $this->obliby_get_topic_media( $topic_title, 'video' );

			return array_merge( $data, $topic_videos );
		}

		$all_content = $this->obliby_get_all_content_types( $topic );

		if ( ! empty( $all_content ) && ! is_wp_error( $all_content ) ) {

			$content_data = array();

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

		if ( ! empty( $content_data ) ) {
			$data = array_merge( $data, $content_data );
		}

		return $data;
	}

	/**
	 * Get posts related to given topic.
	 *
	 * @since    1.0.0
	 * @param string $topic .
	 */
	private function obliby_get_topic_posts( $topic ) {

		$topic_posts = array();

		$category = get_category_by_slug( $topic );

		if ( empty( $category ) || is_wp_error( $category ) ) {
			return $topic_posts;
		}

		$post_ids = get_posts(
			array(
				'post_type'   => 'post',
				'numberposts' => 50,
				'category'    => $category->term_id,
				'post_status' => 'publish',
				'fields'      => 'ids',
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
	 */
	private function obliby_get_topic_courses( $topic ) {

		$topic_courses = array();

		$category = get_term_by( 'slug', $topic, 'course-category' );

		if ( empty( $category ) || is_wp_error( $category ) ) {
			return $topic_courses;
		}

		$post_ids = get_posts(
			array(
				'post_type'   => 'courses',
				'numberposts' => 50,
				'post_status' => 'publish',
				'fields'      => 'ids',
				'tax_query'   => array( //phpcs:ignore
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
	private function obliby_get_all_content_types( $topic_data, $offset = 0 ) {

		global $wpdb;

		$topic_slug   = $topic_data['slug'];
		$topic_title  = $topic_data['title'];
		$albums_table = $wpdb->prefix . 'bp_media_albums';
		$media_table  = $wpdb->prefix . 'bp_media';

		$topic_posts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT *
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
				GROUP BY posts.ID LIMIT 5 OFFSET %d",
				$topic_slug,
				$topic_slug,
				$topic_title,
				'public',
				$offset
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
	 */
	private function obliby_get_topic_media( $topic, $type = '' ) {

		global $wpdb;
		$albums_table = $wpdb->prefix . 'bp_media_albums';
		$media_table  = $wpdb->prefix . 'bp_media';

		$media_data = array();

		$media_result = $wpdb->get_results( $wpdb->prepare( "SELECT m.attachment_id AS ID,m.user_id,m.title,m.album_id,m.type,m.activity_id,m.id AS media_id from $media_table AS m LEFT JOIN $albums_table AS a ON m.album_id = a.id WHERE a.title = %s AND a.privacy = %s AND m.type = %s LIMIT 50",  $topic, 'public', $type ) , OBJECT ); //phpcs:ignore

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
}
