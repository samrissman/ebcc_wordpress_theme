<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#main-content" class="skip-link"><?php esc_html_e( 'Skip to main content', 'ebccc' ); ?></a>

<?php
$phone        = ebccc_phone( 'display' );
$phone_href   = ebccc_phone( 'href' );
$hours        = esc_html( get_theme_mod( 'ebccc_hours', 'Mon–Fri 7am–6pm' ) );
$address      = ebccc_address();
$storypark    = esc_url( get_theme_mod( 'ebccc_storypark_url', 'https://family.storypark.com' ) );
$contact_url  = ebccc_contact_url( 'book-tour' );
$home_url     = esc_url( home_url( '/' ) );
?>

<!-- ① Utility Bar -->
<div class="utility-bar" role="banner" aria-label="<?php esc_attr_e( 'Contact information', 'ebccc' ); ?>">
  <div class="utility-inner">
    <div class="utility-left">
      <a href="<?php echo esc_attr( $phone_href ); ?>" class="utility-phone"
         aria-label="<?php printf( esc_attr__( 'Call East Bentleigh Child Care Centre on %s', 'ebccc' ), $phone ); ?>">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <?php echo esc_html( $phone ); ?>
      </a>
      <span class="utility-sep" aria-hidden="true">·</span>
      <span class="utility-hours"><?php echo $hours; ?></span>
      <span class="utility-sep" aria-hidden="true">·</span>
      <span class="utility-address">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <?php echo $address; ?>
      </span>
    </div>

  </div>
</div>

<!-- ② Main Header -->
<header class="site-header" id="site-header">
  <div class="header-inner">

    <a href="<?php echo $home_url; ?>" class="logo" aria-label="<?php esc_attr_e( 'East Bentleigh Child Care Centre — Home', 'ebccc' ); ?>">
      <?php if ( has_custom_logo() ) : ?>
        <?php the_custom_logo(); ?>
      <?php else : ?>
        <div class="logo-mark" aria-hidden="true">E</div>
        <div class="logo-text">
          <span class="logo-name"><?php esc_html_e( 'East Bentleigh', 'ebccc' ); ?></span>
          <span class="logo-sub"><?php esc_html_e( 'Child Care Centre', 'ebccc' ); ?></span>
        </div>
      <?php endif; ?>
    </a>

    <nav class="main-nav" aria-label="<?php esc_attr_e( 'Main navigation', 'ebccc' ); ?>" id="main-nav">
      <?php
      wp_nav_menu( [
        'theme_location' => 'primary',
        'menu_class'     => 'nav-list',
        'container'      => false,
        'items_wrap'     => '<ul class="nav-list" role="list">%3$s</ul>',
        'walker'         => new EBCCC_Nav_Walker(),
        'fallback_cb'    => 'ebccc_primary_nav_fallback',
      ] );
      ?>
    </nav>

    <a href="<?php echo $contact_url; ?>" class="btn-cta-header">
      <?php esc_html_e( 'Book a Tour →', 'ebccc' ); ?>
    </a>

    <button class="hamburger" id="hamburger"
            aria-expanded="false"
            aria-controls="mobile-drawer"
            aria-label="<?php esc_attr_e( 'Open navigation menu', 'ebccc' ); ?>">
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
    </button>

  </div>
</header>

<!-- Mobile Drawer -->
<div class="mobile-drawer" id="mobile-drawer" aria-hidden="true" role="dialog" aria-modal="true"
     aria-label="<?php esc_attr_e( 'Navigation menu', 'ebccc' ); ?>">
  <div class="drawer-overlay" id="drawer-overlay"></div>
  <nav class="drawer-nav" aria-label="<?php esc_attr_e( 'Mobile navigation', 'ebccc' ); ?>">
    <button class="drawer-close" id="drawer-close"
            aria-label="<?php esc_attr_e( 'Close navigation menu', 'ebccc' ); ?>">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>

    <?php
    wp_nav_menu( [
      'theme_location' => 'primary',
      'container'      => false,
      'items_wrap'     => '<ul class="drawer-list" role="list">%3$s</ul>',
      'walker'         => new EBCCC_Drawer_Walker(),
      'fallback_cb'    => false,
    ] );
    ?>

    <div class="drawer-ctas">
      <a href="<?php echo $contact_url; ?>" class="btn-cta-full"><?php esc_html_e( 'Book a Tour →', 'ebccc' ); ?></a>
      <a href="<?php echo $storypark; ?>" target="_blank" rel="noopener noreferrer" class="btn-ghost-full"><?php esc_html_e( 'Parent Login', 'ebccc' ); ?></a>
    </div>
    <div class="drawer-contact">
      <a href="<?php echo esc_attr( $phone_href ); ?>"><?php echo esc_html( $phone ); ?></a>
      <span><?php echo $hours; ?></span>
    </div>
  </nav>
</div>

<?php
/**
 * Fallback nav for when no menu is assigned.
 * Lists all published pages with appropriate classes.
 */
function ebccc_primary_nav_fallback(): void {
	echo '<ul class="nav-list" role="list">';
	$pages = get_pages( [ 'sort_column' => 'menu_order' ] );
	foreach ( $pages as $page ) {
		$current = is_page( $page->ID ) ? ' aria-current="page"' : '';
		printf(
			'<li><a href="%s" class="nav-link"%s>%s</a></li>',
			esc_url( get_permalink( $page->ID ) ),
			$current,
			esc_html( $page->post_title )
		);
	}
	echo '</ul>';
}
