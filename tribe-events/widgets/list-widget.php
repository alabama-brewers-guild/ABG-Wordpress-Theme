<?php
/**
 * Events List Widget Template
 * This is the template for the output of the events list widget.
 * All the items are turned on and off through the widget admin.
 * There is currently no default styling, which is needed.
 *
 * This view contains the filters required to create an effective events list widget view.
 *
 * You can recreate an ENTIRELY new events list widget view by doing a template override,
 * and placing a list-widget.php file in a tribe-events/widgets/ directory
 * within your theme directory, which will override the /views/widgets/list-widget.php.
 *
 * You can use any or all filters included in this file or create your own filters in
 * your functions.php. In order to modify or extend a single filter, please see our
 * readme on templates hooks and filters (TO-DO)
 *
 * @version 4.5.13
 * @return string
 *
 * @package TribeEventsCalendar
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_plural = tribe_get_event_label_plural();
$events_label_plural_lowercase = tribe_get_event_label_plural_lowercase();

$posts = tribe_get_list_widget_events();

// Check if any event posts are found.
if ( $posts ) : ?>

	<ol class="tribe-list-widget">
		<?php
		// Setup the post data for each event.
		foreach ( $posts as $post ) :
			setup_postdata( $post );
			?>
			<li class="tribe-events-list-widget-events <?php tribe_events_event_classes() ?>">
				<?php
				
				do_action( 'tribe_events_list_widget_before_the_event_image' );
				$post_thumbnail      = get_the_post_thumbnail( null, array(100,100) );
				?>
				<div class="tribe-event-image">
				<?php
						// not escaped because it contains markup
						echo $post_thumbnail;
				?>
				</div>
				<?php do_action( 'tribe_events_list_widget_after_the_event_image' ); ?>

				<div class="tribe-event-description" style="padding-left: 10px;">
				
				<?php do_action( 'tribe_events_list_widget_before_the_event_title' ); ?>
				<!-- Event Title -->
				<h4 class="tribe-event-title" style="font-weight: bold; font-size: 16px">
					<a href="<?php echo tribe_get_event_meta( get_the_ID(), '_EventURL', true ); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h4>
				<?php do_action( 'tribe_events_list_widget_after_the_event_title' ); ?>
				
				<!-- Event Time -->
				<?php do_action( 'tribe_events_list_widget_before_the_meta' ) ?>
				<div class="tribe-event-duration" style="display: block;">
					<?php echo tribe_events_event_schedule_details(); ?>
				</div>
				<div class="tribe-event-location">
					<?php echo tribe_get_city(); ?>
				</div>
				<?php do_action( 'tribe_events_list_widget_after_the_meta' ) ?>
				
				</div>
				<div style="clear:both">
				</div>
			</li>
		<?php
		endforeach;
		?>
	</ol><!-- .tribe-list-widget -->

<?php
// No events were found.
else : ?>
	<p><?php printf( esc_html__( 'There are no upcoming %s at this time.', 'the-events-calendar' ), $events_label_plural_lowercase ); ?></p>
<?php
endif;
