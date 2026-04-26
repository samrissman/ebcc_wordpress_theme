<?php
/**
 * index.php — WordPress required fallback template
 *
 * WordPress requires this file to exist in every theme. It is used
 * as a fallback when no more specific template is found in the hierarchy.
 * For EBCCC, all primary routes have dedicated templates, so this should
 * rarely if ever be reached in production.
 *
 * @package EBCCC
 */

get_header();
?>

<main id="main-content" style="padding: var(--space-3xl) var(--space-lg);">
  <div style="max-width: var(--max-width); margin: 0 auto;">

    <?php if ( have_posts() ) : ?>

      <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <header>
            <h1 class="section-heading"><?php the_title(); ?></h1>
          </header>
          <div class="entry-content prose">
            <?php the_content(); ?>
          </div>
        </article>
      <?php endwhile; ?>

      <?php the_posts_navigation(); ?>

    <?php else : ?>

      <section aria-labelledby="not-found-heading" style="text-align:center; padding: var(--space-3xl) 0;">
        <p class="section-eyebrow"><?php esc_html_e( '404 — Not Found', 'ebccc' ); ?></p>
        <h1 id="not-found-heading" class="section-heading" style="margin-bottom: var(--space-md);">
          <?php esc_html_e( "We couldn't find that page.", 'ebccc' ); ?>
        </h1>
        <p class="section-lead" style="margin-bottom: var(--space-xl);">
          <?php esc_html_e( "It may have moved or no longer exist. Try the navigation above, or call us directly.", 'ebccc' ); ?>
        </p>
        <div style="display:flex; gap: var(--space-md); justify-content:center; flex-wrap:wrap;">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary">
            <?php esc_html_e( '← Back to Home', 'ebccc' ); ?>
          </a>
          <a href="<?php echo esc_attr( ebccc_phone( 'href' ) ); ?>" class="btn-secondary">
            <?php printf( esc_html__( 'Call %s', 'ebccc' ), ebccc_phone( 'display' ) ); ?>
          </a>
        </div>
      </section>

    <?php endif; ?>

  </div>
</main>

<?php get_footer();
