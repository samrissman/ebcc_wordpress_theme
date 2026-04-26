<?php
/**
 * Page Template: Infectious Diseases & Exclusions
 *
 * Template Name: Infectious Diseases & Exclusions (page-infectious-diseases.php)
 *
 * Content is managed via Disease Exclusions → Add New in the WP admin.
 * Uses the disease_entry CPT registered in functions.php.
 *
 * @package EBCCC
 */

get_header();

$contact_url = ebccc_contact_url();
$phone       = ebccc_phone( 'display' );
$phone_href  = ebccc_phone( 'href' );

$diseases = ebccc_get_diseases();
?>

<main id="main-content">

<?php ebccc_page_hero( [
  'eyebrow'     => __( 'Health information', 'ebccc' ),
  'heading'     => __( 'Infectious Diseases & Exclusions', 'ebccc' ),
  'lead'        => __( 'Minimum exclusion periods as required under the Public Health and Wellbeing Regulations 2019 — Schedule 7.', 'ebccc' ),
  'breadcrumbs' => [
    [ 'label' => __( 'Home', 'ebccc' ),                 'url' => home_url( '/' ) ],
    [ 'label' => __( 'Policies & Procedures', 'ebccc' ), 'url' => get_permalink( ebccc_get_page_by_template( 'page-policies.php' ) ) ],
    [ 'label' => __( 'Infectious Diseases & Exclusions', 'ebccc' ) ],
  ],
] ); ?>

