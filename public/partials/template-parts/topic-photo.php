<?php //phpcs:ignore
/**
 * Topic photo template.
 *
 * @link       https://hashcodeab.se/
 * @since      1.0.0
 *
 * @package    BuddyBoss_Theme
 */

?>

<div class="col-12 col-md-6 col-lg-4 col-xl-3 col-xxl mb-4">
	<div class="topic-item topic-photo">
		
		<?php if ( isset( $topic_item['media_url'] ) && ! empty( $topic_item['media_url'] ) ) : ?>
			<div class="topic-item-img mb-3 overflow-hidden d-flex w-100 align-items-center">
				<a href="<?php echo esc_url( $topic_item['activity_url'] ); ?>" class="w-100">
					<img src="<?php echo esc_url( buddypress()->plugin_url ); ?>bp-templates/bp-nouveau/images/placeholder.png" data-src="<?php echo esc_url( $topic_item['media_url'] ); ?>" alt="<?php echo esc_attr( $topic_item['author_name'] ); ?>" class="mw-100 w-100 lazy" />
				</a>	
			</div>

		<?php endif; ?>

		<div class="d-flex mt-auto">
			<?php if ( isset( $topic_item['author_avatar'] ) && ! empty( $topic_item['author_avatar'] ) ) : ?>
				<div class="topic-item-author me-2">
					<a href="<?php echo esc_url( $topic_item['author_profile'] ); ?>">
						<img src="<?php echo esc_url( $topic_item['author_avatar'] ); ?>" alt="<?php echo esc_attr( $topic_item['author_name'] ); ?>" class="rounded-circle mw-100 w-100" />
					</a>
				</div>
			<?php endif; ?>
			
			<div class="topic-item-title flex-grow-1">
				<div class="d-flex align-items-center">
					<i class="bb-icon-l buddyboss bb-icon-user" aria-hidden="true"></i>
					<a href="<?php echo esc_url( $topic_item['author_profile'] ); ?>" class="ms-1">
						<span class="text-dark small-text" style="--bs-text-opacity: .8;"><?php echo wp_kses_post( $topic_item['author_name'] ); ?></span>
					</a>
				</div>
				<div class="d-flex align-items-center">
					<span class="text-capitalize">
						<i class="bb-icon-l buddyboss bb-icon-tag" aria-hidden="true"></i>
						<span class="ms-1 small-text"><?php echo wp_kses_post( $topic_item['type_translated'] ); ?></span>
					</span>
					<span class="ms-3">
						<i class="bb-icon-l buddyboss bb-icon-calendar" aria-hidden="true"></i>
						<?php /* translators: %s: date format */ ?>
						<span class="ms-1 small-text"><?php echo wp_kses_post( sprintf( __( '%s ago', 'content-topics-hashcode' ), $topic_item['time_diff'] ) ); ?></span>
					</span>
				</div>
			</div>

		</div>

	</div>
</div>
