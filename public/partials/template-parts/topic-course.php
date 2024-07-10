<?php //phpcs:ignore
/**
 * Topic course template.
 *
 * @link       https://hashcodeab.se/
 * @since      1.0.0
 *
 * @package    BuddyBoss_Theme
 */

?>

<div class="col-12 col-md-6 col-lg-4 col-xl-3 col-xxl mb-4">
	<div class="topic-item topic-course position-relative h-100 d-flex flex-column">
		
		<?php if ( isset( $topic_item['post_thumbnail'] ) && ! empty( $topic_item['post_thumbnail'] ) ) : ?>
			<div class="topic-item-img mb-3 overflow-hidden">
				<a href="<?php echo esc_url( $topic_item['post_url'] ); ?>">
					<img src="<?php echo esc_url( buddypress()->plugin_url ); ?>bp-templates/bp-nouveau/images/placeholder.png" data-src="<?php echo esc_url( $topic_item['post_thumbnail'] ); ?>" alt="<?php echo esc_attr( $topic_item['post_title'] ); ?>" class="mw-100 w-100 lazy" />
				</a>
			</div>
		<?php endif; ?>

		<div class="d-flex flex-grow-1">
			<?php if ( isset( $topic_item['author_avatar'] ) && ! empty( $topic_item['author_avatar'] ) ) : ?>
				<div class="topic-item-author me-2">
					<a href="<?php echo esc_url( $topic_item['author_profile'] ); ?>">
						<img src="<?php echo esc_url( $topic_item['author_avatar'] ); ?>" alt="<?php echo esc_attr( $topic_item['author_name'] ); ?>" class="rounded-circle mw-100 w-100" />
					</a>
				</div>
			<?php endif; ?>
			
			<div class="topic-item-title d-flex flex-column flex-grow-1">
				<a href="<?php echo esc_url( $topic_item['post_url'] ); ?>">
					<h4 class="mb-0"><?php echo wp_kses_post( $topic_item['post_title'] ); ?></h4>
				</a>
				<div class="d-flex flex-column justify-content-end flex-grow-1">
					<div class="d-flex align-items-center mt-2">
						<i class="bb-icon-l buddyboss bb-icon-user" aria-hidden="true"></i>
						<a href="<?php echo esc_url( $topic_item['author_profile'] ); ?>" class="ms-1">
							<span class="text-dark" style="--bs-text-opacity: .8;"><?php echo wp_kses_post( $topic_item['author_name'] ); ?></span>
						</a>
					</div>
					<div class="d-flex align-items-center">
						<span class="text-capitalize">
							<i class="bb-icon-l buddyboss bb-icon-tag" aria-hidden="true"></i>
							<span class="ms-1"><?php echo wp_kses_post( $topic_item['type'] ); ?></span>
						</span>
						<span class="ms-3">
							<i class="bb-icon-l buddyboss bb-icon-calendar" aria-hidden="true"></i>
							<span class="ms-1"><?php echo wp_kses_post( $topic_item['time_diff'] ); ?> <?php esc_html_e( 'ago', 'buddyboss' ); ?></span>
						</span>
					</div>
				</div>
				
			</div>

		</div>

	</div>
</div>