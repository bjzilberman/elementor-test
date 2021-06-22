# elementor-test
Elementor Wordpress Development Test,
I have posted screenshots of arbitrary data, not attached to this repository, as it wasn't stated in the requirements.

### Display Products on the homepage as a grid list

Items displayed on the frontpage, on sale items have a badge, displaying that they're on sale. Made sure they look good in every screen size.

![large](https://i.imgur.com/JXwgOTd.png 'large')

![medium](https://i.imgur.com/fs0pUGV.png 'medium')

![small](https://i.imgur.com/zvUcfjn.png 'small')

### Single Product page
Code adds to existing single.php template to use with custom post type, it will appear like so (with youtube added to the end of the description, price, gallery and related afterwards):

![single](https://i.imgur.com/1VguSQS.png)

After the gallery:

![single](https://i.imgur.com/3SSI2xQ.png)

### shortcode

`[product product_id=35 bg_color=lightyellow]` will produce:

![shortcode](https://i.imgur.com/x3T2LLc.png 'shortcode')

### json-api
Display items under custom products category via category ID/name
```
/wp-json/products/v1/products/<ID|name>
```
