<?php
/*
Plugin Name: Megaventory Example
*/

if (!defined('ABSPATH')) { 
    exit; // Exit if accessed directly
}
require_once( ABSPATH . "wp-includes/pluggable.php" );
require_once("classes.php");

$GLOBALS["MG"] = new Megaventory();

function order_placed($order_id){
    $order = wc_get_order($order_id);
    var_dump($order);
    echo "<br><br>";
    
    foreach ($order->get_items() as $value) {
		echo $value;
		$product = new WC_Product($value['product_id']);
		echo $product->get_sku();
	}
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	
	$hook_to = 'woocommerce_thankyou';
	$what_to_hook = 'order_placed';
	$prioriy = 111;
	$num_of_arg = 1;    
	add_action($hook_to, $what_to_hook, $prioriy, $num_of_arg);
	
	add_action('admin_menu', 'test_plugin_setup_menu');
}

function test_plugin_setup_menu(){
        add_menu_page( 'Test Plugin Page', 'Test Plugin', 'manage_options', 'test-plugin', 'test_init' );
}
 
function test_init(){
	echo '<form id="sync-categories-form" method="post">';
	echo '<input type="hidden" name="sync-categories" value="true" />';
	echo '<input type="submit" value="Synchronize Products" />';
	echo '</form>';
}

if (isset($_POST['sync-categories'])) {
	add_action('init', 'synchronize_categories');
}

function synchronize_categories() {
	
	$prods = $GLOBALS["MG"]->get_products();
	
	foreach ($prods as $product) {		
		//var_dump($product);
		echo "Now doing: " . $product->SKU;
		wc_save_product($product); 
		echo "<br>";
		
		break; //debug
	}
	
}

?>
