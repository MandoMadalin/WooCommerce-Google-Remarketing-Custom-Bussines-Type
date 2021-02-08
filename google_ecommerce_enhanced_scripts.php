add_action( 'wp_footer', 'add_custom_script_events' , 100);
function add_custom_script_events(){
	
	global $product, $woocommerce, $posts;
	
	
	$enhanced_data = array();
	
	if(is_front_page()) {
		
		?>
		
		<script type="text/javascript">
		console.log('is front page');
		</script>
		
		<?
			
	}elseif(is_product_category()){
		
		?>
		
		<script type="text/javascript">
		console.log('is category page');
		</script>
		
		<?
		
	}elseif(is_product()){
		$product_id = get_the_ID();
		$product    = wc_get_product($product_id);
		

		//$brand = get_the_terms($product_id,'pa_brand'); 
		$enhanced_data['items'] = array();
		
		
		array_push($enhanced_data['items'],array(
			'name' => $product->get_title(),
			'id' => $product_id,
			'price' => (float)$product->get_price(),
		));
				
		
		
		
		?>
		
		
		<script type="text/javascript">
			
			gtag('event','view_item',<?=json_encode($enhanced_data);?>);
			
			jQuery(document).ready(function(){
				jQuery('.single_add_to_cart_button').on('click', function() {
					gtag('event','add_to_cart',<?=json_encode($enhanced_data);?>);
				});
			
			});
			
		</script>
		
		<?
		
		
			
			
				
	}elseif(is_cart()){
		?>
		
		<script type="text/javascript">
		console.log('is cart');
		</script>
		
		<?
		
	}elseif(is_checkout()){
		
		if( is_wc_endpoint_url( 'order-received' ) && isset( $_GET['key'] ) ) {
			
			$order_key      = $_GET['key'];
			$order          = new WC_Order( wc_get_order_id_by_order_key( $order_key ) );
			$order_subtotal = $order->get_subtotal();
			$order_subtotal = $order_subtotal - $order->get_total_discount();
			$order_items       = $order->get_items();
			
			
			if(!$order->has_status('failed')){
				
				$enhanced_data['transaction_id'] = $order->get_id();
				$enhanced_data['value'] = (float)$order_subtotal;
				$enhanced_data['currency'] = 'RON';
				$enhanced_data['items'] = array();

				
				foreach ( $order->get_items() as $item_id => $item ) {
					array_push($enhanced_data['items'],array(
						'name' => $item->get_name(),
						'id' => $item->get_product_id(),
						'price' => (float)$item->get_subtotal(),
						'quantity' => $item->get_quantity()
					));
	            }
				
				
				?>
				
				<script type="text/javascript">
					
					if(document.cookie.indexOf('orderconfirmation-<?=$order->get_id();?>') <= 0){
						
						
						gtag('event','purchase',<?=json_encode($enhanced_data);?>);
						
						document.cookie = 'orderconfirmation-<?=$order->get_id();?>=1; expires=; path=/';
						
					}
				</script>
				
				
				<?

		
			}
			
			
		}else{
			
			$cartprods = $woocommerce->cart->get_cart();
			//$cartprods = array_values($cartprods);
			
			
			//print_r($cartprods);
			$enhanced_data['items'] = array();
			
		
			foreach($cartprods as $item => $values) { 
	            $product =  wc_get_product( $values['data']->get_id()); 
	            
				array_push($enhanced_data['items'],array(
					'name' => $product->get_title(),
					'id' => $values['product_id'],
					//'price' => (float)$product->get_price(),
				));
			
			
			}		

			?>
			
			<script type="text/javascript">
				gtag('event','begin_checkout',<?=json_encode($enhanced_data);?>);
			</script>
			
			<?
		}
		
		
	}elseif(is_order_received_page()){
		
		
		?>
		
		<script type="text/javascript">
			console.log('is_order_received_page()');	
		</script>
			
		<?


	}else{
		?>
		
		<script type="text/javascript">
			console.log('other page');	
		</script>
			
		<?
	}
	
}
