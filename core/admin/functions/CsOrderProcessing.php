<?php namespace EddBtcAltGateWayCoreLib\admin\functions;
/**
 * Settings
 * 
 * @package Admin 
 * @since 1.0.0
 * @author CodeSolz <customer-service@codesolz.com>
 */

if ( ! defined( 'CS_EBAPG_VERSION' ) ) {
    exit;
}

use EddBtcAltGateWayCoreLib\lib\Util;
use EddBtcAltGateWayCoreLib\lib\cartFunctions;
use EddBtcAltGateWayCoreLib\admin\builders\CsEbapgForm;
use EddBtcAltGateWayCoreLib\frontend\scripts\CsEbapgScript;

class CsOrderProcessing{
    
    
    function __construct() {

    }
 
    /**
     * Process payment
     * 
     * @return boolean
     */
    public static function process_payment( $purchase_data ) {
        
        // $order = wc_get_order( $order_id );
        $payment_confirm = isset($_POST['payment_confirm']) ? Util::check_evil_script( $_POST['payment_confirm'] ) : '';
        $payment_info = isset( $_POST['payment_info'] ) ? Util::check_evil_script($_POST['payment_info']) : '';
        $reference_trxid = isset($_POST['trxid']) ? Util::check_evil_script($_POST['trxid']) : '';
        // $reference_trxid = '';
        
        //get checkout type
        $checkout_type = cartFunctions::get_temp_log_checkout_type();
        
        
        if( empty($payment_info)){
            edd_set_error( 'empty_payment_info', __( 'Sorry! Something went wrong. Please refresh this page and try again.', 'edd'));
        }

        $payment_info = explode( '__', $payment_info);
        if( empty( $reference_trxid ) ){
            edd_set_error( 'empty_trxid', sprintf(__( 'Please enter your %s transaction trxID.', 'edd-bitcoin-altcoin-payment-gateway'), $payment_info[ 2 ] ) );
        }

        if( empty($payment_confirm) && ( empty($checkout_type) || $checkout_type != 2) ){
            edd_set_error( 'empty_payment_confirm', __('Please click the confirmation checkbox that you have already transfered the coin successfully!', 'edd'));
        }


        $errors = edd_get_errors();
        if(!$errors) {

            $purchase_summary = edd_get_purchase_summary($purchase_data);
            $payment = array( 
                'price' => $purchase_data['price'], 
                'date' => $purchase_data['date'], 
                'user_email' => $purchase_data['user_email'],
                'purchase_key' => $purchase_data['purchase_key'],
                'currency' => $edd_options['currency'],
                'downloads' => $purchase_data['downloads'],
                'cart_details' => $purchase_data['cart_details'],
                'user_info' => $purchase_data['user_info'],
                'status' => 'pending'
            );
    
            // record the pending payment
            $payment = edd_insert_payment($payment);

            //save order info
            cartFunctions::save_payment_info( $payment, array(
                'coin_db_id' => sanitize_text_field($_POST['altcoin']),
                'special_discount' => isset($_POST['special_discount_amount']) ? $_POST['special_discount_amount'] : '',
                'cart_total' => $purchase_data['price'],
                'coin_name' => $payment_info[2],
                'total_coin' => $payment_info[1],
                'coin_price' => $payment_info[4],
                'ref_trxid' => $reference_trxid,
                'your_address' => $payment_info[3],
                'datetime' => Util::get_current_datetime()
            ));


            if( !empty($checkout_type) && $checkout_type == 2 ){
                //remove temp info
                cartFunctions::delete_temp_log_checkout_type( $order_id );
                cartFunctions::delete_transaction_successful_log( $order_id );
                
                $auto_setting_config = CsAutomaticOrderConfirmationSettings::get_order_confirm_settings_data();
                $status = isset($auto_setting_config['order_status']) && !empty($auto_setting_config['order_status']) ? $auto_setting_config['order_status'] : 'completed';
                //automatic confirmation
                
            }else{
                //manual confirmation
                $status = 'pending';
            }

            // once a transaction is successful, set the purchase to complete
            edd_update_payment_status($payment, $status );

            // go to the success page			
            edd_send_to_success_page();

        }else {
            $fail = true; // errors were detected
        }

        if( $fail !== false ) {
            edd_send_back_to_checkout('?payment-mode=' . $purchase_data['post_data']['edd-gateway']);
        }

    }    
    
    
}
