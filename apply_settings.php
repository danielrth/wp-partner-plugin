<?php
add_action( 'wp_ajax_apply_partner_options', 'apply_partner_options' );

function apply_partner_options() {

  define("ELEID_CITIES_HEADLINE", "itm-ptn-cst-cities-headline");
  define("ELEID_ABOUT_US", "itm-ptn-cst-about-us");
  define("ELEID_FOOTER", "itm-ptn-cst-footer");
  define("ELEID_PHONE", "itm-ptn-cst-phone");
  define("ELEID_CALLCENTER_HOUR", "itm-ptn-cst-callcenter-hour");
  define("ELEID_SATISFACTION", "itm-ptn-cst-satisfaction");

  global $wpdb;

  $tableName = $_POST['data']['table_prefix'] . 'posts';

  if ( !$wpdb->query("SELECT 1 FROM {$tableName} LIMIT 1") ) {
      wp_die("Table Not Exist!");
  }

  //---------------cities headline-----------------
  $query = "SELECT * FROM {$tableName} WHERE post_content LIKE '%" 
    . ELEID_CITIES_HEADLINE . "%' AND post_status='publish'";
  $posts = $wpdb->get_results($query);

  for ($i = 0; $i < count($posts); $i++) {

    $postContent = $posts[$i]->post_content;
    $idPos = strpos( $postContent, ELEID_CITIES_HEADLINE );
    $repStart = strpos($postContent, '>', $idPos) + 1;
    $repEnd = strpos($postContent, '<', $idPos);

    if ( substr($postContent, $repStart, $repEnd - $repStart) != $_POST['data']['cities_headline'] ) {
      $newString = substr_replace($postContent, $_POST['data']['cities_headline'], $repStart, $repEnd - $repStart);
      if ( $wpdb->update(
        $tableName,
        array( 'post_content' => $newString ),
        array( 'ID' => $posts[$i]->ID ),
        array( '%s' ),
        array( '%d' )
      ) > 0 )
        echo "City headline changed.\n";
    }
    
  }

  //---------------about us-----------------
  //should replace all appearances
  $query = "SELECT * FROM {$tableName} WHERE post_content LIKE '%" . 
    ELEID_ABOUT_US . "%' AND post_status='publish'";
  $posts = $wpdb->get_results($query);

  for ($i = 0; $i < count($posts); $i++) {
    
    $postContent = $posts[$i]->post_content;
    $eleCount = substr_count($postContent, ELEID_ABOUT_US);

    $newString = $postContent;
    $newPos = 0;

    for ($eleNo=0; $eleNo < $eleCount; $eleNo++) { 
      $idPos = strpos($newString, ELEID_ABOUT_US, $newPos);
      $repStart = strpos($newString, '>', $idPos) + 1;
      $repEnd = strpos($newString, '</span>', $idPos);
      $newPos = $repEnd;

      if ( substr($newString, $repStart, $repEnd - $repStart) != $_POST['data']['about_us'] )
        $newString = substr_replace($newString, $_POST['data']['about_us'], $repStart, $repEnd - $repStart);
    }
    

    if ( $newString != $postContent ) {
      if ( $wpdb->update(
        $tableName,
        array( 'post_content' => $newString ),
        array( 'ID' => $posts[$i]->ID ),
        array( '%s' ),
        array( '%d' )
      ) > 0 )
        echo "AboutUs changed.\n";
    }
    
  }

  //---------------footer-----------------
  $query = "SELECT * FROM {$tableName} WHERE post_content LIKE '%"
    . ELEID_FOOTER . "%' AND post_status='publish'";
  $posts = $wpdb->get_results($query);

  for ($i = 0; $i < count($posts); $i++) {
    
    $postContent = $posts[$i]->post_content;
    $idPos = strpos( $postContent, ELEID_FOOTER );
    $repStart = strpos($postContent, '>', $idPos) + 1;
    $repEnd = strpos($postContent, '</span>', $idPos);
    $strFooter = "<em><small>" . $_POST['data']['footer'] . "</small></em>";

    if ( substr($postContent, $repStart, $repEnd - $repStart) != $strFooter ) {
      $newString = substr_replace($postContent, $strFooter, $repStart, $repEnd - $repStart);
      if ( $wpdb->update(
        $tableName,
        array( 'post_content' => $newString ),
        array( 'ID' => $posts[$i]->ID ),
        array( '%s' ),
        array( '%d' )
      ) > 0 )
        echo "Footer changed.\n";
    }
    
  }

  //---------------phone-----------------
  //should replace all appearances
  $query = "SELECT * FROM {$tableName} WHERE post_content LIKE '%" . 
    ELEID_PHONE . "%' AND post_status='publish'";
  $posts = $wpdb->get_results($query);

  for ($i = 0; $i < count($posts); $i++) {
    
    $postContent = $posts[$i]->post_content;
    $eleCount = substr_count($postContent, ELEID_PHONE);

    $newString = $postContent;
    $newPos = 0;

    for ($eleNo=0; $eleNo < $eleCount; $eleNo++) { 
      $idPos = strpos($newString, ELEID_PHONE, $newPos);
      $repStart = strpos($newString, '>', $idPos) + 1;
      $repEnd = strpos($newString, '</span>', $idPos);
      $newPos = $repEnd;

      if ( substr($newString, $repStart, $repEnd - $repStart) != $_POST['data']['phone'] )
        $newString = substr_replace($newString, $_POST['data']['phone'], $repStart, $repEnd - $repStart);
    }
    
    if ( $newString != $postContent ) {
      if ( $wpdb->update(
        $tableName,
        array( 'post_content' => $newString ),
        array( 'ID' => $posts[$i]->ID ),
        array( '%s' ),
        array( '%d' )
      ) > 0 )
        echo "Phone changed.\n";
    }
    
  }

  wp_die();
}
?>