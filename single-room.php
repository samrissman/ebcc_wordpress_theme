<?php
/**
 * Single Room Template
 *
 * Used for individual room CPT entries (Gumnuts, Wombats, Possums).
 * Renders the full room detail page including hero, daily schedule,
 * sibling room navigation, testimonial, and CTA.
 *
 * @package EBCCC
 */

get_header();

while ( have_posts() ) :
  the_post();

  $post_id    = get_the_ID();
  $tag        = ebccc_room_meta( $post_id, '_room_tag' );
  $age        = ebccc_room_meta( $post_id, '_room_age_range' );
  $places     = ebccc_room_meta( $post_id, '_room_places' );
  $ratio      = ebccc_room_meta( $post_id, '_room_ratio' );
  $hero_class = ebccc_room_meta( $post_id, '_room_hero_style' );
  $slug       = get_post_field( 'post_name', $post_id );

  // All rooms for sibling nav
  $all_rooms = ebccc_get_rooms();

  $breadcrumbs = [
    [ 'label' => __( 'Home', 'ebccc' ),     'url' => home_url( '/' ) ],
    [ 'label' => __( 'Programs', 'ebccc' ), 'url' => get_post_type_archive_link( 'room' ) ?: home_url( '/programs/' ) ],
    [ 'label' => get_the_title() ],
  ];

  $chips = array_filter( [ $places, $ratio ] );
  if ( $age ) array_unshift( $chips, $age );

  $contact_url = ebccc_contact_url( 'book-tour' );
  $fees_url    = esc_url( get_permalink( ebccc_get_page_by_template( 'page-fees.php' ) ) ?: home_url( '/fees/' ) );
?>

<main id="main-content">

  <!-- Page Hero -->
  <section class="page-hero<?php echo $hero_class ? ' ' . sanitize_html_class( $hero_class ) : ''; ?>"
           aria-labelledby="page-heading">
    <div class="room-hero-inner">
      <div>
        <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ebccc' ); ?>">
          <?php foreach ( $breadcrumbs as $i => $crumb ) :
            $is_last = ( $i === array_key_last( $breadcrumbs ) ); ?>
            <?php if ( $is_last ) : ?>
              <span class="breadcrumb-current" aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></span>
            <?php else : ?>
              <a href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['label'] ); ?></a>
              <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <?php endif; ?>
          <?php endforeach; ?>
        </nav>

        <?php if ( $tag ) : ?>
          <div class="page-hero-tag"><?php echo esc_html( $tag ); ?></div>
        <?php endif; ?>

        <h1 id="page-heading"><?php the_title(); ?></h1>

        <?php if ( has_excerpt() ) : ?>
          <p class="page-hero-lead"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
        <?php endif; ?>

        <?php if ( ! empty( $chips ) ) : ?>
          <div class="page-hero-meta">
            <?php foreach ( $chips as $chip ) : ?>
              <span class="page-hero-chip"><?php echo esc_html( $chip ); ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Hero photo -->
      <div class="room-hero-photo">
        <?php if ( has_post_thumbnail() ) : ?>
          <?php the_post_thumbnail( 'ebccc-room-hero', [ 'alt' => '' ] ); ?>
        <?php else : ?>
          <div class="photo-placeholder"
               style="height:100%;background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
              <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
            </svg>
            <span><?php the_title(); ?></span>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Sibling Room Nav -->
  <?php if ( count( $all_rooms ) > 1 ) : ?>
  <div class="content-section content-section--pale" style="padding:var(--space-lg);">
    <div class="content-inner">
      <p style="font-size:var(--fs-sm);font-weight:700;color:var(--text-muted);margin-bottom:var(--space-md);">
        <?php esc_html_e( 'Other rooms:', 'ebccc' ); ?>
      </p>
      <div class="room-siblings">
        <?php foreach ( $all_rooms as $sibling ) :
          $s_age   = ebccc_room_meta( $sibling->ID, '_room_age_range' );
          $s_name  = ebccc_room_meta( $sibling->ID, '_room_sibling_label' ) ?: $sibling->post_title;
          $current = $sibling->ID === $post_id;
        ?>
          <a href="<?php echo esc_url( get_permalink( $sibling->ID ) ); ?>"
             class="room-sibling-link<?php echo $current ? ' is-current' : ''; ?>"
             <?php echo $current ? 'aria-current="page"' : ''; ?>>
            <div>
              <?php if ( $s_age ) : ?><div class="room-sibling-age"><?php echo esc_html( $s_age ); ?></div><?php endif; ?>
              <div class="room-sibling-name"><?php echo esc_html( $s_name ); ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Main Content (from WP editor) -->
  <section class="content-section content-section--white" aria-label="<?php the_title_attribute(); ?>">
    <div class="content-inner">
      <div class="entry-content prose" style="max-width:860px;margin:0 auto;">
        <?php the_content(); ?>
      </div>
    </div>
  </section>

  <!-- CTA Banner -->
  <?php
  ebccc_cta_banner( [
    'heading'         => sprintf( __( 'Book a tour of the %s', 'ebccc' ), get_the_title() ),
    'body'            => __( 'Come and see the space, meet the team, and ask all the questions on your mind. No obligation — just a conversation.', 'ebccc' ),
    'cta_label'       => __( 'Book a Tour →', 'ebccc' ),
    'cta_url'         => $contact_url,
    'secondary_label' => __( 'Fees & Enrolment', 'ebccc' ),
    'secondary_url'   => $fees_url,
  ] );
  ?>

</main>

<?php endwhile; ?>

<?php get_footer();
