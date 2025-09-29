<?php // For implementation instructions see: https://aceplugins.com/how-to-add-a-code-snippet/
/**
 * Override loop template and show quantities next to add to cart buttons
 */


function child_remove_parent_function() {
    add_action( 'woocommerce_init', 'remove_message_after_add_to_cart', 99);

    function remove_message_after_add_to_cart(){
        if( isset( $_GET['add-to-cart'] ) ){
            wc_clear_notices();
        }
    }

}
add_action( 'wp_loaded', 'child_remove_parent_function' );