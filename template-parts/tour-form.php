<?php
/**
 * Template Part: Tour Booking Form
 *
 * Renders the full tour request form section.
 * Submits via AJAX to ebccc_tour_submit handler in functions.php.
 * Falls back to CF7 shortcode if the shortcode exists.
 *
 * @package EBCCC
 */

// Check if Contact Form 7 is active and a form ID is configured
$cf7_id = (int) get_theme_mod( 'ebccc_cf7_tour_form_id', 0 );

$phone      = ebccc_phone( 'display' );
$phone_href = ebccc_phone( 'href' );
?>

<section class="form-section" id="book-tour" aria-labelledby="form-heading">
  <div class="form-inner">

    <div class="form-content">
      <p class="section-eyebrow section-eyebrow--light"><?php esc_html_e( 'Ready to visit?', 'ebccc' ); ?></p>
      <h2 id="form-heading" class="section-heading section-heading--light">
        <?php esc_html_e( 'Book a Centre Tour', 'ebccc' ); ?>
      </h2>
      <p class="form-intro">
        <?php esc_html_e( 'Fill in your details and one of our friendly team will call you within 1 business day to arrange a walk through our rooms and outdoor spaces. No obligation — just a conversation.', 'ebccc' ); ?>
      </p>
      <div class="form-contact-alt">
        <p><?php esc_html_e( 'Prefer to call us directly?', 'ebccc' ); ?></p>
        <a href="<?php echo esc_attr( $phone_href ); ?>" class="form-phone-link"><?php echo esc_html( $phone ); ?></a>
        <span><?php esc_html_e( 'Mon–Fri, 7am–6pm', 'ebccc' ); ?></span>
      </div>
    </div>

    <div class="form-card">

      <?php if ( $cf7_id && shortcode_exists( 'contact-form-7' ) ) : ?>
        <?php echo do_shortcode( '[contact-form-7 id="' . $cf7_id . '" title="Tour Booking"]' ); ?>

      <?php else : ?>

        <!-- Success State -->
        <div class="form-success" id="form-success" hidden role="alert" aria-live="assertive">
          <div class="success-icon" aria-hidden="true">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/><polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <h3><?php esc_html_e( 'Tour request received!', 'ebccc' ); ?></h3>
          <p><?php esc_html_e( 'Thank you — we\'ll call you within 1 business day to arrange your visit. We look forward to meeting you and your family.', 'ebccc' ); ?></p>
          <a href="<?php echo esc_attr( $phone_href ); ?>" class="btn-primary-sm">
            <?php printf( esc_html__( 'Or call us now: %s', 'ebccc' ), esc_html( $phone ) ); ?>
          </a>
        </div>

        <!-- Native AJAX form -->
        <form class="tour-form" id="tour-form" novalidate
              aria-label="<?php esc_attr_e( 'Book a centre tour', 'ebccc' ); ?>">

          <?php wp_nonce_field( 'ebccc_tour_nonce', 'nonce' ); ?>
          <input type="hidden" name="action" value="ebccc_tour_submit" />

          <p class="form-required-note">
            <span aria-hidden="true">*</span> <?php esc_html_e( 'Required fields', 'ebccc' ); ?>
          </p>

          <div class="form-row form-row--2">
            <div class="field-group">
              <label for="field-name" class="field-label">
                <?php esc_html_e( 'Your name', 'ebccc' ); ?>
                <span class="required-star" aria-hidden="true">*</span>
              </label>
              <input type="text" id="field-name" name="name" class="field-input"
                     required aria-required="true" autocomplete="name"
                     placeholder="<?php esc_attr_e( 'Jane Smith', 'ebccc' ); ?>" />
              <span class="field-error" id="field-name-error" role="alert" aria-live="polite"></span>
            </div>
            <div class="field-group">
              <label for="field-phone" class="field-label">
                <?php esc_html_e( 'Phone number', 'ebccc' ); ?>
                <span class="required-star" aria-hidden="true">*</span>
              </label>
              <input type="tel" id="field-phone" name="phone" class="field-input"
                     required aria-required="true" autocomplete="tel"
                     placeholder="<?php esc_attr_e( '04XX XXX XXX', 'ebccc' ); ?>" />
              <span class="field-error" id="field-phone-error" role="alert" aria-live="polite"></span>
            </div>
          </div>

          <div class="field-group">
            <label for="field-email" class="field-label">
              <?php esc_html_e( 'Email address', 'ebccc' ); ?>
              <span class="required-star" aria-hidden="true">*</span>
            </label>
            <input type="email" id="field-email" name="email" class="field-input"
                   required aria-required="true" autocomplete="email"
                   placeholder="<?php esc_attr_e( 'jane@example.com', 'ebccc' ); ?>" />
            <span class="field-error" id="field-email-error" role="alert" aria-live="polite"></span>
          </div>

          <div class="form-row form-row--2">
            <div class="field-group">
              <label for="field-child-age" class="field-label">
                <?php esc_html_e( "Child's age", 'ebccc' ); ?>
              </label>
              <select id="field-child-age" name="child_age" class="field-input field-select" autocomplete="off">
                <option value=""><?php esc_html_e( 'Select age range', 'ebccc' ); ?></option>
                <option value="6wks-12m"><?php esc_html_e( '6 weeks – 12 months', 'ebccc' ); ?></option>
                <option value="12m-2yr"><?php esc_html_e( '12 months – 2 years', 'ebccc' ); ?></option>
                <option value="2-3yr"><?php esc_html_e( '2 – 3 years', 'ebccc' ); ?></option>
                <option value="3-4yr"><?php esc_html_e( '3 – 4 years', 'ebccc' ); ?></option>
                <option value="4-5yr"><?php esc_html_e( '4 – 5 years (Kinder)', 'ebccc' ); ?></option>
              </select>
            </div>
            <div class="field-group">
              <label for="field-tour-date" class="field-label">
                <?php esc_html_e( 'Preferred tour date', 'ebccc' ); ?>
              </label>
              <input type="date" id="field-tour-date" name="preferred_date" class="field-input" />
            </div>
          </div>

          <div class="field-group">
            <label for="field-message" class="field-label">
              <?php esc_html_e( 'Any questions for us?', 'ebccc' ); ?>
            </label>
            <textarea id="field-message" name="message" class="field-input field-textarea" rows="3"
                      placeholder="<?php esc_attr_e( 'e.g. Do you have places available in the Wombats room?', 'ebccc' ); ?>"></textarea>
          </div>

          <p class="form-privacy">
            <?php esc_html_e( "We'll only use your details to call you about your tour. No mailing lists. We'll call within 1 business day.", 'ebccc' ); ?>
          </p>

          <button type="submit" class="btn-submit">
            <?php esc_html_e( 'Request a tour →', 'ebccc' ); ?>
          </button>

        </form>

      <?php endif; ?>

    </div><!-- .form-card -->
  </div><!-- .form-inner -->
</section>
