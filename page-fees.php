<?php
/**
 * Page Template: Fees & Enrolment
 *
 * Template Name: Fees & Enrolment Page
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
    'tag'         => __( 'CCS Approved · All meals included', 'ebccc' ),
    'heading'     => __( 'Fees & Enrolment', 'ebccc' ),
    'lead'        => __( "We believe quality childcare should be accessible. We're CCS approved, not-for-profit, and include all meals in our daily fee. Call us for our current fee schedule.", 'ebccc' ),
    'chips'       => [ __( 'CCS approved', 'ebccc' ), __( 'Meals included', 'ebccc' ), __( 'From 1 day/week', 'ebccc' ) ],
    'breadcrumbs' => [
      [ 'label' => __( 'Home', 'ebccc' ), 'url' => home_url( '/' ) ],
      [ 'label' => __( 'Fees & Enrolment', 'ebccc' ) ],
    ],
  ] );
  ?>

  <!-- Fee enquiry CTA -->
  <div style="background:var(--surface-cream);border-bottom:1px solid rgba(0,0,0,0.06);padding:var(--space-xl) var(--space-lg);">
    <div style="max-width:var(--max-width);margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-lg);">
      <div>
        <p style="font-size:var(--fs-md);font-weight:800;color:var(--brand-deep);margin-bottom:4px;">
          <?php esc_html_e( 'Want to know our current daily rate?', 'ebccc' ); ?>
        </p>
        <p style="font-size:var(--fs-sm);color:var(--text-muted);">
          <?php esc_html_e( "Our fees are reviewed annually. Call us directly — we'll also work out your estimated CCS entitlement with you.", 'ebccc' ); ?>
        </p>
      </div>
      <div style="display:flex;gap:var(--space-md);flex-wrap:wrap;">
        <a href="<?php echo esc_attr( $phone_href ); ?>" class="btn-primary">
          <?php printf( esc_html__( 'Call %s', 'ebccc' ), esc_html( $phone ) ); ?>
        </a>
        <a href="<?php echo $contact_url; ?>" class="btn-secondary"><?php esc_html_e( 'Book a tour', 'ebccc' ); ?></a>
      </div>
    </div>
  </div>

  <!-- Page editor content (CCS explainer, enrolment steps etc.) -->
  <section class="content-section content-section--white">
    <div class="content-inner">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php if ( get_the_content() ) : ?>
          <div class="entry-content prose" style="max-width:860px;margin:0 auto;">
            <?php the_content(); ?>
          </div>
        <?php else : ?>
          <!-- Default content if editor is empty -->
          <div class="fees-grid" style="max-width:var(--max-width);margin:0 auto;">
            <?php
            $fee_cards = [
              [ 'title' => __( 'Child Care Subsidy', 'ebccc' ),      'body' => __( "We're fully CCS approved. Eligible families receive a government subsidy that significantly reduces your daily fee.", 'ebccc' ) ],
              [ 'title' => __( 'All meals included', 'ebccc' ),       'body' => __( 'No lunchboxes, no extras. Breakfast, morning tea, lunch, and afternoon tea are freshly prepared on-site and included in your daily fee.', 'ebccc' ), 'highlight' => true ],
              [ 'title' => __( 'Flexible from 1 day', 'ebccc' ),      'body' => __( "Start with as little as one day per week and adjust as your family's needs change. No lock-in to a minimum booking.", 'ebccc' ) ],
            ];
            foreach ( $fee_cards as $card ) :
              $hl = ! empty( $card['highlight'] ) ? ' fee-card--highlight' : '';
            ?>
              <div class="fee-card<?php echo $hl; ?>">
                <h3><?php echo esc_html( $card['title'] ); ?></h3>
                <p><?php echo esc_html( $card['body'] ); ?></p>
              </div>
            <?php endforeach; ?>
          </div>
          <!-- Enrolment steps -->
          <div style="max-width:720px;margin:var(--space-2xl) auto 0;">
            <h2 style="text-align:center;font-size:var(--fs-xl);font-weight:900;color:var(--brand-deep);margin-bottom:var(--space-xl);">
              <?php esc_html_e( 'Three simple steps to enrol', 'ebccc' ); ?>
            </h2>
            <ol class="steps-list" role="list">
              <?php
              $steps = [
                [ __( 'Book and attend a centre tour', 'ebccc' ),         __( "Use the form on our Contact page or call us. We'll arrange a time that suits you.", 'ebccc' ) ],
                [ __( 'Join our wait list', 'ebccc' ),                    __( "We'll add your child to our wait list and keep you updated on availability.", 'ebccc' ) ],
                [ __( 'Complete enrolment and orientation', 'ebccc' ),     __( "When a place becomes available we'll work through enrolment forms and arrange orientation visits.", 'ebccc' ) ],
              ];
              foreach ( $steps as $n => $step ) : ?>
                <li class="step">
                  <span class="step-num" aria-hidden="true"><?php echo ( $n + 1 ); ?></span>
                  <div>
                    <strong><?php echo esc_html( $step[0] ); ?></strong>
                    <p><?php echo esc_html( $step[1] ); ?></p>
                  </div>
                </li>
              <?php endforeach; ?>
            </ol>
          </div>
        <?php endif; ?>
      <?php endwhile; ?>
    </div>
  </section>

  <?php
  ebccc_cta_banner( [
    'heading'         => __( 'Ready to take the next step?', 'ebccc' ),
    'body'            => __( "Book a tour — it's the best way to get all your questions answered, see the rooms, and meet the team. No obligation.", 'ebccc' ),
    'cta_label'       => __( 'Book a Tour →', 'ebccc' ),
    'cta_url'         => $contact_url,
    'secondary_label' => sprintf( __( 'Call %s', 'ebccc' ), $phone ),
    'secondary_url'   => $phone_href,
  ] );
  ?>

</main>

<?php get_footer();
