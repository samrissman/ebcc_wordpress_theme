<?php
$phone       = ebccc_phone( 'display' );
$phone_href  = ebccc_phone( 'href' );
$maps_url    = ebccc_maps_url();
$facebook    = esc_url( get_theme_mod( 'ebccc_facebook_url',  'https://facebook.com' ) );
$instagram   = esc_url( get_theme_mod( 'ebccc_instagram_url', 'https://instagram.com' ) );
$storypark   = esc_url( get_theme_mod( 'ebccc_storypark_url', 'https://family.storypark.com' ) );
$abn         = esc_html( get_theme_mod( 'ebccc_footer_abn',  'ABN 98 216 454 135' ) );
$inc         = esc_html( get_theme_mod( 'ebccc_footer_inc',  'Incorporated Assoc. A0006269W' ) );
$copyright   = esc_html( get_theme_mod( 'ebccc_footer_copy', '© 2026 East Bentleigh Childcare Centre Association Inc. All rights reserved.' ) );
$address     = ebccc_address();
$hours_full  = esc_html( get_theme_mod( 'ebccc_hours', 'Mon–Fri 7am–6pm' ) );
?>

<footer class="site-footer" id="find-us" aria-label="<?php esc_attr_e( 'Site footer', 'ebccc' ); ?>">
  <div class="footer-inner">

    <!-- Brand column -->
    <div class="footer-col footer-brand">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo" aria-label="<?php esc_attr_e( 'East Bentleigh Child Care Centre — Home', 'ebccc' ); ?>">
        <?php if ( has_custom_logo() ) : ?>
          <?php the_custom_logo(); ?>
        <?php else : ?>
          <div class="footer-logo-mark" aria-hidden="true">E</div>
          <div>
            <span class="footer-logo-name"><?php esc_html_e( 'East Bentleigh', 'ebccc' ); ?></span>
            <span class="footer-logo-sub"><?php esc_html_e( 'Child Care Centre', 'ebccc' ); ?></span>
          </div>
        <?php endif; ?>
      </a>
      <p class="footer-tagline"><?php esc_html_e( 'Not-for-profit community childcare', 'ebccc' ); ?></p>
      <p class="footer-abn"><?php echo $abn; ?><br><small><?php echo $inc; ?></small></p>

      <div class="footer-social">
        <?php if ( $facebook ) : ?>
          <a href="<?php echo $facebook; ?>" target="_blank" rel="noopener noreferrer"
             aria-label="<?php esc_attr_e( 'East Bentleigh Child Care Centre on Facebook', 'ebccc' ); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
            </svg>
          </a>
        <?php endif; ?>
        <?php if ( $instagram ) : ?>
          <a href="<?php echo $instagram; ?>" target="_blank" rel="noopener noreferrer"
             aria-label="<?php esc_attr_e( 'East Bentleigh Child Care Centre on Instagram', 'ebccc' ); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
              <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
            </svg>
          </a>
        <?php endif; ?>
      </div>

      <div class="footer-trust-badges" aria-label="<?php esc_attr_e( 'Accreditations', 'ebccc' ); ?>">
        <span class="trust-badge"><?php esc_html_e( 'State Kinder Funded', 'ebccc' ); ?></span>
        <span class="trust-badge"><?php esc_html_e( 'Kinder Tick ✓', 'ebccc' ); ?></span>
      </div>
    </div>

    <!-- Footer nav column -->
    <nav class="footer-col footer-nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'ebccc' ); ?>">
      <h3 class="footer-col-heading"><?php esc_html_e( 'Navigate', 'ebccc' ); ?></h3>
      <?php
      wp_nav_menu( [
        'theme_location' => 'footer',
        'container'      => false,
        'items_wrap'     => '<ul role="list">%3$s</ul>',
        'walker'         => new EBCCC_Footer_Walker(),
        'fallback_cb'    => function () use ( $storypark ) {
          // Minimal fallback if no footer menu assigned
          $pages = get_pages( [ 'sort_column' => 'menu_order' ] );
          echo '<ul role="list">';
          foreach ( $pages as $p ) {
            printf( '<li><a href="%s">%s</a></li>', esc_url( get_permalink( $p->ID ) ), esc_html( $p->post_title ) );
          }
          printf( '<li><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></li>', esc_url( $storypark ), esc_html__( 'Parent Login', 'ebccc' ) );
          echo '</ul>';
        },
      ] );
      ?>
    </nav>

    <!-- Address column -->
    <address class="footer-col footer-address">
      <h3 class="footer-col-heading"><?php esc_html_e( 'Find us', 'ebccc' ); ?></h3>
      <p><?php echo nl2br( esc_html( get_theme_mod( 'ebccc_address', '70E East Boundary Rd' . "\n" . 'East Bentleigh VIC 3165' ) ) ); ?></p>
      <a href="<?php echo esc_attr( $phone_href ); ?>" class="footer-phone"><?php echo esc_html( $phone ); ?></a>
      <p class="footer-hours">
        <?php esc_html_e( 'Monday to Friday · 7am–6pm', 'ebccc' ); ?><br>
        <?php esc_html_e( 'Closed public holidays', 'ebccc' ); ?><br>
        <?php esc_html_e( 'Closed 2 weeks over Christmas', 'ebccc' ); ?>
      </p>
      <a href="<?php echo $maps_url; ?>" target="_blank" rel="noopener noreferrer" class="footer-map-link">
        <?php esc_html_e( 'Get directions →', 'ebccc' ); ?>
      </a>
    </address>

  </div><!-- .footer-inner -->

  <div class="footer-bottom">
    <p><?php echo $copyright; ?><br>
    <small><?php esc_html_e( 'Volunteer parent committee · Community run since the 1990s', 'ebccc' ); ?></small></p>
  </div>
</footer>

<button class="back-to-top" id="back-to-top"
        aria-label="<?php esc_attr_e( 'Back to top of page', 'ebccc' ); ?>"
        hidden>
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
    <polyline points="18 15 12 9 6 15"/>
  </svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
