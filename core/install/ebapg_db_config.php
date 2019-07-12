<?php
/**
 * Database Config
 * 
 * @package DB
 * @since 1.0.0
 * @author CodeSolz <customer-service@codesolz.com>
 */

if ( ! defined( 'CS_EBAPG_VERSION' ) ) {
   exit;
}

global $ebapg_tables, $ebapg_current_db_version, $wpdb;

//assign db version globally in variable
$ebapg_current_db_version = CS_EBAPG_DB_VERSION;

/**
 * load custom table names
 */
if( ! isset( $ebapg_tables ) ){
    $ebapg_tables = array(
        'coins' => $wpdb->prefix . 'ebapg_coins',
        'addresses' => $wpdb->prefix . 'ebapg_coin_addresses',
        'offers' => $wpdb->prefix . 'ebapg_coin_offers',
        'coin_trxids' => $wpdb->prefix . 'ebapg_coin_transactions'
    );
}