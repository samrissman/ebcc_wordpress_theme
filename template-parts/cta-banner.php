<?php
/**
 * Template Part: CTA Banner
 *
 * @var array{
 *   heading:          string,
 *   body:             string,
 *   cta_label:        string,
 *   cta_url:          string,
 *   secondary_label?: string,
 *   secondary_url?:   string,
 * } $args
 *
 * @package EBCCC
 */

$heading         = isset( $args['heading'] ) ? wp_kses_post( $args['heading'] ) : '';
$body            = isset( $args['body'] )    ? wp_kses_post( $args['body'] )    : '';
$cta_label       = isset( $args['cta_label'] )       ? esc_html( $args['cta_label'] )       : esc_html__( 'Book a Tour →', 'ebccc' );
$cta_url         = isset( $args['cta_url'] )         ? esc_url( $args['cta_url'] )           : ebccc_contact_url( 'book-tour' );
$secondary_label = isset( $args['secondary_label'] ) ? esc_html( $args['secondary_label'] )  : '';
$secondary_url   = isset( $args['secondary_url'] )   ? esc_url( $args['secondary_url'] )     : '';
?>

<div class="cta-banner">
  <div class="cta-banner-inner">
    <?php if ( $heading ) : ?>
      <h2><?php echo $heading; ?></h2>
    <?php endif; ?>
    <?php if ( $body ) : ?>
      <p><?php echo $body; ?></p>
    <?php endif; ?>
    <div class="cta-banner-actions">
      <a href="<?php echo $cta_url; ?>" class="btn-primary"><?php echo $cta_label; ?></a>
      <?php if ( $secondary_label && $secondary_url ) : ?>
        <a href="<?php echo $secondary_url; ?>" class="btn-secondary"
           style="border-color:rgba(255,255,255,0.4);color:#fff;"><?php echo $secondary_label; ?></a>
      <?php endif; ?>
    </div>
  </div>
</div>
