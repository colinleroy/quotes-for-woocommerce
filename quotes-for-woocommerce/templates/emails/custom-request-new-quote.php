<?php
/**
 * Request New Quote email
 */
$order_obj = new WC_order( $order->order_id );

$opening_paragraph = __( 'A request for quote has been made by %s and is awaiting your attention. The details of the order are as follows:', 'quote-wc' );
?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<?php
$billing_first_name = ( version_compare( WOOCOMMERCE_VERSION, "3.0.0" ) < 0 ) ? $order_obj->billing_first_name : $order_obj->get_billing_first_name();
$billing_last_name = ( version_compare( WOOCOMMERCE_VERSION, "3.0.0" ) < 0 ) ? $order_obj->billing_last_name : $order_obj->get_billing_last_name(); 
if ( $order && $billing_first_name && $billing_last_name ) : ?>
	<p><?php printf( $opening_paragraph, $billing_first_name . ' ' . $billing_last_name ); ?></p>
<?php endif; ?>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<tbody>
		<tr>
			<th style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product Image', 'quote-wc' ); ?></th>
			<th style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'quote-wc' ); ?></th>
			<th style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'quote-wc' ); ?></th>
			<th style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product Price', 'quote-wc' ); ?></th>
			
		</tr>
		<?php
		foreach( $order_obj->get_items() as $items ) {
			$product_id = $items->get_product_id();
			$variation_id = $items->get_variation_id();
			$product_id = isset( $variation_id ) && $variation_id > 0 ? $variation_id : $product_id;
			$image = '';
			$shop_thumbnail = array( 125, 125 );
			if ( has_post_thumbnail( $product_id ) ) {
				$image = get_the_post_thumbnail( $product_id, $shop_thumbnail );
			} elseif ( ( $parent_id = wp_get_post_parent_id( $product_id ) ) && has_post_thumbnail( $parent_id ) ) {
				$image = get_the_post_thumbnail( $parent_id, $shop_thumbnail );
			} elseif ( $placeholder ) {
				$image = wc_placeholder_img( $shop_thumbnail );
			}
		    ?>
		    <tr>
		    	<td style="text-align:left; border: 1px solid #eee;"><?php echo $image; ?></td>
                <td style="text-align:left; border: 1px solid #eee;"><?php echo $items->get_name(); ?></td>
                <td style="text-align:left; border: 1px solid #eee;"><?php echo $items->get_quantity(); ?></td>
                <td style="text-align:left; border: 1px solid #eee;"><?php echo $order_obj->get_formatted_line_subtotal( $items ); ?></td>
            </tr>
            <?php 
		} 
		?>
	</tbody>
</table>

<p><?php _e( 'This order is awaiting a quote.', 'quote-wc' ); ?></p>

<p><?php echo make_clickable( sprintf( __( 'You can view and edit this order in the dashboard here: %s', 'quote-wc' ), admin_url( 'post.php?post=' . $order->order_id . '&action=edit' ) ) ); ?></p>

<?php do_action( 'woocommerce_email_footer' ); ?>
