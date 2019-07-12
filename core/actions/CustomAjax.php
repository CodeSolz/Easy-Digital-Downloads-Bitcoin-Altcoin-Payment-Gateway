<?php

namespace EddBtcAltGateWayCoreLib\actions;

/**
 * Class: Custom ajax call
 * 
 * @package Admin
 * @since 1.0.0
 * @author CodeSolz <customer-support@codesolz.net>
 */

if (!defined('CS_EBAPG_VERSION')) {
    die();
}

use EddBtcAltGateWayCoreLib\lib\Util;

class CustomAjax
{

    /**
     * plugin name space
     */
    private $namespace = 'EddBtcAltGateWayCoreLib';

    function __construct()
    {
        add_action('wp_ajax__cs_ebapg_custom_call', array($this, '_cs_ebapg_custom_call'));
        add_action('wp_ajax_nopriv__cs_ebapg_custom_call', array($this, '_cs_ebapg_custom_call'));
    }


    /**
     * custom ajax call
     */
    public function _cs_ebapg_custom_call()
    {

        if (!isset($_REQUEST['cs_token']) || false === check_ajax_referer(SECURE_AUTH_SALT, 'cs_token', false)) {
            wp_send_json(array(
                'status' => false,
                'title' => __('Invalid token', 'edd-bitcoin-altcoin-payment-gateway'),
                'text' => __('Sorry! we are unable recognize your auth!', 'edd-bitcoin-altcoin-payment-gateway')
            ));
        }

        if (!isset($_REQUEST['data']) && isset($_POST['method'])) {
            $data = Util::check_evil_script($_POST);
        } else {
            $data = Util::check_evil_script($_REQUEST['data']);
        }

        if (empty($method = $data['method']) || strpos($method, '@') === false) {
            wp_send_json(array(
                'status' => false,
                'title' => __('Invalid Request', 'edd-bitcoin-altcoin-payment-gateway'),
                'text' => __('Method parameter missing / invalid!', 'edd-bitcoin-altcoin-payment-gateway')
            ));
        }

        $method = explode('@', $method);
        $class_path = str_replace('\\\\', '\\', "\\{$this->namespace}\\" . $method[0]);
        if (!class_exists($class_path)) {
            wp_send_json(array(
                'status' => false,
                'title' => __('Invalid Library', 'edd-bitcoin-altcoin-payment-gateway'),
                'text' => sprintf(__('Library Class "%s" not found! ', 'edd-bitcoin-altcoin-payment-gateway'), $class_path)
            ));
        }

        if (!method_exists($class_path, $method[1])) {
            wp_send_json(array(
                'status' => false,
                'title' => __('Invalid Method', 'edd-bitcoin-altcoin-payment-gateway'),
                'text' => sprintf(__('Method "%s" not found in Class "%s"! ', 'edd-bitcoin-altcoin-payment-gateway'), $method[1], $class_path)
            ));
        }

        echo (new $class_path())->{$method[1]}($data);
        exit;
    }
}
