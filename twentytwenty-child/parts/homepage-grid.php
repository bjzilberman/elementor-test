<h2 class="heading-size-2 wptest-grid-heading">Products</h2>

<div class="wptest-homepage-grid">

<?php
  $args = array(
    'post_type' => 'products',
    'post_status' => 'publish',
    'posts_per_page' => -1
  );
  $loop = new WP_Query( $args );
  while ( $loop->have_posts() ) { $loop->the_post();
  $display_image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
?>

<div class="wptest-single-product">
  <a href="<?php the_permalink(); ?>">
    <div class="inner">
      <?php if( get_post_meta( get_the_ID(), 'wptest_field_onsale', true ) ) { ?>
        <div class="on-sale"><span>On Sale!</span></div>
      <?php } ?>
      <div class="product-title"><?php the_title(); ?></div>
      <div class="product-image">
      <?php if( !empty( $display_image ) ) { ?>
        <img src="<?= $display_image; ?>" />
      <?php } else { ?>
        No Image
      <?php } ?>
      </div>
    </div>
  </a>
</div>

<?php
  }
  wp_reset_postdata();
?>

</div>

<hr class="post-separator styled-separator is-style-wide section-inner" />
