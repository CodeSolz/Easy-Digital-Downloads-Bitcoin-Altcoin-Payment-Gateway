<?php

namespace EddBtcAltGateWayCoreLib\admin\functions;

/**
 * Retrive Settings Data
 * 
 * @package Admin 
 * @since 1.0.0
 * @author CodeSolz <customer-service@codesolz.com>
 */

if (!defined('CS_EBAPG_VERSION')) {
    exit;
}

use EddBtcAltGateWayCoreLib\admin\functions\EddFunctions;
use EddBtcAltGateWayCoreLib\lib\Util;

class CsPaymentGateway
{

    private static $checkout_page_options_id = 'csebapg_checkout_page_optn';
    private static $product_page_options_id = 'csebapg_product_page_optn';

    /**
     * save default settings
     * 
     * @return type
     */
    public function save_general_settings()
    {
        $gateway_id = EddFunctions::get_gateway_id();
        $settings = Util::check_evil_script($_POST['cs_altcoin_config']);
        array_walk($settings, 'sanitize_text_field');
        update_option($gateway_id, $settings, 'yes');

        //edd option update
        $edd_db_options = get_option('edd_settings');

        //enable disable gateway
        if (isset($settings['enabled']) && !empty($settings['enabled'])) {
            if (isset($edd_db_options['gateways'])) {
                $edd_db_options['gateways'] += array(
                    $gateway_id => 1
                );
            } else {
                $edd_db_options += array('gateways' => array($gateway_id => 1));
            }
        } elseif (isset($edd_db_options['gateways'][$gateway_id])) {
            unset($edd_db_options['gateways'][$gateway_id]);
        }

        //check icon
        if (isset($settings['is_show_icon']) && !empty($settings['is_show_icon'])) {
            if (!isset($edd_db_options['accepted_cards'])) {
                $edd_db_options += array(
                    'accepted_cards' =>
                    array(
                        $settings['payment_icon_url'] => $settings['title']
                    )
                );
            }
        }

        //make default dateway
        if (isset($settings['is_default_gateway']) && !empty($settings['is_default_gateway'])) {
            $edd_db_options['default_gateway'] = $gateway_id;
        } elseif ($edd_db_options['default_gateway'] == $gateway_id) {
            $edd_db_options['default_gateway'] = '';
        }

        //update edd options
        update_option('edd_settings', $edd_db_options);

        return wp_send_json(array(
            'status' => true,
            'title' => __("Success", 'edd-bitcoin-altcoin-payment-gateway'),
            'text' => __("Your settings have been saved.", 'edd-bitcoin-altcoin-payment-gateway'),
        ));
    }


    /**
     * get settings value
     * 
     * @return type
     */
    public static function get_settings_options()
    {
        $altcoin_id = EddFunctions::get_gateway_id();
        return get_option($altcoin_id);
    }

    /**
     * Save checkout page options
     * 
     * @return array
     */
    public function save_checkout_page_options()
    {
        $settings = Util::check_evil_script($_POST['cs_altcoin_config']);
        array_walk($settings, 'sanitize_text_field');
        update_option(self::$checkout_page_options_id, $settings, 'yes');

        return wp_send_json(array(
            'status' => true,
            'title' => __("Success", 'edd-bitcoin-altcoin-payment-gateway'),
            'text' => __("Your settings have been saved.", 'edd-bitcoin-altcoin-payment-gateway'),
        ));
    }

    /**
     * import settings from old version
     * 
     */
    public static function save_checkout_page_optn_from_old($data)
    {
        update_option(self::$checkout_page_options_id, $data, 'yes');
    }

    /**
     * get checkout page options
     */
    public static function get_checkout_page_options()
    {
        return get_option(self::$checkout_page_options_id);
    }


    /**
     * Save checkout page options
     * 
     * @return array
     */
    public function save_product_page_options()
    {
        $settings = Util::check_evil_script($_POST['cs_altcoin_config']);
        array_walk($settings, 'sanitize_text_field');
        update_option(self::$product_page_options_id, $settings, 'yes');

        return wp_send_json(array(
            'status' => true,
            'title' => __("Success", 'edd-bitcoin-altcoin-payment-gateway'),
            'text' => __("Your settings have been saved.", 'edd-bitcoin-altcoin-payment-gateway'),
        ));
    }

    /**
     * import settings from old version
     * 
     */
    public static function save_product_page_optn_from_old($data)
    {
        update_option(self::$product_page_options_id, $data, 'yes');
    }

    /**
     * get checkout page options
     */
    public static function get_product_page_options()
    {
        return get_option(self::$product_page_options_id);
    }

    /**
     * Get option for checkout
     */
    public static function get_Ebapg_options()
    {
        $checkout_options = self::get_checkout_page_options();
        $altcoin_id = EddFunctions::get_gateway_id();
        $default_option = get_option($altcoin_id);

        return array_merge_recursive($default_option, $checkout_options);
    }
}
