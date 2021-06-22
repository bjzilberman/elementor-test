<?php

// Load parent and child theme.css
function wptest_theme_styles() {
    $theme = wp_get_theme();
    wp_enqueue_style( 'twentytwenty-style', get_template_directory_uri() . '/style.css', array(), $theme->parent()->get('Version') );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'twentytwenty-style' ), $theme->get('Version') );
}

add_action( 'wp_enqueue_scripts', 'wptest_theme_styles' );

// Create new user
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

// Hide adminbar for new user named 'wp-test'
function wptest_hide_adminbar() {
  $user = wp_get_current_user();
  if( is_user_logged_in() && ( $user->user_login == 'wp-test' ) ) {
    add_filter( 'show_admin_bar', '__return_false' );
  }
}

add_action( 'init', 'wptest_hide_adminbar' );

// Create custom post type products
function wptest_posttype_products()
{
  $args = array(
    'labels' => array(
      'name' => __( 'Products' ),
      'singular_name' => __( 'Product' )
    ),
    'public'             => true,
    'rewrite'            => array( 'slug' => 'products' ),
    'has_archive'        => true,
    'menu_icon'          => 'dashicons-cart',
    'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
    'taxonomies'         => array( 'products_category' )
  );
  register_post_type( 'products', $args );
}

add_action( 'init', 'wptest_posttype_products' );

// Create custom taxonomy for custom post type products
function wptest_taxonomy_products_category() {
  $args = array(
    'hierarchical'      => true,
    'labels'            => array(
      'name'              => __( 'Categories' ),
      'singular_name'     => __( 'Category' ),
    ),
    'show_ui'           => true,
    'show_admin_column' => true,
    'rewrite'           => array( 'slug' => 'products_category' ),
  );
  register_taxonomy( 'products_category', array( 'products' ), $args );
}

add_action( 'init', 'wptest_taxonomy_products_category' );

// Create custom metaboxes for custom fields in custom post type
function wptest_products_custom_fields() {
  add_meta_box(
    'wptest_fields_gallery',
    'Product Gallery',
    'wptest_fields_gallery',
    'products',
    'normal'
  );
  add_meta_box(
    'wptest_fields_product_options',
    'Product Options',
    'wptest_fields_product_options',
    'products',
    'side'
  );
}

function wptest_fields_gallery() {
  include( 'admin/products-gallery-field.php' );
}

function wptest_fields_product_options() {
  include( 'admin/products-fields.php' );
}

add_action( 'add_meta_boxes', 'wptest_products_custom_fields' );

// make sure that the custom post type form can submit files (for gallery images)
function wptest_products_form_enctype() {
    echo ' enctype="multipart/form-data"';
}
add_action('post_edit_form_tag', 'wptest_products_form_enctype');

// save everything
function wptest_save_custom_fields( $id ) {

  // save post custom fields data
  $checked = ( $_POST['wptest_field_onsale'] == 'on' ) ? true : false;
  if( !metadata_exists( 'post', $id, 'wptest_field_onsale' ) ) {
    add_post_meta($id, 'wptest_field_onsale', $checked);
  } else {
    update_post_meta($id, 'wptest_field_onsale', $checked);
  }

  $textfields = array(
    'wptest_field_price',
    'wptest_field_saleprice',
    'wptest_field_youtube',
  );

  foreach( $textfields as $field ) {
    if( !metadata_exists( 'post', $id, $field ) ) {
      add_post_meta( $id, $field, $_POST[$field] );
    } else {
      update_post_meta( $id, $field, $_POST[$field] );
    }
  }

  // save the gallery images
  for( $i=1; $i <= 6; $i++ ) {
    if( !empty( $_FILES['wptest_gallery_field_'.$i]['name'] ) ) {
      $upload = wp_upload_bits( $_FILES['wptest_gallery_field_'.$i]['name'], null, file_get_contents( $_FILES['wptest_gallery_field_'.$i]['tmp_name'] ) );
      if( isset( $upload['error'] ) && $upload['error'] != 0 ) {
        wp_die( 'There was an error uploading your file. The error is: ' . $upload['error'] );
      } else {
        add_post_meta( $id, 'wptest_gallery_field_'.$i, $upload );
        update_post_meta( $id, 'wptest_gallery_field_'.$i, $upload );
      }
    }
  }
}

