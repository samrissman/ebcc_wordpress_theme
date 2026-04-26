<?php
/**
 * EBCCC Theme — functions.php
 *
 * @package EBCCC
 */

defined( 'ABSPATH' ) || exit;

define( 'EBCCC_VERSION', '1.3.3' );
define( 'EBCCC_DIR', get_template_directory() );
define( 'EBCCC_URI', get_template_directory_uri() );

// ─────────────────────────────────────────────
// 1. THEME SUPPORT & SETUP
// ─────────────────────────────────────────────
add_action( 'after_setup_theme', 'ebccc_setup' );
function ebccc_setup(): void {
	load_theme_textdomain( 'ebccc', EBCCC_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
	add_theme_support( 'custom-logo', [
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	] );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );

	// Image sizes
	add_image_size( 'ebccc-room-card',    600, 240, true );
	add_image_size( 'ebccc-room-hero',    900, 600, true );
	add_image_size( 'ebccc-staff',        400, 400, true );
	add_image_size( 'ebccc-photo-strip',  560, 400, true );
	add_image_size( 'ebccc-hero-main',   1200, 800, true );
	add_image_size( 'ebccc-hero-accent',  700, 560, true );

	// Navigation menus
	register_nav_menus( [
		'primary'  => __( 'Primary Navigation', 'ebccc' ),
		'footer'   => __( 'Footer Navigation', 'ebccc' ),
		'utility'  => __( 'Utility Bar (Parent Login etc.)', 'ebccc' ),
	] );
}

// ─────────────────────────────────────────────
// 2. ENQUEUE SCRIPTS & STYLES
// ─────────────────────────────────────────────
add_action( 'wp_enqueue_scripts', 'ebccc_enqueue' );
function ebccc_enqueue(): void {
	// Google Fonts (preconnect handled in header.php)
	wp_enqueue_style(
		'ebccc-fonts',
		'https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=Source+Serif+4:ital,wght@0,400;0,600;1,400;1,600&display=swap',
		[],
		null
	);

	// Main stylesheet
	wp_enqueue_style(
		'ebccc-style',
		EBCCC_URI . '/assets/css/style.css',
		[ 'ebccc-fonts' ],
		EBCCC_VERSION
	);

	// Pages stylesheet (inner page additions)
	if ( ! is_front_page() ) {
		wp_enqueue_style(
			'ebccc-pages',
			EBCCC_URI . '/assets/css/pages.css',
			[ 'ebccc-style' ],
			EBCCC_VERSION
		);
	}

	// Main JS — deferred, no jQuery dependency
	wp_enqueue_script(
		'ebccc-js',
		EBCCC_URI . '/assets/js/ebccc.js',
		[],
		EBCCC_VERSION,
		[ 'strategy' => 'defer', 'in_footer' => true ]
	);

	// Pass PHP data to JS
	wp_localize_script( 'ebccc-js', 'ebcccData', [
		'homeUrl'    => esc_url( home_url( '/' ) ),
		'contactUrl' => esc_url( get_permalink( ebccc_get_page_by_template( 'page-contact.php' ) ) ),
		'ajaxUrl'    => esc_url( admin_url( 'admin-ajax.php' ) ),
		'nonce'      => wp_create_nonce( 'ebccc_tour_nonce' ),
		'isFront'    => is_front_page() ? 'true' : 'false',
	] );
}

// ─────────────────────────────────────────────
// 3. CUSTOM POST TYPE — ROOMS
// ─────────────────────────────────────────────
add_action( 'init', 'ebccc_register_room_cpt' );
function ebccc_register_room_cpt(): void {
	$labels = [
		'name'               => _x( 'Rooms', 'post type general name', 'ebccc' ),
		'singular_name'      => _x( 'Room', 'post type singular name', 'ebccc' ),
		'menu_name'          => _x( 'Rooms', 'admin menu', 'ebccc' ),
		'add_new'            => __( 'Add New Room', 'ebccc' ),
		'add_new_item'       => __( 'Add New Room', 'ebccc' ),
		'edit_item'          => __( 'Edit Room', 'ebccc' ),
		'view_item'          => __( 'View Room', 'ebccc' ),
		'all_items'          => __( 'All Rooms', 'ebccc' ),
		'search_items'       => __( 'Search Rooms', 'ebccc' ),
		'not_found'          => __( 'No rooms found.', 'ebccc' ),
		'not_found_in_trash' => __( 'No rooms found in Trash.', 'ebccc' ),
	];

	register_post_type( 'room', [
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'programs', 'with_front' => false ],
		'capability_type'    => 'post',
		'has_archive'        => 'programs',
		'hierarchical'       => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-groups',
		'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ],
		'show_in_rest'       => true,
	] );
}

// ─────────────────────────────────────────────
// 4. ROOM META BOXES (ACF-compatible fallback)
// ─────────────────────────────────────────────
add_action( 'add_meta_boxes', 'ebccc_room_meta_boxes' );
function ebccc_room_meta_boxes(): void {
	add_meta_box(
		'ebccc_room_details',
		__( 'Room Details', 'ebccc' ),
		'ebccc_room_details_cb',
		'room',
		'side',
		'high'
	);
}

