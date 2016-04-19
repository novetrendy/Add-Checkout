<?php
/*
Plugin Name: Add Checkout
Plugin URI: http://webstudionovetrendy.eu/
Description: Add a buttons "continue shopping" and "empty cart" and the custom text on the cart page
Version: 1.1
Author: Webstudio Nove Trendy
Author URI: http://webstudionovetrendy.eu/
Text Domain: add-checkout
DomainPath: /includes/languages/
License: Free to use and adapt
WC requires at least: 2.3
WC tested up to: 2.4
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Zkontrolujeme jestli je WooCommerce aktivní
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

/**
 * Vložíme soubor s lokalizací
 */
 add_action('plugins_loaded', 'ntplugin_add_checkout_init');
 function ntplugin_add_checkout_init() {
 load_plugin_textdomain( 'add-checkout', false, plugin_basename( dirname( __FILE__ ) ) . '/includes/languages' );
}

/**
 * Vložíme CSS file pro frontend.
 */
function addcheckout_scripts() {
    wp_register_style( 'add-checkout',  plugin_dir_url( __FILE__ ) . 'includes/css/add-checkout.css' );
    wp_enqueue_style( 'add-checkout' );
}
add_action( 'wp_enqueue_scripts', 'addcheckout_scripts' );

/**
 * Vložíme CSS a JavaScript pro backend
  */
 function addcheckout_admin_theme_style() {
    wp_enqueue_style('add-checkout', plugins_url('includes/css/add-checkout-admin.css', __FILE__));
    //script teď načítám inline, pro budoucí povolit načítání s adresáře
    //wp_enqueue_script('add-checkout-admin-script', plugins_url('includes/js/add-checkout-admin.js', __FILE__), array(), '1.1', false );
}
add_action('admin_enqueue_scripts', 'addcheckout_admin_theme_style');
add_action('login_enqueue_scripts', 'addcheckout_admin_theme_style');

/**
 * Přidáme stránku do menu
 */

add_action( 'admin_menu', 'nt_admin_menu' );
function nt_admin_menu() {
	add_menu_page( __('New Trends','add-checkout'), __('New Trends','add-checkout'), 'manage_options', 'add-checkout-admin-page', 'nt_add_checkout_page', 'dashicons-admin-tools', 3  );
}



function nt_add_checkout_page() {
    echo '<h1>' . __( 'Mainpage for setting plugins from New Trends', 'add-checkout' ) . '</h1>';
    echo '<img alt="WebStudio New Trends" class="ntlogo" src=" '. plugin_dir_url( __FILE__ ) .'images/logo.png" /><br />';
    do_action('addcheckout_after_main_content_admin_page_loop_action');
 }
add_action('addcheckout_after_main_content_admin_page_loop_action', 'addcheckout_print_details');

function addcheckout_print_details(){
  echo  '<hr /><br /><a href="' . admin_url(). 'admin.php?page=add_checkout">Add Checkout</a><br /><p>'. __( 'Plugin Add Checkout adds two notes and two buttons to cart page', 'add-checkout' ) .'</p><br /><hr />';
}

/**
 * Načteme třídu s nastavením pluginu
 */
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-add-checkout-admin.php';


if( is_admin() )

    $addcheckout_settings_page = new AddCheckoutPage();

// check for empty-cart get param to clear the cart
add_action('init', 'woocommerce_clear_cart_url');

function woocommerce_clear_cart_url() {
	global $woocommerce;
	if( isset($_REQUEST['clear-cart']) ) {
		$woocommerce->cart->empty_cart();
	}
}


function nt_add_notes_to_cart() {
    // Z db vybereme uživatelsky zadané poznámky a checkboxy
   $NTNotesOptions = get_option('nt_notes');
foreach($NTNotesOptions as $option => $value) {
    $ntchecknt1 = $NTNotesOptions['check_nt_note_1'];
    $ntn1 = $NTNotesOptions['nt_note_1'];
    $ntchecknt2 = $NTNotesOptions['check_nt_note_2'];
    $ntn2 = $NTNotesOptions['nt_note_2'];
    $ntn_empty_cart = $NTNotesOptions['nt_empty_cart'];
    $ntncontinueshopping = $NTNotesOptions['nt_button_continue_shopping'];

}
    // a zobrazíme je na stránce s košíkem
echo "<span>";

    // Tlačítko pokračovat v nákupu
    if ( $ntncontinueshopping == 1) {
        echo "<a class='btn wc-backward btn-primary' href='" . get_permalink(wc_get_page_id('shop')) ."' >";
        _e( 'Continue shopping', 'add-checkout' );
        echo "</a>";
        }


    // Tlačítko vyprázdnit košík
     if ($ntn_empty_cart == 1) { ?>
    <button type="submit" class="clear-cart btn btn-primary alt" name="clear-cart" value="<?php _e('Empty cart', 'add-checkout'); ?>"><?php _e('Empty cart', 'add-checkout'); ?></button>
<?php
        }

echo "</span>";


    if ($ntchecknt1 == 1) { echo "<br /><p class=\"nt1\">" . $ntn1 . "</p>";}
    if ($ntchecknt2 == 1) { echo "<p class=\"nt2\">" . $ntn2 . "</p>";}

}

add_action( 'woocommerce_after_cart_table', 'nt_add_notes_to_cart', 10, 2 );

}

else  {
 // Pokud není WooCommerce aktivní, plugin deaktivujeme

 if ( is_admin() ) {
          add_action( 'admin_init', 'my_plugin_deactivate' );
          add_action( 'admin_notices', 'my_plugin_admin_notice' );

          function my_plugin_deactivate() {
              deactivate_plugins( plugin_basename( __FILE__ ) );
          }

          function my_plugin_admin_notice() {
               echo '<div class="updated"><p><strong>Add Checkout</strong> vyžaduje ke svému chodu Woocommerce. Protože Woocommerce plugin není nainstalován, nebo aktivní <strong>plugin byl deaktivován</strong>.</p></div>';
               if ( isset( $_GET['activate'] ) )
                    unset( $_GET['activate'] );
          }

        }
    }

    ?>