add_action( 'save_post_products', 'wptest_save_custom_fields' );

// style the admin forms and items
function wptest_products_admin_css() {
  wp_enqueue_style( 'wptest_products_css', get_stylesheet_directory_uri() . '/admin/products-fields.css', false, '1.0.0' );
}

add_action('admin_enqueue_scripts', 'wptest_products_admin_css');

// add the products grid to the top of our homepage (before the page's main query)
function wptest_homepage_grid( $query ){
  if( $query->is_main_query() && is_home() ){
    get_template_part( 'parts/homepage-grid' );
  }
}

add_action( 'loop_start', 'wptest_homepage_grid' );

// hide main image on products page
function wptest_hide_product_image( $image ) {
	if( is_single() && get_post_type() == 'products' ) {
		return '';
	} else {
    return $image;
  }
}

add_filter( 'post_thumbnail_html', 'wptest_hide_product_image' );

// add youtube video to content
function wptest_single_product_youtube( $content ) {
  $youtube = get_post_meta( get_the_ID(), 'wptest_field_youtube', true );
  if( is_single() && get_post_type() == 'products' && !empty( $youtube ) ) {
    $content .= '<p>' . $youtube . '</p>';
  }
  return $content;
}

add_filter( 'the_content', 'wptest_single_product_youtube', 0 );

// build the content on the single.php content
function wptest_single_product_page( $content ) {
  if( is_single() && get_post_type() == 'products' ) {
    echo $content;
    get_template_part( 'parts/single-product' );
  } else {
    return $content;
  }
}

add_filter( 'the_content', 'wptest_single_product_page' );

// product box shortcode
function wptest_product_inabox( $atts = array() ) {
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
    $inabox_atts = shortcode_atts(
        array(
            'product_id' => 0,
            'bg_color' => '#fff'
        ), $atts
    );

    $product_inabox = '';

    $args = array(
      'post_type' => 'products',
      'post_status' => 'publish',
      'posts_per_page' => 3,
      'p' => $inabox_atts['product_id'],
    );
    $inabox = new WP_Query( $args );
    while ( $inabox->have_posts() ) { $inabox->the_post();
      $image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
      $title = get_the_title();
      $price = get_post_meta( get_the_ID(), 'wptest_field_price', true );
      $price_onsale = get_post_meta( get_the_ID(), 'wptest_field_saleprice', true );
      $onsale = get_post_meta( get_the_ID(), 'wptest_field_onsale', true );
      $product_inabox .= '<div><a href="' . get_the_permalink() . '" class="product-inabox" style="background-color: ' . $inabox_atts['bg_color'] . '">';
      $product_inabox .= '<div class="inabox-title">' . $title . '</div>';
      $product_inabox .= '<div class="inabox-title"></div>';
      if( !empty( $image ) ) {
        $product_inabox .= '<div class="inabox-image"><img src="' . $image . '" /></div>';
      }
      $product_inabox .= '<div class="inabox-price">';
      if( $onsale ) {
        $product_inabox .= '<span class="color-accent">On Sale!</span> ' . '<s>' . $price . '</s> ' . $price_onsale;
      } else {
        $product_inabox .= $price;
      }
      $product_inabox .= '</div>';
      $product_inabox .= '</a></div>';
    }
    wp_reset_postdata();
    return $product_inabox;
}

add_shortcode('product', 'wptest_product_inabox');

// function to override return value
function wptest_shortcode_filter( $content ) {
  $content .= '<div>Override shortcode return value</div>';
  // Actually override:
  // $content = '<div>Override shortcode return value</div>';
  return $content;
}

add_filter( 'do_shortcode_tag', 'wptest_shortcode_filter' );

// custom color for address bar
function wptest_custom_address_bar() {
  $color = '#cd2653';
  $address_bar = '<meta name="theme-color" content="' . $color . '">';
  $address_bar .= '<meta name="msapplication-navbutton-color" content="' . $color . '">';
  $address_bar .= '<meta name="apple-mobile-web-app-status-bar-style" content="' . $color . '">';
  echo $address_bar;
}

add_action( 'wp_head', 'wptest_custom_address_bar' );
