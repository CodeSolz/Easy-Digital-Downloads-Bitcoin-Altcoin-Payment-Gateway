<?php

namespace EddBtcAltGateWayCoreLib\frontend\functions;

/**
 * From Builder
 * 
 * @package Admin 
 * @since 1.0.0
 * @author CodeSolz <customer-service@codesolz.com>
 */

if (!defined('CS_EBAPG_VERSION')) {
    exit;
}

use EddBtcAltGateWayCoreLib\admin\functions\CsAdminQuery;
use EddBtcAltGateWayCoreLib\admin\functions\CsPaymentGateway;
use EddBtcAltGateWayCoreLib\lib\Util;

class CsEbapgCheckoutForm
{

    /**
     * render checkout block
     */
    public static function checkout_form()
    {
        global $wp;
        $options = (object) CsPaymentGateway::get_checkout_page_options();
        ?>
    <fieldset id="" class="">
        <legend><?php echo isset($options->block_section_title) ? esc_html($options->block_section_title) : __('Cryptocurrency Payment Information', 'edd-bitcoin-altcoin-payment-gateway'); ?></legend>
        <p>
            <label><?php echo isset($options->select_box_lebel) ? esc_html($options->select_box_lebel) : __('Please select coin you want to pay:', 'edd-bitcoin-altcoin-payment-gateway'); ?></label>
            <?php echo self::getActiveAltCoinSelect($options); ?>
        </p>
        <div class="coin-detail">
            <!--coin calculation-->
        </div>
        <?php $premade_order_id = isset($wp->query_vars['order-pay']) ? $wp->query_vars['order-pay'] : 0; ?>
        <input type="hidden" name="is_premade_order" id="is_premade_order" value="<?php echo $premade_order_id; ?>" />
    </fieldset>
<?php

}

/**
 * Active altCoins List - checkout page
 * 
 * @param type $refObj
 * @return type
 */
public static function getActiveAltCoinSelect($refObj = false)
{
    $custom_fields = CsAdminQuery::get_coins(array('where' => " c.status = 1 "));
    // $custom_fields = '';
    if (empty($custom_fields)) {
        return __('Sorry! No Cryptocurrency is activate! Please contact administration for more information.', 'edd-bitcoin-altcoin-payment-gateway');
    }

    $altCoin = '<select name="altcoin" id="CsaltCoin" class="select">';
    $lebel = isset($refObj->select_box_option_lebel) && !empty($refObj->select_box_option_lebel) ? \esc_html($refObj->select_box_option_lebel) : __('Please Select An AltCoin', 'edd-bitcoin-altcoin-payment-gateway');
    $altCoin .= '<option value="0">' . esc_html($lebel) . '</option>';
    foreach ($custom_fields as $field) {
        $altCoin .= '<option value="' . Util::check_evil_script($field->cid) . '">' . \esc_html($field->name) . '(' . \esc_html($field->symbol) . ')</option>';
    }
    return $altCoin .= '</select>';
}
}
