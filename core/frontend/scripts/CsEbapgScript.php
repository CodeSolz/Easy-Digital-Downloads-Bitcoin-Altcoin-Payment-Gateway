<?php namespace EddBtcAltGateWayCoreLib\frontend\scripts;
/**
 * Frontend Script
 * 
 * @package Admin 
 * @since 1.0.0
 * @author CodeSolz <customer-service@codesolz.com>
 */

if ( ! defined( 'CS_EBAPG_VERSION' ) ) {
    exit;
}

use EddBtcAltGateWayCoreLib\admin\functions\CsPaymentGateway;

class CsEbapgScript {
    /**
     * Hold Main class reference
     *
     * @var type 
     */
    var $Ref;
    
    function __construct(){
        //get main ref
        $this->Ref = (object) CsPaymentGateway::get_Ebapg_options();
        
        
        //load script on frontend footer
        // add_action( 'wp_footer', array( $this, 'ebapg_altCoinCustomScript') );
        $this->ebapg_altCoinCustomScript();
        // pre_print($this->Ref);
    }
    
    /**
     * cart page
     * 
     * @return string
     */
    public function ebapg_altCoinCustomScript(){
        // echo 'fuck'; exit;
        ?>
            <script type="text/javascript">
                var module = {
                    altCoinPayment: function( res ){
                        var sdm = ''; var sda = ''; var sdf = ''; var ctfd = '';
                        if( true === res.special_discount_status ){
                            sdm = res.special_discount_msg; 
                            sda = '<tr class="cart-discount">'+
                                '<td><?php _e( 'Special Discount', 'edd-bitcoin-altcoin-payment-gateway' ); ?></td>'+
                                '<td><span class="woocommerce-Price-amount amount">'+res.special_discount_amount+'</span></td>'+
                            '</tr>';
                            sdf = '<input type="hidden" name="special_discount_amount" value="'+res.special_discount_amount+'" />';
                            ctfd = '<span class="woocommerce-cart-subtotal-after-discount">' + res.currency_symbol + res.cartTotalAfterDiscount + '</span><br>';
                        }
                        
                        var stc = '';
                        if( res.nativeAltCoinPrice > 0 ){
                            stc = '<br><span class="price-tag"> 1 '+res.coinName+' = ' + res.currency_symbol + res.nativeAltCoinPrice +' (' + res.store_currency_shortname + ')  </span>';
                        }
                        
                        var cfn = '';
                        if( res.store_currency_fullname != '' ){
                            cfn = '<br><span class="currency-fullname help-info"> ( '+res.store_currency_fullname+' ) </span>';
                        }
                        
                        var ctc = '';
                        if( res.checkoutType == 1 ){
                            ctc = '<p class="form-row form-row-wide">'+
                                '<input type="checkbox" name="payment_confirm" required=""/>'+
                                '<?php _e( 'I have completed the coin transfer successfully! ', 'edd-bitcoin-altcoin-payment-gateway' ); ?>'+
                            '</p>';
                        }else if( res.checkoutType == 2 ){
                            ctc = 
                            '<p class="form-row form-row-wide">'+
                            '<label for="user-alt-coinAddress"><?php _e( 'Please enter a secret word.', 'edd-bitcoin-altcoin-payment-gateway' ); ?><span class="required">*</span></label>'+
                            '<input id="secret_word" name="secret_word" class="input-text " required  type="text" placeholder="<?php _e('please enter a secret word.', 'edd-bitcoin-altcoin-payment-gateway');?>" />'+
                            '<span class="hints"><?php _e( 'Incidentally if you close this window or somehow you become disconnected, You can use this secret word to submit your order. Save the word in safe place.', 'edd-bitcoin-altcoin-payment-gateway' ); ?></span>'+
                            '</p>'+
                            '<div class="loader-coin-track hide"></div> '+
                            '<div class="tracking-response hide"></div> '+
                            '<p class="form-row form-row-wide section-btn-coin-track">'+
                                '<input type="hidden" class="cs-form-status" value="1" /> '+
                                '<input type="button" class="btn-coin-track" value="<?php _e( 'Track My Coin Transfer ', 'wccpg-txt-dom' ); ?>" /> '+
                            '</p>'+
                            '<p class="form-row form-row-wide tracking-notice">'+
                                '<?php _e( 'N.B: After initiate the coin transfer, please click the "Track My Coin Transfer" button immediately. Do not close the window until the tracking process get done. Otherwise your order will not be proceed.', 'wccpg-txt-dom' ); ?>'+
                            '</p>';
                        }
                        
                        
                        return sdm +
                        '<h3 id="Ebapg_order_review_heading"><?php echo isset( $this->Ref->price_section_title ) && !empty($this->Ref->price_section_title) ? $this->Ref->price_section_title : __( 'You have to pay:', 'edd-bitcoin-altcoin-payment-gateway' ); ?></h3>'+
                        '<div id="Ebapg_order_review" class="woocommerce-checkout-review-order">'+
                            '<table class="shop_table woocommerce-checkout-review-order-table">'+
                        '<thead>'+
                            '<tr>'+
                                '<th class="product-name"><?php _e( 'Coin', 'edd-bitcoin-altcoin-payment-gateway' ); ?></th>'+
                                '<th class="product-total"><?php _e( 'Total', 'edd-bitcoin-altcoin-payment-gateway' ); ?></th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>'+
                            '<tr class="cart_item">'+
                                '<td class="product-name">'+
                                    res.coinFullName+'&nbsp;<strong class="product-quantity">&times; '+res.totalCoin+'</strong><br>'+
                                    '<span class="price-tag"> 1 '+res.coinName+' = &#36;'+res.coinPrice+' (USD) </span>'+stc+
                                '</td>'+
                                '<td class="product-total">'+
                                    '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'+res.currency_symbol+'</span>'+res.cartTotal+'</span>'+
                                    cfn+
                                '</td>'+
                            '</tr>' + sda +
                        '</tbody>'+
                    '<tfoot>'+
                    '<tr class="cart-subtotal">'+
                        '<th><?php _e( 'Subtotal', 'edd-bitcoin-altcoin-payment-gateway' ); ?></th>'+
                        '<td>'+ ctfd +
                            '<span class="woocommerce-Price-amount amount">'+res.totalCoin+' - <span class="woocommerce-Price-currencySymbol">'+res.coinFullName+'</span> </span>'+
                        '</td>'+
                    '</tr>'+
                    '<tr class="order-total">'+
			'<th><?php _e( 'Net Payable Amount', 'edd-bitcoin-altcoin-payment-gateway' ); ?><span class="price-tag"><br><?php _e( '(*Transfer Fee Not Included)', 'edd-bitcoin-altcoin-payment-gateway' ); ?></span></th>'+
			'<td>'+
                        '<strong><span class="woocommerce-Price-amount amount">'+res.totalCoin+' - <span class="woocommerce-Price-currencySymbol">'+res.coinFullName+'</span></span></strong>'+
                        '</td>'+
                    '</tr>'+
                    '</tfoot>'+
                    '</table>'+
                    '<p class="form-row form-row-wide">'+
                        '<label for="alt-coinAddress"><?php _e( 'Please pay to following address:', 'edd-bitcoin-altcoin-payment-gateway' ); ?></label>'+
                    '</p>'+
                    '<div class="coinAddress-qr">'+
                        '<img class="qr-code" src="https://chart.googleapis.com/chart?chs=225x225&cht=qr&chl='+res.coinName+':'+res.coinAddress+'?cart_total:'+res.totalCoin+'"/>'+
                        '<div class="coinAddress-info">'+
                            '<h3><strong>'+res.totalCoin+'</strong> '+res.coinFullName+'</h3>'+
                            '<input id="alt-coinAddress" name="marchant_alt_address" class="input-text wc-altcoin-form-user-alt-coinAddress" value="'+res.coinAddress+'" type="text" /><p></p>'+
                            '<p class="form-row form-row-wide alt-info"><?php _e( "NB: Don\'t forget to add the transfer fee.", 'edd-bitcoin-altcoin-payment-gateway' ); ?><p>'+
                        '</div>'+
                    '</div>'+
                    
                    '<p class="form-row form-row-wide">'+
                        '<label for="user-alt-coinAddress"><?php _e( 'Please enter your ', 'edd-bitcoin-altcoin-payment-gateway' ); ?>'+res.coinName+'<?php _e( ' transaction id:', 'edd-bitcoin-altcoin-payment-gateway' ); ?> <span class="required">*</span></label>'+
                        '<input id="user_alt_coinAddress" name="trxid" class="input-text wc-altcoin-form-user-alt-res.coinAddress" inputmode="numeric" required  autocorrect="no" autocapitalize="no" spellcheck="no" type="text" placeholder="<?php _e('please enter here your coin transaction id', 'edd-bitcoin-altcoin-payment-gateway');?>" />'+
                        '<input type="hidden" name="payment_info" value="'+res.cartTotal+'__'+res.totalCoin+'__'+res.coinName+'__'+res.coinAddress+'__'+res.coinPrice+'" />'+
                        '<input type="hidden" name="premade_order_id" value="'+res.premadeOrderId+'" />'+
                        sdf+
                    '</p>' + ctc;
                    },
                    submit_auto_order: async function( form_data ){
                        return new Promise( ( resolve, reject) => {
                            jQuery.post( Ebapg_ajax.ajax_url, form_data)
                                .done(function( res ) {resolve( res ) })
                                .error(function( res ) {reject( res ) });
                        });
                    },
                    disable_fields : function(){
                        jQuery("#secret_word, #user_alt_coinAddress, #CsaltCoin").attr( 'readonly', '' );
                    },        
                    enable_fields : function(){
                        jQuery("#secret_word, #user_alt_coinAddress, #CsaltCoin").removeAttr( 'readonly');
                    }        
                };
                
                jQuery(document).ready(function(){
                    var $orderSubmitBtn = jQuery(".edd-submit");
                    
                    jQuery("body").on('change', 'input[name="payment_method"]', function(){
                        $orderSubmitBtn.show('slow');
                    });
                    jQuery('body').on( "change", '#CsaltCoin',function(){
                        var val = jQuery(this).val();
                        if( parseInt(val) === 0 ){
                            jQuery(".coin-detail").slideUp('slow').html('');
                        }else{
                            jQuery(".coin-detail").html('<div class="loader"><img src="<?php echo $this->Ref->loader_gif_url; ?>" /></div>').slideDown('slow');
                            
                            
                            var form_data = {
                                action : '_cs_ebapg_custom_call',
                                cs_token  : '<?php echo wp_create_nonce(SECURE_AUTH_SALT); ?>',
                                data   : {
                                    method : 'frontend\\functions\\CsEbapgCoinCal@calcualteCoinPrice',
                                    coin_id : val,
                                    coin_name : jQuery(this).find("option:selected").text(),
                                    pre_order_id: jQuery("#is_premade_order").val()
                                }
                            };
                            
                            jQuery.post( Ebapg_ajax.ajax_url, form_data, function ( res ) {
                                console.log( res );
                                console.log('dolly');
                                if( res.response === true ){
                                    jQuery(".coin-detail").html( module.altCoinPayment( res ) ).slideDown('slow');
                                    if( res.checkoutType == 2 ){
                                        setTimeout(()=>{
                                            // jQuery("#place_order").hide('slow');
                                            $orderSubmitBtn.hide('true');
                                        }, 2000);
                                    }else{
                                        $orderSubmitBtn.show('true');
                                        // jQuery("#place_order").show('slow');
                                    }
                                }else{
                                    jQuery(".coin-detail").html( res.msg ).slideDown('slow');
                                }
                                $orderSubmitBtn.removeAttr('disabled');
                            });
                        }
                    });
                    
                    jQuery("body").on( 'focus', '.wc-altcoin-form-user-alt-coinAddress', function(){
                        var $this = jQuery(this);
                        $this.select();
                        document.execCommand( 'Copy', false, null );
                        $this.next("p").css('color','forestgreen').slideDown('slow').text('<?php _e( 'Address has been coppied to clipboard!', 'edd-bitcoin-altcoin-payment-gateway' ); ?> ');
                    }).on( 'blur', '.wc-altcoin-form-user-alt-coinAddress', function(){
                        jQuery(this).next("p").slideUp('slow');
                    });
                    
                    jQuery("body").on( 'click', '.btn-coin-track', function(){
                        var $this = jQuery(this);
                        jQuery(".loader-coin-track").show('slow').html('<img src="<?php echo $this->Ref->autotracking_gif_url; ?>" width = "120"/>');
                        $this.attr( 'disabled', '' );
                        $this.attr( 'value', 'Please wait...' );
                        
                        jQuery(".tracking-response").hide('slow').html();
                        var form_data = {
                            action : '_cs_ebapg_custom_call',
                            cs_token  : '<?php echo wp_create_nonce(SECURE_AUTH_SALT); ?>',
                            data   : {
                                method : 'frontend\\functions\\CsEbapgAutoOrderConfirm@track_coin',
                                form_data : jQuery('form').serialize()
                            }
                        };
                        module.disable_fields();
                        (async () => {
                            
                            //call first time
                            console.log( 'api calling 1st..' );
                            const response = await module.submit_auto_order( form_data )
                                    .then( (res) => { console.log( res ); return res; })
                                    .catch( (res) => { console.log( res ); return res; });
                            
                          
                            if( typeof response.error !== 'undefined' && true === response.error ){
                                jQuery(".loader-coin-track").show('slow').html( response.response );    
                                $this.removeAttr( 'disabled' );
                                module.enable_fields();
                                $this.attr( 'value', 'Track My Coin Transfer' );
                                console.log( 'exit1');
                            }
                            else if( typeof response.success !== 'undefined' && false === response.success ){
                                jQuery(".tracking-response").show('slow').html( response.response );
                                var heartbeat = window.setInterval( async function(){
                                    console.log('api calling 2nd..');
                                    const apiResponse = await module.submit_auto_order( form_data )
                                                .then( (res) => { return res; })
                                                .catch( (res) => { console.log( res ); return res; });
                                    
                                    if( typeof apiResponse.error !== 'undefined' && true === apiResponse.error ) {
                                        jQuery(".loader-coin-track").show('slow').html( apiResponse.response );    
                                        $this.removeAttr( 'disabled' );
                                        clearInterval( heartbeat );
                                        module.enable_fields();
                                        $this.attr( 'value', 'Track My Coin Transfer' );
                                        console.log( 'exit2');
                                    }else if( typeof apiResponse.success !== 'undefined' && false === apiResponse.success ) {
                                        //continue
                                        jQuery(".tracking-response").show('slow').html( apiResponse.response );
                                    }else if( typeof apiResponse.success !== 'undefined' && true === apiResponse.success ) {
                                        //stop and submit order
                                        clearInterval( heartbeat );
                                        jQuery(".loader-coin-track").hide('slow');    
                                        jQuery(".tracking-response").show('slow').html( apiResponse.response );
                                        $this.removeAttr( 'disabled' );
                                        var orderBtn = jQuery(".edd-submit");
                                        orderBtn.show('slow');
                                        if( orderBtn.length > 0 ) {
                                            orderBtn.trigger( "click" );
                                        }
                                        module.enable_fields();
                                        $this.attr( 'value', 'Successful..' );
                                        console.log( 'exit3');
                                    }

                                }, 40000 );
                            }
                            else if( typeof response.success !== 'undefined' && true === response.success ){
                                //successfull - submit the order
                                jQuery(".loader-coin-track").hide('slow');    
                                jQuery(".tracking-response").show('slow').html( response.response );
                                console.log( 'exit4');
                                $this.removeAttr( 'disabled' );
                                var orderBtn = jQuery(".edd-submit");
                                orderBtn.show('slow');
                                if( orderBtn.length > 0 ) {
                                    orderBtn.trigger( "click" );
                                }
                                $this.attr( 'value', 'Successful..' );
                                module.enable_fields();
                            }else if( typeof response.response !== 'undefined' ){
                                jQuery(".loader-coin-track").show('slow').html( response.response );    
                                $this.removeAttr( 'disabled' );
                                clearInterval( heartbeat );
                                module.enable_fields();
                                $this.attr( 'value', 'Track My Coin Transfer' );
                                console.log( 'exit5');
                            }
                            
                        })();
                        
                        
                    });
                    
                });
            </script>
            <style type="text/css">
                .alt-info{font-style: italic;margin-bottom: 10px;border: 2px dashed #999;padding: 10px;margin-top: 25px;}
                .coin-detail{margin-bottom: 1.5em;}
                .coin-detail .loader, .loader-coin-track{ text-align: center;}
                .price-tag, .help-info{font-style: italic;font-size: 11px;}
                .qr-code{ max-height: 225px !important; }
                .coinAddress-qr{text-align: center;border: 2px dashed #999;padding: 16px 0px 5px 0px;margin-bottom: 15px; display: table;width: 100%; }
                .coinAddress-info{ position: relative;display: table-cell;vertical-align: top;padding: 10px 20px; width: 63%;}
                .coinAddress-info h3{ font-size: 15px; }
                .coinAddress-qr img{display: table-cell; padding: 0px 0px 11px 18px;}
                .special-discount-msg{ color: forestgreen; font-size: 15px; }
                .con{ color: red; font-weight: bold; }
                .blink{ animation: blink-animation 1s steps(5, start) infinite;-webkit-animation: blink-animation 1s steps(5, start) infinite;}
                .hide{ display: none; }
                @keyframes blink-animation {to {visibility: hidden;}}
                @-webkit-keyframes blink-animation {to{visibility: hidden;}}
                .loader-coin-track { margin-bottom: 60px; }
                .hints{ font-size: 12px; font-style: italic; }
                .tracking-notice{ font-size: 12px; border: 1px dashed; padding: 10px; font-style: italic; }
                #secret_word{ margin-bottom: 10px }
                .error-notice{ border: 1px solid red;padding: 8px;text-align: left;}
                .success-notice{ background: forestgreen;padding: 12px;text-align: left;color: #fff;font-size: 14px; }
                .tracking-response{ margin-bottom: 20px; }
            </style>
        <?php
    }
    
}
