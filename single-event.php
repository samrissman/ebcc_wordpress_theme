<?php
/**
 * Single Event Template
 *
 * Full event detail page — linked from "Read more →" on the events list.
 * URL: /events/{slug}/
 *
 * @package EBCCC
 */

get_header();

$events_url  = esc_url( get_permalink( ebccc_get_page_by_template( 'page-events.php' ) ) ?: home_url( '/events/' ) );
$contact_url = ebccc_contact_url( 'book-tour' );

$category_labels = [
	'committee'  => __( 'Committee meeting', 'ebccc' ),
	'fundraiser' => __( 'Fundraiser', 'ebccc' ),
	'family'     => __( 'Family event', 'ebccc' ),
	'incursion'  => __( 'Incursion / excursion', 'ebccc' ),
	'info'       => __( 'Information night', 'ebccc' ),
	'kinder'     => __( 'Kinder', 'ebccc' ),
	'other'      => __( 'Other', 'ebccc' ),
];

while ( have_posts() ) :
	the_post();

	$post_id    = get_the_ID();
	$date       = get_post_meta( $post_id, '_event_date',       true );
	$time_start = get_post_meta( $post_id, '_event_time_start', true );
	$time_end   = get_post_meta( $post_id, '_event_time_end',   true );
	$location   = get_post_meta( $post_id, '_event_location',   true );
	$category   = get_post_meta( $post_id, '_event_category',   true );
	$cost       = get_post_meta( $post_id, '_event_cost',       true );
	$reg_url    = get_post_meta( $post_id, '_event_reg_url',    true );
	$cancelled  = get_post_meta( $post_id, '_event_cancelled',  true ) === '1';

	$cat_label = isset( $category_labels[ $category ] ) ? $category_labels[ $category ] : '';
	$date_disp = ebccc_event_date_display( $date );
	$time_disp = ebccc_event_time_display( $time_start, $time_end );
?>

<main id="main-content">

  <?php ebccc_page_hero( [
    'eyebrow'     => $cat_label ?: __( 'Event', 'ebccc' ),
    'heading'     => get_the_title(),
    'lead'        => $date_disp . ( $time_disp ? ' · ' . $time_disp : '' ),
    'breadcrumbs' => [
      [ 'label' => __( 'Home', 'ebccc' ),   'url' => home_url( '/' ) ],
      [ 'label' => __( 'Events', 'ebccc' ), 'url' => $events_url ],
      [ 'label' => get_the_title() ],
    ],
  ] ); ?>

  <section class="content-section content-section--white">
    <div class="content-inner">
      <div class="event-detail-layout">

        <!-- Main content -->
        <div class="event-detail-body">
          <?php if ( $cancelled ) : ?>
            <div class="event-detail-cancelled">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
              <?php esc_html_e( 'This event has been cancelled.', 'ebccc' ); ?>
            </div>
          <?php endif; ?>

          <div class="entry-content event-detail-content">
            <?php the_content(); ?>
          </div>
        </div>

        <!-- Sidebar details -->
        <aside class="event-detail-sidebar" aria-label="<?php esc_attr_e( 'Event details', 'ebccc' ); ?>">
          <div class="event-detail-card">
            <h2 class="event-detail-card-heading"><?php esc_html_e( 'Event details', 'ebccc' ); ?></h2>

            <?php if ( $date ) :
              $ts = strtotime( $date ); ?>
              <div class="event-detail-date-block">
                <span class="event-day-name"><?php echo esc_html( $ts ? date_i18n( 'D', $ts ) : '' ); ?></span>
                <span class="event-day-num"><?php echo esc_html( $ts ? date_i18n( 'j', $ts ) : '' ); ?></span>
                <span class="event-month"><?php echo esc_html( $ts ? date_i18n( 'F Y', $ts ) : '' ); ?></span>
              </div>
            <?php endif; ?>

            <dl class="event-detail-dl">
              <?php if ( $time_disp ) : ?>
                <dt><?php esc_html_e( 'Time', 'ebccc' ); ?></dt>
                <dd><?php echo esc_html( $time_disp ); ?></dd>
              <?php endif; ?>
              <?php if ( $location ) : ?>
                <dt><?php esc_html_e( 'Location', 'ebccc' ); ?></dt>
                <dd><?php echo esc_html( $location ); ?></dd>
              <?php endif; ?>
              <?php if ( $cost ) : ?>
                <dt><?php esc_html_e( 'Cost', 'ebccc' ); ?></dt>
                <dd><?php echo esc_html( $cost ); ?></dd>
              <?php endif; ?>
              <?php if ( $cat_label ) : ?>
                <dt><?php esc_html_e( 'Category', 'ebccc' ); ?></dt>
                <dd><?php echo esc_html( $cat_label ); ?></dd>
              <?php endif; ?>
            </dl>

            <?php if ( $reg_url && ! $cancelled ) : ?>
              <a href="<?php echo esc_url( $reg_url ); ?>"
                 class="btn-primary btn-event-sidebar-register"
                 target="_blank" rel="noopener noreferrer">
                <?php esc_html_e( 'Register for this event →', 'ebccc' ); ?>
              </a>
            <?php endif; ?>

            <a href="<?php echo $events_url; ?>" class="event-detail-back">
              ← <?php esc_html_e( 'All events', 'ebccc' ); ?>
            </a>
          </div>
        </aside>

      </div>
    </div>
  </section>

</main>

<?php endwhile; ?>

<?php get_footer();
