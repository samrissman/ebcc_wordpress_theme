<?php
/**
 * Template Part: Page Hero
 *
 * @var array{
 *   heading:     string,
 *   lead:        string,
 *   eyebrow?:    string,
 *   tag?:        string,
 *   chips?:      string[],
 *   breadcrumbs?: array{label:string,url?:string}[],
 *   class?:      string,
 * } $args
 *
 * @package EBCCC
 */

$heading     = isset( $args['heading'] )     ? wp_kses_post( $args['heading'] )         : get_the_title();
$lead        = isset( $args['lead'] )        ? wp_kses_post( $args['lead'] )             : '';
$eyebrow     = isset( $args['eyebrow'] )     ? esc_html( $args['eyebrow'] )              : '';
$tag         = isset( $args['tag'] )         ? esc_html( $args['tag'] )                  : '';
$chips       = isset( $args['chips'] )       ? (array) $args['chips']                    : [];
$breadcrumbs = isset( $args['breadcrumbs'] ) ? (array) $args['breadcrumbs']              : [];
$extra_class = isset( $args['class'] )       ? ' ' . sanitize_html_class( $args['class'] ) : '';
?>

<section class="page-hero<?php echo $extra_class; ?>" aria-labelledby="page-heading">
  <div class="page-hero-inner">

    <?php if ( ! empty( $breadcrumbs ) ) : ?>
      <nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ebccc' ); ?>">
        <?php foreach ( $breadcrumbs as $i => $crumb ) :
          $is_last = ( $i === array_key_last( $breadcrumbs ) );
          if ( $is_last ) : ?>
            <span class="breadcrumb-current" aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></span>
          <?php else : ?>
            <a href="<?php echo esc_url( $crumb['url'] ?? home_url( '/' ) ); ?>"><?php echo esc_html( $crumb['label'] ); ?></a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
          <?php endif;
        endforeach; ?>
      </nav>
    <?php endif; ?>

    <?php if ( $tag ) : ?>
      <div class="page-hero-tag"><?php echo $tag; ?></div>
    <?php endif; ?>

    <h1 id="page-heading"><?php echo $heading; ?></h1>

    <?php if ( $lead ) : ?>
      <p class="page-hero-lead"><?php echo $lead; ?></p>
    <?php endif; ?>

    <?php if ( ! empty( $chips ) ) : ?>
      <div class="page-hero-meta">
        <?php foreach ( $chips as $chip ) : ?>
          <span class="page-hero-chip"><?php echo wp_kses_post( $chip ); ?></span>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>
