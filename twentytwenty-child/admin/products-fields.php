<label>
  <p class="description">Price</p>
  <input type="number" name="wptest_field_price" value="<?= get_post_meta( get_the_ID(), 'wptest_field_price', true ) ?>" placeholder="Product Price" />
</label>

<label>
  <p class="description">Sale Price</p>
  <input type="number" name="wptest_field_saleprice" value="<?= get_post_meta( get_the_ID(), 'wptest_field_saleprice', true ) ?>" placeholder="Product Sale Price" />
</label>

<label>
  <?php $checked = get_post_meta( get_the_ID(), 'wptest_field_onsale', true ); ?>
  <p class="description">Product on Sale</p>
  <input type="checkbox" name="wptest_field_onsale" <?= ($checked) ? 'checked' : '' ?> /> On Sale
</label>

<label>
  <p class="description">YouTube video</p>
  <input type="text" name="wptest_field_youtube" value="<?= get_post_meta( get_the_ID(), 'wptest_field_youtube', true ) ?>" placeholder="Product YouTube Video" />
</label>
