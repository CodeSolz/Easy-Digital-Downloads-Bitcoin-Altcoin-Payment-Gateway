<?php namespace EddBtcAltGateWayCoreLib\admin\builders;

/**
 * Class: Admin Pages
 * 
 * @package Admin
 * @since 1.0.9
 * @author CodeSolz <customer-support@codesolz.net>
 */

if ( ! defined( 'CS_EBAPG_VERSION' ) ) {
    die();
}

use EddBtcAltGateWayCoreLib\lib\Util;

class CsFormHelperLib {
    
    /**
     * Gateway order confirmation
     * 
     * @return type
     */
    public static function order_confirm_options(){
        $options = array(
            '1'  => __( 'Manual', 'edd-bitcoin-altcoin-payment-gateway' ),
            '2'     => __( 'Automatic', 'edd-bitcoin-altcoin-payment-gateway' )
        );
        if(has_filter('filter_cs_Ebapg_order_confirm_options')) {
            $options = apply_filters( 'filter_cs_Ebapg_order_confirm_options', $options );
        }
        
        return $options;
    }
    
    /**
     * Get all active coin list
     */
    public static function get_all_active_coins(){
        global $ebapg_current_db_version, $wpdb, $ebapg_tables;
        $coins = $wpdb->get_results( " select * from `{$ebapg_tables['coins']}` where status = 1" );
        if( $coins ){
            $ret = [];
            foreach( $coins as $coin ){
                $ret += array(
                    $coin->coin_web_id . '___' . $coin->symbol => $coin->name .'('. $coin->symbol . ')' 
                ); 
            }
            return $ret;
        }
        return array( '0' => 'Please at first add coin from "Add New Coin" Menu' );
    }
    
}
