<?php
/**
 * Front Page Template — Homepage
 *
 * @package EBCCC
 */

get_header();

$hero_heading_raw = get_theme_mod( 'ebccc_hero_heading', 'Where children||thrive, learn||and grow.' );
$hero_lead        = esc_html( get_theme_mod( 'ebccc_hero_lead', 'Community-run long day care for children aged 6 weeks to 5 years — open Monday to Friday, 7am to 6pm. All meals included.' ) );
$avail_text       = esc_html( get_theme_mod( 'ebccc_avail_text', 'Places available — Join our wait list today.' ) );
$contact_url      = ebccc_contact_url( 'book-tour' );
$programs_pid     = ebccc_get_page_by_template( 'page-programs.php' );
$programs_url     = $programs_pid ? esc_url( get_permalink( $programs_pid ) ) : esc_url( home_url( '/programs/' ) );

// Build hero heading with line breaks (|| separator or <br>)
$hero_heading = implode( '<br>', array_map( 'esc_html', explode( '||', $hero_heading_raw ) ) );
// Wrap last segment in <em> for styling
$hero_heading = preg_replace( '/^(.*\|\|)(.+?)(\|\|.+)?$/', '', $hero_heading );
// Simpler: first || splits eyebrow, second segment gets <em> wrapping first part
// We'll just output it with a known pattern
$parts = explode( '||', $hero_heading_raw );
if ( count( $parts ) >= 3 ) {
	$hero_h1 = esc_html( $parts[0] ) . '<br><em>' . esc_html( $parts[1] ) . '</em><br>' . esc_html( $parts[2] );
} elseif ( count( $parts ) === 2 ) {
	$hero_h1 = esc_html( $parts[0] ) . '<br><em>' . esc_html( $parts[1] ) . '</em>';
} else {
	$hero_h1 = esc_html( $hero_heading_raw );
}

$rooms = ebccc_get_rooms();
?>

