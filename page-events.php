<?php
/**
 * Page Template: Events
 *
 * Template Name: Events (page-events.php)
 *
 * Displays upcoming and past events from the event CPT.
 * Events are managed via Events → Add New in the WP admin.
 *
 * @package EBCCC
 */

get_header();

$contact_url    = ebccc_contact_url();
$phone          = ebccc_phone( 'display' );
$phone_href     = ebccc_phone( 'href' );

$upcoming = ebccc_get_events( false );
$past     = ebccc_get_events( true );
$past     = array_reverse( array_filter( $past, function( $p ) {
	$date = get_post_meta( $p->ID, '_event_date', true );
	return $date && $date < date( 'Y-m-d' );
} ) );

$category_labels = [
	'committee'  => __( 'Committee meeting', 'ebccc' ),
	'fundraiser' => __( 'Fundraiser', 'ebccc' ),
	'family'     => __( 'Family event', 'ebccc' ),
	'incursion'  => __( 'Incursion / excursion', 'ebccc' ),
	'info'       => __( 'Information night', 'ebccc' ),
	'kinder'     => __( 'Kinder', 'ebccc' ),
	'other'      => __( 'Other', 'ebccc' ),
];
?>

<main id="main-content">

<?php ebccc_page_hero( [
  'eyebrow'     => __( 'What\'s on', 'ebccc' ),
  'heading'     => __( 'Events & News', 'ebccc' ),
  'lead'        => __( 'Family events, fundraisers, committee meetings, and centre news — everything happening at EBCCC.', 'ebccc' ),
  'breadcrumbs' => [
    [ 'label' => __( 'Home', 'ebccc' ), 'url' => home_url( '/' ) ],
    [ 'label' => __( 'Events', 'ebccc' ) ],
  ],
] ); ?>

