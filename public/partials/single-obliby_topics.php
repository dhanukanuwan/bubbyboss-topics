<?php //phpcs:ignore
/**
 * Single topic template.
 *
 * @link       https://hashcodeab.se/
 * @since      1.0.0
 *
 * @package    BuddyBoss_Theme
 */

get_header( 'topic' );

	while ( have_posts() ) : the_post(); //phpcs:ignore

	$topic_icon    = get_field( 'topic_icon' );
	$active_filter = get_query_var( 'topicfilter' );
	$topic_slug    = get_post_field( 'post_name', get_the_ID() );
	$topic_filters = apply_filters( 'obliby_topic_content_filters', array(), $active_filter );

	$active_filter_array = array();

	if ( ! empty( $topic_filters ) ) {
		$item_key = array_search( $active_filter, array_column( $topic_filters, 'slug' ), true );

		if ( false !== $item_key ) {
			$active_filter_array = $topic_filters[ $item_key ];
		}
	}

	$topic_data = array(
		'slug'  => $topic_slug,
		'title' => get_the_title(),
	);

	$topic_content = apply_filters( 'obliby_topic_content_data', array(), $topic_data, $active_filter_array );

	?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="d-flex pt-4 ps-2 ps-lg-4 align-items-center">

					<?php if ( ! empty( $topic_icon ) ) : ?>
						<span class="pe-2 title-icon"><i class="bb-icon-l buddyboss bb-icon-<?php echo esc_attr( $topic_icon ); ?>" aria-hidden="true"></i></span>
					<?php endif; ?>
					<h1 class="mb-0"><?php the_title(); ?></h1>
				</div>

				<?php
					$topic_heading = get_field( 'topic_heading' );
					$topic_link    = get_permalink();
			?>
				
				<?php if ( ! empty( $topic_heading ) ) : ?>
					<div class="topic-heading-wrap mt-3 ps-2 ps-lg-4">
						<h3><?php echo wp_kses_post( $topic_heading ); ?></h3>
					</div>
				<?php endif; ?>

			</div>
		</div>
		
		<div class="row">
			<div class="col-12">

				<?php if ( ! empty( $topic_filters ) ) : ?>
					<div class="topic-filters">
						<div class="ps-2 ps-lg-4 d-flex">
							
							<a href="<?php echo esc_url( $topic_link ); ?>" class="px-3 pb-2 <?php echo $active_filter ? '' : 'active'; ?>"><?php echo esc_html_e( 'All', 'buddyboss' ); ?></a>

							<?php foreach ( $topic_filters as $topic_filter ) : ?>
								<a href="<?php echo esc_url( $topic_link . $topic_filter['slug'] ); ?>" class="px-3 pb-2 <?php echo esc_attr( $topic_filter['classes'] ); ?>"><?php echo esc_html( $topic_filter['name'] ); ?></a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( ! empty( $topic_content ) ) : ?>
			<div class="topic-content-outer ps-2 ps-lg-4 mt-4">
				<div class="row row-cols-xxl-5 g-2" id="topic-content-row">
					
					<?php foreach ( $topic_content as $topic_item ) : ?>

						<?php include plugin_dir_path( __DIR__ ) . 'partials/template-parts/topic-' . $topic_item['type'] . '.php'; ?>

					<?php endforeach; ?>

				</div>
				
				<div class="row">
					<div class="col-12 mt-4 mb-5">
						<div class="d-flex justify-content-center">
							<button type="button" id="topic_load_more" class="load-more-icon d-flex align-items-center px-4" filter="<?php echo $active_filter ? esc_attr( $active_filter ) : 'all'; ?>" offset="1" numberofposts="5" topicdata="<?php echo esc_attr( wp_slash( wp_json_encode( $topic_data ) ) ); ?>">
								<span class="me-1"><?php esc_html_e( 'Load more', 'buddyboss' ); ?></span>
								<span class="bb-icon-angle-down bb-icon-l"></span>
							</button>
						</div>
					</div>
				</div>

			</div>
		
		<?php else : ?>

			<div class="row">
				<div class="col-12 pt-4">
					<h3><?php esc_html_e( 'No content found.', 'buddyboss' ); ?></h3>
				</div>
			</div>
						
		<?php endif; ?>

	</div>

<?php endwhile; ?>

<?php
get_footer( 'topic' );
