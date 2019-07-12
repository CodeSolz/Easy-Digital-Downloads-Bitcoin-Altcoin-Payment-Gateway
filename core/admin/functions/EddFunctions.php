<?php namespace EddBtcAltGateWayCoreLib\admin\functions;
/**
 * Register Gateway & Retrive Settings Data
 * 
 * @package Admin/functions
 * @since 1.0.0
 * @author CodeSolz <customer-service@codesolz.com>
 */

if ( ! defined( 'CS_EBAPG_VERSION' ) ) {
    exit;
}

use EddBtcAltGateWayCoreLib\admin\functions\CsPaymentGateway;

class EddFunctions {

    public function __construct(){
        
    }
    
    /**
     * Get payment gateway info
     * 
     * @return type
     */
    public function get_payment_info(){
        $edd_options = get_option( self::get_gateway_id() );   
        return empty($edd_options) ? false : $edd_options;
    }
    
    /**
     * Get gateway id
     * 
     * @return type
     */
    public static function get_gateway_id(){
        return 'edd_ebapg_payment_gateway';
    }


    /**
     * Register ebapg gateway
     * 
     * @return array
     */
    public static function register_edd_ebapg_gateway( $gateways ){
        $gateway_config = CsPaymentGateway::get_settings_options();
        if(!empty($gateway_config)){
            $admin_label = isset($gateway_config['title']) ? $gateway_config['title'] : '';
            $checkout_label = isset($gateway_config['description']) ? $gateway_config['description'] : '';

            //add gateway
            $gateways[ self::get_gateway_id() ] = array(
                'admin_label' => $admin_label, 
                'checkout_label' => $checkout_label
            );
        }
	    return $gateways;
    }
    
    /**
     * Payment Icon
     */
    public static function register_edd_payment_icon( $icons ){
        $gateway_config = CsPaymentGateway::get_settings_options();
        $icon = CS_EBAPG_PLUGIN_ASSET_URI .'img/icon-24x24.png';
        if( !empty( $gateway_config['payment_icon_url']) ){
            $icon = $gateway_config['payment_icon_url'];
        }
        $icons[ $icon ] = isset($gateway_config['title']) ? $gateway_config['title'] : __('Cryptocurrency Payment', 'edd-bitcoin-altcoin-payment-gateway');
        return $icons;
    }
    
}