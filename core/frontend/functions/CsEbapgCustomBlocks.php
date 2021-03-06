<?php

namespace EddBtcAltGateWayCoreLib\frontend\functions;

/**
 * Frontend Custom Blocks
 * 
 * @package Admin 
 * @since 1.0.0
 * @author CodeSolz <customer-service@codesolz.com>
 */

if (!defined('CS_EBAPG_VERSION')) {
    exit;
}

use EddBtcAltGateWayCoreLib\lib\Util;
use EddBtcAltGateWayCoreLib\admin\functions\CsAdminQuery;
use EddBtcAltGateWayCoreLib\admin\functions\CsPaymentGateway;

class CsEbapgCustomBlocks
{

    /**
     * Special discount notification on product page
     * 
     * @return string
     */
    public static function special_discount_offer_box()
    {
        $offers = CsAdminQuery::get_offers_info();
        $settings = CsPaymentGateway::get_checkout_page_options();
        if ($offers) {
            ?>
        <div class="special-discount-notification">
            <span class="highlight"> <?php echo isset($settings['offer_msg_blink_text']) ? \esc_html($settings['offer_msg_blink_text']) : __('Special Discount!', 'edd-bitcoin-altcoin-payment-gateway'); ?></a> <?php _e('to see more..', 'edd-bitcoin-altcoin-payment-gateway'); ?>
        </div>
        <div class="special-discount-box hidden">
            <b><?php
                echo isset($settings['offer_msg_text']) ? \esc_html($settings['offer_msg_text']) : __('You will get special discount, if you pay with following AltCoins', 'edd-bitcoin-altcoin-payment-gateway');
                ?></b>
            <ol>
                <?php foreach ($offers as $offer) { ?>
                    <li><?php echo $offer->name; ?> - <?php echo Util::get_discount_type($offer->offer_amount, $offer->offer_type); ?></li>
                <?php } ?>
            </ol>
        </div>
        <style type="text/css">
            .special-discount-box {
                background: aliceblue;
                border: 1px dashed black;
                padding: 10px;
                margin: 15px 0px;
            }

            .special-discount-box ol {
                margin: 10px 0px 0px 10px;
            }

            .special-discount-box ol li {
                font-style: italic;
                line-height: 16px;
            }

            .special-discount-notification span.highlight {
                cursor: pointer;
                color: forestgreen;
                animation: blink-animation 1s steps(5, start) infinite;
                -webkit-animation: blink-animation 1s steps(5, start) infinite;
            }

            @keyframes blink-animation {
                to {
                    visibility: hidden;
                }
            }

            @-webkit-keyframes blink-animation {
                to {
                    visibility: hidden;
                }
            }

            .special-discount-notification a {
                cursor: pointer;
            }

            .special-discount-notification {
                margin: 15px 0px;
            }

            .hidden {
                display: none;
            }
        </style>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery(".special-discount-notification").toggle(function() {
                    jQuery(".special-discount-box").slideDown('slow');
                }, function() {
                    jQuery(".special-discount-box").slideUp('slow');

                });
            });
        </script>
    <?php
    }
}
}
