<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_post_type(
			'service',
			array(
				'labels'        => array(
					'name'          => 'פעילויות',
					'singular_name' => 'פעילות',
					'add_new'       => 'הוסף פעילות',
					'add_new_item'  => 'הוסף פעילות חדשה',
					'edit_item'     => 'ערוך פעילות',
					'new_item'      => 'פעילות חדשה',
					'view_item'     => 'הצג פעילות',
					'search_items'  => 'חפש פעילויות',
					'not_found'     => 'לא נמצאו פעילויות',
					'menu_name'     => 'פעילויות (services)',
				),
				'public'        => true,
				'show_in_menu'  => true,
				'menu_icon'     => 'dashicons-portfolio',
				'menu_position' => 25,
				'show_in_rest'  => true,
				'rest_base'     => 'services',
				'has_archive'   => false,
				'rewrite'       => array(
					'slug'       => 'services',
					'with_front' => false,
				),
				'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
				'taxonomies'    => array( 'world' ),
			)
		);
	}
);
