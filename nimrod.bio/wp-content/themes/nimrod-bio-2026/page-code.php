<?php
defined( 'ABSPATH' ) || exit;
get_header();
get_template_part( 'template-parts/t1-body', null, array( 'world' => 'code' ) );
get_footer();