<div class="events-page">
  <div class="events-container">

    <!-- Upcoming events -->
    <section aria-labelledby="upcoming-heading">
      <h2 id="upcoming-heading" class="events-section-title">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <?php esc_html_e( 'Upcoming events', 'ebccc' ); ?>
      </h2>

      <?php if ( ! empty( $upcoming ) ) : ?>
        <div class="events-list">
          <?php foreach ( $upcoming as $event ) :
            $date       = get_post_meta( $event->ID, '_event_date',       true );
            $time_start = get_post_meta( $event->ID, '_event_time_start', true );
            $time_end   = get_post_meta( $event->ID, '_event_time_end',   true );
            $location   = get_post_meta( $event->ID, '_event_location',   true );
            $category   = get_post_meta( $event->ID, '_event_category',   true );
            $cost       = get_post_meta( $event->ID, '_event_cost',       true );
            $reg_url    = get_post_meta( $event->ID, '_event_reg_url',    true );
            $featured   = get_post_meta( $event->ID, '_event_featured',   true ) === '1';
            $cancelled  = get_post_meta( $event->ID, '_event_cancelled',  true ) === '1';
            $cat_label  = isset( $category_labels[ $category ] ) ? $category_labels[ $category ] : '';
            $date_disp  = ebccc_event_date_display( $date );
            $time_disp  = ebccc_event_time_display( $time_start, $time_end );
            $permalink  = get_permalink( $event->ID );

            // Use WP excerpt (set in editor) or auto-generate from content — max 30 words
            $excerpt = $event->post_excerpt
              ? wp_trim_words( $event->post_excerpt, 30, '…' )
              : wp_trim_words( wp_strip_all_tags( $event->post_content ), 30, '…' );

            $has_more = $event->post_content && str_word_count( wp_strip_all_tags( $event->post_content ) ) > 30;
          ?>
          <article class="event-row<?php echo $featured ? ' event-row--featured' : ''; ?><?php echo $cancelled ? ' event-row--cancelled' : ''; ?>"
                   aria-labelledby="event-title-<?php echo $event->ID; ?>">

            <div class="event-row-aside">
              <?php if ( $date ) :
                $ts = strtotime( $date );
              ?>
              <div class="event-date-block" aria-label="<?php echo esc_attr( $date_disp ); ?>">
                <span class="event-day-name"><?php echo esc_html( $ts ? date_i18n( 'D', $ts ) : '' ); ?></span>
                <span class="event-day-num"><?php echo esc_html( $ts ? date_i18n( 'j', $ts ) : '' ); ?></span>
                <span class="event-month"><?php echo esc_html( $ts ? date_i18n( 'M Y', $ts ) : '' ); ?></span>
              </div>
              <?php endif; ?>
            </div>

            <div class="event-row-body">
              <div class="event-row-header">
                <?php if ( $cat_label ) : ?>
                  <span class="event-category-pill"><?php echo esc_html( $cat_label ); ?></span>
                <?php endif; ?>
                <?php if ( $cancelled ) : ?>
                  <span class="event-cancelled-pill"><?php esc_html_e( 'Cancelled', 'ebccc' ); ?></span>
                <?php endif; ?>
              </div>

              <h3 class="event-row-title" id="event-title-<?php echo $event->ID; ?>">
                <?php if ( $has_more && $permalink ) : ?>
                  <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $event->post_title ); ?></a>
                <?php else : ?>
                  <?php echo esc_html( $event->post_title ); ?>
                <?php endif; ?>
              </h3>

              <div class="event-meta">
                <?php if ( $time_disp ) : ?>
                  <span class="event-meta-item">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <?php echo esc_html( $time_disp ); ?>
                  </span>
                <?php endif; ?>
                <?php if ( $location ) : ?>
                  <span class="event-meta-item">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?php echo esc_html( $location ); ?>
                  </span>
                <?php endif; ?>
                <?php if ( $cost ) : ?>
                  <span class="event-meta-item">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    <?php echo esc_html( $cost ); ?>
                  </span>
                <?php endif; ?>
              </div>

              <?php if ( $excerpt ) : ?>
                <p class="event-row-excerpt"><?php echo esc_html( $excerpt ); ?></p>
              <?php endif; ?>

              <div class="event-row-actions">
                <?php if ( $has_more && $permalink ) : ?>
                  <a href="<?php echo esc_url( $permalink ); ?>" class="event-read-more">
                    <?php esc_html_e( 'Read more →', 'ebccc' ); ?>
                  </a>
                <?php endif; ?>
                <?php if ( $reg_url && ! $cancelled ) : ?>
                  <a href="<?php echo esc_url( $reg_url ); ?>"
                     class="btn-event-register"
                     target="_blank" rel="noopener noreferrer">
                    <?php esc_html_e( 'Register →', 'ebccc' ); ?>
                  </a>
                <?php endif; ?>
              </div>
            </div>

          </article>
          <?php endforeach; ?>
        </div>

      <?php else : ?>
        <div class="events-empty">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
          <p><?php esc_html_e( 'No upcoming events at the moment — check back soon.', 'ebccc' ); ?></p>
          <p><?php printf(
            esc_html__( 'Call us on %s to hear about what\'s coming up.', 'ebccc' ),
            '<a href="' . esc_attr( $phone_href ) . '">' . esc_html( $phone ) . '</a>'
          ); ?></p>
        </div>
      <?php endif; ?>
    </section>

    <!-- Past events -->
    <?php if ( ! empty( $past ) ) : ?>
    <section aria-labelledby="past-heading" class="events-past-section">
      <h2 id="past-heading" class="events-section-title events-section-title--muted">
        <?php esc_html_e( 'Past events', 'ebccc' ); ?>
      </h2>
      <div class="events-past-list">
        <?php foreach ( $past as $event ) :
          $date      = get_post_meta( $event->ID, '_event_date',     true );
          $category  = get_post_meta( $event->ID, '_event_category', true );
          $cat_label = isset( $category_labels[ $category ] ) ? $category_labels[ $category ] : '';
          $date_disp = ebccc_event_date_display( $date );
        ?>
        <div class="event-past-item">
          <span class="event-past-date"><?php echo esc_html( $date_disp ); ?></span>
          <span class="event-past-title"><?php echo esc_html( $event->post_title ); ?></span>
          <?php if ( $cat_label ) : ?>
            <span class="event-past-cat"><?php echo esc_html( $cat_label ); ?></span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

  </div>
</div>

<?php
ebccc_cta_banner( [
  'heading'         => __( 'Want to get involved?', 'ebccc' ),
  'body'            => __( 'EBCCC is a volunteer-run centre. From fundraising to committee membership — there are many ways to be part of the community.', 'ebccc' ),
  'cta_label'       => __( 'Contact us →', 'ebccc' ),
  'cta_url'         => $contact_url,
  'secondary_label' => sprintf( __( 'Call us: %s', 'ebccc' ), $phone ),
  'secondary_url'   => $phone_href,
] );

get_footer();
