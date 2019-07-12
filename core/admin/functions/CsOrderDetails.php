<?php namespace EddBtcAltGateWayCoreLib\admin\functions;
/**
 * Order Details
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

class CsOrderDetails {
    
    /**
     * metabox coin details
     * 
     * @param type $post
     */
    public function order_metabox_coin_details( $order_id ){
        $payment_details = cartFunctions::get_payment_info( $order_id );
        ?>
        <div class="coin-details postbox" >
        <h3 class="hndle"><?php _e( 'Coin Details', 'edd-bitcoin-altcoin-payment-gateway'); ?></h3>
        <div class="inside edd-clearfix">
        <table class="cs-coin-info">
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
        <div class="cs-ref-info cs-ref-info-first">
            <div class="name"><?php _e( 'Coin Sent To(Your altcoin address)', 'edd-bitcoin-altcoin-payment-gateway' ); ?></div>
            <div>:</div>
            <div><?php echo $payment_details['your_address']; ?></div>
        </div>    
        <div class="cs-ref-info">
            <div class="name"><?php _e( 'Transaction Reference / TrxID', 'edd-bitcoin-altcoin-payment-gateway' ); ?></div>
            <div>:</div>
            <div><?php echo $payment_details['ref_trxid']; ?></div>
        </div>    
        </div>    
        </div>    
        <?php
        self::metabox_style();
    }
    
    
    /**
     * metabox styling
     */
    private static function metabox_style(){
        ?>
            <style type="text/css">
                .cs-coin-info{ width:100%; text-align:left; border-collapse:collapse; }
                .cs-reference-fields{ background: #fab13f; }
                .cs-ref-info{ display: flex; }
                .cs-ref-info .name{ font-weight: bold; color: #adb5bd; }
                .cs-ref-info div:first-child{ width: 220px;}
                .cs-ref-info div:nth-child(2){ width: 8px;}
                .cs-ref-info-first{margin-top: 15px;border-top: 1px dashed;padding-top: 15px;}
            </style>    
        <?php
    }
    
}
