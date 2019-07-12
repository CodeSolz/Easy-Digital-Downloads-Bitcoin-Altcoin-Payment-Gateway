<?php namespace EddBtcAltGateWayCoreLib\admin\options\pages;

/**
 * Class: AutoOrderSettings
 * 
 * @package Admin
 * @since 1.0.0
 * @author CodeSolz <customer-support@codesolz.net>
 */

if ( ! defined( 'CS_EBAPG_VERSION' ) ) {
    die();
}

use EddBtcAltGateWayCoreLib\lib\Util;
use EddBtcAltGateWayCoreLib\admin\builders\CsAdminPageBuilder;
use EddBtcAltGateWayCoreLib\admin\builders\CsFormBuilder;
use EddBtcAltGateWayCoreLib\admin\functions\CsAutomaticOrderConfirmationSettings;

class AutoOrderSettings {
    
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
        
    }
    
    /**
     * Generate add new coin page
     * 
     * @param type $args
     * @return type
     */
    public function generate_settings( $args ){
        
        $settings_data = CsAutomaticOrderConfirmationSettings::get_order_confirm_settings_data();
        $fields = array(
            'cs_altcoin_config[cms_username]'=> array(
                'title'            => __( 'CoinMarketStats Username', 'edd-bitcoin-altcoin-payment-gateway' ),
                'type'             => 'text',
                'class'            => "form-control",
                'required'         => true,
                'value'            => CsFormBuilder::get_value( 'cms_username', $settings_data, ''),
                'placeholder'      => __( 'Enter your username', 'edd-bitcoin-altcoin-payment-gateway' ),
                'desc_tip'         => __( 'Enter your username / email used in the registration.', 'edd-bitcoin-altcoin-payment-gateway' ),
            ),
            'cs_altcoin_config[cms_pass]'=> array(
                'title'            => __( 'CoinMarketStats Password', 'edd-bitcoin-altcoin-payment-gateway' ),
                'type'             => 'password',
                'class'            => "form-control",
                'required'         => true,
                'value'            => CsFormBuilder::get_value( 'cms_pass', $settings_data, ''),
                'placeholder'      => __( 'Enter your password', 'edd-bitcoin-altcoin-payment-gateway' ),
                'desc_tip'         => __( 'Enter your password used in the registration.', 'edd-bitcoin-altcoin-payment-gateway' ),
            ),
            'cs_altcoin_config[api_key]'=> array(
                'title'            => __( 'API Key', 'edd-bitcoin-altcoin-payment-gateway' ),
                'type'             => 'text',
                'class'            => "form-control",
                'required'         => true,
                'value'            => CsFormBuilder::get_value( 'api_key', $settings_data, ''),
                'placeholder'      => __( 'Enter your API key', 'edd-bitcoin-altcoin-payment-gateway' ),
                'desc_tip'         => sprintf( __( 'Enter your API key. You can found your API key in the API menu in %s myportal area %s .', 'edd-bitcoin-altcoin-payment-gateway' ), "<a href='http://myportal.coinmarketstats.online/' target='_blank'>", '</a>'),
            ),
            'st1' => array(
                'type' => 'section_title',
                'title'         => __( 'Order Confirmation Settings', 'edd-bitcoin-altcoin-payment-gateway' ),
                'desc_tip'         => __( 'Please set the following information for order confirmation and status', 'edd-bitcoin-altcoin-payment-gateway' ),
            ),
            'cs_altcoin_config[confirmation_count]'     => array(
                'title'                     => __( 'Minimum Confirmation For Transaction', 'edd-bitcoin-altcoin-payment-gateway' ),
                'type'                      => 'select',
                'class'                     => "form-control coin-type-select",
                'required'                  => true,
                'placeholder'               => __( 'Please select confirmation count', 'edd-bitcoin-altcoin-payment-gateway' ),
                'options'                   => array(
                    1 => 1, 2 => 2, 3 => 3, 4 => 4,5 => 5,6 => 6
                ),
                'value'                     => CsFormBuilder::get_value( 'confirmation_count', $settings_data, 6 ),
                'desc_tip'                  => __( 'Select how many confirmation will be treated as a successful transaction e.g : Standard is: 6, 3 is enough for payments $1,000 - $10,000', 'edd-bitcoin-altcoin-payment-gateway' ),
            ),
            'cs_altcoin_config[order_status]'     => array(
                'title'                     => __( 'Order Status', 'edd-bitcoin-altcoin-payment-gateway' ),
                'type'                      => 'select',
                'class'                     => "form-control coin-type-select",
                'required'                  => true,
                'placeholder'               => __( 'Please select order status', 'edd-bitcoin-altcoin-payment-gateway' ),
                'options'                   => array(
                    'on-hold' => 'On Hold', 'processing' => 'Processing', 'completed' => 'Completed'
                ),
                'value'                     => CsFormBuilder::get_value( 'order_status', $settings_data, 'completed'),
                'desc_tip'                  => __( 'Please select order status after successful transaction e.g : Completed', 'edd-bitcoin-altcoin-payment-gateway' ),
            ),
        );
        
        $args['content'] = $this->Form_Generator->generate_html_fields( $fields );

        $hidden_fields = array(
            'method'=> array(
                'id'   => 'method',
                'type'  => 'hidden',
                'value' => "admin\\functions\\CsAutomaticOrderConfirmationSettings@save_settings"
            ),
            'swal_title'=> array(
                'id' => 'swal_title',
                'type'  => 'hidden',
                'value' => 'Settings Updating'
            ),
            'cs_altcoin_config[cms_refferer]'=> array(
                'id' => 'cs_altcoin_config[cms_refferer]',
                'type'  => 'hidden',
                'value' => site_url()
            ),
            'cs_altcoin_config[cms_refferer_id]'=> array(
                'id' => 'cs_altcoin_config[cms_refferer_id]',
                'type'  => 'hidden',
                'value' => 2
            ),
            
        );
                
        $args['hidden_fields'] = $this->Form_Generator->generate_hidden_fields( $hidden_fields );
        
        $args['btn_text'] = 'Save Settings';
        $args['show_btn'] = true;
        $args['body_class'] = 'no-bottom-margin';
        
        $args['well'] = "<ul>
            <li> <b>Basic Hints</b>
                <ol>
                    <li>
                        Please register here - <a href='http://myportal.coinmarketstats.online/register' target=\"_blank\">http://myportal.coinmarketstats.online</a> for your API Key.
                    </li>
                    <li>
                        After login to your dashboard, go to 'API Keys' menu. From bottom of the page you can generate your API key. 
                    </li>
                    <li>
                        You can purchase pro package for unlimited automatic order confirmation from your dashboard. Free package included 5 automatic order confirmation.
                    </li>
                </ol>
            </li>
        </ul>";
        
        
        return $this->Admin_Page_Generator->generate_page( $args );
    }
    
}