<?php namespace EddBtcAltGateWayCoreLib\frontend\functions;
/**
 * Frontend Functions
 * 
 * @package Ebapg Admin 
 * @since 1.0.0
 * @author CodeSolz <customer-service@codesolz.com>
 */

if ( ! defined( 'CS_EBAPG_VERSION' ) ) {
    exit;
}

use EddBtcAltGateWayCoreLib\lib\cartFunctions;
use EddBtcAltGateWayCoreLib\admin\functions\CsAdminQuery;

class CsEbapgCustomTy {
    
    /**
     * Order summery in thank you page
     * 
     * @param type $order
     */
    public static function order_summary(){
        global $edd_receipt_args;
        $order_id   = get_post( $edd_receipt_args['id'] );
        if( !isset($order_id->ID)){
            return false;
        }
        // pre_print( $order_id );
        $payment_details = cartFunctions::get_payment_info( $order_id->ID );
        ?>
        <h2><?php _e( 'Coin Details', 'edd-bitcoin-altcoin-payment-gateway' ); ?></h2>
        <table class="woocommerce-table shop_table coin_info">
            <thead>
                <tr>
                    <th><?php _e( 'Coin', 'edd-bitcoin-altcoin-payment-gateway' ); ?></th>
                    <th><?php _e( 'Total', 'edd-bitcoin-altcoin-payment-gateway' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <td> 
                    <?php echo $payment_details['coin_name']; ?> &times; <?php echo $payment_details['total_coin']; ?> <br>
                    ( 1 <?php echo $payment_details['coin_name']; ?> = &#36;<?php echo $payment_details['coin_price']; ?> )
                </td>
                <td>$<?php echo $payment_details['cart_total']; ?></td>
            </tbody>
            <tfoot>
                <?php if( !empty($payment_details['special_discount'])) { ?>
                    <tr>
                        <td><?php _e( 'Special Discount', 'edd-bitcoin-altcoin-payment-gateway' ); ?></td>
                        <td> <?php echo $payment_details['special_discount']; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th><?php _e( 'Subtotal', 'edd-bitcoin-altcoin-payment-gateway' ); ?></th>
                    <td> <?php echo $payment_details['total_coin']; ?> - <?php echo $payment_details['coin_name']; ?></td>
                </tr>
                <tr>
                    <th><?php _e( 'Total', 'edd-bitcoin-altcoin-payment-gateway' ); ?></th>
                    <td> <?php echo $payment_details['total_coin']; ?> - <?php echo $payment_details['coin_name']; ?> </td>
                </tr>
            </tfoot>
        </table>
        <div class="cs-ref-info ">
            <div class="name"><?php _e( 'Coin Sent To(marchent altcoin address)', 'edd-bitcoin-altcoin-payment-gateway' ); ?> : </div>
            <div><?php echo $payment_details['your_address']; ?></div>
        </div>    
        <div class="cs-ref-info cs-ref-info-last">
            <div class="name"><?php _e( 'Transaction Reference / TrxID', 'edd-bitcoin-altcoin-payment-gateway' ); ?> : </div>
            <div><?php echo $payment_details['ref_trxid']; ?></div>
        </div>    
        <?php
        self::coin_details_style();
    }
    
    /**
     * metabox styling
     */
    private static function coin_details_style(){
        ?>
            <style type="text/css">
                .cs-ref-info .name{font-weight: bold; color: #adb5bd; }
                .cs-ref-info-last{margin: 15px 0px 35px 0px;border-bottom: 1px dashed;padding-bottom: 15px;}
            </style>    
        <?php
    }
 
   
}
