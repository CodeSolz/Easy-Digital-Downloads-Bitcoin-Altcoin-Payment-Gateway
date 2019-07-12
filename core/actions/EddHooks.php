<?php namespace EddBtcAltGateWayCoreLib\Actions;

/**
 * Class: EDD Hooks
 * 
 * @package Admin
 * @since 1.0.0
 * @author CodeSolz <customer-support@codesolz.net>
 */

if ( ! defined( 'CS_EBAPG_VERSION' ) ) {
    die();
}

use EddBtcAltGateWayCoreLib\admin\functions\EddFunctions;
use EddBtcAltGateWayCoreLib\frontend\functions\CsEbapgCheckoutForm;
use EddBtcAltGateWayCoreLib\frontend\functions\CsMiscellaneous;
use EddBtcAltGateWayCoreLib\frontend\scripts\CsEbapgScript;
use EddBtcAltGateWayCoreLib\admin\functions\CsOrderProcessing;
use EddBtcAltGateWayCoreLib\admin\functions\CsOrderDetails;
use EddBtcAltGateWayCoreLib\frontend\functions\CsEbapgCustomTy;

class EddHooks {
    
    /**
     * Hold user order details
     *
     * @var type 
     */
    private $Thank_You_Page;
    
    /**
     * Hold admin order details
     *
     * @var type 
     */
    private $Cs_Order_Detail;
            
    function __construct() {
        
        //register the gateway
        add_filter('edd_payment_gateways', array( $this, 'register_ebapg_gateway' ) );
        
        //register payment icon
        add_filter('edd_accepted_payment_icons', array( $this, 'register_ebapg_icon' ));

        //register the section
        add_action('edd_' . EddFunctions::get_gateway_id() . '_cc_form', array( $this, 'register_ebapg_checkout_form') );

        //product price filter
        add_filter('edd_price_option_output', array( '\EddBtcAltGateWayCoreLib\\frontend\\functions\\CsMiscellaneous', 'show_coin_price' ), 10, 6);

        //show special discount notification
        add_action('edd_after_price_options_list', array( '\EddBtcAltGateWayCoreLib\\frontend\\functions\\CsEbapgCustomBlocks', 'special_discount_offer_box' ));

        //process the purchase
        add_action('edd_gateway_' . EddFunctions::get_gateway_id(), array( $this, 'ebapg_edd_process_payment') );

        //user order details
        add_action( 'edd_payment_receipt_after_table', array( $this, 'ebapg_payment_info_after_table') );

        //admin order details
        add_action('edd_view_order_details_billing_after', array( $this, 'ebapg_paid_coin_details') );
    }

    

    /**
     * register gateway
     */
    public function register_ebapg_gateway( $gateways ){
        return EddFunctions::register_edd_ebapg_gateway( $gateways );
    }
    
    /**
     * Register payment icon
     */
    public function register_ebapg_icon( $icons ){
        return EddFunctions::register_edd_payment_icon( $icons );
    }
    

    /**
     * register frontend form
     */
    public function register_ebapg_checkout_form(){
        //register script
        new CsEbapgScript();
        return CsEbapgCheckoutForm::checkout_form();
    }

    /**
     * Process the payment
     */
    public function ebapg_edd_process_payment( $purchase_data ){
        return CsOrderProcessing::process_payment( $purchase_data );
    }

    /**
     * payment info after table
     */
    public function ebapg_payment_info_after_table(){
        return CsEbapgCustomTy::order_summary();
    }

    /**
     * payment info coin details
     */
    public function ebapg_paid_coin_details( $order_id ){
        return CsOrderDetails::order_metabox_coin_details( $order_id );
    }

    
}
