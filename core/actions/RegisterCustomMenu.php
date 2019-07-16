<?php

namespace EddBtcAltGateWayCoreLib\actions;

/**
 * Class: Register custom menu
 * 
 * @package Admin
 * @since 1.0.0
 * @author CodeSolz <customer-support@codesolz.net>
 */

if (!defined('CS_EBAPG_VERSION')) {
    die();
}


use EddBtcAltGateWayCoreLib\admin\builders\CsAdminPageBuilder;
use EddBtcAltGateWayCoreLib\admin\options\Scripts_Settings;
use EddBtcAltGateWayCoreLib\admin\functions\EddFunctions;

class RegisterCustomMenu
{

    /**
     * Hold pages
     *
     * @var type 
     */
    private $pages;

    /**
     *
     * @var type 
     */
    private $WcFunc;

    /**
     *
     * @var type 
     */
    public $current_screen;

    public function __construct()
    {
        //call wordpress admin menu hook
        add_action('admin_menu', array($this, 'cs_register_ebapg_menu'));
    }

    /**
     * Init current screen
     * 
     * @return type
     */
    public function init_current_screen()
    {
        $this->current_screen = get_current_screen();
        return $this->current_screen;
    }

    /**
     * Create plugins menu
     */
    public function cs_register_ebapg_menu()
    {
        global $altcoin_menu;
        add_menu_page(
            __('Edd Bitcoin Altcoin Payment', 'edd-bitcoin-altcoin-payment-gateway'),
            "EDD Crypto Gateway ",
            'manage_options',
            CS_EBAPG_PLUGIN_IDENTIFIER,
            'cs-edd-bitcoin-altcoin-payment-gateway',
            CS_EBAPG_PLUGIN_ASSET_URI . 'img/icon-24x24.png',
            57.3
        );

        $altcoin_menu['default_settings'] = add_submenu_page(
            CS_EBAPG_PLUGIN_IDENTIFIER,
            __('Settings', 'edd-bitcoin-altcoin-payment-gateway'),
            "Default Settings",
            'manage_options',
            'cs-edd-bitcoin-altcoin-payment-gateway-settings',
            array($this, 'load_settings_page')
        );
        $altcoin_menu['register_automatic_order'] = add_submenu_page(
            CS_EBAPG_PLUGIN_IDENTIFIER,
            __('Automatic Order Confirmation Registration', 'edd-bitcoin-altcoin-payment-gateway'),
            "Order Settings",
            'manage_options',
            'cs-edd-bitcoin-altcoin-automatic-order-confirmation-settings',
            array($this, 'load_automatic_order_confirmation_settings_page')
        );
        $altcoin_menu['product_page_options_settings'] = add_submenu_page(
            CS_EBAPG_PLUGIN_IDENTIFIER,
            __('Product Page Options', 'edd-bitcoin-altcoin-payment-gateway'),
            "Product Page Options",
            'manage_options',
            'cs-edd-bitcoin-altcoin-product-option-settings',
            array($this, 'load_product_page_option_settings')
        );

        $altcoin_menu['checkout_options_settings'] = add_submenu_page(
            CS_EBAPG_PLUGIN_IDENTIFIER,
            __('Checkout Page', 'edd-bitcoin-altcoin-payment-gateway'),
            "Checkout Page Options",
            'manage_options',
            'cs-edd-bitcoin-altcoin-checkout-option-settings',
            array($this, 'load_checkout_settings_page')
        );

        $altcoin_menu['add_new_coin'] = add_submenu_page(
            CS_EBAPG_PLUGIN_IDENTIFIER,
            __('Add New Coin', 'edd-bitcoin-altcoin-payment-gateway'),
            "Add New Coin",
            'manage_options',
            'cs-edd-bitcoin-altcoin-add-new-coin',
            array($this, 'load_add_new_coin_page')
        );
        $altcoin_menu['all_coins_list'] = add_submenu_page(
            CS_EBAPG_PLUGIN_IDENTIFIER,
            __('All Coins', 'edd-bitcoin-altcoin-payment-gateway'),
            "All Coins",
            'manage_options',
            'cs-edd-bitcoin-altcoin-all-coins',
            array($this, 'load_all_coins_list_page')
        );


        //load script
        add_action("load-{$altcoin_menu['default_settings']}", array($this, 'register_admin_settings_scripts'));
        add_action("load-{$altcoin_menu['register_automatic_order']}", array($this, 'register_admin_settings_scripts'));
        add_action("load-{$altcoin_menu['add_new_coin']}", array($this, 'register_admin_settings_scripts'));
        add_action("load-{$altcoin_menu['all_coins_list']}", array($this, 'register_admin_settings_scripts'));
        add_action("load-{$altcoin_menu['checkout_options_settings']}", array($this, 'register_admin_settings_scripts'));
        add_action("load-{$altcoin_menu['product_page_options_settings']}", array($this, 'register_admin_settings_scripts'));

        remove_submenu_page(CS_EBAPG_PLUGIN_IDENTIFIER, CS_EBAPG_PLUGIN_IDENTIFIER);

        //init pages
        $this->pages = new CsAdminPageBuilder();

        //init gateway settings
        $this->EddGatewayObj = new EddFunctions();
    }

