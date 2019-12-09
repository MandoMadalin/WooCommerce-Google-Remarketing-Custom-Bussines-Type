<?php
$google_conversion_id = '';
?>

<!-- Global site tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-<?php echo esc_html($google_conversion_id) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'AW-<?php echo esc_html($google_conversion_id) ?>');
</script>


<script type="text/javascript">
	var _data = {'send_to': 'AW-<?php echo esc_html($google_conversion_id) ?>', 'dynx_pagetype':'other'};
	<?php
	if(is_front_page()) {
	?>
		_data["dynx_pagetype"] = 'home';
	<?php	
	}elseif(is_product_category()){
		$product_id = get_the_ID();
		$prod_cats        = get_the_terms( $product_id, 'product_cat' );
	?>
		_data["dynx_pagetype"] = 'searchresults';
		_data["dynx_itemcategory"] = '<?php echo($prod_cats[0]->name); ?>';
	<?php
	}elseif(is_search()){
	?>
		_data["dynx_pagetype"] = 'searchresults';
	<?php
	}elseif(is_product()){
		$product_id = get_the_ID();
		$product    = wc_get_product($product_id);
	?>
		_data["dynx_itemid"] = '<?php echo $product_id; ?>';
		_data["dynx_pagetype"] = 'offerdetail';
		_data["dynx_totalvalue"] = '<?=(float)$product->get_price();?>';
	<?php
	}elseif(is_cart()){
		global  $woocommerce;
		$cartprods = $woocommerce->cart->get_cart();
		$cartprods = array_values($cartprods);
		echo isset($cartprods[0]) ?  '_data["dynx_itemid"] = \''.$cartprods[0]['product_id'].'\';' : '';
		echo isset($cartprods[1]) ?  '_data["dynx_itemid2"] = \''.$cartprods[1]['product_id'].'\';' : '';
	?>
		_data["dynx_pagetype"] = 'conversionintent';
		_data["dynx_totalvalue"] = '<?php echo WC()->cart->get_cart_contents_total(); ?>';
	<?php
	}elseif(is_order_received_page()){
		$order_key      = $_GET['key'];
		$order          = new WC_Order( wc_get_order_id_by_order_key( $order_key ) );
		$order_subtotal = $order->get_subtotal();
		$order_subtotal = $order_subtotal - $order->get_total_discount();
		$order_items       = $order->get_items();
		if(!$order->has_status('failed')){
			$order_items = array_values($order_items);
			echo isset($order_items[0]) ?  '_data["dynx_itemid"] = \''.$order_items[0]['product_id'].'\';' : '';
			echo isset($order_items[1]) ?  '_data["dynx_itemid2"] = \''.$order_items[1]['product_id'].'\';' : '';
		?>
			_data["dynx_pagetype"] = 'conversion';
			_data["dynx_totalvalue"] = '<?=$order_subtotal;?>';
		<?php	
		}
	}
	?>
	gtag('event', 'page_view', _data);
</script>
