<?php
/**
 * View: Month View - Multiday Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/views/v2/month/calendar-body/day/multiday-events/multiday-event.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.9.11
 *
 * @var string $day_date        The `Y-m-d` date of the day currently being displayed.
 * @var string $today_date      Today's date in the `Y-m-d` format.
 * @var string $grid_start_date The `Y-m-d` date of the day where the grid starts.
 * @var WP_Post $event          An event post object with event-specific properties added from the the `tribe_get_event`
 *                              function.
 * @var bool $is_start_of_week  Whether the current grid day being rendered is the first day of the week or not.
 *
 * @see tribe_get_event() For the format of the event object and its properties.
 *
 */

use Tribe__Date_Utils as Dates;

/*
 * To keep the calendar accessible, in the context of a week, we'll print the event only on either its first day
 * or the first day of the week.
 */
$should_display = $event->dates->start_display->format( 'Y-m-d' ) === $day_date
                  || $is_start_of_week;

$classes = get_post_class( [ 'tribe-events-calendar-month__multiday-event' ], $event->ID );

// @todo @fe move class configuration to template tag

if ( $event->featured ) {
	$classes[] = 'tribe-events-calendar-month__multiday-event--featured';
}

// If the event started on a previous month.
$started_previous_month = $event->dates->start_display->format( 'Y-m-d' ) < $grid_start_date;

// We display the tooltip only if there's excpert or cost or it has a thumbnail.
$display_tooltip        = ! empty( $event->excerpt ) || ! empty( $event->cost ) || $event->thumbnail->exists;
$is_first_appearance    = ( $event->dates->start_display->format( 'Y-m-d' ) === $day_date )
                          || ( $started_previous_month && $grid_start_date === $day_date );
// We print the tooltip contents if it's the first appearrance and we should display it.
$should_print_tooltip   = $is_first_appearance && $display_tooltip;

// If it starts today and this week, let's add the left border and set the width.
if ( $should_display ) {

	/*
	 * The "duration" here is how many days the event will take this week, not in total.
	 * The two values might be the same but they will differ for events that last more than one week.
	 */
	$classes[] = 'tribe-events-calendar-month__multiday-event--width-' . $event->this_week_duration;
	$classes[] = 'tribe-events-calendar-month__multiday-event--display';

	// If it ends this week, let's add the start class (left border).
	if ( $event->starts_this_week ) {
		$classes[] = 'tribe-events-calendar-month__multiday-event--start';
	}

	// If it ends this week, let's add the end class (right border).
	if ( $event->ends_this_week ) {
		$classes[] = 'tribe-events-calendar-month__multiday-event--end';
	}

	if ( $event->dates->end->format( 'Y-m-d' ) < $today_date ) {
		$classes[] = 'tribe-events-calendar-month__multiday-event--past';
	}
}

?>
<div class="tribe-events-calendar-month__multiday-event-wrapper">
	<article <?php tribe_classes( $classes ); ?> data-event-id="<?php echo esc_attr( $event->ID ); ?>">
		<div class="tribe-events-calendar-month__multiday-event-hidden">
			<time
				datetime="<?php echo esc_attr( $event->dates->start->format( Dates::DBDATEFORMAT ) ); ?>"
				class="tribe-common-a11y-visual-hide"
			>
				<?php echo esc_attr( $event->dates->start->format( Dates::DBDATEFORMAT ) ); ?>
			</time>
			<a
				href="<?php echo esc_url( $event->permalink ); ?>"
				class="tribe-events-calendar-month__multiday-event-hidden-link"
				<?php if ( $display_tooltip ) : ?>
					data-js="tribe-events-tooltip"
					data-tooltip-content="#tribe-events-tooltip-content-<?php echo esc_attr( $event->ID ); ?>"
					aria-describedby="tribe-events-tooltip-content-<?php echo esc_attr( $event->ID ); ?>"
				<?php endif; ?>
			>
				<?php if ( $event->featured ) : ?>
					<em
						class="tribe-events-calendar-month__multiday-event-hidden-featured-icon tribe-common-svgicon tribe-common-svgicon--featured"
						aria-label="<?php esc_attr_e( 'Featured', 'the-events-calendar' ); ?>"
						title="<?php esc_attr_e( 'Featured', 'the-events-calendar' ); ?>"
					></em>
				<?php endif; ?>
				<h3 class="tribe-events-calendar-month__multiday-event-hidden-title tribe-common-h8">
					<?php echo wp_kses_post( get_the_title( $event->ID ) ); ?>
				</h3>
			</a>
		</div>
		<?php if ( $should_display ) : ?>
			<div class="tribe-events-calendar-month__multiday-event-bar">
				<div class="tribe-events-calendar-month__multiday-event-bar-inner">
					<?php if ( $event->featured ) : ?>
						<em
							class="tribe-events-calendar-month__multiday-event-bar-featured-icon tribe-common-svgicon tribe-common-svgicon--featured"
							aria-label="<?php esc_attr_e( 'Featured', 'the-events-calendar' ); ?>"
							title="<?php esc_attr_e( 'Featured', 'the-events-calendar' ); ?>"
						></em>
					<?php endif; ?>
					<h3 class="tribe-events-calendar-month__multiday-event-bar-title tribe-common-h8">
						<?php echo wp_kses_post( get_the_title( $event->ID ) ); ?>
					</h3>
				</div>
			</div>
			<?php if ( $should_print_tooltip ) : ?>
				<?php $this->template( 'month/calendar-body/day/calendar-events/calendar-event/tooltip', [ 'event' => $event ] ); ?>
			<?php endif; ?>
		<?php endif; ?>
	</article>
</div>
