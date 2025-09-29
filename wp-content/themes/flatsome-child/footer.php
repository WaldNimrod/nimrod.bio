<?php
/**
 * The template for displaying the footer.
 *
 * @package flatsome
 */

global $flatsome_opt;
?>

</main><!-- #main -->

<footer id="footer" class="footer-wrapper">

	<?php do_action('flatsome_footer'); ?>

</footer><!-- .footer-wrapper -->

</div><!-- #wrapper -->

<?php wp_footer(); ?>

<script>
 wpcf7Elm.addEventListener( 'wpcf7mailsent', function( event ) {
        $('.submit-success').fadeIn();
        setTimeout( function() {
            $('.submit-success').fadeOut();
        },5000);
    }, false );
    
    </script>

</body>
</html>
