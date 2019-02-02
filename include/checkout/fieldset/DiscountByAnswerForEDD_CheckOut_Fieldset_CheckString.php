<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_CheckOut_Fieldset_CheckString extends DiscountByAnswerForEDD_PluginUtility {

    public function __construct() {
        add_action( 'edd_checkout_form_top', array( $this, 'replyToPrintField' ), -0.5 );
    }

    /**
     * Renders the field that the user provides their string on a web page.
     * When the button is clicked, the plugin searches the string on the page with the pre-defined URL,
     * and if it is found, a discount will be applied.
     *
     * @since 0.0.1
     * @return void
     * @callback    action  edd_checkout_form_top
    */
    public function replyToPrintField() {

        if ( ! $this->___shouldPrintField() ) {
            return;
        }

        /**
         * Check the items in the cart using `edd_get_cart_content_details()` or `edd_get_cart_contents()`.
         * the `id` element holds the downloads ID. Then check if discount by web-string is enabled or not.
         * If enabled, add the field for each. In other words, multiple items and their fields can be possible here.
         */
        $_aDownloadIDs = wp_list_pluck( edd_get_cart_contents(), 'id' );
        $_aCampaigns   = $this->getCartActiveCampaigns( $_aDownloadIDs );
        if ( empty( $_aCampaigns ) ) {
            return;
        }

        $_sColor        = edd_get_option( 'checkout_color', 'blue' );
        $_sColor        = ( $_sColor == 'inherit' ) ? '' : $_sColor;
        $_sButtonStyle  = edd_get_option( 'button_style', 'button' );

        echo $this->getOutputBuffer(
            array( $this, 'printCampaigns' ),   // callback
            array( $_aDownloadIDs, $_aCampaigns, $_sColor, $_sButtonStyle )
        );

    }

        /**
         * @return bool
         */
        private function ___shouldPrintField() {
            if ( isset( $_GET[ 'payment-mode' ] ) && edd_is_ajax_disabled() ) {
                return false; // Only show before a payment method has been selected if ajax is disabled
            }
            if ( ! edd_is_checkout() ) {
                return false;
            }
            if ( ! ( edd_has_active_discounts() && edd_get_cart_total() ) ) {
                return false ;
            }
            return true;
        }

        /**
         * @param array $aDownloadIDs
         * @param string  $sColor
         * @param
         * @return string
         * @since   1.0.0
         */
        public function printCampaigns( array $aDownloadIDs, array $aCampaigns, $sColor, $sButtonStyle ) {

            $_sOutput = '';
            foreach( $aCampaigns as $_oCampaign ) {

                // Check if the associated base discount code is active or not, if not do not display the field.
                $_iBaseDiscountCodeID = $_oCampaign->get( array( '_edddba_discount', 'base_discount_code', 'value' ), 0 );

                if ( ! edd_is_discount_active( $_iBaseDiscountCodeID, false ) ) {
                    continue;
                }
                $_sOutput .= $this->getOutputBuffer(
                    array( $this, 'printEachCampaign' ),   // callback
                    array( $aDownloadIDs, $_oCampaign, $sColor, $sButtonStyle )
                );
            }
            return $_sOutput;

        }
            /**
             * @param array $aDownloadIDs
             * @param DiscountByAnswerForEDD_Campaign $oCampaign
             * @param $sColor
             * @param $sButtonStyle
             * @return  string
             * @remark  This is based on the `edd_discount_field()` core function.
             */
            public function printEachCampaign( array $aDownloadIDs, DiscountByAnswerForEDD_Campaign $oCampaign, $sColor, $sButtonStyle ) {

                $_sID          = ( string ) $oCampaign->get( 'id' );
                $_aRequests    = $oCampaign->getArray( '_edddba_requests' );
                $_iItem        = 0;
                $_sDownloadIDs = implode( ',', $aDownloadIDs );

                ?>
                <fieldset id="edddba_campaign_<?php echo $_sID; ?>" class="edddba_campaign">
                    <p id="edddba_show_<?php echo $_sID; ?>" class="edddba_show" style="display:none;">
                        <?php echo $oCampaign->getLabel( 'catch' ); ?> <a href="#" class="edddba_link"><?php echo $oCampaign->getLabel( 'expand' ); ?></a>
                    </p>
                    <div id="edddba_wrap_<?php echo $_sID; ?>" class="edd-cart-adjustment edddba_wrap">
                        <p id="edddba_hide_<?php echo $_sID; ?>" class="edddba_hide">
                            <a href="#" class="edddba_link"><?php echo $oCampaign->getLabel( 'close' ); ?></a>
                        </p>
                        <?php $this->___printCampaignTitle( $oCampaign->getLabel( 'title' ) ); ?>
                            <?php foreach( $_aRequests as $_iIndex => $_aRequest ) :  ?>
                            <?php
                                if ( ! $this->getElement( $_aRequest, 'status' ) ) {
                                    continue;
                                }
                                ?>
                                <?php if ( $_iItem ) : ?>
                                <span class="margin_for_inline"></span>
                                <?php endif; ?>
                                <span class="edddba_request_wrap">
                                    <?php
                                    $this->___printFieldTitle( $_aRequest );
                                    ?>
                                    <span class="edd-description edddba_description"><?php echo trim( $this->getElement( $_aRequest, array( 'labels', 'description' ) ) ); ?></span>
                                    <?php $this->___printEachRequestField( $_aRequest, $oCampaign ); ?>
                                </span>
                            <?php ++$_iItem; ?>
                            <?php endforeach; ?>
                            <span class="edd-discount-code-field-wrap submit">
                                <input type="hidden" class="edddba_data" data-id="<?php echo $_sDownloadIDs; ?>" data-campaignID="<?php echo $_sID; ?>" />
                                <input type="submit" class="edd-apply-discount edd-submit edddba_submit <?php echo $sColor . ' ' . $sButtonStyle; ?>" value="<?php echo $oCampaign->getLabel( 'button', __( 'Submit', 'discount-by-answer-for-easy-digital-downloads' ) ); ?>"/>
                            </span>
                            <span id="edddba_loader_<?php echo $_sID; ?>" data-id="edd-discount-loader" class="edd-discount-loader edd-loading edddba_loader" style="display:none;"></span>
                            <span id="edddba_error_wrap_<?php echo $_sID; ?>" data-id="edd-discount-error-wrap" class="edd_error edd-alert edd-alert-error edddba_error_wrap" aria-hidden="true" style="display:none;"></span>
                            <span id="edddba_notice_wrap_<?php echo $_sID; ?>" class="edddba_notice_wrap edd-alert" aria-hidden="true" style="display:none;"></span>
                    </div>
                </fieldset>
                <?php
            }
                private function ___printCampaignTitle( $sTitle ) {
                    $sTitle = trim( $sTitle );
                    if ( ! $sTitle ) {
                        return;
                    }
                    ?>
                    <legend><?php echo $sTitle; ?></legend>
                    <?php
                }
                private function ___printEachRequestField( array $aRequest, DiscountByAnswerForEDD_Campaign $oCampaign ) {

                    $_sType     = $this->getElement( $aRequest, array( 'type' ) );
                    if ( ! $_sType ) {
                        trigger_error( 'Discount by Answer For Easy Digital Downloads: The request type is not set.' );
                        return;
                    }
                    $_aOptions  = $this->getElementAsArray( $aRequest, array( $_sType ) );
                    $_sClass    = "DiscountByAnswerForEDD_CheckOut_Fieldset_Input_{$_sType}";

                    $_oRenderer = new $_sClass( $_aOptions, $aRequest, $oCampaign );
                    echo $_oRenderer->get();

                }

                private function ___printFieldTitle( array $aRequest ) {
                    $_sTitle     = $this->getElement( $aRequest, 'name' );
                    $_sIndicator = $this->___getRequiredIndicator( $aRequest );
                    if ( ! $_sTitle && ! $_sIndicator ) {
                        return;
                    }
                    $_sTitle     = $_sTitle
                        ? '<span class="edddba-field-title">' . $_sTitle . '</span>'
                        : '';
                    ?>
                    <label class="edd-label" for="edd-discount"><?php echo $_sTitle . $_sIndicator; ?></label>
                    <?php
                }

                private function ___getRequiredIndicator( array $aRequest ) {
                    if ( ! $this->getElement( $aRequest, 'required' ) ) {
                        return;
                    }
                    return '<span class="edd-required-indicator">*</span>';
                }

}