    /**
     * Generate default settings page
     * 
     * @return type
     */
    public function load_settings_page()
    {

        $Default_Settings = $this->pages->DefaultSettings();
        if (is_object($Default_Settings)) {
            echo $Default_Settings->generate_default_settings(array_merge_recursive(array(
                'title' => __('Gateway Settings', 'edd-bitcoin-altcoin-payment-gateway'),
                'sub_title' => __('EDD Bitcoin / Altcoin payment gatway defult settings. Please fill up the following informaton correctly.', 'edd-bitcoin-altcoin-payment-gateway'),
            ), array('gateway_settings' => $this->EddGatewayObj->get_payment_info())));
        } else {
            echo $Default_Settings;
        }
    }

    /**
     * Generate checkout settings page
     * 
     * @return type
     */
    public function load_checkout_settings_page()
    {

        $Checkout_Page_Settings = $this->pages->CheckoutPageSettings();
        if (is_object($Checkout_Page_Settings)) {
            echo $Checkout_Page_Settings->generate_checkout_settings(array(
                'title' => __('Checkout Page Options', 'edd-bitcoin-altcoin-payment-gateway'),
                'sub_title' => __('Following options will be applied to the checkout page', 'edd-bitcoin-altcoin-payment-gateway'),
            ));
        } else {
            echo $Checkout_Page_Settings;
        }
    }

    /**
     * Generate product page options settings
     * 
     * @return type
     */
    public function load_product_page_option_settings()
    {
        $Product_PageOptions = $this->pages->ProductPageOptions();
        if (is_object($Product_PageOptions)) {
            echo $Product_PageOptions->generate_product_options_settings(array(
                'title' => __('Product Page Options', 'edd-bitcoin-altcoin-payment-gateway'),
                'sub_title' => __('Following options will be applied to the product\'s page', 'edd-bitcoin-altcoin-payment-gateway'),
            ));
        } else {
            echo $Product_PageOptions;
        }
    }

    /**
     * 
     * @return type
     */
    public function load_automatic_order_confirmation_settings_page()
    {
        $Auto_Order_Settings = $this->pages->AutoOrderSettings();
        if (is_object($Auto_Order_Settings)) {
            echo $Auto_Order_Settings->generate_settings(array(
                'title' => __('Automatic Order Confirmation Settings', 'edd-bitcoin-altcoin-payment-gateway'),
                'sub_title' => __('Please complete your registration to use automatic order confirmation.', 'edd-bitcoin-altcoin-payment-gateway'),
            ));
        } else {
            echo $Auto_Order_Settings;
        }
    }

    /**
     * Load add new coin page
     * 
     * @return type
     */
    public function load_add_new_coin_page()
    {
        $Add_New_Coin = $this->pages->AddNewCoin();
        if (is_object($Add_New_Coin)) {
            echo $Add_New_Coin->add_new_coin(array(
                'title' => __('Add New Coin', 'edd-bitcoin-altcoin-payment-gateway'),
                'sub_title' => __('Please fill up the following informaton correctly to add new coin to payment method.', 'edd-bitcoin-altcoin-payment-gateway'),
            ));
        } else {
            echo $Add_New_Coin;
        }
    }

    /**
     * load all products page
     */
    public function load_all_coins_list_page()
    {
        $Coin_List = $this->pages->AllCoins();
        if (is_object($Coin_List)) {
            echo $Coin_List->generate_coin_list(array(
                'title' => __('All Coins', 'edd-bitcoin-altcoin-payment-gateway'),
                'sub_title' => __('Following coins has been added to the payment gateway\'s coin list.', 'edd-bitcoin-altcoin-payment-gateway'),
            ));
        } else {
            echo $Coin_List;
        }
    }

    /**
     * load funnel builder scripts
     */
    public function register_admin_settings_scripts()
    {

        //register scripts
        add_action('admin_enqueue_scripts', array($this, 'ebapg_load_settings_scripts'));

        //init current screen
        $this->init_current_screen();

        //load all admin footer script
        add_action('admin_footer', array($this, 'ebapg_load_admin_footer_script'));
    }

    /**
     * Load admin scripts
     */
    public function ebapg_load_settings_scripts($page_id)
    {
        Scripts_Settings::load_admin_settings_scripts($page_id);
    }

    /**
     * load custom scripts on admin footer
     */
    public function ebapg_load_admin_footer_script()
    {
        Scripts_Settings::load_admin_footer_script($this->current_screen->id);
    }
}
