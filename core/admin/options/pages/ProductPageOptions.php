<?php namespace EddBtcAltGateWayCoreLib\admin\options\pages;

/**
 * Class: ProductPageOptions
 * 
 * @package Admin
 * @since 1.0.0
 * @author CodeSolz <customer-support@codesolz.net>
 */

if ( ! defined( 'CS_EBAPG_VERSION' ) ) {
    die();
}

use EddBtcAltGateWayCoreLib\admin\builders\CsAdminPageBuilder;
use EddBtcAltGateWayCoreLib\admin\builders\CsFormBuilder;
use EddBtcAltGateWayCoreLib\admin\functions\CsPaymentGateway;
use EddBtcAltGateWayCoreLib\admin\builders\CsFormHelperLib;


class ProductPageOptions {
    
    /**
     * Hold page generator class
     *
     * @var type 
     */
    private $Admin_Page_Generator;
    
    /**
     * Form Generator
     *
     * @var type 
     */
    private $Form_Generator;
    
    
    public function __construct(CsAdminPageBuilder $AdminPageGenerator) {
        $this->Admin_Page_Generator = $AdminPageGenerator;
        
        /*create obj form generator*/
        $this->Form_Generator = new CsFormBuilder();
        
        add_action( 'admin_footer', array( $this, 'default_page_scripts'));
    }
    
    /**
     * Generate add new coin page
     * 
     * @param type $args
     * @return type
     */
    public function generate_product_options_settings( $args ){
        
        $settings = CsPaymentGateway::get_product_page_options();
        
        $fields = array(
            'st1' => array(
                'type' => 'section_title',
                'title'         => __( 'Offer Message Text', 'edd-bitcoin-altcoin-payment-gateway' ),
                'desc_tip'         => __( 'Please use the following options to change offer notification shown in the single product page', 'edd-bitcoin-altcoin-payment-gateway' ),
            ),
            'cs_altcoin_config[offer_msg_blink_text]'=> array(
                'title'            => __( 'Offer Blink Text', 'edd-bitcoin-altcoin-payment-gateway' ),
                'type'             => 'text',
                'class'            => "form-control",
                'required'         => true,
                'value'            => CsFormBuilder::get_value( 'offer_msg_blink_text', $settings , 'Special Offers Available!'),
                'placeholder'      => __( 'Enter offer blink text', 'edd-bitcoin-altcoin-payment-gateway' ),
                'desc_tip'         => __( 'Enter offer notification blink text, this text will diplay top of the add to cart button in single product page', 'edd-bitcoin-altcoin-payment-gateway' ),
            ),
            'cs_altcoin_config[offer_msg_text]'=> array(
                'title'            => __( 'Offer Notification Box Title Text', 'edd-bitcoin-altcoin-payment-gateway' ),
                'type'             => 'text',
                'class'            => "form-control",
                'required'         => true,
                'value'            => CsFormBuilder::get_value( 'offer_msg_text', $settings, 'You will get special discount, if you pay with following AltCoins'),
                'placeholder'      => __( 'Enter offer message text', 'edd-bitcoin-altcoin-payment-gateway' ),
                'desc_tip'         => __( 'Enter offer notification text, this text will diplay top of the add to cart button in single product page', 'edd-bitcoin-altcoin-payment-gateway' ),
            ),
            'st2' => array(
                'type' => 'section_title',
                'title'            => __( 'Show Live Coin Price', 'edd-bitcoin-altcoin-payment-gateway' ),
                'desc_tip'         => __( 'Following options will enable you to show live coins price beside your product price', 'edd-bitcoin-altcoin-payment-gateway' ),
            ),
            'cs_altcoin_config[show_live_price]'    => array(
                'title'                     => __( 'Enable / Disable', 'edd-bitcoin-altcoin-payment-gateway' ),
                'type'                      => 'checkbox',
                'value'                     => 'yes',
                'has_value'                 => CsFormBuilder::get_value( 'show_live_price', $settings, ''),
                'desc_tip'                  => __( 'Enable this option to show live coin price beside product price', 'edd-bitcoin-altcoin-payment-gateway' ),
            ),
            'cs_altcoin_config[show_live_coin_list][]'     => array(
                'title'                     => __( 'Select Coin', 'edd-bitcoin-altcoin-payment-gateway' ),
                'type'                      => 'select',
                'class'                     => "form-control live_price_coins",
                'multiple'                  => true,
                'placeholder'               => __( 'Please select coin', 'edd-bitcoin-altcoin-payment-gateway' ),
                'options'                   => CsFormHelperLib::get_all_active_coins(),
                'value'                     => CsFormBuilder::get_value( 'show_live_coin_list', $settings, ''),
                'desc_tip'                  => __( 'Select / Enter coin name to show for live price. e.g : Bitcoin', 'edd-bitcoin-altcoin-payment-gateway' ),
            )
        );
        
        $args['content'] = $this->Form_Generator->generate_html_fields( $fields );
        
        $hidden_fields = array(
            'method'=> array(
                'id'   => 'method',
                'type'  => 'hidden',
                'value' => "admin\\functions\\CsPaymentGateway@save_product_page_options"
            ),
            'swal_title'=> array(
                'id' => 'swal_title',
                'type'  => 'hidden',
                'value' => 'Settings Updating'
            ),
            
        );
        $args['hidden_fields'] = $this->Form_Generator->generate_hidden_fields( $hidden_fields );
        
        $args['btn_text'] = 'Save Settings';
        $args['show_btn'] = true;
        $args['body_class'] = 'no-bottom-margin';
        
        return $this->Admin_Page_Generator->generate_page( $args );
    }
 
    /**
     * Add custom scripts
     */
    public function default_page_scripts(){
        ?>
            <script>
                $.wpMediaUploader( { buttonClass : '.button-secondary' } );
                
                jQuery(document).ready(function($) {
                    $('.live_price_coins').select2();
                });
                
            </script>
        <?php
    }
    
}