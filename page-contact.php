<?php
/**
 * Page Template: Contact
 *
 * Template Name: Contact Page
 * @package EBCCC
 */
get_header();

$phone_href = ebccc_phone( 'href' );
$phone      = ebccc_phone( 'display' );
$maps_url   = ebccc_maps_url();
$storypark  = esc_url( get_theme_mod( 'ebccc_storypark_url', 'https://family.storypark.com' ) );
$address    = ebccc_address();
?>

<main id="main-content">

  <?php
  ebccc_page_hero( [
    'tag'         => __( 'Tours · Enquiries · Call us', 'ebccc' ),
    'heading'     => __( "Get in touch — we'd love to meet you", 'ebccc' ),
    'lead'        => __( "Book a centre tour, ask a question, or just call us. We're a small team and we answer the phone. If we miss you, we'll call you back.", 'ebccc' ),
    'chips'       => [
      sprintf( '<a href="%s" style="text-decoration:none;color:inherit;">%s %s</a>',
        esc_attr( $phone_href ),
        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
        esc_html( $phone )
      ),
      __( 'Mon–Fri · 7am–6pm', 'ebccc' ),
    ],
    'class'       => 'page-hero--contact',
    'breadcrumbs' => [
      [ 'label' => __( 'Home', 'ebccc' ), 'url' => home_url( '/' ) ],
      [ 'label' => __( 'Contact', 'ebccc' ) ],
    ],
  ] );
  ?>

  <!-- Tour Form — full width, uses the same dark-green form-section as homepage -->
  <?php get_template_part( 'template-parts/tour-form' ); ?>

  <!-- How to reach us — separate pale section below form -->
  <section class="content-section content-section--pale" aria-labelledby="contact-heading">
    <div class="contact-details-wrap">

      <h2 id="contact-heading" class="section-heading" style="text-align:center;margin-bottom:var(--space-2xl);">
        <?php esc_html_e( 'How to reach us', 'ebccc' ); ?>
      </h2>

      <div class="contact-details-grid">

        <div class="contact-detail">
          <div class="contact-detail-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </div>
          <div class="contact-detail-body">
            <span class="contact-detail-label"><?php esc_html_e( 'Phone', 'ebccc' ); ?></span>
            <span class="contact-detail-value"><a href="<?php echo esc_attr( $phone_href ); ?>"><?php echo esc_html( $phone ); ?></a></span>
            <span class="contact-detail-sub"><?php esc_html_e( 'Monday to Friday, 7am–6pm', 'ebccc' ); ?><br><?php esc_html_e( "If we miss you, we'll call back the same day.", 'ebccc' ); ?></span>
          </div>
        </div>

        <div class="contact-detail">
          <div class="contact-detail-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div class="contact-detail-body">
            <span class="contact-detail-label"><?php esc_html_e( 'Address', 'ebccc' ); ?></span>
            <span class="contact-detail-value"><?php echo $address; ?></span>
            <span class="contact-detail-sub"><?php esc_html_e( 'Parking available on East Boundary Rd.', 'ebccc' ); ?></span>
            <a href="<?php echo $maps_url; ?>" target="_blank" rel="noopener noreferrer"
               style="font-size:var(--fs-sm);font-weight:700;color:var(--brand-mid);margin-top:var(--space-xs);display:inline-block;">
              <?php esc_html_e( 'Get directions →', 'ebccc' ); ?>
            </a>
          </div>
        </div>

        <div class="contact-detail">
          <div class="contact-detail-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <div class="contact-detail-body">
            <span class="contact-detail-label"><?php esc_html_e( 'Operating hours', 'ebccc' ); ?></span>
            <span class="contact-detail-value"><?php esc_html_e( '7:00am – 6:00pm', 'ebccc' ); ?></span>
            <span class="contact-detail-sub">
              <?php esc_html_e( 'Monday to Friday', 'ebccc' ); ?><br>
              <?php esc_html_e( 'Closed public holidays', 'ebccc' ); ?><br>
              <?php esc_html_e( 'Closed approx. 2 weeks over Christmas–New Year', 'ebccc' ); ?>
            </span>
          </div>
        </div>

        <div class="contact-detail">
          <div class="contact-detail-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
          </div>
          <div class="contact-detail-body">
            <span class="contact-detail-label"><?php esc_html_e( 'Parent Login', 'ebccc' ); ?></span>
            <span class="contact-detail-value"><a href="<?php echo $storypark; ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Storypark Family App', 'ebccc' ); ?></a></span>
            <span class="contact-detail-sub"><?php esc_html_e( "Access your child's daily updates and learning stories.", 'ebccc' ); ?></span>
          </div>
        </div>

      </div><!-- .contact-details-grid -->

      <div class="map-embed" aria-label="<?php esc_attr_e( 'Map showing EBCCC location', 'ebccc' ); ?>">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--brand-mid)" stroke-width="1.5" style="margin-bottom:var(--space-sm);" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <p style="margin:0 0 var(--space-sm);"><strong><?php echo $address; ?></strong></p>
        <a href="<?php echo $maps_url; ?>" target="_blank" rel="noopener noreferrer"
           style="font-weight:700;color:var(--brand-mid);">
          <?php esc_html_e( 'Open in Google Maps →', 'ebccc' ); ?>
        </a>
      </div>

    </div><!-- .contact-details-wrap -->
  </section>

  <!-- Editor content (tour FAQs etc.) -->
  <?php while ( have_posts() ) : the_post(); ?>
    <?php if ( get_the_content() ) : ?>
      <section class="content-section content-section--white">
        <div class="content-inner" style="max-width:720px;margin:0 auto;">
          <div class="entry-content"><?php the_content(); ?></div>
        </div>
      </section>
    <?php endif; ?>
  <?php endwhile; ?>

</main>

<?php get_footer();
