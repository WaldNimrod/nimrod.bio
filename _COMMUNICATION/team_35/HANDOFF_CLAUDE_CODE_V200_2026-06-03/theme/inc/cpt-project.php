<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_post_type(
			'project',
			array(
				'labels'        => array(
					'name'          => 'פרויקטים',
					'singular_name' => 'פרויקט',
					'add_new'       => 'הוסף פרויקט',
					'add_new_item'  => 'הוסף פרויקט חדש',
					'edit_item'     => 'ערוך פרויקט',
					'new_item'      => 'פרויקט חדש',
					'view_item'     => 'הצג פרויקט',
					'search_items'  => 'חפש פרויקטים',
					'not_found'     => 'לא נמצאו פרויקטים',
					'menu_name'     => 'פרויקטים (projects)',
				),
				'public'        => true,
				'show_in_menu'  => true,
				'menu_icon'     => 'dashicons-portfolio',
				'menu_position' => 26,
				'show_in_rest'  => true,
				'rest_base'     => 'projects',
				'has_archive'   => 'projects',
				'rewrite'       => array(
					'slug'       => 'project',
					'with_front' => false,
				),
				'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
				'taxonomies'    => array( 'world' ),
			)
		);
	}
);
