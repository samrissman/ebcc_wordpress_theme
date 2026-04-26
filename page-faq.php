<?php
/**
 * Page Template: FAQ
 *
 * Template Name: FAQ Page
 * Outputs FAQ schema markup in <head> via wp_head hook.
 *
 * @package EBCCC
 */

// Inject FAQ schema into <head>
add_action( 'wp_head', 'ebccc_faq_schema' );
function ebccc_faq_schema(): void {
  if ( ! is_page_template( 'page-faq.php' ) ) return;

  // Pull FAQ items from post content or use defaults
  // In production, these would come from CPT/ACF FAQ entries
  $faqs = [
    [ 'q' => 'How do I get a place at EBCCC?',
      'a' => 'The best first step is to book a centre tour. After your visit, if you would like to proceed, we will add your child to our wait list.' ],
    [ 'q' => 'Is there a minimum number of days I need to book?',
      'a' => 'No — we are flexible from just one day per week.' ],
    [ 'q' => 'Are you approved for the Child Care Subsidy (CCS)?',
      'a' => 'Yes, we are fully CCS approved. Most families find it significantly reduces their out-of-pocket costs.' ],
    [ 'q' => 'Are meals included in the fee?',
      'a' => 'Yes — all meals are included. Breakfast, morning tea, lunch, and afternoon tea are freshly prepared on-site each day. Sunscreen is also provided.' ],
    [ 'q' => 'Is your kindergarten program government funded?',
      'a' => 'Yes. Our 4-year-old kindergarten program in the Possums Room is state-funded (Victorian Government).' ],
  ];

  $schema = [ '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => [] ];
  foreach ( $faqs as $faq ) {
    $schema['mainEntity'][] = [
      '@type'          => 'Question',
      'name'           => $faq['q'],
      'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $faq['a'] ],
    ];
  }
  echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

get_header();

$contact_url = ebccc_contact_url( 'book-tour' );
$phone_href  = ebccc_phone( 'href' );
$phone       = ebccc_phone( 'display' );

// FAQ categories — in production, drive from a CPT or ACF repeater
$faq_categories = [
  [
    'id'    => 'enrolment',
    'icon'  => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    'title' => __( 'Enrolment', 'ebccc' ),
    'items' => [
      [ 'q' => __( 'How do I get a place at EBCCC?', 'ebccc' ),
        'a' => __( 'Book a centre tour — it\'s the best first step. After your visit, if you\'d like to proceed, we\'ll add your child to our wait list. When a place becomes available, we\'ll contact you to begin the enrolment process.', 'ebccc' ) ],
      [ 'q' => __( 'How long is the wait list?', 'ebccc' ),
        'a' => __( 'Wait times vary depending on your child\'s age, the days you need, and the time of year. Call us for current information.', 'ebccc' ) ],
      [ 'q' => __( 'Is there a minimum number of days?', 'ebccc' ),
        'a' => __( 'No — we\'re flexible from just one day per week. Many families start with one day and increase as their needs change.', 'ebccc' ) ],
      [ 'q' => __( 'What ages do you care for?', 'ebccc' ),
        'a' => __( 'We care for children from 6 weeks to 5 years across Gumnuts (6 weeks–2 years), Wombats (2–4 years), and Possums (4–5 years with funded kinder).', 'ebccc' ) ],
      [ 'q' => __( 'What documentation do I need to enrol?', 'ebccc' ),
        'a' => __( 'You\'ll need your child\'s AIR immunisation statement, birth certificate or passport, emergency contacts, and your CRN for CCS linkage. We\'ll send you a full checklist when a place becomes available.', 'ebccc' ) ],
    ],
  ],
  [
    'id'    => 'fees',
    'icon'  => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
    'title' => __( 'Fees & Child Care Subsidy', 'ebccc' ),
    'items' => [
      [ 'q' => __( 'Are you CCS approved?', 'ebccc' ),
        'a' => __( 'Yes, fully CCS approved. Most families find the subsidy significantly reduces their out-of-pocket cost. Check your entitlement at the Services Australia website.', 'ebccc' ) ],
      [ 'q' => __( 'What are your daily fees?', 'ebccc' ),
        'a' => __( 'Our fees are reviewed annually. Please call us directly — we\'ll give you current information and help estimate your CCS entitlement.', 'ebccc' ) ],
      [ 'q' => __( 'Are meals included?', 'ebccc' ),
        'a' => __( 'Yes — all four meals are included in your daily fee. No lunchbox needed. Sunscreen and incursion costs are also included.', 'ebccc' ) ],
      [ 'q' => __( 'Do I pay for days my child is sick?', 'ebccc' ),
        'a' => __( 'Yes, like most providers we charge for booked days regardless of attendance. CCS is still paid on an allowance of absence days per year set by the federal government.', 'ebccc' ) ],
    ],
  ],
  [
    'id'    => 'daily-life',
    'icon'  => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
    'title' => __( 'Daily life at EBCCC', 'ebccc' ),
    'items' => [
      [ 'q' => __( 'What do I need to bring?', 'ebccc' ),
        'a' => __( 'Labelled change of clothes (at least 2), nappies and wipes if applicable, formula/bottles if needed, and a comfort object. We provide everything else including all meals and sunscreen.', 'ebccc' ) ],
      [ 'q' => __( 'How will I know what my child has been doing?', 'ebccc' ),
        'a' => __( 'We use Storypark — a secure digital portfolio platform where educators post photos, observations, and learning stories throughout the day. You\'ll get access as part of your enrolment.', 'ebccc' ) ],
      [ 'q' => __( 'What are the pick-up and drop-off arrangements?', 'ebccc' ),
        'a' => __( 'The centre is open 7am–6pm Monday to Friday. You can arrive and depart at any time within those hours. All authorised collectors must be listed on your enrolment form.', 'ebccc' ) ],
      [ 'q' => __( 'Do you go outside every day?', 'ebccc' ),
        'a' => __( 'Yes — we go outside every day, weather permitting. We have shaded outdoor areas for all-weather play. Children will get muddy and wet — this is a feature, not a bug.', 'ebccc' ) ],
    ],
  ],
  [
    'id'    => 'kinder',
    'icon'  => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
    'title' => __( 'Kindergarten', 'ebccc' ),
    'items' => [
      [ 'q' => __( 'Is your kinder state-funded?', 'ebccc' ),
        'a' => __( 'Yes. Our 4-year-old kindergarten program in the Possums Room is approved for Victorian Government funding, delivered within the long day care day at no extra charge.', 'ebccc' ) ],
      [ 'q' => __( 'Do I need to register separately through council?', 'ebccc' ),
        'a' => __( 'No — we manage the registration process on your behalf as an approved provider.', 'ebccc' ) ],
      [ 'q' => __( 'Who teaches the kinder program?', 'ebccc' ),
        'a' => __( 'Our Possums program is led by a qualified early childhood teacher (Bachelor of ECE minimum), as required by Victorian Government funding.', 'ebccc' ) ],
      [ 'q' => __( 'What does the school transition process look like?', 'ebccc' ),
        'a' => __( 'In Terms 3 and 4 we run a school readiness program. Every child receives a detailed transition statement sent directly to their primary school.', 'ebccc' ) ],
    ],
  ],
  [
    'id'    => 'safety',
    'icon'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
    'title' => __( 'Safety & health', 'ebccc' ),
    'items' => [
      [ 'q' => __( 'Is your centre secure?', 'ebccc' ),
        'a' => __( 'Yes. Keypad-controlled entry at all access points, changed regularly. All visitors are signed in and supervised. Outdoor spaces are fully fenced.', 'ebccc' ) ],
      [ 'q' => __( 'Do your educators hold Working with Children checks?', 'ebccc' ),
        'a' => __( 'Yes — all staff hold a current Victorian Working with Children Check, verified before starting. All educators also hold current first aid, anaphylaxis, and asthma management certificates.', 'ebccc' ) ],
      [ 'q' => __( 'Are immunisations required?', 'ebccc' ),
        'a' => __( 'Yes, as required by law. We can only enrol children who are up-to-date with immunisations, on a catch-up schedule, or have an approved medical exemption.', 'ebccc' ) ],
    ],
  ],
];
?>

<main id="main-content">

  <?php
  ebccc_page_hero( [
    'tag'         => __( 'Common questions answered', 'ebccc' ),
    'heading'     => __( 'Frequently asked questions', 'ebccc' ),
    'lead'        => sprintf( __( 'Everything you need to know before booking a tour. Can\'t find your answer? Call us on <a href="%s" style="color:var(--brand-light);font-weight:800;">%s</a>.', 'ebccc' ), esc_attr( $phone_href ), esc_html( $phone ) ),
    'breadcrumbs' => [
      [ 'label' => __( 'Home', 'ebccc' ), 'url' => home_url( '/' ) ],
      [ 'label' => __( 'FAQ', 'ebccc' ) ],
    ],
  ] );
  ?>

  <!-- Jump links -->
  <div style="background:var(--surface-cream);border-bottom:1px solid rgba(0,0,0,0.06);">
    <div style="max-width:var(--max-width);margin:0 auto;padding:var(--space-md) var(--space-lg);display:flex;gap:var(--space-md);flex-wrap:wrap;align-items:center;">
      <span style="font-size:var(--fs-xs);font-weight:800;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);">
        <?php esc_html_e( 'Jump to:', 'ebccc' ); ?>
      </span>
      <?php foreach ( $faq_categories as $cat ) : ?>
        <a href="#<?php echo esc_attr( $cat['id'] ); ?>"
           style="font-size:var(--fs-sm);font-weight:700;color:var(--brand-mid);padding:6px 14px;background:var(--surface-white);border-radius:var(--radius-full);border:1px solid rgba(0,0,0,0.08);">
          <?php echo esc_html( $cat['title'] ); ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

  <section class="content-section content-section--white">
    <div class="content-inner" style="max-width:860px;margin:0 auto;">

      <?php foreach ( $faq_categories as $cat ) : ?>
        <div class="faq-section-block" id="<?php echo esc_attr( $cat['id'] ); ?>">
          <h2 class="faq-section-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <?php echo $cat['icon']; ?>
            </svg>
            <?php echo esc_html( $cat['title'] ); ?>
          </h2>
          <div class="faq-list">
            <?php foreach ( $cat['items'] as $i => $item ) :
              $faq_id = sanitize_key( $cat['id'] ) . '-' . $i;
            ?>
              <div class="faq-item">
                <button class="faq-trigger" aria-expanded="false" aria-controls="<?php echo esc_attr( $faq_id ); ?>">
                  <?php echo esc_html( $item['q'] ); ?>
                  <svg class="faq-chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <polyline points="6 9 12 15 18 9"/>
                  </svg>
                </button>
                <div class="faq-panel" id="<?php echo esc_attr( $faq_id ); ?>" hidden>
                  <p><?php echo wp_kses_post( $item['a'] ); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <!-- Extra content from WP editor (optional) -->
      <?php while ( have_posts() ) : the_post(); ?>
        <?php if ( get_the_content() ) : ?>
          <div class="entry-content"><?php the_content(); ?></div>
        <?php endif; ?>
      <?php endwhile; ?>

    </div>
  </section>

  <?php
  ebccc_cta_banner( [
    'heading'         => __( 'Still have questions?', 'ebccc' ),
    'body'            => __( "We're a small team and we're happy to talk. Call us directly, or book a tour and ask everything in person.", 'ebccc' ),
    'cta_label'       => __( 'Book a Tour →', 'ebccc' ),
    'cta_url'         => $contact_url,
    'secondary_label' => sprintf( __( 'Call %s', 'ebccc' ), $phone ),
    'secondary_url'   => $phone_href,
  ] );
  ?>

</main>

<?php get_footer();
