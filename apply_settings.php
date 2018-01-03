<?php
add_action( 'wp_ajax_apply_partner_options', 'apply_partner_options' );

function apply_partner_options() {
  var_dump($_POST['data']);
  wp_die();
}
?>