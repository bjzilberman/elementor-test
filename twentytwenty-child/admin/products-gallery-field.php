<div class="products-gallery">
<?php for ( $field = 1; $field <= 6; $field++ ) { ?>
  <div class="products-gallery-field">
    <label>
      <p class="description">
        Gallery Image #<?php echo $field; ?>
      </p>
      <?php
        $product_gallery_image = get_post_meta( get_the_ID(), 'wptest_gallery_field_' . $field, true );
        if( !empty( $product_gallery_image ) ) {
      ?>

      <div class="products-gallery-field-preview">
        <img src="<?php echo $product_gallery_image['url']; ?>" />
      </div>

      <?php } else { ?>

      <div class="products-gallery-field-new">
        <?php echo __( 'New Image', 'products_gallery_new_image' ); ?>
      </div>

      <?php } ?>
      <a class="button">Select an Image</a>
      <input type="file" id="wptest_gallery_field_<?php echo $field; ?>" name="wptest_gallery_field_<?php echo $field; ?>" onchange="jQuery(this).prev().prev().html('Waiting for save.').addClass('waiting')" />
    </label>
  </div>
<?php } ?>
</div>
