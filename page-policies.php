<?php
/**
 * Page Template: Policies & Procedures
 *
 * Template Name: Policies & Procedures (page-policies.php)
 *
 * Content is managed via Policies → Add New in the WP admin.
 * Uses the policy_document CPT registered in functions.php.
 *
 * @package EBCCC
 */

get_header();

$contact_url = ebccc_contact_url();
$phone       = ebccc_phone( 'display' );
$phone_href  = ebccc_phone( 'href' );

$policy_categories = ebccc_get_policies_by_category();
$populated = array_filter( $policy_categories, fn( $c ) => ! empty( $c['policies'] ) );
?>

<main id="main-content">

<?php ebccc_page_hero( [
  'eyebrow'     => __( 'Information for families', 'ebccc' ),
  'heading'     => __( 'Policies & Procedures', 'ebccc' ),
  'lead'        => __( 'Our full set of centre policies — available to download or request a hard copy from the director at any time.', 'ebccc' ),
  'breadcrumbs' => [
    [ 'label' => __( 'Home', 'ebccc' ), 'url' => home_url( '/' ) ],
    [ 'label' => __( 'Policies & Procedures', 'ebccc' ) ],
  ],
] ); ?>

<div class="policies-page">
  <div class="policies-container">

    <div class="policy-notice">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <div>
        <strong><?php esc_html_e( 'All policies available on request', 'ebccc' ); ?></strong>
        <p><?php printf(
          esc_html__( 'Copies of all EBCCC policies are available from the centre at any time. Call us on %s or speak with the director during your visit. Where a PDF is available to download, a button will appear on the card.', 'ebccc' ),
          '<a href="' . esc_attr( $phone_href ) . '">' . esc_html( $phone ) . '</a>'
        ); ?></p>
      </div>
    </div>

    <?php if ( ! empty( $populated ) ) : ?>

    <nav class="policy-jumpnav" aria-label="<?php esc_attr_e( 'Jump to policy category', 'ebccc' ); ?>">
      <?php foreach ( $populated as $key => $cat ) : ?>
        <a href="#<?php echo esc_attr( $key ); ?>" class="policy-jumplink">
          <?php echo esc_html( $cat['label'] ); ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <?php foreach ( $policy_categories as $key => $cat ) :
      if ( empty( $cat['policies'] ) ) continue; ?>

    <section class="policy-category" id="<?php echo esc_attr( $key ); ?>"
             aria-labelledby="cat-<?php echo esc_attr( $key ); ?>">
      <h2 class="policy-category-title" id="cat-<?php echo esc_attr( $key ); ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path d="<?php echo esc_attr( $cat['icon'] ); ?>"/>
        </svg>
        <?php echo esc_html( $cat['label'] ); ?>
      </h2>
      <div class="policy-grid">
        <?php foreach ( $cat['policies'] as $policy ) :
          $desc     = get_post_meta( $policy->ID, '_policy_description', true );
          $file_url = get_post_meta( $policy->ID, '_policy_file_url', true );
          $updated  = get_post_meta( $policy->ID, '_policy_updated', true );
          $has_file = ! empty( $file_url );
        ?>
        <div class="policy-card">
          <div class="policy-card-body">
            <div class="policy-card-top">
              <p class="policy-card-title"><?php echo esc_html( $policy->post_title ); ?></p>
              <?php if ( $has_file ) : ?>
                <span class="policy-pill policy-pill--available"><?php esc_html_e( 'PDF available', 'ebccc' ); ?></span>
              <?php else : ?>
                <span class="policy-pill policy-pill--request"><?php esc_html_e( 'On request', 'ebccc' ); ?></span>
              <?php endif; ?>
            </div>
            <?php if ( $desc ) : ?>
              <p class="policy-card-desc"><?php echo esc_html( $desc ); ?></p>
            <?php endif; ?>
            <?php if ( $updated ) : ?>
              <p class="policy-updated"><?php printf( esc_html__( 'Last reviewed: %s', 'ebccc' ), esc_html( $updated ) ); ?></p>
            <?php endif; ?>
          </div>
          <div class="policy-card-footer">
            <?php if ( $has_file ) : ?>
              <a href="<?php echo esc_url( $file_url ); ?>"
                 class="btn-policy-download"
                 target="_blank" rel="noopener noreferrer"
                 aria-label="<?php printf( esc_attr__( 'Download %s as PDF', 'ebccc' ), $policy->post_title ); ?>">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="7 10 12 15 17 10"/>
                  <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <?php esc_html_e( 'Download PDF', 'ebccc' ); ?>
              </a>
            <?php else : ?>
              <a href="<?php echo esc_attr( $phone_href ); ?>" class="btn-policy-request">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <?php esc_html_e( 'Request a copy', 'ebccc' ); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <?php endforeach; ?>

    <?php else : ?>
    <div class="policy-notice" style="margin-top:var(--space-xl);">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <div>
        <strong><?php esc_html_e( 'No policies published yet', 'ebccc' ); ?></strong>
        <p><?php esc_html_e( 'Add policies via Policies → Add New in the WordPress admin. Each policy needs a title, category, and description as a minimum.', 'ebccc' ); ?></p>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>

<?php
ebccc_cta_banner( [
  'heading'         => __( 'Questions about our policies?', 'ebccc' ),
  'body'            => __( 'Our director is happy to walk you through any policy during a centre tour or over the phone.', 'ebccc' ),
  'cta_label'       => __( 'Book a Tour →', 'ebccc' ),
  'cta_url'         => $contact_url,
  'secondary_label' => sprintf( __( 'Call us: %s', 'ebccc' ), $phone ),
  'secondary_url'   => $phone_href,
] );

get_footer();
