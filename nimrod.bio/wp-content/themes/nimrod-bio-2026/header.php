<?php defined( 'ABSPATH' ) || exit; ?>
<!doctype html>
<html lang="he-IL" dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="theme-color" content="#f5f3ec">
	<meta name="description" content="nimrod.bio - אתר אישי בעברית של נימרוד ולד. אדמה, ייעוץ והוראה, ודיגיטל.">
	<link rel="icon" href="<?php echo esc_url( NB_THEME_URI . '/assets/img/favicon.svg' ); ?>" type="image/svg+xml">
	<link rel="alternate icon" href="<?php echo esc_url( NB_THEME_URI . '/assets/img/favicon-32.png' ); ?>" sizes="32x32">
	<link rel="apple-touch-icon" href="<?php echo esc_url( NB_THEME_URI . '/assets/img/favicon-180.png' ); ?>">
	<?php
	// Open Graph / Twitter fallback image. Only emit when Yoast is NOT active —
	// when Yoast is present it owns OG output (see wpseo_opengraph_image filter
	// in functions.php), so this guard prevents duplicate og:image tags.
	if ( ! defined( 'WPSEO_VERSION' ) ) :
		$nb_og_image = esc_url( NB_THEME_URI . '/assets/img/og-image.png' );
		?>
	<meta property="og:image" content="<?php echo $nb_og_image; ?>">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:image" content="<?php echo $nb_og_image; ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-active-world="<?php echo esc_attr( nb_active_world() ?? '' ); ?>" data-page="<?php echo esc_attr( is_singular() ? get_post_field( 'post_name', get_queried_object_id() ) : ( is_page() ? get_post_field( 'post_name', get_queried_object_id() ) : '' ) ); ?>">
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main">דלג לתוכן</a>

<?php get_template_part( 'template-parts/shell-nav' ); ?>

<main id="main" class="nb-main">