function ebccc_room_details_cb( \WP_Post $post ): void {
	wp_nonce_field( 'ebccc_room_meta', 'ebccc_room_nonce' );
	$fields = [
		'_room_tag'       => [ 'label' => __( 'Category Tag (e.g. Babies &amp; Toddlers)', 'ebccc' ), 'type' => 'text' ],
		'_room_age_range' => [ 'label' => __( 'Age Range (e.g. 6 weeks – 2 years)', 'ebccc' ),        'type' => 'text' ],
		'_room_places'    => [ 'label' => __( 'Number of Places (e.g. 11 places)', 'ebccc' ),         'type' => 'text' ],
		'_room_ratio'     => [ 'label' => __( 'Educator Ratio (e.g. 1:4 ratio)', 'ebccc' ),           'type' => 'text' ],
		'_room_order'     => [ 'label' => __( 'Display Order (1=first)', 'ebccc' ),                    'type' => 'number' ],
		'_room_featured'  => [ 'label' => __( 'Featured Room (border highlight)', 'ebccc' ),           'type' => 'checkbox' ],
		'_room_hero_style'=> [ 'label' => __( 'Hero colour class (page-hero--gumnuts etc.)', 'ebccc' ),'type' => 'text' ],
		'_room_sibling_label' => [ 'label' => __( 'Short name for sibling nav (e.g. Gumnuts)', 'ebccc' ), 'type' => 'text' ],
	];
	echo '<table class="form-table" style="margin-top:0;">';
	foreach ( $fields as $key => $field ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<tr><th><label for="' . esc_attr( $key ) . '">' . wp_kses_post( $field['label'] ) . '</label></th><td>';
		if ( 'checkbox' === $field['type'] ) {
			echo '<input type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="1"' . checked( $val, '1', false ) . ' />';
		} else {
			echo '<input type="' . esc_attr( $field['type'] ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" class="widefat" />';
		}
		echo '</td></tr>';
	}
	echo '</table>';
}

add_action( 'save_post_room', 'ebccc_save_room_meta' );
function ebccc_save_room_meta( int $post_id ): void {
	if ( ! isset( $_POST['ebccc_room_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ebccc_room_nonce'] ) ), 'ebccc_room_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$text_fields = [ '_room_tag', '_room_age_range', '_room_places', '_room_ratio', '_room_order', '_room_hero_style', '_room_sibling_label' ];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	update_post_meta( $post_id, '_room_featured', isset( $_POST['_room_featured'] ) ? '1' : '0' );
}

// ─────────────────────────────────────────────
// 5. STAFF META BOXES
// ─────────────────────────────────────────────
add_action( 'init', 'ebccc_register_staff_cpt' );
function ebccc_register_staff_cpt(): void {
	register_post_type( 'staff_member', [
		'labels' => [
			'name'          => __( 'Staff', 'ebccc' ),
			'singular_name' => __( 'Staff Member', 'ebccc' ),
			'add_new_item'  => __( 'Add New Staff Member', 'ebccc' ),
			'edit_item'     => __( 'Edit Staff Member', 'ebccc' ),
			'all_items'     => __( 'All Staff', 'ebccc' ),
			'view_item'     => __( 'View Profile', 'ebccc' ),
		],
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'menu_icon'          => 'dashicons-id-alt',
		'menu_position'      => 21,
		'supports'           => [ 'title', 'thumbnail', 'editor' ],
		'show_in_rest'       => true,
		'rewrite'            => [ 'slug' => 'team', 'with_front' => false ],
		'has_archive'        => false,
	] );

	add_action( 'add_meta_boxes', function () {
		add_meta_box( 'ebccc_staff_details', __( 'Staff Details', 'ebccc' ), 'ebccc_staff_meta_cb', 'staff_member', 'side', 'high' );
	} );
}

function ebccc_staff_meta_cb( \WP_Post $post ): void {
	wp_nonce_field( 'ebccc_staff_meta', 'ebccc_staff_nonce' );
	$fields = [
		'_staff_role'   => __( 'Role (e.g. Lead Educator — Gumnuts)', 'ebccc' ),
		'_staff_tenure' => __( 'Tenure (e.g. At EBCCC · 7 years)', 'ebccc' ),
		'_staff_room'   => __( 'Room slug (gumnuts / wombats / possums)', 'ebccc' ),
	];
	foreach ( $fields as $key => $label ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
		echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" class="widefat" /></p>';
	}
	echo '<p class="description">' . esc_html__( 'Bio: use the main content editor area below the title.', 'ebccc' ) . '</p>';
}

add_action( 'save_post_staff_member', function ( int $pid ) {
	if ( ! isset( $_POST['ebccc_staff_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ebccc_staff_nonce'] ) ), 'ebccc_staff_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	foreach ( [ '_staff_role', '_staff_tenure', '_staff_room' ] as $k ) {
		if ( isset( $_POST[ $k ] ) ) update_post_meta( $pid, $k, sanitize_text_field( wp_unslash( $_POST[ $k ] ) ) );
	}
} );

// ─────────────────────────────────────────────
// 5b. EVENT CPT
// ─────────────────────────────────────────────
add_action( 'init', 'ebccc_register_event_cpt' );
function ebccc_register_event_cpt(): void {
	register_post_type( 'event', [
		'labels' => [
			'name'          => __( 'Events', 'ebccc' ),
			'singular_name' => __( 'Event', 'ebccc' ),
			'add_new_item'  => __( 'Add New Event', 'ebccc' ),
			'edit_item'     => __( 'Edit Event', 'ebccc' ),
			'all_items'     => __( 'All Events', 'ebccc' ),
		],
		'public'        => true,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_icon'     => 'dashicons-calendar-alt',
		'menu_position' => 24,
		'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ],
		'show_in_rest'  => true,
		'rewrite'       => [ 'slug' => 'events', 'with_front' => false ],
		'has_archive'   => 'events',
	] );

	add_action( 'add_meta_boxes', function () {
		add_meta_box(
			'ebccc_event_details',
			__( 'Event Details', 'ebccc' ),
			'ebccc_event_meta_cb',
			'event',
			'side',
			'high'
		);
	} );
}

