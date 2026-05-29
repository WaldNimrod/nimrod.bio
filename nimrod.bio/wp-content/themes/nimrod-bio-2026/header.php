<?php defined( 'ABSPATH' ) || exit; ?>
<!doctype html>
<html lang="he-IL" dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="theme-color" content="#f5f3ec">
	<meta name="description" content="nimrod.bio - אתר אישי בעברית של נימרוד ולד. אדמה, ייעוץ והוראה, ודיגיטל.">
	<link rel="icon" href="<?php echo esc_url( NB_THEME_URI . '/assets/icons/home.svg' ); ?>" type="image/svg+xml">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-active-world="<?php echo esc_attr( nb_active_world() ?? '' ); ?>" data-page="<?php echo esc_attr( is_singular() ? get_post_field( 'post_name', get_queried_object_id() ) : ( is_page() ? get_post_field( 'post_name', get_queried_object_id() ) : '' ) ); ?>">
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main">דלג לתוכן</a>

<?php get_template_part( 'template-parts/shell-nav' ); ?>

<main id="main" class="nb-main">
