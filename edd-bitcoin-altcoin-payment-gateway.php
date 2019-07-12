<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Easy Digital Downloads - Bitcoin / AltCoin Payment Gateway
 * Plugin URI:        https://coinmarketstats.online/wordpress-plugin/easy-digital-downloads-bitcoin-altcoin-payment-gateway
 * Description:       A very light weight Cryptocurrency payment gateway for Easy Digital Downloads.  
 * Version:           1.0.0
 * Author:            CodeSolz.net
 * Author URI:        https://www.codesolz.net
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl.txt
 * Domain Path:       /languages
 * Text Domain:       edd-bitcoin-altcoin-payment-gateway
 * Requires PHP: 6.0
 * Requires At Least: 4.0
 * Tested Up To: 5.2
  * EDD tested up to: 2.9
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! class_exists( 'EDD_Bitcoin_Altcoin_Payment_Gateway' ) ){
    
    class EDD_Bitcoin_Altcoin_Payment_Gateway {
        
        /**
         * Hold actions hooks
         *
         * @var type 
         */
        private static $ebapg_hooks = [];
        
        /**
         * Hold version
         * 
         * @var String 
         */
        private static $version = '1.0.0';
        
        /**
         * Hold version
         * 
         * @var String 
         */
        private static $db_version = '1.0.0';

        /**
         * Hold nameSpace
         *
         * @var type 
         */
        private static $namespace = 'EddBtcAltGateWayCoreLib';
                
        
        function __construct(){
            
            //load plugins constant
            self::set_constants();
            
            //load core files
            self::load_core_framework();
            
            //load init
            self::load_action_files();
            
            //during activation
            self::init_activation();
            
            /**load textdomain */
            add_action( 'plugins_loaded', array( __CLASS__, 'ebapg_textdomain' ), 15 );
            
            /**check plugin db*/
            add_action( 'plugins_loaded', array( __CLASS__, 'ebapg_check_db' ), 17 );
            
            /**load gateway*/
            add_action( 'plugins_loaded', array( __CLASS__, 'ebapg_init_gateway' ), 21 );
            
        }
        
        /**
         * Set constant data
         */
        private static function set_constants(){
            $constants = array(
                'CS_EBAPG_VERSION'           => self::$version, //Define current version
                'CS_EBAPG_DB_VERSION'        => self::$db_version, //Define current db version
                'CS_EBAPG_BASE_DIR_PATH'     => untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/', //Hold plugins base dir path
                'CS_EBAPG_PLUGIN_ASSET_URI'  => plugin_dir_url( __FILE__ ) . 'assets/', //Define asset uri
                'CS_EBAPG_PLUGIN_LIB_URI'    => plugin_dir_url( __FILE__ ) . 'lib/', //Library uri
                'CS_EBAPG_PLUGIN_IDENTIFIER' => plugin_basename( __FILE__ ), //plugins identifier - base dir
                'CS_EBAPG_PLUGIN_NAME'       => 'Easy Digital Downloads - Bitcoin / AltCoin Payment Gateway' //Plugin name
            );
            
            foreach($constants as $name => $value ){
                self::set_constant( $name, $value );
            }
            
            return true;
        }
        
        /**
         * Set constant
         * 
         * @param type $name
         * @param type $value
         * @return boolean
         */
        private static function set_constant( $name, $value ){
            if( ! defined( $name ) ){
                define( $name, $value );
            }
            return true;
        }
        
        /**
         * load core framework
         */
        private static function load_core_framework(){
            require_once CS_EBAPG_BASE_DIR_PATH . 'vendor/autoload.php';
        }
        
        /**
         * load action files
         */
        private static function load_action_files(){
            foreach ( glob( CS_EBAPG_BASE_DIR_PATH . "core/actions/*.php") as $cs_action_file ) {
                $class_name = basename( $cs_action_file, '.php' );
                $class =  self::$namespace . '\\actions\\'. $class_name; 
                if ( class_exists( $class ) && ! array_key_exists( $class, self::$ebapg_hooks ) ) { //check class doesn't load multiple time
                    self::$ebapg_hooks[ $class ] = new $class();
                }
            }
        }
        
        /**
         * init activation hook
         */
        private static function init_activation(){
            //load config
            require_once CS_EBAPG_BASE_DIR_PATH . 'core/install/ebapg_db_config.php';
            //register hook
            register_activation_hook( __FILE__, array( self::$namespace . '\\install\\Activate', 'on_activate' ) );
            
            return true;
        }

        /**
         * init textdomain
         */
        public static function ebapg_textdomain(){
            load_plugin_textdomain( 'edd-bitcoin-altcoin-payment-gateway', false, CS_EBAPG_BASE_DIR_PATH . '/languages/' );
        }
        
        /**
         * Init payment gateway
         * 
         * @return 
         */
        public static function ebapg_init_gateway(){
            $CsWooPlMissing = self::$namespace . "\admin\\functions\\CsWapgNotice";
            
            //check is subscribed or not
            $CsAutoOrder = self::$namespace . "\\admin\\functions\\CsAutomaticOrderConfirmationSettings";
            $isAutoOrder = $CsAutoOrder::get_order_confirm_settings_data();
            if(empty($isAutoOrder)){
                new $CsWooPlMissing( 2 );
            }
            
            $CsGatewayInit = self::$namespace . "\admin\settings\CsGateWaySettings";
            return new $CsGatewayInit();
        }

        /**
         * Check db status
         */
        public static function ebapg_check_db(){
            $cls_install = self::$namespace . "\install\Activate";
            $cls_install::ebapg_check_db_status();
        }
        
    }
    
    global $EBAPG;
    $EBAPG = new EDD_Bitcoin_Altcoin_Payment_Gateway(); 
}