function ebccc_event_meta_cb( \WP_Post $post ): void {
	wp_nonce_field( 'ebccc_event_meta', 'ebccc_event_nonce' );
	$date         = get_post_meta( $post->ID, '_event_date',         true );
	$time_start   = get_post_meta( $post->ID, '_event_time_start',   true );
	$time_end     = get_post_meta( $post->ID, '_event_time_end',     true );
	$location     = get_post_meta( $post->ID, '_event_location',     true );
	$category     = get_post_meta( $post->ID, '_event_category',     true );
	$reg_url      = get_post_meta( $post->ID, '_event_reg_url',      true );
	$cost         = get_post_meta( $post->ID, '_event_cost',         true );
	$featured     = get_post_meta( $post->ID, '_event_featured',     true );
	$cancelled    = get_post_meta( $post->ID, '_event_cancelled',    true );

	$categories = [
		''           => __( '— Select category —', 'ebccc' ),
		'committee'  => __( 'Committee meeting', 'ebccc' ),
		'fundraiser' => __( 'Fundraiser', 'ebccc' ),
		'family'     => __( 'Family event', 'ebccc' ),
		'incursion'  => __( 'Incursion / excursion', 'ebccc' ),
		'info'       => __( 'Information night', 'ebccc' ),
		'kinder'     => __( 'Kinder', 'ebccc' ),
		'other'      => __( 'Other', 'ebccc' ),
	];
	?>
	<table class="form-table" style="margin-top:0;">
		<tr>
			<th><label for="_event_date"><?php esc_html_e( 'Date', 'ebccc' ); ?></label></th>
			<td><input type="date" id="_event_date" name="_event_date" value="<?php echo esc_attr( $date ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'YYYY-MM-DD format.', 'ebccc' ); ?></p></td>
		</tr>
		<tr>
			<th><label for="_event_time_start"><?php esc_html_e( 'Start time', 'ebccc' ); ?></label></th>
			<td><input type="time" id="_event_time_start" name="_event_time_start" value="<?php echo esc_attr( $time_start ); ?>" class="widefat" /></td>
		</tr>
		<tr>
			<th><label for="_event_time_end"><?php esc_html_e( 'End time', 'ebccc' ); ?></label></th>
			<td><input type="time" id="_event_time_end" name="_event_time_end" value="<?php echo esc_attr( $time_end ); ?>" class="widefat" /></td>
		</tr>
		<tr>
			<th><label for="_event_location"><?php esc_html_e( 'Location', 'ebccc' ); ?></label></th>
			<td><input type="text" id="_event_location" name="_event_location" value="<?php echo esc_attr( $location ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. EBCCC Hall, or Online', 'ebccc' ); ?>" /></td>
		</tr>
		<tr>
			<th><label for="_event_category"><?php esc_html_e( 'Category', 'ebccc' ); ?></label></th>
			<td>
				<select id="_event_category" name="_event_category" class="widefat">
					<?php foreach ( $categories as $val => $label ) : ?>
						<option value="<?php echo esc_attr( $val ); ?>"<?php selected( $category, $val ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="_event_cost"><?php esc_html_e( 'Cost', 'ebccc' ); ?></label></th>
			<td><input type="text" id="_event_cost" name="_event_cost" value="<?php echo esc_attr( $cost ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. Free, $5/family, Gold coin', 'ebccc' ); ?>" /></td>
		</tr>
		<tr>
			<th><label for="_event_reg_url"><?php esc_html_e( 'Registration URL', 'ebccc' ); ?></label></th>
			<td><input type="url" id="_event_reg_url" name="_event_reg_url" value="<?php echo esc_url( $reg_url ); ?>" class="widefat" placeholder="https://..." />
			<p class="description"><?php esc_html_e( 'Leave blank if no registration needed.', 'ebccc' ); ?></p></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Options', 'ebccc' ); ?></th>
			<td>
				<label><input type="checkbox" name="_event_featured" value="1"<?php checked( $featured, '1' ); ?> /> <?php esc_html_e( 'Feature this event (highlighted card)', 'ebccc' ); ?></label><br>
				<label><input type="checkbox" name="_event_cancelled" value="1"<?php checked( $cancelled, '1' ); ?> /> <?php esc_html_e( 'Mark as cancelled', 'ebccc' ); ?></label>
			</td>
		</tr>
	</table>
	<?php
}

add_action( 'save_post_event', 'ebccc_save_event_meta' );
function ebccc_save_event_meta( int $post_id ): void {
	if ( ! isset( $_POST['ebccc_event_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ebccc_event_nonce'] ) ), 'ebccc_event_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$text_fields = [ '_event_date', '_event_time_start', '_event_time_end', '_event_location', '_event_category', '_event_cost' ];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
	}
	if ( isset( $_POST['_event_reg_url'] ) ) {
		update_post_meta( $post_id, '_event_reg_url', esc_url_raw( wp_unslash( $_POST['_event_reg_url'] ) ) );
	}
	update_post_meta( $post_id, '_event_featured',  isset( $_POST['_event_featured'] )  ? '1' : '0' );
	update_post_meta( $post_id, '_event_cancelled', isset( $_POST['_event_cancelled'] ) ? '1' : '0' );
}

/**
 * Get upcoming events (today and future), ordered by date ascending.
 * Pass $include_past = true to include past events.
 *
 * @return WP_Post[]
 */
function ebccc_get_events( bool $include_past = false ): array {
	$posts = get_posts( [
		'post_type'      => 'event',
		'posts_per_page' => -1,
		'orderby'        => 'meta_value',
		'meta_key'       => '_event_date',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	] );

	if ( $include_past ) return $posts;

	$today = date( 'Y-m-d' );
	return array_values( array_filter( $posts, function ( $p ) use ( $today ) {
		$date = get_post_meta( $p->ID, '_event_date', true );
		return ! $date || $date >= $today;
	} ) );
}

/**
 * Format an event date for display.
 * e.g. '2026-06-15' → 'Sunday 15 June 2026'
 */
function ebccc_event_date_display( string $date_str ): string {
	if ( ! $date_str ) return '';
	$ts = strtotime( $date_str );
	return $ts ? date_i18n( 'l j F Y', $ts ) : esc_html( $date_str );
}

/**
 * Format event time range for display.
 * e.g. '09:00', '11:00' → '9:00am – 11:00am'
 */
function ebccc_event_time_display( string $start, string $end = '' ): string {
	$fmt = function( string $t ): string {
		if ( ! $t ) return '';
		$ts = strtotime( "2000-01-01 $t" );
		return $ts ? date_i18n( 'g:ia', $ts ) : $t;
	};
	$out = $fmt( $start );
	if ( $end ) $out .= ' – ' . $fmt( $end );
	return $out;
}

