<?php
/**
 * Page Template: Programs
 *
 * Template Name: Programs Page
 * @package EBCCC
 */
get_header();

$rooms       = ebccc_get_rooms();
$contact_url = ebccc_contact_url( 'book-tour' );
$fees_url    = esc_url( get_permalink( ebccc_get_page_by_template( 'page-fees.php' ) ) ?: home_url( '/fees/' ) );
?>

<main id="main-content">

  <?php
  ebccc_page_hero( [
    'tag'         => __( 'Ages 6 weeks – 5 years', 'ebccc' ),
    'heading'     => __( 'A room for every stage of childhood', 'ebccc' ),
    'lead'        => __( 'Three purpose-designed rooms, each staffed by specialist educators, each with its own outdoor space, and each built around how children at that developmental stage actually learn and grow.', 'ebccc' ),
    'chips'       => [ __( 'All meals included', 'ebccc' ), __( 'State-funded kinder', 'ebccc' ), __( 'Play-based EYLF approach', 'ebccc' ) ],
    'breadcrumbs' => [
      [ 'label' => __( 'Home', 'ebccc' ), 'url' => home_url( '/' ) ],
      [ 'label' => __( 'Programs', 'ebccc' ) ],
    ],
  ] );
  ?>

  <!-- Room overview — uses same room-card component as homepage -->
  <section class="content-section content-section--white" aria-labelledby="rooms-heading">
    <div class="content-inner">
      <h2 id="rooms-heading" class="section-heading" style="text-align:center;margin-bottom:var(--space-2xl);">
        <?php esc_html_e( 'Our three rooms', 'ebccc' ); ?>
      </h2>

      <?php if ( ! empty( $rooms ) ) : ?>
        <div class="room-cards" role="list">
          <?php foreach ( $rooms as $room ) : ?>
            <?php ebccc_room_card( $room, ebccc_room_meta( $room->ID, '_room_featured' ) === '1' ); ?>
          <?php endforeach; ?>
        </div>

        <!-- Detailed room sections below cards -->
        <div class="programs-room-detail">
        <?php foreach ( $rooms as $i => $room ) :
          $bg      = ( $i % 2 === 0 ) ? 'content-section--pale' : 'content-section--white';
          $tag     = ebccc_room_meta( $room->ID, '_room_tag' );
          $age     = ebccc_room_meta( $room->ID, '_room_age_range' );
          $places  = ebccc_room_meta( $room->ID, '_room_places' );
          $ratio   = ebccc_room_meta( $room->ID, '_room_ratio' );
          $excerpt = get_the_excerpt( $room );
          $permalink = get_permalink( $room->ID );
          $featured = ebccc_room_meta( $room->ID, '_room_featured' ) === '1';
          $border = $featured ? 'border:2px solid var(--brand-light);' : '';
        ?>
          <article class="content-section <?php echo $bg; ?>"
                   style="<?php echo esc_attr( $border ); ?>border-radius:var(--radius-lg);padding:var(--space-xl) var(--space-2xl);margin-bottom:var(--space-lg);"
                   aria-labelledby="room-heading-<?php echo esc_attr( $room->post_name ); ?>">
            <div class="prose-cols prose-cols--image">
              <?php if ( $i % 2 !== 0 ) : ?>
                <div class="inline-photo">
                  <?php if ( has_post_thumbnail( $room->ID ) ) : ?>
                    <?php echo get_the_post_thumbnail( $room->ID, 'ebccc-room-hero', [ 'alt' => '', 'style' => 'height:280px;width:100%;object-fit:cover;border-radius:var(--radius-md);' ] ); ?>
                  <?php else : ?>
                    <div class="photo-placeholder" style="height:280px;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg><span><?php echo esc_html( $room->post_title ); ?></span></div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

              <div class="prose">
                <?php if ( $tag ) : ?>
                  <div class="room-tag" style="margin-bottom:var(--space-md);"><?php echo esc_html( $tag ); ?></div>
                <?php endif; ?>
                <h3 id="room-heading-<?php echo esc_attr( $room->post_name ); ?>"
                    style="font-size:var(--fs-xl);font-weight:900;color:var(--brand-deep);margin-bottom:var(--space-sm);margin-top:0;">
                  <?php echo esc_html( $room->post_title ); ?>
                </h3>
                <?php if ( $age ) : ?>
                  <p style="font-size:var(--fs-md);font-weight:700;color:var(--brand-mid);margin-bottom:var(--space-md);">
                    <?php echo esc_html( $age ); ?>
                    <?php if ( $places ) echo ' · ' . esc_html( $places ); ?>
                    <?php if ( $ratio )  echo ' · ' . esc_html( $ratio ); ?>
                  </p>
                <?php endif; ?>
                <?php if ( $excerpt ) : ?>
                  <p><?php echo wp_kses_post( $excerpt ); ?></p>
                <?php endif; ?>
                <a href="<?php echo esc_url( $permalink ); ?>" class="btn-primary" style="margin-top:var(--space-lg);display:inline-flex;">
                  <?php printf( esc_html__( 'Explore %s →', 'ebccc' ), esc_html( $room->post_title ) ); ?>
                </a>
              </div>

              <?php if ( $i % 2 === 0 ) : ?>
                <div class="inline-photo">
                  <?php if ( has_post_thumbnail( $room->ID ) ) : ?>
                    <?php echo get_the_post_thumbnail( $room->ID, 'ebccc-room-hero', [ 'alt' => '', 'style' => 'height:280px;width:100%;object-fit:cover;border-radius:var(--radius-md);' ] ); ?>
                  <?php else : ?>
                    <div class="photo-placeholder" style="height:280px;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg><span><?php echo esc_html( $room->post_title ); ?></span></div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
        </div>

      <?php else : ?>
        <p class="section-lead" style="text-align:center;">
          <?php esc_html_e( 'Room details coming soon. Please call us on ', 'ebccc' ); ?>
          <a href="<?php echo esc_attr( ebccc_phone( 'href' ) ); ?>"><?php echo ebccc_phone(); ?></a>.
        </p>
      <?php endif; ?>

      <!-- Editor content (nutrition section etc.) -->
      <?php while ( have_posts() ) : the_post(); ?>
        <?php if ( get_the_content() ) : ?>
          <div class="entry-content" style="margin-top:var(--space-2xl);">
            <?php the_content(); ?>
          </div>
        <?php endif; ?>
      <?php endwhile; ?>
    </div>
  </section>

  <?php
  ebccc_cta_banner( [
    'heading'         => __( "Ready to find the right room for your child?", 'ebccc' ),
    'body'            => __( "Book a tour and we'll walk you through each room, introduce you to the educators, and answer any questions about how the program works for your child's age.", 'ebccc' ),
    'cta_label'       => __( 'Book a Tour →', 'ebccc' ),
    'cta_url'         => $contact_url,
    'secondary_label' => __( 'Fees & Enrolment', 'ebccc' ),
    'secondary_url'   => $fees_url,
  ] );
  ?>

</main>

<?php get_footer();
