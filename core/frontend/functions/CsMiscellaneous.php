<?php namespace EddBtcAltGateWayCoreLib\frontend\functions;
/**
 * Front End Functions
 * 
 * @package FE 
 * @since 1.0.0
 * @author CodeSolz <customer-service@codesolz.com>
 */

if ( ! defined( 'CS_EBAPG_VERSION' ) ) {
    exit;
}

use EddBtcAltGateWayCoreLib\frontend\functions\CsEbapgCoinCal;
use EddBtcAltGateWayCoreLib\admin\functions\CsPaymentGateway;

class CsMiscellaneous {

    /**
     * show live coin price
     * 
     * @return string
     */    
    public static function show_coin_price( $price_output, $download_id, $key, $price, $form_id, $item_prop ){

        $settings = CsPaymentGateway::get_product_page_options();
        if( isset($settings['show_live_price'] ) && $settings['show_live_price'] == 'yes' ){
            $crypto_price = (new CsEbapgCoinCal())->getCryptoLivePrices( $settings['show_live_coin_list'], $price['amount'] );
            $coin_price = implode('/ ', array_map(function($el){ return $el['html']; }, $crypto_price));
            if( count($crypto_price) > 1 ){
                return $price_output .'<br/>'. $coin_price;
            }
            return $price_output ."&#160;/&#160;". $coin_price;
        }
        return $price_output;
    }
    
}