<div class="id-page">
  <div class="id-container">

    <div class="id-notice">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <div>
        <strong><?php esc_html_e( 'Public Health and Wellbeing Regulations 2019 — Schedule 7', 'ebccc' ); ?></strong>
        <p><?php esc_html_e( 'The table below sets out the minimum exclusion periods required by law. EBCCC must not allow a child to attend the centre during the specified exclusion period once informed of an infectious disease diagnosis or contact.', 'ebccc' ); ?></p>
      </div>
    </div>

    <div class="id-legend" aria-label="<?php esc_attr_e( 'Severity key', 'ebccc' ); ?>">
      <span class="id-legend-label"><?php esc_html_e( 'Exclusion required:', 'ebccc' ); ?></span>
      <span class="id-badge id-badge--high"><?php esc_html_e( 'Strict', 'ebccc' ); ?></span>
      <span class="id-badge id-badge--moderate"><?php esc_html_e( 'Moderate', 'ebccc' ); ?></span>
      <span class="id-badge id-badge--mild"><?php esc_html_e( 'Mild', 'ebccc' ); ?></span>
      <span class="id-badge id-badge--none"><?php esc_html_e( 'Not required', 'ebccc' ); ?></span>
    </div>

    <?php if ( ! empty( $diseases ) ) : ?>

    <div class="id-table-wrap" role="region" aria-label="<?php esc_attr_e( 'Infectious disease exclusion table', 'ebccc' ); ?>">
      <table class="id-table">
        <thead>
          <tr>
            <th scope="col"><?php esc_html_e( 'Condition', 'ebccc' ); ?></th>
            <th scope="col"><?php esc_html_e( 'Exclusion of the child with the illness', 'ebccc' ); ?></th>
            <th scope="col"><?php esc_html_e( 'Exclusion of contacts', 'ebccc' ); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ( $diseases as $d ) :
            $severity        = get_post_meta( $d->ID, '_disease_severity',        true ) ?: 'mild';
            $exclude_case    = get_post_meta( $d->ID, '_disease_exclude_case',    true );
            $exclude_contact = get_post_meta( $d->ID, '_disease_exclude_contact', true );
            $note            = get_post_meta( $d->ID, '_disease_note',            true );
          ?>
          <tr class="id-row id-row--<?php echo esc_attr( $severity ); ?>">
            <td class="id-condition" data-label="<?php esc_attr_e( 'Condition', 'ebccc' ); ?>">
              <span class="id-dot id-dot--<?php echo esc_attr( $severity ); ?>" aria-hidden="true"></span>
              <?php echo esc_html( $d->post_title ); ?>
              <?php if ( $note ) : ?>
                <span class="id-asterisk" aria-label="<?php esc_attr_e( 'See note below', 'ebccc' ); ?>">*</span>
              <?php endif; ?>
            </td>
            <td data-label="<?php esc_attr_e( 'Exclusion — child', 'ebccc' ); ?>">
              <?php echo esc_html( $exclude_case ); ?>
            </td>
            <td data-label="<?php esc_attr_e( 'Exclusion — contacts', 'ebccc' ); ?>">
              <?php echo esc_html( $exclude_contact ); ?>
            </td>
          </tr>
          <?php if ( $note ) : ?>
          <tr class="id-note-row">
            <td colspan="3" class="id-note">
              <span aria-hidden="true">* </span><?php echo esc_html( $note ); ?>
            </td>
          </tr>
          <?php endif; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php else : ?>
    <div class="id-notice" style="margin-top:var(--space-xl);">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <div>
        <strong><?php esc_html_e( 'No disease entries published yet', 'ebccc' ); ?></strong>
        <p><?php esc_html_e( 'Add entries via Disease Exclusions → Add New in the WordPress admin. The condition name is the post title.', 'ebccc' ); ?></p>
      </div>
    </div>
    <?php endif; ?>

    <!-- Head lice — always shown, static content -->
    <section class="id-supp" aria-labelledby="head-lice-heading">
      <h2 id="head-lice-heading"><?php esc_html_e( 'Head lice', 'ebccc' ); ?></h2>
      <p><?php esc_html_e( 'Children who have lice may return to the centre as soon as effective treatment has started. An effective treatment is one where a treatment is used and all the lice are dead. Lice treatments can be purchased from the pharmacy.', 'ebccc' ); ?></p>
      <ul>
        <li><?php esc_html_e( 'Check your child\'s head once a week for head lice.', 'ebccc' ); ?></li>
        <li><?php esc_html_e( 'If you find any lice or eggs, begin treatment immediately.', 'ebccc' ); ?></li>
        <li><?php esc_html_e( 'Check for effectiveness every 2 days until no lice are found for 10 consecutive days.', 'ebccc' ); ?></li>
      </ul>
      <p><?php esc_html_e( 'For further information:', 'ebccc' ); ?>
        <a href="https://www.betterhealth.vic.gov.au/health/conditionsandtreatments/head-lice-nits"
           target="_blank" rel="noopener noreferrer" class="id-ext-link">
          <?php esc_html_e( 'Better Health Channel — Head lice (nits)', 'ebccc' ); ?>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </a>
      </p>
    </section>

    <!-- Regulation 111 — always shown, static content -->
    <section class="id-regulation" aria-labelledby="reg-111-heading">
      <h2 id="reg-111-heading"><?php esc_html_e( 'Regulation 111', 'ebccc' ); ?></h2>
      <p><?php esc_html_e( 'A person in charge of the education and care service must not allow a child to attend the premises for the period or in the circumstances:', 'ebccc' ); ?></p>
      <ul>
        <li><?php esc_html_e( 'specified in column 2 of the Table in Schedule 7 if the person in charge has been informed that the child is infected with an infectious disease listed in column 1 of that Table; or', 'ebccc' ); ?></li>
        <li><?php esc_html_e( 'specified in column 3 of the Table in Schedule 7 if the person in charge has been informed that the child has been in contact with a person who is infected with an infectious disease listed in column 1 of that Table.', 'ebccc' ); ?></li>
      </ul>
      <p class="id-regulation-contact">
        <?php esc_html_e( 'For further information contact the Communicable Disease Prevention and Control Section:', 'ebccc' ); ?>
        <a href="tel:1300651160">1300 651 160</a>
        <?php esc_html_e( 'or visit', 'ebccc' ); ?>
        <a href="https://www2.health.vic.gov.au/public-health/infectious-diseases/school-exclusion"
           target="_blank" rel="noopener noreferrer">health.vic.gov.au</a>
      </p>
    </section>

  </div>
</div>

<?php
ebccc_cta_banner( [
  'heading'         => __( 'Questions about exclusions or illness?', 'ebccc' ),
  'body'            => __( 'Call the centre directly — our educators are happy to advise on whether your child needs to stay home.', 'ebccc' ),
  'cta_label'       => __( 'Book a Tour →', 'ebccc' ),
  'cta_url'         => $contact_url,
  'secondary_label' => sprintf( __( 'Call us: %s', 'ebccc' ), $phone ),
  'secondary_url'   => $phone_href,
] );

get_footer();
