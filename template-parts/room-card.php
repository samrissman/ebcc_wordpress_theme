<?php
/**
 * Template Part: Room Card
 *
 * @var array{ room: WP_Post, featured: bool } $args
 * @package EBCCC
 */

$room     = $args['room'] ?? null;
if ( ! $room instanceof WP_Post ) return;

$featured  = ! empty( $args['featured'] ) || ebccc_room_meta( $room->ID, '_room_featured' ) === '1';
$tag       = ebccc_room_meta( $room->ID, '_room_tag' );
$age       = ebccc_room_meta( $room->ID, '_room_age_range' );
$places    = ebccc_room_meta( $room->ID, '_room_places' );
$ratio     = ebccc_room_meta( $room->ID, '_room_ratio' );
$permalink = get_permalink( $room->ID );
$excerpt   = get_the_excerpt( $room );
?>

<article class="room-card<?php echo $featured ? ' room-card--featured' : ''; ?>" role="listitem">
  <a href="<?php echo esc_url( $permalink ); ?>"
     class="room-card-link"
     aria-label="<?php printf( esc_attr__( 'Learn more about the %s', 'ebccc' ), $room->post_title ); ?>">

    <div class="room-card-photo" aria-hidden="true">
      <?php if ( has_post_thumbnail( $room->ID ) ) : ?>
        <?php echo get_the_post_thumbnail( $room->ID, 'ebccc-room-card', [ 'alt' => '' ] ); ?>
      <?php else : ?>
        <div class="photo-placeholder room-photo">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
          </svg>
        </div>
      <?php endif; ?>
    </div>

    <div class="room-card-body">
      <?php if ( $tag ) : ?>
        <div class="room-tag"><?php echo esc_html( $tag ); ?></div>
      <?php endif; ?>

      <h3 class="room-name"><?php echo esc_html( $room->post_title ); ?></h3>

      <?php if ( $age ) : ?>
        <p class="room-age"><?php echo esc_html( $age ); ?></p>
      <?php endif; ?>

      <?php if ( $places || $ratio ) : ?>
        <div class="room-meta">
          <?php if ( $places ) : ?><span class="room-places"><?php echo esc_html( $places ); ?></span><?php endif; ?>
          <?php if ( $ratio )  : ?><span class="room-ratio"><?php echo esc_html( $ratio ); ?></span><?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if ( $excerpt ) : ?>
        <p class="room-desc"><?php echo esc_html( $excerpt ); ?></p>
      <?php endif; ?>

      <span class="room-link" aria-hidden="true"><?php esc_html_e( 'Learn more →', 'ebccc' ); ?></span>
    </div>

  </a>
</article>
