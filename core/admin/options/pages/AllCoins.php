<?php

namespace EddBtcAltGateWayCoreLib\admin\options\pages;

/**
 * Class: Coin LIst
 * 
 * @package Admin
 * @since 1.0.0
 * @author CodeSolz <customer-support@codesolz.net>
 */

if (!defined('CS_EBAPG_VERSION')) {
    die();
}

use EddBtcAltGateWayCoreLib\admin\builders\CsAdminPageBuilder;
use EddBtcAltGateWayCoreLib\admin\options\Coin_List;
use EddBtcAltGateWayCoreLib\lib\Util;

class AllCoins
{

    /**
     * Hold page generator class
     *
     * @var type 
     */
    private $Admin_Page_Generator;

    public function __construct(CsAdminPageBuilder $AdminPageGenerator)
    {
        $this->Admin_Page_Generator = $AdminPageGenerator;
    }

    /**
     * Generate all coins list
     * 
     * @param type $args
     * @return type
     */
    public function generate_coin_list($args)
    {

        $page = isset($_GET['page']) ? Util::check_evil_script($_GET['page']) : '';
        if (isset($_GET['s']) && !empty($_GET['s'])) {
            $back_url = Util::cs_generate_admin_url($page);
            $args['well'] = "<p class='search-keyword'>Search results for : '<b>" . Util::check_evil_script($_GET['s']) . "</b>' </p> <a href='{$back_url}' class='button'><< Back to all</a> ";
        }

        ob_start();
        $adCodeList = new Coin_List();
        $adCodeList->prepare_items();
        echo '<form id="plugins-filter" method="get"><input type="hidden" name="page" value="' . $page . '" />';
        $adCodeList->views();
        $adCodeList->search_box(__('Search Coin', 'edd-bitcoin-altcoin-payment-gateway'), '');
        $adCodeList->display();
        echo "</form>";
        $html = ob_get_clean();

        $args['content'] = $html;


        return $this->Admin_Page_Generator->generate_page($args);
    }
}
