<?php
  $on_sale      = get_post_meta( get_the_ID(), 'wptest_field_onsale', true );
  $sale_price   = get_post_meta( get_the_ID(), 'wptest_field_saleprice', true );
  $price        = get_post_meta( get_the_ID(), 'wptest_field_price', true );
?>
<div class="single-product-page">
  <div class="product-info">
    <div class="pinfo-key">Price</div>
    <div class="pinfo-line"></div>
    <div class="pinfo-value">
      <?php
        if( $on_sale ) {
          echo '<span class="color-accent">On Sale!</span> ' . '<s>' . $price . '</s> ' . $sale_price;
        } else {
          echo $price;
        }
      ?>
    </div>
  </div>

  <div class="single-gallery">

    <?php for ( $field = 1; $field <= 6; $field++ ) { ?>

      <?php
        $product_gallery_image = get_post_meta( get_the_ID(), 'wptest_gallery_field_' . $field, true );
        if( !empty( $product_gallery_image ) ) {
      ?>
      <a href="<?php echo $product_gallery_image['url']; ?>" target="_blank" class="single-gallery-item">
        <img src="<?php echo $product_gallery_image['url']; ?>" />
      </a>
      <?php } ?>

    <?php } ?>

  </div>

</div>

<div class="more-products">

  <?php
    $terms = get_the_terms( $post->ID , 'products_category' );
    $term = array_pop( $terms );
  ?>
  <b>More <u><?= $term->name ?></u> Products</b>:
  <?php
    $args = array(
      'post_type' => 'products',
      'post_status' => 'publish',
      'posts_per_page' => 3,
      'tax_query' => array(
          array(
              'taxonomy' => 'products_category',
              'field'    => 'slug',
              'terms'    => $term->slug,
          ),
      ),
    );
    $related_loop = new WP_Query( $args );
    while ( $related_loop->have_posts() ) { $related_loop->the_post();
      // TODO: add some more styling or change the template
      ?>
      <div class=""><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
      <?php
    }
    wp_reset_postdata();
  ?>
</div>
