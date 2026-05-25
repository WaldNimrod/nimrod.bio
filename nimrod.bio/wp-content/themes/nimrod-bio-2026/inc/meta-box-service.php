<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'add_meta_boxes',
	function () {
		add_meta_box( 'nb_service_fields', 'פרטי פעילות', 'nb_render_service_meta_box', 'service', 'normal', 'high' );
	}
);

function nb_render_service_meta_box( $post ) {
	wp_nonce_field( 'nb_save_service_meta', 'nb_service_nonce' );
	$worlds        = wp_get_post_terms( $post->ID, 'world', array( 'fields' => 'slugs' ) );
	$tagline       = nb_meta( $post->ID, 'tagline' );
	$lede          = nb_meta( $post->ID, 'lede' );
	$service_type  = nb_meta( $post->ID, 'service_type', 'service' );
	$stage         = nb_meta( $post->ID, 'stage', 'live' );
	$is_free       = (bool) nb_meta( $post->ID, 'is_free' );
	$cta_label     = nb_meta( $post->ID, 'cta_label' );
	$cta_href      = nb_meta( $post->ID, 'cta_whatsapp_href' );
	$is_anchor     = nb_meta( $post->ID, 'is_anchor_for_world' );
	$sections_json = nb_meta( $post->ID, 'sections', '{"who":"","how":"","what":""}' );
	$meta_strip    = nb_meta( $post->ID, 'meta_strip', '[]' );
	$linked        = (array) get_post_meta( $post->ID, '_nb_linked_projects', true );
	$related       = (array) get_post_meta( $post->ID, '_nb_related_posts', true );
	?>
	<style>.nb-grid{display:grid;grid-template-columns:160px 1fr;gap:14px 24px;margin:8px 0}.nb-grid label{font-weight:600;align-self:start;padding-top:6px}.nb-grid input[type=text],.nb-grid input[type=url],.nb-grid textarea,.nb-grid select{width:100%}.nb-grid textarea{min-height:80px}.nb-divider{grid-column:1/-1;border-top:1px solid #ddd;margin:8px 0;padding-top:8px;font-weight:700;color:#666}</style>
	<div class="nb-grid">
		<label>Tagline</label>            <input type="text" name="nb[tagline]" value="<?php echo esc_attr( $tagline ); ?>">
		<label>Lede</label>               <textarea name="nb[lede]"><?php echo esc_textarea( $lede ); ?></textarea>
		<label>Worlds (1-3)</label>
		<div>
			<?php foreach ( nb_get_worlds() as $slug => $label ) : ?>
				<label style="display:inline-block;margin-left:16px"><input type="checkbox" name="nb_worlds[]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( in_array( $slug, $worlds, true ) ); ?>> <?php echo esc_html( $label ); ?> (<?php echo esc_html( $slug ); ?>)</label>
			<?php endforeach; ?>
		</div>
		<label>Service type</label>
		<select name="nb[service_type]">
			<?php foreach ( nb_get_service_types() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $service_type, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<label>Stage</label>
		<select name="nb[stage]">
			<?php foreach ( nb_get_stages() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $stage, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<label>Is free?</label>           <label><input type="checkbox" name="nb[is_free]" value="1" <?php checked( $is_free ); ?>> פעילות חינמית</label>
		<label>CTA label</label>          <input type="text" name="nb[cta_label]" value="<?php echo esc_attr( $cta_label ); ?>">
		<label>CTA href (WhatsApp / URL)</label><input type="text" name="nb[cta_whatsapp_href]" value="<?php echo esc_attr( $cta_href ); ?>" placeholder="https://wa.me/972547776770">
		<label>Anchor for world</label>
		<select name="nb[is_anchor_for_world]">
			<option value="">- none -</option>
			<?php foreach ( nb_get_worlds() as $slug => $label ) : ?>
				<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $is_anchor, $slug ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>

		<div class="nb-divider">סקציות (T2 §01-03 - who / how / what)</div>
		<label>Sections JSON</label>
		<textarea name="nb[sections]" placeholder='{"who":"...","how":"...","what":"..."}' style="font-family:monospace;font-size:12px"><?php echo esc_textarea( $sections_json ); ?></textarea>

		<div class="nb-divider">Meta strip (4 פריטים - JSON array)</div>
		<label>Meta strip JSON</label>
		<textarea name="nb[meta_strip]" placeholder='[{"k":"שם","v":"ערך"}, ...]' style="font-family:monospace;font-size:12px"><?php echo esc_textarea( $meta_strip ); ?></textarea>

		<div class="nb-divider">קישורים</div>
		<label>Linked projects (slugs, comma-separated)</label>
		<input type="text" name="nb[linked_projects]" value="<?php echo esc_attr( implode( ',', $linked ) ); ?>">
		<label>Related posts (slugs, comma-separated)</label>
		<input type="text" name="nb[related_posts]" value="<?php echo esc_attr( implode( ',', $related ) ); ?>">
	</div>
	<?php
}

add_action(
	'save_post_service',
	function ( $post_id ) {
		if ( ! isset( $_POST['nb_service_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nb_service_nonce'] ) ), 'nb_save_service_meta' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$in = isset( $_POST['nb'] ) && is_array( $_POST['nb'] ) ? wp_unslash( $_POST['nb'] ) : array();

		foreach ( array( 'tagline', 'lede', 'cta_label' ) as $key ) {
			update_post_meta( $post_id, '_nb_' . $key, sanitize_text_field( $in[ $key ] ?? '' ) );
		}
		update_post_meta( $post_id, '_nb_service_type', in_array( $in['service_type'] ?? '', array_keys( nb_get_service_types() ), true ) ? $in['service_type'] : 'service' );
		update_post_meta( $post_id, '_nb_stage', in_array( $in['stage'] ?? '', array_keys( nb_get_stages() ), true ) ? $in['stage'] : 'live' );
		update_post_meta( $post_id, '_nb_is_free', ! empty( $in['is_free'] ) ? 1 : 0 );
		update_post_meta( $post_id, '_nb_cta_whatsapp_href', nb_sanitize_url_field( $in['cta_whatsapp_href'] ?? '' ) );
		update_post_meta( $post_id, '_nb_is_anchor_for_world', in_array( $in['is_anchor_for_world'] ?? '', array_keys( nb_get_worlds() ), true ) ? $in['is_anchor_for_world'] : '' );

		foreach ( array( 'sections', 'meta_strip' ) as $key ) {
			$raw     = (string) ( $in[ $key ] ?? '' );
			$decoded = json_decode( $raw, true );
			update_post_meta( $post_id, '_nb_' . $key, null === $decoded ? $raw : wp_json_encode( $decoded, JSON_UNESCAPED_UNICODE ) );
		}

		foreach ( array( 'linked_projects', 'related_posts' ) as $key ) {
			$list = array_filter( array_map( 'sanitize_title', array_map( 'trim', explode( ',', $in[ $key ] ?? '' ) ) ) );
			update_post_meta( $post_id, '_nb_' . $key, $list );
		}

		$worlds = array_values( array_intersect( array_keys( nb_get_worlds() ), (array) ( $_POST['nb_worlds'] ?? array() ) ) );
		wp_set_object_terms( $post_id, $worlds, 'world', false );
	}
);
