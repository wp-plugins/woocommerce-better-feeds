<?php
/*
Plugin Name: WooCommerce Better Feeds
Plugin URI: http://www.limeframe.gr/wpplugins/woocommerce-better-feeds
Description: Add Featured Image and Price to woocommerce rss feeds
Version: 1.1
Author: Giannopoulos Konstantinos
Author URI: http://www.limeframe.gr
Domain Path: /languages
Text Domain: woocommerce-better-feeds
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/


function getRssProductPrice() {
	global $post;
	$product = new WC_Product( $post->ID );
	$theproductprice = $product->price;
	return $theproductprice;
}

function wcbfeedAddImageToContent($content) {
	$wcbfeed_rss_image_size = get_option('wcbfeed_rss_image_size');
	global $post;
	if(get_option('wcbfeed_rss_export_image')==1) {
		if ( has_post_thumbnail( $post->ID ) ){
			$content = '' . get_the_post_thumbnail( $post->ID, $wcbfeed_rss_image_size, array( 'style' => 'float:left; margin:0 15px 15px 0;' ) ) . '' . $content;
		}
	}
	if(get_option('wcbfeed_rss_export_price')==1) {
		$content = '<br>Price:' . getRssProductPrice() . '<br>' . $content;
	}
	return $content;
}


add_filter('the_excerpt_rss', 'wcbfeedAddImageToContent');
add_filter('the_content_feed', 'wcbfeedAddImageToContent');


function addImageUrlToCustomTag() {
	global $post;
	$theproductimage = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID),'medium' );
	return $theproductimage[0];
}



add_action('rss_item','wcbfeedAddExtraItems');
add_action('rss2_item','wcbfeedAddExtraItems');

function wcbfeedAddExtraItems() { ?>

<?php if(get_option('wcbfeed_rss_export_price')==1 || get_option('wcbfeed_rss_export_image')==1) {?>
<product>
<?php if(get_option('wcbfeed_rss_export_price')==1) { ?>
	<price><?php print_r(getRssProductPrice());?></price>
<?php } ?>
<?php if(get_option('wcbfeed_rss_export_image')==1) { ?>
<image><?php echo addImageUrlToCustomTag();?></image>
<?php } ?>
</product>

<?php } }

include('wcbfeed.php');
?>