<?php
/**
 * Page Template: About
 *
 * Template Name: About Page
 * @package EBCCC
 */
get_header();

$contact_url = ebccc_contact_url( 'book-tour' );
$phone_href  = ebccc_phone( 'href' );
$phone       = ebccc_phone( 'display' );
?>

<main id="main-content">

  <?php
  ebccc_page_hero( [
    'tag'         => __( 'Est. 30+ Years · Not-for-profit', 'ebccc' ),
    'heading'     => __( 'A community that cares — for over three decades', 'ebccc' ),
    'lead'        => __( "East Bentleigh Child Care Centre has been a cornerstone of the local community since the early 1990s. We're not-for-profit, volunteer committee–run, and proudly independent — every dollar goes back into the children and the centre.", 'ebccc' ),
    'chips'       => [ __( 'Volunteer committee', 'ebccc' ), __( 'Not-for-profit', 'ebccc' ), __( 'Kinder Tick certified', 'ebccc' ) ],
    'breadcrumbs' => [
      [ 'label' => __( 'Home', 'ebccc' ), 'url' => home_url( '/' ) ],
      [ 'label' => __( 'About Us', 'ebccc' ) ],
    ],
  ] );
  ?>

  <!-- Page content from WP editor -->
  <section class="content-section content-section--white content-section--tight-bottom">
    <div class="content-inner">
      <?php while ( have_posts() ) : the_post(); ?>
        <div class="entry-content prose" style="max-width:860px;margin:0 auto;">
          <?php the_content(); ?>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- Staff Grid (from CPT) -->
  <?php
  $staff = get_posts( [ 'post_type' => 'staff_member', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ] );
  if ( ! empty( $staff ) ) : ?>
  <section class="content-section content-section--white content-section--tight-top" aria-labelledby="staff-heading">
    <div class="content-inner">
      <div class="section-header" style="text-align:center;max-width:640px;margin:0 auto var(--space-2xl);">
        <p class="section-eyebrow"><?php esc_html_e( 'Our Team', 'ebccc' ); ?></p>
        <h2 id="staff-heading" class="section-heading"><?php esc_html_e( 'Educators who know your child by name', 'ebccc' ); ?></h2>
        <p class="section-lead"><?php esc_html_e( 'Our team is our greatest asset. Many have been with EBCCC for years — some for over a decade.', 'ebccc' ); ?></p>
      </div>
      <div class="staff-grid">
        <?php foreach ( $staff as $member ) :
          $role      = get_post_meta( $member->ID, '_staff_role',   true );
          $tenure    = get_post_meta( $member->ID, '_staff_tenure', true );
          $has_bio   = ! empty( $member->post_content );
          $permalink = get_permalink( $member->ID );
        ?>
          <div class="staff-card">
            <?php if ( $permalink ) : ?>
            <a href="<?php echo esc_url( $permalink ); ?>"
               class="staff-card-photo-link"
               tabindex="-1" aria-hidden="true">
            <?php endif; ?>
              <div class="staff-photo">
                <?php if ( has_post_thumbnail( $member->ID ) ) : ?>
                  <?php echo get_the_post_thumbnail( $member->ID, 'ebccc-staff', [ 'alt' => '' ] ); ?>
                <?php else : ?>
                  <div class="photo-placeholder" style="height:200px;border-radius:0;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                  </div>
                <?php endif; ?>
              </div>
            <?php if ( $permalink ) : ?>
            </a>
            <?php endif; ?>
            <div class="staff-info">
              <span class="staff-name"><?php echo esc_html( $member->post_title ); ?></span>
              <?php if ( $role ) : ?>
                <span class="staff-role"><?php echo esc_html( $role ); ?></span>
              <?php endif; ?>
              <?php if ( $has_bio && $permalink ) : ?>
                <a href="<?php echo esc_url( $permalink ); ?>"
                   class="staff-bio-link"
                   aria-label="<?php printf( esc_attr__( 'Read %s\'s profile', 'ebccc' ), $member->post_title ); ?>">
                  <?php printf( esc_html__( 'Meet %s →', 'ebccc' ), esc_html( explode( ' ', $member->post_title )[0] ) ); ?>
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
    'heading'         => __( 'Come and see for yourself', 'ebccc' ),
    'body'            => __( "A tour is the best way to understand what makes EBCCC special. Walk through our rooms, meet our educators, and ask all the questions that are on your mind.", 'ebccc' ),
    'cta_label'       => __( 'Book a Tour →', 'ebccc' ),
    'cta_url'         => $contact_url,
    'secondary_label' => sprintf( __( 'Call %s', 'ebccc' ), $phone ),
    'secondary_url'   => $phone_href,
  ] );
  ?>

</main>

<?php get_footer();
