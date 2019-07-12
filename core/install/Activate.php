<?php namespace EddBtcAltGateWayCoreLib\install;
/**
 * Database Tables Installation
 * 
 * @package DB
 * @since 1.0.0
 * @author CodeSolz <customer-service@codesolz.com>
 */

if ( ! defined( 'CS_EBAPG_VERSION' ) ) {
   exit;
}

class Activate{
    
    /**
     * On install Create table
     * 
     * @global type $wpdb
     */
    public static function on_activate(){
        global $wpdb, $ebapg_tables;
        $charset_collate = $wpdb->get_charset_collate();
        
        $sqls = array(
            "CREATE TABLE IF NOT EXISTS `{$ebapg_tables['coins']}`(
            `id` int(11) NOT NULL auto_increment,
            `name` varchar(56),
            `coin_web_id` varchar(56),
            `symbol` varchar(20),
            `checkout_type` char(1),
            `status` char(1),
            PRIMARY KEY ( `id`)
            ) $charset_collate",
            "CREATE TABLE IF NOT EXISTS `{$ebapg_tables['addresses']}`(
            `id` int(11) NOT NULL auto_increment,
            `coin_id` int(11),
            `address` varchar(1024),
            `lock_status` char(1),  
            PRIMARY KEY ( `id`)
            ) $charset_collate",
            "CREATE TABLE IF NOT EXISTS `{$ebapg_tables['offers']}`(
            `id` int(11) NOT NULL auto_increment,
            `coin_id` int(11),
            `offer_amount` int(11),
            `offer_type` char(1),
            `offer_status` char(1),  
            `offer_show_on_product_page` char(1),  
            `offer_start` datetime,  
            `offer_end` datetime,  
            PRIMARY KEY ( `id`)
            ) $charset_collate",
            "CREATE TABLE IF NOT EXISTS `{$ebapg_tables['coin_trxids']}`(
            `id` bigint(20) NOT NULL auto_increment,
            `cart_hash` varchar(128),
            `transaction_id` varchar(1024),
            `secret_word` varchar(1024),
            `used_in` datetime,  
            PRIMARY KEY ( `id`)
            ) $charset_collate"
        );
        
        foreach ( $sqls as $sql ) {
            if ( $wpdb->query( $sql ) === false ){
                continue;
            }
        }    
        
        //add db version to db
        add_option( 'ebapg_db_version', CS_EBAPG_DB_VERSION );
        
    }
    
    /**
     * check db status
     * 
     * @global type $ebapg_current_db_version
     */
    public static function ebapg_check_db_status(){
        global $ebapg_current_db_version, $wpdb, $ebapg_tables;
        $get_installed_db_version = get_site_option( 'ebapg_db_version' );
        if( empty( $get_installed_db_version ) ){
            self::on_activate();
        }
        elseif( \version_compare( $get_installed_db_version, $ebapg_current_db_version, '!=' ) ){    
            // new db updates goes here           
        }
    }



}