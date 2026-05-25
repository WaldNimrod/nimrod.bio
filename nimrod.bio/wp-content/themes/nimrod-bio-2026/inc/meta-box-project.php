<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'add_meta_boxes',
	function () {
		add_meta_box( 'nb_project_fields', 'פרטי פרויקט', 'nb_render_project_meta_box', 'project', 'normal', 'high' );
	}
);

function nb_render_project_meta_box( $post ) {
	wp_nonce_field( 'nb_save_project_meta', 'nb_project_nonce' );
	$worlds           = wp_get_post_terms( $post->ID, 'world', array( 'fields' => 'slugs' ) );
	$scope            = nb_meta( $post->ID, 'scope', 'client-case' );
	$stage            = nb_meta( $post->ID, 'stage', 'live' );
	$year             = nb_meta( $post->ID, 'year' );
	$location         = nb_meta( $post->ID, 'location' );
	$duration         = nb_meta( $post->ID, 'duration' );
	$summary          = nb_meta( $post->ID, 'summary' );
	$name_tbc         = (bool) nb_meta( $post->ID, 'name_tbc' );
	$linked_services  = (array) get_post_meta( $post->ID, '_nb_linked_services', true );
	$gallery          = (array) get_post_meta( $post->ID, '_nb_gallery', true );
	$more_projects    = (array) get_post_meta( $post->ID, '_nb_more_projects_ids', true );
	$outcomes         = nb_meta( $post->ID, 'outcomes', '[]' );
	$seeking_note     = nb_meta( $post->ID, 'seeking_note' );
	$legacy_of        = nb_meta( $post->ID, 'legacy_of' );
	?>
	<style>.nb-grid{display:grid;grid-template-columns:190px 1fr;gap:14px 24px;margin:8px 0}.nb-grid label{font-weight:600;align-self:start;padding-top:6px}.nb-grid input[type=text],.nb-grid textarea,.nb-grid select{width:100%}.nb-grid textarea{min-height:80px}.nb-divider{grid-column:1/-1;border-top:1px solid #ddd;margin:8px 0;padding-top:8px;font-weight:700;color:#666}.nb-help{display:block;margin-top:4px;color:#646970;font-size:12px}</style>
	<div class="nb-grid">
		<div class="nb-divider">בסיס</div>
		<label>Summary</label>
		<textarea name="nb[summary]"><?php echo esc_textarea( $summary ); ?></textarea>
		<label>Name TBD?</label>
		<label><input type="checkbox" name="nb[name_tbc]" value="1" <?php checked( $name_tbc ); ?>> שם זמני</label>
		<label>Scope</label>
		<select name="nb[scope]">
			<?php foreach ( nb_get_scopes() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $scope, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<label>Stage</label>
		<select name="nb[stage]">
			<?php foreach ( nb_get_stages() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $stage, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<label>Story</label>
		<div>
			<strong>Story uses the main WordPress editor</strong>
			<span class="nb-help">השדה נשמר ב-post_content (לא ב-meta נפרד).</span>
		</div>

		<div class="nb-divider">מזהים</div>
		<label>Year</label>     <input type="text" name="nb[year]" value="<?php echo esc_attr( $year ); ?>">
		<label>Location</label> <input type="text" name="nb[location]" value="<?php echo esc_attr( $location ); ?>">
		<label>Duration</label> <input type="text" name="nb[duration]" value="<?php echo esc_attr( $duration ); ?>">

		<div class="nb-divider">Worlds</div>
		<label>Worlds (1-3)</label>
		<div>
			<?php foreach ( nb_get_worlds() as $slug => $label ) : ?>
				<label style="display:inline-block;margin-left:16px"><input type="checkbox" name="nb_worlds[]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( in_array( $slug, $worlds, true ) ); ?>> <?php echo esc_html( $label ); ?> (<?php echo esc_html( $slug ); ?>)</label>
			<?php endforeach; ?>
		</div>

		<div class="nb-divider">Outcomes (JSON, 4 slots)</div>
		<label>Outcomes JSON</label>
		<textarea name="nb[outcomes]" placeholder='[{"number":"","label":"","desc":""}, {"number":"","label":"","desc":""}, {"number":"","label":"","desc":""}, {"number":"","label":"","desc":""}]' style="font-family:monospace;font-size:12px"><?php echo esc_textarea( $outcomes ); ?></textarea>

		<div class="nb-divider">Gallery</div>
		<label>Gallery IDs</label>
		<input type="text" name="nb[gallery]" value="<?php echo esc_attr( implode( ',', $gallery ) ); ?>" placeholder="123,456,789">

		<div class="nb-divider">Relations</div>
		<label>Linked services (slugs)</label>
		<input type="text" name="nb[linked_services]" value="<?php echo esc_attr( implode( ',', $linked_services ) ); ?>">
		<label>More projects IDs/slugs</label>
		<input type="text" name="nb[more_projects_ids]" value="<?php echo esc_attr( implode( ',', $more_projects ) ); ?>">

		<div class="nb-divider">Conditional notes</div>
		<label>Seeking note</label>
		<div>
			<textarea name="nb[seeking_note]"><?php echo esc_textarea( $seeking_note ); ?></textarea>
			<span class="nb-help">Used by template only when stage matches.</span>
		</div>
		<label>Legacy of</label>
		<div>
			<textarea name="nb[legacy_of]"><?php echo esc_textarea( $legacy_of ); ?></textarea>
			<span class="nb-help">Used by template only when stage matches.</span>
		</div>
	</div>
	<?php
}

add_action(
	'save_post_project',
	function ( $post_id ) {
		if ( ! isset( $_POST['nb_project_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nb_project_nonce'] ) ), 'nb_save_project_meta' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$in = isset( $_POST['nb'] ) && is_array( $_POST['nb'] ) ? wp_unslash( $_POST['nb'] ) : array();

		update_post_meta( $post_id, '_nb_scope', in_array( $in['scope'] ?? '', array_keys( nb_get_scopes() ), true ) ? $in['scope'] : 'client-case' );
		update_post_meta( $post_id, '_nb_stage', in_array( $in['stage'] ?? '', array_keys( nb_get_stages() ), true ) ? $in['stage'] : 'live' );

		foreach ( array( 'year', 'location', 'duration' ) as $key ) {
			update_post_meta( $post_id, '_nb_' . $key, sanitize_text_field( $in[ $key ] ?? '' ) );
		}
		update_post_meta( $post_id, '_nb_summary', sanitize_textarea_field( $in['summary'] ?? '' ) );
		update_post_meta( $post_id, '_nb_name_tbc', ! empty( $in['name_tbc'] ) ? 1 : 0 );
		update_post_meta( $post_id, '_nb_seeking_note', sanitize_textarea_field( $in['seeking_note'] ?? '' ) );
		update_post_meta( $post_id, '_nb_legacy_of', sanitize_textarea_field( $in['legacy_of'] ?? '' ) );

		$outcomes_raw = (string) ( $in['outcomes'] ?? '' );
		$outcomes     = json_decode( $outcomes_raw, true );
		update_post_meta( $post_id, '_nb_outcomes', null === $outcomes ? $outcomes_raw : wp_json_encode( $outcomes, JSON_UNESCAPED_UNICODE ) );

		foreach ( array( 'linked_services', 'gallery', 'more_projects_ids' ) as $key ) {
			$list = array_filter( array_map( 'sanitize_title', array_map( 'trim', explode( ',', $in[ $key ] ?? '' ) ) ) );
			update_post_meta( $post_id, '_nb_' . $key, $list );
		}

		$worlds = array_values( array_intersect( array_keys( nb_get_worlds() ), (array) ( $_POST['nb_worlds'] ?? array() ) ) );
		wp_set_object_terms( $post_id, $worlds, 'world', false );
	}
);
