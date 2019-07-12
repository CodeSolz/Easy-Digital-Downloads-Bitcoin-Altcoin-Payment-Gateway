<?php namespace EddBtcAltGateWayCoreLib\admin\settings;
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

class CsGateWaySettings{
    
    function __construct() {
        //load ajax url into frontend
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'ebapg_frontEnd_Enqueue' ) );
    }

    /**
     * Enqueue script
     * 
     * @return String
     */
    public static function ebapg_frontEnd_Enqueue(){
        wp_enqueue_script( 'ajax-script', CS_EBAPG_PLUGIN_ASSET_URI . '/js/Ebapg_ajax.js' , array('jquery') );

	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'ajax-script', 'Ebapg_ajax',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), array() ) );
    }
}