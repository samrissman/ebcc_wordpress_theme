<?php
/**
 * Single Staff Member — Profile Page
 *
 * Template for individual staff/educator profiles.
 * URL: /team/{slug}/
 *
 * Displays: photo, name, role, tenure, room, and full bio from the editor.
 * Linked from "Meet [Name] →" on the About page staff grid.
 *
 * @package EBCCC
 */

get_header();

$contact_url = ebccc_contact_url( 'book-tour' );
$about_url   = esc_url( get_permalink( ebccc_get_page_by_template( 'page-about.php' ) ) ?: home_url( '/about/' ) );

while ( have_posts() ) :
	the_post();

	$post_id = get_the_ID();
	$role    = get_post_meta( $post_id, '_staff_role',   true );
	$tenure  = get_post_meta( $post_id, '_staff_tenure', true );
	$room    = get_post_meta( $post_id, '_staff_room',   true );
	$name    = get_the_title();
	$has_bio = ! empty( get_the_content() );

	// Room label map
	$room_labels = [
		'gumnuts' => __( 'Gumnuts Room', 'ebccc' ),
		'wombats' => __( 'Wombats Room', 'ebccc' ),
		'possums' => __( 'Possums Room', 'ebccc' ),
	];
	$room_label = isset( $room_labels[ $room ] ) ? $room_labels[ $room ] : '';
	$room_url   = $room ? esc_url( home_url( '/programs/' . $room . '/' ) ) : '';
?>

<main id="main-content">

  <!-- Profile hero -->
  <section class="staff-profile-hero" aria-labelledby="profile-name">
    <div class="staff-profile-hero-inner">

      <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ebccc' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'ebccc' ); ?></a>
        <span class="breadcrumb-sep" aria-hidden="true">›</span>
        <a href="<?php echo $about_url; ?>"><?php esc_html_e( 'About Us', 'ebccc' ); ?></a>
        <span class="breadcrumb-sep" aria-hidden="true">›</span>
        <span class="breadcrumb-current" aria-current="page"><?php echo esc_html( $name ); ?></span>
      </nav>

      <div class="staff-profile-layout">

        <!-- Photo -->
        <div class="staff-profile-photo" aria-hidden="true">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'ebccc-staff', [ 'alt' => '' ] ); ?>
          <?php else : ?>
            <div class="photo-placeholder staff-profile-placeholder">
              <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true">
                <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
              </svg>
            </div>
          <?php endif; ?>
        </div>

        <!-- Identity -->
        <div class="staff-profile-identity">
          <?php if ( $role ) : ?>
            <div class="page-hero-tag"><?php echo esc_html( $role ); ?></div>
          <?php endif; ?>

          <h1 id="profile-name"><?php echo esc_html( $name ); ?></h1>

          <div class="staff-profile-meta">
            <?php if ( $tenure ) : ?>
              <span class="staff-profile-meta-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                <?php echo esc_html( $tenure ); ?>
              </span>
            <?php endif; ?>
            <?php if ( $room_label ) : ?>
              <span class="staff-profile-meta-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                  <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <?php if ( $room_url ) : ?>
                  <a href="<?php echo $room_url; ?>"><?php echo esc_html( $room_label ); ?></a>
                <?php else : ?>
                  <?php echo esc_html( $room_label ); ?>
                <?php endif; ?>
              </span>
            <?php endif; ?>
          </div>
        </div>

      </div><!-- .staff-profile-layout -->
    </div><!-- .staff-profile-hero-inner -->
  </section>

  <!-- Bio -->
  <?php if ( $has_bio ) : ?>
  <section class="content-section content-section--white" aria-label="<?php printf( esc_attr__( 'About %s', 'ebccc' ), $name ); ?>">
    <div class="content-inner">
      <div class="staff-profile-bio entry-content prose">
        <?php the_content(); ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- Back link + other staff -->
  <?php
  // Sibling staff — others in same room, or all staff
  $siblings = get_posts( [
    'post_type'      => 'staff_member',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
    'exclude'        => [ $post_id ],
  ] );
  // Filter to same room if possible; fall back to all
  $same_room = $room ? array_filter( $siblings, fn( $s ) => get_post_meta( $s->ID, '_staff_room', true ) === $room ) : [];
  $show_siblings = ! empty( $same_room ) ? array_values( $same_room ) : array_slice( $siblings, 0, 4 );
  ?>

  <?php if ( ! empty( $show_siblings ) ) : ?>
  <section class="content-section content-section--pale" aria-labelledby="other-educators-heading">
    <div class="content-inner">
      <h2 id="other-educators-heading" class="section-heading" style="margin-bottom:var(--space-xl);">
        <?php
        if ( ! empty( $same_room ) && $room_label ) {
          printf( esc_html__( 'More educators in the %s', 'ebccc' ), esc_html( $room_label ) );
        } else {
          esc_html_e( 'Other educators', 'ebccc' );
        }
        ?>
      </h2>
      <div class="staff-grid">
        <?php foreach ( $show_siblings as $sibling ) :
          $s_role    = get_post_meta( $sibling->ID, '_staff_role', true );
          $s_has_bio = ! empty( $sibling->post_content );
          $s_url     = get_permalink( $sibling->ID );
        ?>
          <div class="staff-card">
            <?php if ( $s_url ) : ?>
            <a href="<?php echo esc_url( $s_url ); ?>" class="staff-card-photo-link" tabindex="-1" aria-hidden="true">
            <?php endif; ?>
              <div class="staff-photo">
                <?php if ( has_post_thumbnail( $sibling->ID ) ) : ?>
                  <?php echo get_the_post_thumbnail( $sibling->ID, 'ebccc-staff', [ 'alt' => '' ] ); ?>
                <?php else : ?>
                  <div class="photo-placeholder" style="height:200px;border-radius:0;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                  </div>
                <?php endif; ?>
              </div>
            <?php if ( $s_url ) : ?>
            </a>
            <?php endif; ?>
            <div class="staff-info">
              <span class="staff-name"><?php echo esc_html( $sibling->post_title ); ?></span>
              <?php if ( $s_role ) : ?>
                <span class="staff-role"><?php echo esc_html( $s_role ); ?></span>
              <?php endif; ?>
              <?php if ( $s_has_bio && $s_url ) : ?>
                <a href="<?php echo esc_url( $s_url ); ?>"
                   class="staff-bio-link"
                   aria-label="<?php printf( esc_attr__( 'Read %s\'s profile', 'ebccc' ), $sibling->post_title ); ?>">
                  <?php printf( esc_html__( 'Meet %s →', 'ebccc' ), esc_html( explode( ' ', $sibling->post_title )[0] ) ); ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php
  ebccc_cta_banner( [
    'heading'         => __( 'Come and meet the team in person', 'ebccc' ),
    'body'            => __( 'Book a centre tour and we\'ll introduce you to the educators who will care for your child.', 'ebccc' ),
    'cta_label'       => __( 'Book a Tour →', 'ebccc' ),
    'cta_url'         => $contact_url,
    'secondary_label' => __( 'Back to About Us', 'ebccc' ),
    'secondary_url'   => $about_url,
  ] );
  ?>

</main>

<?php endwhile; ?>

<?php get_footer();