<main id="main-content">

  <!-- ③ Hero -->
  <section class="hero" aria-label="<?php esc_attr_e( 'Welcome to East Bentleigh Child Care Centre', 'ebccc' ); ?>">
    <div class="hero-bg-shape" aria-hidden="true"></div>
    <div class="hero-inner">
      <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'East Bentleigh · Not-for-Profit · Est. 30+ Years', 'ebccc' ); ?></p>
        <h1 class="hero-heading"><?php echo $hero_h1; ?></h1>
        <p class="hero-lead"><?php echo $hero_lead; ?></p>

        <ul class="hero-chips" role="list" aria-label="<?php esc_attr_e( 'Key features', 'ebccc' ); ?>">
          <li class="chip"><?php esc_html_e( 'Not-for-profit', 'ebccc' ); ?></li>
          <li class="chip"><?php esc_html_e( 'Ages 6 wks – 5 yrs', 'ebccc' ); ?></li>
          <li class="chip"><?php esc_html_e( 'All meals included', 'ebccc' ); ?></li>
          <li class="chip"><?php esc_html_e( 'CCS approved', 'ebccc' ); ?></li>
          <li class="chip"><?php esc_html_e( '30+ years', 'ebccc' ); ?></li>
          <li class="chip"><?php esc_html_e( 'From 1 day/week', 'ebccc' ); ?></li>
        </ul>

        <div class="hero-actions">
          <a href="<?php echo $contact_url; ?>" class="btn-primary"><?php esc_html_e( 'Book a Tour →', 'ebccc' ); ?></a>
          <a href="<?php echo $programs_url; ?>" class="btn-secondary"><?php esc_html_e( 'Our programs', 'ebccc' ); ?></a>
        </div>
      </div>

      <div class="hero-image-block" aria-hidden="true">
        <?php
        $hero_img_id    = get_theme_mod( 'ebccc_hero_main_image', 0 );
        $hero_accent_id = get_theme_mod( 'ebccc_hero_accent_image', 0 );
        ?>
        <div class="hero-photo hero-photo-1">
          <?php if ( $hero_img_id ) : ?>
            <?php echo wp_get_attachment_image( $hero_img_id, 'ebccc-hero-main', false, [ 'alt' => '', 'class' => 'photo-large' ] ); ?>
          <?php else : ?>
            <div class="photo-placeholder photo-large">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
              <span><?php esc_html_e( 'Children learning', 'ebccc' ); ?></span>
            </div>
          <?php endif; ?>
        </div>
        <div class="hero-photo hero-photo-2">
          <?php if ( $hero_accent_id ) : ?>
            <?php echo wp_get_attachment_image( $hero_accent_id, 'ebccc-hero-accent', false, [ 'alt' => '', 'class' => 'photo-small' ] ); ?>
          <?php else : ?>
            <div class="photo-placeholder photo-small">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              <span><?php esc_html_e( 'Our centre', 'ebccc' ); ?></span>
            </div>
          <?php endif; ?>
        </div>
        <div class="hero-badge" aria-label="<?php esc_attr_e( '30 plus years in Bentleigh', 'ebccc' ); ?>">
          <span class="badge-number">30+</span>
          <span class="badge-text"><?php esc_html_e( 'Years in', 'ebccc' ); ?><br><?php esc_html_e( 'Bentleigh', 'ebccc' ); ?></span>
        </div>
      </div>
    </div>
  </section>

  <!-- ④ Trust Bar -->
  <div class="trust-bar" role="complementary" aria-label="<?php esc_attr_e( 'Certifications and awards', 'ebccc' ); ?>">
    <div class="trust-inner">
      <div class="trust-item">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="#c8784f" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        <span><?php esc_html_e( 'Highly rated on Google', 'ebccc' ); ?></span>
      </div>
      <div class="trust-divider" aria-hidden="true"></div>
      <div class="trust-item">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <span><?php esc_html_e( '30+ years in Bentleigh', 'ebccc' ); ?></span>
      </div>
      <div class="trust-divider" aria-hidden="true"></div>
      <div class="trust-item">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span><?php esc_html_e( 'State-funded kindergarten', 'ebccc' ); ?></span>
      </div>
      <div class="trust-divider" aria-hidden="true"></div>
      <div class="trust-item">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
        <span><?php esc_html_e( 'Kinder Tick certified', 'ebccc' ); ?></span>
      </div>
      <div class="trust-divider" aria-hidden="true"></div>
      <div class="trust-item">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span><?php esc_html_e( 'Parent-run committee', 'ebccc' ); ?></span>
      </div>
    </div>
  </div>

  <!-- ⑤ Room Cards -->
  <section class="programs-section" id="programs" aria-labelledby="programs-heading">
    <div class="section-inner">
      <div class="section-header">
        <p class="section-eyebrow"><?php esc_html_e( 'Our Programs', 'ebccc' ); ?></p>
        <h2 id="programs-heading" class="section-heading">
          <?php esc_html_e( 'A room for every', 'ebccc' ); ?><br><?php esc_html_e( 'stage of childhood', 'ebccc' ); ?>
        </h2>
        <p class="section-lead">
          <?php esc_html_e( 'Three purpose-designed rooms for children aged 6 weeks to 5 years — each with specialist educators, age-appropriate equipment, and direct access to our natural outdoor spaces.', 'ebccc' ); ?>
        </p>
      </div>

      <div class="room-cards" role="list">
        <?php if ( ! empty( $rooms ) ) : ?>
          <?php foreach ( $rooms as $room ) : ?>
            <?php ebccc_room_card( $room, ebccc_room_meta( $room->ID, '_room_featured' ) === '1' ); ?>
          <?php endforeach; ?>
        <?php else : ?>
          <!-- Fallback static cards when no rooms CPT entries exist -->
          <article class="room-card" role="listitem">
            <div class="room-card-photo" aria-hidden="true"><div class="photo-placeholder room-photo"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></div></div>
            <div class="room-card-body">
              <div class="room-tag"><?php esc_html_e( 'Babies &amp; Toddlers', 'ebccc' ); ?></div>
              <h3 class="room-name"><?php esc_html_e( 'Gumnuts Room', 'ebccc' ); ?></h3>
              <p class="room-age"><?php esc_html_e( '6 weeks – 2 years', 'ebccc' ); ?></p>
              <div class="room-meta"><span class="room-places"><?php esc_html_e( '11 places', 'ebccc' ); ?></span><span class="room-ratio"><?php esc_html_e( '1:4 ratio', 'ebccc' ); ?></span></div>
              <p class="room-desc"><?php esc_html_e( 'A nurturing home-away-from-home for your youngest. Our educators focus on secure attachment, sensory exploration, and warm, consistent care.', 'ebccc' ); ?></p>
              <a href="<?php echo esc_url( home_url( '/programs/gumnuts/' ) ); ?>" class="room-link"><?php esc_html_e( 'Learn more →', 'ebccc' ); ?></a>
            </div>
          </article>
          <article class="room-card room-card--featured" role="listitem">
            <div class="room-card-photo" aria-hidden="true"><div class="photo-placeholder room-photo"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01z"/></svg></div></div>
            <div class="room-card-body">
              <div class="room-tag"><?php esc_html_e( 'Toddlers &amp; Preschool', 'ebccc' ); ?></div>
              <h3 class="room-name"><?php esc_html_e( 'Wombats Room', 'ebccc' ); ?></h3>
              <p class="room-age"><?php esc_html_e( '2 – 4 years', 'ebccc' ); ?></p>
              <div class="room-meta"><span class="room-places"><?php esc_html_e( '15 places', 'ebccc' ); ?></span><span class="room-ratio"><?php esc_html_e( '1:5 ratio', 'ebccc' ); ?></span></div>
              <p class="room-desc"><?php esc_html_e( 'A busy, curious environment built for growing imaginations. Play-based learning, collaborative projects, outdoor exploration, and early numeracy.', 'ebccc' ); ?></p>
              <a href="<?php echo esc_url( home_url( '/programs/wombats/' ) ); ?>" class="room-link"><?php esc_html_e( 'Learn more →', 'ebccc' ); ?></a>
            </div>
          </article>
          <article class="room-card" role="listitem">
            <div class="room-card-photo" aria-hidden="true"><div class="photo-placeholder room-photo"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div></div>
            <div class="room-card-body">
              <div class="room-tag"><?php esc_html_e( 'Kinder-Age', 'ebccc' ); ?></div>
              <h3 class="room-name"><?php esc_html_e( 'Possums Room', 'ebccc' ); ?></h3>
              <p class="room-age"><?php esc_html_e( '4 – 5 years', 'ebccc' ); ?></p>
              <div class="room-meta"><span class="room-places"><?php esc_html_e( '10 places', 'ebccc' ); ?></span><span class="room-ratio"><?php esc_html_e( 'Kinder teacher', 'ebccc' ); ?></span></div>
              <p class="room-desc"><?php esc_html_e( 'State-funded 4-year-old kindergarten within long day care hours — the best of both a structured kinder program and flexible care for working families.', 'ebccc' ); ?></p>
              <a href="<?php echo esc_url( home_url( '/programs/possums/' ) ); ?>" class="room-link"><?php esc_html_e( 'Learn more →', 'ebccc' ); ?></a>
            </div>
          </article>
        <?php endif; ?>
      </div>

      <div class="programs-footer">
        <div class="availability-module" role="status" aria-live="polite">
          <div class="avail-dot"></div>
          <p><strong><?php echo $avail_text; ?></strong>
             <a href="<?php echo $contact_url; ?>"><?php esc_html_e( 'Book a tour →', 'ebccc' ); ?></a></p>
        </div>
      </div>
    </div>
  </section>

  <!-- ⑥ Why EBCCC -->
  <section class="why-section" id="about" aria-labelledby="why-heading">
    <div class="why-bg-texture" aria-hidden="true"></div>
    <div class="section-inner">
      <div class="section-header section-header--light">
        <p class="section-eyebrow section-eyebrow--light"><?php esc_html_e( 'Why EBCCC', 'ebccc' ); ?></p>
        <h2 id="why-heading" class="section-heading section-heading--light"><?php esc_html_e( 'What makes us different', 'ebccc' ); ?></h2>
      </div>
      <div class="why-grid" role="list">
        <?php
        $why_items = [
          [
            'icon' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
            'title' => __( 'Natural outdoor spaces', 'ebccc' ),
            'body'  => __( 'Beautifully landscaped park setting with age-appropriate equipment — yoga on the deck, gardening in the native garden, sandpit play in all weather.', 'ebccc' ),
          ],
          [
            'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            'title' => __( 'Parent-run community', 'ebccc' ),
            'body'  => __( 'Our volunteer committee means profits go back into the centre — not to shareholders. Parents are involved in everything from fundraising to hiring decisions.', 'ebccc' ),
          ],
          [
            'icon'  => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
            'title' => __( 'Flexible from just 1 day/week', 'ebccc' ),
            'body'  => __( "Most centres require two days minimum. We're different — start with one day and adjust as your family's needs change.", 'ebccc' ),
            'accent' => true,
          ],
          [
            'icon' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
            'title' => __( 'Long-tenured, caring staff', 'ebccc' ),
            'body'  => __( 'Low staff turnover means educators who know your child by name, year after year. Consistency is central to early childhood wellbeing.', 'ebccc' ),
          ],
          [
            'icon' => '<path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>',
            'title' => __( 'All meals included — no lunchbox', 'ebccc' ),
            'body'  => __( 'Breakfast, morning tea, lunch, and afternoon tea — all prepared fresh on-site. Sunscreen and all incursion costs are also included in your fees.', 'ebccc' ),
          ],
          [
            'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'title' => __( 'On-site funded kindergarten', 'ebccc' ),
            'body'  => __( 'State-funded 4-year-old kinder delivered within long day care hours — the best of structured education and flexible care for working families.', 'ebccc' ),
          ],
        ];
        foreach ( $why_items as $item ) :
          $accent_class = ! empty( $item['accent'] ) ? ' why-item--accent' : '';
        ?>
        <div class="why-item<?php echo $accent_class; ?>" role="listitem">
          <div class="why-icon" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <?php echo $item['icon']; ?>
            </svg>
          </div>
          <h3><?php echo esc_html( $item['title'] ); ?></h3>
          <p><?php echo esc_html( $item['body'] ); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ⑦ Photo Strip — Carousel -->
  <?php
  $strip_ids = get_theme_mod( 'ebccc_photo_strip_ids', '' );
  $strip_images = $strip_ids ? array_filter( array_map( 'intval', explode( ',', $strip_ids ) ) ) : [];
  $strip_labels = [ __( 'Outdoor play', 'ebccc' ), __( 'Art & creativity', 'ebccc' ), __( 'Garden learning', 'ebccc' ), __( 'Meal time', 'ebccc' ), __( 'Story time', 'ebccc' ), __( 'Sandpit play', 'ebccc' ) ];
  ?>
  <section class="photo-strip" aria-label="<?php esc_attr_e( 'Life at EBCCC — photos of the centre and children', 'ebccc' ); ?>">
    <div class="section-inner">
      <p class="section-eyebrow"><?php esc_html_e( 'Life at EBCCC', 'ebccc' ); ?></p>
    </div>
    <div class="photo-carousel">
      <div class="photo-row" id="photo-carousel-track" role="list" aria-label="<?php esc_attr_e( 'Centre photos', 'ebccc' ); ?>">
        <?php if ( ! empty( $strip_images ) ) : ?>
          <?php foreach ( $strip_images as $i => $img_id ) : ?>
            <div class="photo-strip-item" role="listitem">
              <?php echo wp_get_attachment_image( $img_id, 'ebccc-photo-strip', false, [
                'alt'   => isset( $strip_labels[ $i ] ) ? esc_attr( $strip_labels[ $i ] ) : '',
                'class' => 'strip-photo',
              ] ); ?>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <?php foreach ( $strip_labels as $label ) : ?>
          <div class="photo-strip-item" role="listitem">
            <div class="photo-placeholder strip-photo">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
              <span><?php echo esc_html( $label ); ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="carousel-controls" aria-label="<?php esc_attr_e( 'Photo carousel controls', 'ebccc' ); ?>">
        <button class="carousel-btn" id="carousel-prev" aria-label="<?php esc_attr_e( 'Previous photos', 'ebccc' ); ?>" disabled>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <div class="carousel-dots" id="carousel-dots" role="tablist" aria-label="<?php esc_attr_e( 'Go to slide', 'ebccc' ); ?>"></div>
        <button class="carousel-btn" id="carousel-next" aria-label="<?php esc_attr_e( 'Next photos', 'ebccc' ); ?>">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>
  </section>

  <!-- ⑧ Testimonials -->
  <section class="testimonials-section" aria-labelledby="testimonials-heading">
    <div class="section-inner">
      <p class="section-eyebrow"><?php esc_html_e( 'Families love EBCCC', 'ebccc' ); ?></p>
      <h2 id="testimonials-heading" class="section-heading"><?php esc_html_e( 'What parents say', 'ebccc' ); ?></h2>
      <div class="testimonials-grid">
        <blockquote class="testimonial testimonial--featured">
          <p class="testimonial-quote"><?php echo wp_kses_post( get_theme_mod( 'ebccc_testimonial_1_quote', '"Both of my children have attended EBCCC and we could not be happier. It has a real sense of community — the staff are friendly, caring and just generally amazing with the children. I could not recommend this centre highly enough."' ) ); ?></p>
          <footer class="testimonial-attr">
            <cite><?php echo esc_html( get_theme_mod( 'ebccc_testimonial_1_name', 'Michelle' ) ); ?></cite>
            <span><?php echo esc_html( get_theme_mod( 'ebccc_testimonial_1_role', 'Parent of two' ) ); ?></span>
          </footer>
        </blockquote>
        <div class="testimonial-mini-col">
          <blockquote class="testimonial testimonial--mini">
            <p class="testimonial-quote"><?php echo wp_kses_post( get_theme_mod( 'ebccc_testimonial_2_quote', '"Such a great, cosy community feel. My daughter has thrived here."' ) ); ?></p>
            <footer class="testimonial-attr">
              <cite><?php echo esc_html( get_theme_mod( 'ebccc_testimonial_2_name', 'Nick Kind' ) ); ?></cite>
              <span><?php echo esc_html( get_theme_mod( 'ebccc_testimonial_2_role', 'Parent' ) ); ?></span>
            </footer>
          </blockquote>
          <blockquote class="testimonial testimonial--mini">
            <p class="testimonial-quote"><?php echo wp_kses_post( get_theme_mod( 'ebccc_testimonial_3_quote', '"The staff genuinely care about our son. We couldn\'t be happier with the progress he\'s made."' ) ); ?></p>
            <footer class="testimonial-attr">
              <cite><?php echo esc_html( get_theme_mod( 'ebccc_testimonial_3_name', 'Susie Wilson' ) ); ?></cite>
              <span><?php echo esc_html( get_theme_mod( 'ebccc_testimonial_3_role', 'Parent' ) ); ?></span>
            </footer>
          </blockquote>
        </div>
      </div>
    </div>
  </section>

  <!-- ⑨ Tour Form -->
  <?php get_template_part( 'template-parts/tour-form' ); ?>

</main>

<?php get_footer();