// ─────────────────────────────────────────────
// 6. POLICY DOCUMENT CPT
// ─────────────────────────────────────────────
add_action( 'init', 'ebccc_register_policy_cpt' );
function ebccc_register_policy_cpt(): void {
	register_post_type( 'policy_document', [
		'labels' => [
			'name'          => __( 'Policies', 'ebccc' ),
			'singular_name' => __( 'Policy', 'ebccc' ),
			'add_new_item'  => __( 'Add New Policy', 'ebccc' ),
			'edit_item'     => __( 'Edit Policy', 'ebccc' ),
			'all_items'     => __( 'All Policies', 'ebccc' ),
		],
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_icon'     => 'dashicons-media-document',
		'menu_position' => 22,
		'supports'      => [ 'title', 'page-attributes' ],
		'show_in_rest'  => true,
	] );

	add_action( 'add_meta_boxes', function () {
		add_meta_box( 'ebccc_policy_details', __( 'Policy Details', 'ebccc' ), 'ebccc_policy_meta_cb', 'policy_document', 'normal', 'high' );
	} );
}

function ebccc_policy_meta_cb( \WP_Post $post ): void {
	wp_nonce_field( 'ebccc_policy_meta', 'ebccc_policy_nonce' );
	$category    = get_post_meta( $post->ID, '_policy_category', true );
	$description = get_post_meta( $post->ID, '_policy_description', true );
	$file_url    = get_post_meta( $post->ID, '_policy_file_url', true );
	$updated     = get_post_meta( $post->ID, '_policy_updated', true );

	$categories = [
		'health-safety'  => __( 'Health & Safety', 'ebccc' ),
		'child-safety'   => __( 'Child Safety & Wellbeing', 'ebccc' ),
		'enrolment-fees' => __( 'Enrolment & Fees', 'ebccc' ),
		'operations'     => __( 'Operations & Governance', 'ebccc' ),
		'staffing'       => __( 'Educators & Staffing', 'ebccc' ),
	];
	?>
	<table class="form-table">
		<tr>
			<th><label for="_policy_category"><?php esc_html_e( 'Category', 'ebccc' ); ?></label></th>
			<td>
				<select id="_policy_category" name="_policy_category" class="widefat">
					<option value=""><?php esc_html_e( '— Select category —', 'ebccc' ); ?></option>
					<?php foreach ( $categories as $val => $label ) : ?>
						<option value="<?php echo esc_attr( $val ); ?>"<?php selected( $category, $val ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php esc_html_e( 'Which section this policy appears under on the Policies page.', 'ebccc' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="_policy_description"><?php esc_html_e( 'Description', 'ebccc' ); ?></label></th>
			<td>
				<textarea id="_policy_description" name="_policy_description" class="widefat" rows="3"><?php echo esc_textarea( $description ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Short description shown on the card (1–2 sentences).', 'ebccc' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="_policy_file_url"><?php esc_html_e( 'PDF File URL', 'ebccc' ); ?></label></th>
			<td>
				<input type="url" id="_policy_file_url" name="_policy_file_url" value="<?php echo esc_url( $file_url ); ?>" class="widefat" placeholder="https://..." />
				<p class="description"><?php esc_html_e( 'Leave blank to show "Available on request". Upload the PDF via Media → Add New, then paste the URL here.', 'ebccc' ); ?></p>
				<?php if ( $file_url ) : ?>
					<p><a href="<?php echo esc_url( $file_url ); ?>" target="_blank"><?php esc_html_e( 'View current PDF ↗', 'ebccc' ); ?></a></p>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="_policy_updated"><?php esc_html_e( 'Last Reviewed', 'ebccc' ); ?></label></th>
			<td>
				<input type="text" id="_policy_updated" name="_policy_updated" value="<?php echo esc_attr( $updated ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. January 2026', 'ebccc' ); ?>" />
				<p class="description"><?php esc_html_e( 'Optional. Shown on the card as "Last reviewed: …"', 'ebccc' ); ?></p>
			</td>
		</tr>
	</table>
	<p class="description" style="margin-top:12px;padding:8px 12px;background:#f0f5e8;border-left:3px solid #4a8c3f;">
		<?php esc_html_e( 'Set the display order using the "Order" field in Page Attributes on the right. Lower numbers appear first within each category.', 'ebccc' ); ?>
	</p>
	<?php
}

add_action( 'save_post_policy_document', 'ebccc_save_policy_meta' );
function ebccc_save_policy_meta( int $post_id ): void {
	if ( ! isset( $_POST['ebccc_policy_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ebccc_policy_nonce'] ) ), 'ebccc_policy_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$text_fields = [ '_policy_category', '_policy_description', '_policy_updated' ];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	if ( isset( $_POST['_policy_file_url'] ) ) {
		update_post_meta( $post_id, '_policy_file_url', esc_url_raw( wp_unslash( $_POST['_policy_file_url'] ) ) );
	}
	if ( isset( $_POST['_policy_description'] ) ) {
		update_post_meta( $post_id, '_policy_description', sanitize_textarea_field( wp_unslash( $_POST['_policy_description'] ) ) );
	}
}

/**
 * Get all policy documents grouped by category, ordered by menu_order.
 *
 * @return array<string, array{ label: string, icon: string, policies: WP_Post[] }>
 */
function ebccc_get_policies_by_category(): array {
	$categories = [
		'health-safety'  => [ 'label' => __( 'Health & Safety', 'ebccc' ),         'icon' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',           'policies' => [] ],
		'child-safety'   => [ 'label' => __( 'Child Safety & Wellbeing', 'ebccc' ), 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8z', 'policies' => [] ],
		'enrolment-fees' => [ 'label' => __( 'Enrolment & Fees', 'ebccc' ),         'icon' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM14 2v6h6M16 13H8M16 17H8M10 9H8', 'policies' => [] ],
		'operations'     => [ 'label' => __( 'Operations & Governance', 'ebccc' ),  'icon' => 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5', 'policies' => [] ],
		'staffing'       => [ 'label' => __( 'Educators & Staffing', 'ebccc' ),     'icon' => 'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z', 'policies' => [] ],
	];

	$posts = get_posts( [
		'post_type'      => 'policy_document',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	] );

	foreach ( $posts as $post ) {
		$cat = get_post_meta( $post->ID, '_policy_category', true );
		if ( $cat && isset( $categories[ $cat ] ) ) {
			$categories[ $cat ]['policies'][] = $post;
		}
	}

	return $categories;
}

// ─────────────────────────────────────────────
// 6b. DISEASE ENTRY CPT
// ─────────────────────────────────────────────
add_action( 'init', 'ebccc_register_disease_cpt' );
function ebccc_register_disease_cpt(): void {
	register_post_type( 'disease_entry', [
		'labels' => [
			'name'          => __( 'Disease Exclusions', 'ebccc' ),
			'singular_name' => __( 'Disease Entry', 'ebccc' ),
			'add_new_item'  => __( 'Add New Disease Entry', 'ebccc' ),
			'edit_item'     => __( 'Edit Disease Entry', 'ebccc' ),
			'all_items'     => __( 'All Disease Entries', 'ebccc' ),
		],
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_icon'     => 'dashicons-warning',
		'menu_position' => 23,
		'supports'      => [ 'title', 'page-attributes' ],
		'show_in_rest'  => true,
	] );

	add_action( 'add_meta_boxes', function () {
		add_meta_box( 'ebccc_disease_details', __( 'Exclusion Details', 'ebccc' ), 'ebccc_disease_meta_cb', 'disease_entry', 'normal', 'high' );
	} );
}

function ebccc_disease_meta_cb( \WP_Post $post ): void {
	wp_nonce_field( 'ebccc_disease_meta', 'ebccc_disease_nonce' );
	$severity        = get_post_meta( $post->ID, '_disease_severity', true ) ?: 'mild';
	$exclude_case    = get_post_meta( $post->ID, '_disease_exclude_case', true );
	$exclude_contact = get_post_meta( $post->ID, '_disease_exclude_contact', true );
	$note            = get_post_meta( $post->ID, '_disease_note', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="_disease_severity"><?php esc_html_e( 'Severity', 'ebccc' ); ?></label></th>
			<td>
				<select id="_disease_severity" name="_disease_severity" class="widefat">
					<option value="high"<?php     selected( $severity, 'high' );     ?>><?php esc_html_e( 'Strict (red) — CHO involvement, long exclusion', 'ebccc' ); ?></option>
					<option value="moderate"<?php selected( $severity, 'moderate' ); ?>><?php esc_html_e( 'Moderate (amber) — days-based exclusion', 'ebccc' ); ?></option>
					<option value="mild"<?php    selected( $severity, 'mild' );     ?>><?php esc_html_e( 'Mild (green) — short exclusion until treated', 'ebccc' ); ?></option>
					<option value="none"<?php    selected( $severity, 'none' );     ?>><?php esc_html_e( 'Not required (grey) — exclusion not necessary', 'ebccc' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="_disease_exclude_case"><?php esc_html_e( 'Exclusion — child with illness', 'ebccc' ); ?></label></th>
			<td>
				<textarea id="_disease_exclude_case" name="_disease_exclude_case" class="widefat" rows="4"><?php echo esc_textarea( $exclude_case ); ?></textarea>
				<p class="description"><?php esc_html_e( 'The exclusion period or condition for the infected child (column 2 of Schedule 7).', 'ebccc' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="_disease_exclude_contact"><?php esc_html_e( 'Exclusion — contacts', 'ebccc' ); ?></label></th>
			<td>
				<textarea id="_disease_exclude_contact" name="_disease_exclude_contact" class="widefat" rows="4"><?php echo esc_textarea( $exclude_contact ); ?></textarea>
				<p class="description"><?php esc_html_e( 'The exclusion period or condition for children who have been in contact with a case (column 3 of Schedule 7).', 'ebccc' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="_disease_note"><?php esc_html_e( 'Footnote (optional)', 'ebccc' ); ?></label></th>
			<td>
				<textarea id="_disease_note" name="_disease_note" class="widefat" rows="3"><?php echo esc_textarea( $note ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Optional footnote shown below the row (e.g. to list pathogens covered by "Diarrhoeal illness"). Leave blank for none.', 'ebccc' ); ?></p>
			</td>
		</tr>
	</table>
	<p class="description" style="margin-top:12px;padding:8px 12px;background:#f0f5e8;border-left:3px solid #4a8c3f;">
		<?php esc_html_e( 'The condition name is the post title. Set display order using "Order" in Page Attributes — lower numbers appear first in the table.', 'ebccc' ); ?>
	</p>
	<?php
}

add_action( 'save_post_disease_entry', 'ebccc_save_disease_meta' );
function ebccc_save_disease_meta( int $post_id ): void {
	if ( ! isset( $_POST['ebccc_disease_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ebccc_disease_nonce'] ) ), 'ebccc_disease_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$textarea_fields = [ '_disease_exclude_case', '_disease_exclude_contact', '_disease_note' ];
	foreach ( $textarea_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	if ( isset( $_POST['_disease_severity'] ) ) {
		$allowed = [ 'high', 'moderate', 'mild', 'none' ];
		$sev = sanitize_text_field( wp_unslash( $_POST['_disease_severity'] ) );
		update_post_meta( $post_id, '_disease_severity', in_array( $sev, $allowed, true ) ? $sev : 'mild' );
	}
}

/**
 * Get all disease entries ordered by menu_order.
 *
 * @return WP_Post[]
 */
function ebccc_get_diseases(): array {
	return get_posts( [
		'post_type'      => 'disease_entry',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	] );
}

// ─────────────────────────────────────────────
// 7. TOUR FORM AJAX HANDLER
// ─────────────────────────────────────────────
add_action( 'wp_ajax_nopriv_ebccc_tour_submit', 'ebccc_handle_tour_submission' );
add_action( 'wp_ajax_ebccc_tour_submit',        'ebccc_handle_tour_submission' );

function ebccc_handle_tour_submission(): void {
	check_ajax_referer( 'ebccc_tour_nonce', 'nonce' );

	$name    = isset( $_POST['name'] )    ? sanitize_text_field( wp_unslash( $_POST['name'] ) )    : '';
	$phone   = isset( $_POST['phone'] )   ? sanitize_text_field( wp_unslash( $_POST['phone'] ) )   : '';
	$email   = isset( $_POST['email'] )   ? sanitize_email( wp_unslash( $_POST['email'] ) )        : '';
	$age     = isset( $_POST['child_age'] )? sanitize_text_field( wp_unslash( $_POST['child_age'] ) ) : '';
	$date    = isset( $_POST['preferred_date'] ) ? sanitize_text_field( wp_unslash( $_POST['preferred_date'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	// Validation
	$errors = [];
	if ( strlen( $name ) < 2 )          $errors[] = __( 'Please enter your full name.', 'ebccc' );
	if ( ! is_email( $email ) )          $errors[] = __( 'Please enter a valid email address.', 'ebccc' );
	if ( ! preg_match( '/^(\+61|0)[2-9]\d{8}$/', preg_replace( '/[\s\-().]/', '', $phone ) ) ) {
		$errors[] = __( 'Please enter a valid Australian phone number.', 'ebccc' );
	}

	if ( ! empty( $errors ) ) {
		wp_send_json_error( [ 'errors' => $errors ], 422 );
	}

	// Build notification email
	$admin_email = get_option( 'admin_email' );
	$to_email    = get_theme_mod( 'ebccc_enquiry_email', $admin_email );
	$subject     = sprintf( __( 'New Tour Enquiry — %s', 'ebccc' ), $name );

	$body  = sprintf( "Name:             %s\n", $name );
	$body .= sprintf( "Phone:            %s\n", $phone );
	$body .= sprintf( "Email:            %s\n", $email );
	$body .= sprintf( "Child's Age:      %s\n", $age ?: 'Not specified' );
	$body .= sprintf( "Preferred Date:   %s\n", $date ?: 'Not specified' );
	$body .= sprintf( "Message:\n%s\n", $message ?: '(none)' );
	$body .= "\n---\nSubmitted via EBCCC website tour form.\n";
	$body .= sprintf( "Site: %s\n", home_url( '/' ) );

	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s <%s>', $name, $email ),
	];

	$sent = wp_mail( $to_email, $subject, $body, $headers );

	if ( $sent ) {
		// Auto-reply to enquirer
		$reply_subject = __( 'Thanks for your tour enquiry — EBCCC', 'ebccc' );
		$reply_body    = sprintf( __( "Hi %s,\n\nThank you for your enquiry about a tour at East Bentleigh Child Care Centre.\n\nOne of our team will call you at %s within 1 business day to arrange a time that suits you.\n\nIf you have any urgent questions in the meantime, please call us directly on 03 9579 4547 (Monday–Friday, 7am–6pm).\n\nWarm regards,\nThe EBCCC Team\nEast Bentleigh Child Care Centre\n70E East Boundary Rd, East Bentleigh VIC 3165\n03 9579 4547\nebccc.org.au", 'ebccc' ), $name, $phone );
		wp_mail( $email, $reply_subject, $reply_body );

		wp_send_json_success( [ 'message' => __( 'Tour request received — we\'ll call you within 1 business day.', 'ebccc' ) ] );
	} else {
		wp_send_json_error( [ 'errors' => [ __( 'There was a problem sending your request. Please call us directly on 03 9579 4547.', 'ebccc' ) ] ], 500 );
	}
}

// ─────────────────────────────────────────────
// 7. HELPER FUNCTIONS
// ─────────────────────────────────────────────

/**
 * Get a page ID by its page template filename.
 */
function ebccc_get_page_by_template( string $template ): int {
	$pages = get_pages( [ 'meta_key' => '_wp_page_template', 'meta_value' => $template ] );
	return ! empty( $pages ) ? $pages[0]->ID : 0;
}

/**
 * Return the phone number from Customizer, escaped for use in href and display.
 */
function ebccc_phone( string $context = 'display' ): string {
	$raw = get_theme_mod( 'ebccc_phone', '03 9579 4547' );
	if ( 'href' === $context ) {
		return 'tel:' . preg_replace( '/[^0-9+]/', '', $raw );
	}
	return esc_html( $raw );
}

/**
 * Return the full address from Customizer.
 */
function ebccc_address( bool $escape = true ): string {
	$addr = get_theme_mod( 'ebccc_address', '70E East Boundary Rd, East Bentleigh VIC 3165' );
	return $escape ? esc_html( $addr ) : $addr;
}

/**
 * Return the contact page URL with optional anchor.
 */
function ebccc_contact_url( string $anchor = '' ): string {
	$pid = ebccc_get_page_by_template( 'page-contact.php' );
	$url = $pid ? get_permalink( $pid ) : home_url( '/contact/' );
	return esc_url( $url . ( $anchor ? '#' . $anchor : '' ) );
}

/**
 * Return Google Maps URL for the centre address.
 */
function ebccc_maps_url(): string {
	$q = get_theme_mod( 'ebccc_maps_query', '70E+East+Boundary+Rd+East+Bentleigh+VIC+3165' );
	return esc_url( 'https://maps.google.com/?q=' . rawurlencode( $q ) );
}

/**
 * Render a page hero section.
 *
 * @param array{
 *   heading:    string,
 *   lead:       string,
 *   eyebrow?:   string,
 *   tag?:       string,
 *   chips?:     string[],
 *   breadcrumbs?: array{label:string,url?:string}[],
 *   class?:     string,
 * } $args
 */
function ebccc_page_hero( array $args ): void {
	get_template_part( 'template-parts/page-hero', null, $args );
}

/**
 * Render the tour booking form.
 */
function ebccc_tour_form(): void {
	get_template_part( 'template-parts/tour-form' );
}

/**
 * Render a CTA banner strip.
 *
 * @param array{heading:string,body:string,cta_label:string,cta_url:string,secondary_label?:string,secondary_url?:string} $args
 */
function ebccc_cta_banner( array $args ): void {
	get_template_part( 'template-parts/cta-banner', null, $args );
}

/**
 * Get all rooms ordered by menu_order.
 *
 * @return \WP_Post[]
 */
function ebccc_get_rooms(): array {
	return get_posts( [
		'post_type'      => 'room',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	] );
}

/**
 * Get room meta value, falling back to ACF get_field() if available.
 */
function ebccc_room_meta( int $post_id, string $key ): string {
	if ( function_exists( 'get_field' ) ) {
		$acf = get_field( ltrim( $key, '_' ), $post_id );
		if ( $acf ) return (string) $acf;
	}
	return (string) get_post_meta( $post_id, $key, true );
}

/**
 * Render a room card for the homepage / programs grid.
 */
function ebccc_room_card( \WP_Post $room, bool $featured = false ): void {
	get_template_part( 'template-parts/room-card', null, [
		'room'     => $room,
		'featured' => $featured,
	] );
}

// ─────────────────────────────────────────────
// 8. CUSTOMIZER
// ─────────────────────────────────────────────
add_action( 'customize_register', 'ebccc_customizer' );
function ebccc_customizer( \WP_Customize_Manager $wp_customize ): void {
	// Panel
	$wp_customize->add_panel( 'ebccc_panel', [
		'title'    => __( 'EBCCC Settings', 'ebccc' ),
		'priority' => 30,
	] );

	// ── Contact Details ───────────────────────
	$wp_customize->add_section( 'ebccc_contact', [
		'title'  => __( 'Contact Details', 'ebccc' ),
		'panel'  => 'ebccc_panel',
	] );
	$contact_settings = [
		'ebccc_phone'         => [ __( 'Phone Number', 'ebccc' ),         '03 9579 4547' ],
		'ebccc_address'       => [ __( 'Street Address', 'ebccc' ),       '70E East Boundary Rd, East Bentleigh VIC 3165' ],
		'ebccc_maps_query'    => [ __( 'Google Maps Query String', 'ebccc' ), '70E+East+Boundary+Rd+East+Bentleigh+VIC+3165' ],
		'ebccc_hours'         => [ __( 'Opening Hours', 'ebccc' ),        'Mon–Fri 7am–6pm' ],
		'ebccc_enquiry_email' => [ __( 'Enquiry Email Address', 'ebccc' ), get_option( 'admin_email' ) ],
	];
	foreach ( $contact_settings as $id => [ $label, $default ] ) {
		$wp_customize->add_setting( $id, [ 'default' => $default, 'sanitize_callback' => 'sanitize_text_field' ] );
		$wp_customize->add_control( $id, [ 'label' => $label, 'section' => 'ebccc_contact', 'type' => 'text' ] );
	}

	// ── Social Links ──────────────────────────
	$wp_customize->add_section( 'ebccc_social', [
		'title' => __( 'Social Media Links', 'ebccc' ),
		'panel' => 'ebccc_panel',
	] );
	$social_settings = [
		'ebccc_facebook_url'  => [ __( 'Facebook URL', 'ebccc' ),  'https://facebook.com' ],
		'ebccc_instagram_url' => [ __( 'Instagram URL', 'ebccc' ), 'https://instagram.com' ],
		'ebccc_storypark_url' => [ __( 'Storypark URL', 'ebccc' ), 'https://family.storypark.com' ],
	];
	foreach ( $social_settings as $id => [ $label, $default ] ) {
		$wp_customize->add_setting( $id, [ 'default' => $default, 'sanitize_callback' => 'esc_url_raw' ] );
		$wp_customize->add_control( $id, [ 'label' => $label, 'section' => 'ebccc_social', 'type' => 'url' ] );
	}

	// ── Homepage Content ──────────────────────
	$wp_customize->add_section( 'ebccc_homepage', [
		'title' => __( 'Homepage Content', 'ebccc' ),
		'panel' => 'ebccc_panel',
	] );
	$hp_settings = [
		'ebccc_hero_heading' => [ __( 'Hero H1 Heading (use || for line breaks)', 'ebccc' ), 'Where children||thrive, learn||and grow.' ],
		'ebccc_hero_lead'    => [ __( 'Hero Lead Paragraph', 'ebccc' ), 'Community-run long day care for children aged 6 weeks to 5 years — open Monday to Friday, 7am to 6pm. All meals included.' ],
		'ebccc_avail_text'   => [ __( 'Availability Module Text', 'ebccc' ), 'Places available — Join our wait list today.' ],
		'ebccc_footer_abn'   => [ __( 'Footer ABN Line', 'ebccc' ), 'ABN 98 216 454 135' ],
		'ebccc_footer_inc'   => [ __( 'Footer Inc. Number', 'ebccc' ), 'Incorporated Assoc. A0006269W' ],
		'ebccc_footer_copy'  => [ __( 'Footer Copyright', 'ebccc' ), '© 2026 East Bentleigh Childcare Centre Association Inc. All rights reserved.' ],
	];
	foreach ( $hp_settings as $id => [ $label, $default ] ) {
		$wp_customize->add_setting( $id, [ 'default' => $default, 'sanitize_callback' => 'sanitize_text_field' ] );
		$wp_customize->add_control( $id, [ 'label' => $label, 'section' => 'ebccc_homepage', 'type' => 'text' ] );
	}
}

// ─────────────────────────────────────────────
// 9. NAV WALKERS — dropdown support
// ─────────────────────────────────────────────

/**
 * Desktop primary nav — supports one level of dropdown.
 * Top-level items with children get a chevron button and aria-expanded.
 * Submenus are <ul class="nav-dropdown"> positioned absolutely.
 */
class EBCCC_Nav_Walker extends \Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '<ul class="nav-dropdown" role="list">' . "\n";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '</ul>' . "\n";
		}
	}

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item       = $data_object;
		$classes    = empty( $item->classes ) ? [] : (array) $item->classes;
		$is_current = in_array( 'current-menu-item', $classes, true )
		           || in_array( 'current-menu-ancestor', $classes, true );
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		$aria_current = $is_current ? ' aria-current="page"' : '';

		if ( $depth === 0 ) {
			$li_class = $has_children ? ' class="nav-item nav-item--has-children"' : ' class="nav-item"';
			$output .= '<li' . $li_class . '>';

			if ( $has_children ) {
				// Parent becomes a button that toggles the dropdown
				$output .= '<button class="nav-link nav-link--parent" '
				         . 'aria-expanded="false" aria-haspopup="true"'
				         . $aria_current . '>';
				$output .= esc_html( $item->title );
				$output .= '<svg class="nav-chevron" width="12" height="12" viewBox="0 0 24 24" '
				         . 'fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">'
				         . '<polyline points="6 9 12 15 18 9"/></svg>';
				$output .= '</button>';
			} else {
				$output .= '<a href="' . esc_url( $item->url ) . '" class="nav-link"' . $aria_current . '>';
				$output .= esc_html( $item->title );
				$output .= '</a>';
			}
		} else {
			// Dropdown item
			$output .= '<li class="nav-dropdown-item">';
			$output .= '<a href="' . esc_url( $item->url ) . '" class="nav-dropdown-link"' . $aria_current . '>';
			$output .= esc_html( $item->title );
			$output .= '</a>';
		}
	}

	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		$output .= '</li>' . "\n";
	}
}

/**
 * Mobile drawer walker — top-level items with children show an accordion toggle.
 * Sub-items are nested below the parent label, collapsed by default.
 */
class EBCCC_Drawer_Walker extends \Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '<ul class="drawer-sub" hidden>' . "\n";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '</ul>' . "\n";
		}
	}

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item         = $data_object;
		$classes      = empty( $item->classes ) ? [] : (array) $item->classes;
		$is_current   = in_array( 'current-menu-item', $classes, true )
		             || in_array( 'current-menu-ancestor', $classes, true );
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$aria_current = $is_current ? ' aria-current="page"' : '';

		if ( $depth === 0 ) {
			$output .= '<li class="drawer-item' . ( $has_children ? ' drawer-item--has-children' : '' ) . '">';

			if ( $has_children ) {
				$output .= '<button class="drawer-link drawer-link--parent" aria-expanded="false"' . $aria_current . '>';
				$output .= esc_html( $item->title );
				$output .= '<svg class="drawer-chevron" width="16" height="16" viewBox="0 0 24 24" '
				         . 'fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">'
				         . '<polyline points="6 9 12 15 18 9"/></svg>';
				$output .= '</button>';
			} else {
				$output .= '<a href="' . esc_url( $item->url ) . '" class="drawer-link"' . $aria_current . '>';
				$output .= esc_html( $item->title );
				$output .= '</a>';
			}
		} else {
			$output .= '<li>';
			$output .= '<a href="' . esc_url( $item->url ) . '" class="drawer-sub-link"' . $aria_current . '>';
			$output .= esc_html( $item->title );
			$output .= '</a>';
		}
	}

	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		$output .= '</li>' . "\n";
	}
}

// Walker for footer nav — simple, no dropdowns
class EBCCC_Footer_Walker extends \Walker_Nav_Menu {
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item = $data_object;
		if ( $depth > 0 ) return; // footer: top level only
		$output .= '<li><a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a></li>' . "\n";
	}
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
}

// ─────────────────────────────────────────────
// 10. FLUSH REWRITE RULES ON ACTIVATION
// ─────────────────────────────────────────────
add_action( 'after_switch_theme', function () {
	ebccc_register_room_cpt();
	ebccc_register_staff_cpt();
	ebccc_register_policy_cpt();
	ebccc_register_disease_cpt();
	ebccc_register_event_cpt();
	flush_rewrite_rules();
} );

// ─────────────────────────────────────────────
// 11. DOCUMENT TITLE
// ─────────────────────────────────────────────
add_filter( 'document_title_separator', fn() => '—' );
add_filter( 'document_title_parts', function ( array $parts ): array {
	if ( is_front_page() ) {
		$parts['tagline'] = '';
		$parts['title']   = get_bloginfo( 'name' ) . ' — Community Childcare, Bentleigh VIC';
		unset( $parts['site'] );
	}
	return $parts;
} );

// ─────────────────────────────────────────────
// 12. DISABLE GUTENBERG FOR ROOMS (use classic editor)
// ─────────────────────────────────────────────
add_filter( 'use_block_editor_for_post_type', function ( bool $use, string $post_type ): bool {
	if ( in_array( $post_type, [ 'room', 'staff_member', 'event' ], true ) ) {
		return false;
	}
	return $use;
}, 10, 2 );

// ─────────────────────────────────────────────
// 13. EXCERPT LENGTH
// ─────────────────────────────────────────────
add_filter( 'excerpt_length', fn() => 30 );
add_filter( 'excerpt_more',   fn() => '…' );

// ─────────────────────────────────────────────
// 14. TEMPLATE FALLBACKS BY SLUG
// Ensures page-events.php loads for the 'events' page
// even if _wp_page_template meta is set to 'default'
// or was set before the template existed.
// ─────────────────────────────────────────────
add_filter( 'page_template', function( string $template ): string {
	if ( is_page( 'events' ) && ( empty( $template ) || 'default' === basename( $template ) || ! file_exists( $template ) ) ) {
		$events_tpl = get_template_directory() . '/page-events.php';
		if ( file_exists( $events_tpl ) ) return $events_tpl;
	}
	return $template;
} );
