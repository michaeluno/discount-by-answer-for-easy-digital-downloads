<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Verifies discount codes which are about to be used in the purchase form.
 * This prevents the visitor from using multiple discount codes issued by the same campaign.
 * @since   0.5.0
 */
class DiscountByAnswerForEDD_Campaign_EDD_Event_VerifyDiscountCode extends DiscountByAnswerForEDD_Event_Base {

    /**
     * Sets ups hooks.
     * @return void
     */
    protected function _construct() {
        add_filter( 'edd_is_discount_valid', array( $this, 'isDiscountValid' ), 10, 4 );
        add_filter( 'edd_is_discount_used', array( $this, 'isDiscountUsedByUser' ), 10, 3 );
    }

    /**
     *
     * @callback    filter  edd_is_discount_used
     * @param       boolean
     * @param       string
     * @param       string|integer
     * ### Strucutre
     * e.g
     * ```
     *  Array
     *  (
     *      [0] => (boolean) false
     *      [1] => (string) TEST_DISCOUNTCODE
     *      [2] => (string|integer) jhonedoe@emaildomain.com or an integer of a WordPress user (or customer?) ID
     *  )
     * ```
     * @see edd_is_discount_used()
     */
    public function isDiscountUsedByUser() {

        $_aParameters    = func_get_args();
        $_bValid         = $_aParameters[ 0 ];
        $_sDiscountCode  = $_aParameters[ 1 ];
        $_iUserIDOrEmail = $_aParameters[ 2 ];
        $_iCampaignID    = $this->getCampaignIDFromDiscountCode( $_sDiscountCode );
        if ( ! $_iCampaignID ) {
            return $_bValid;
        }
        if ( $this->___hasPaymentWithCampaign( $_iCampaignID, $_iUserIDOrEmail ) ) {
            edd_set_error( 'edd-discount-error', __( 'You have already used a discount code of the campaign.', 'discount-by-answer-for-easy-digital-downloads' ) );
            return true;
        }
        return $_bValid;

    }
        /**
         * @param $iCampaignID
         *
         * @return bool
         */
        private function ___hasPaymentWithCampaign( $iCampaignID, $iUserIDOrEmail ) {

            $_oCustomer  = $this->___getCustomer( $iUserIDOrEmail );
            if ( current_user_can('manage_options' ) ) {
                return false;   // allow administrators
            }
            $_aArguments = $this->___getQueryArguments( $iCampaignID, $_oCustomer );
            $_oQuery     = new WP_Query( $_aArguments );
            return ( boolean ) $_oQuery->post_count;

        }
            private function ___getQueryArguments( $iCampaignID, $_oCustomer ) {
                $_aArguments = array(
                    // @deprecated
                    // 'wildcard_on_key' => true,  // custom argument referred later with a callback
                    'post_type'       => 'edd_payment',
                    'fields'          => 'ids',
                    'posts_per_page'  => 1,     // only one payment is enough to check
                    'post_status'     => 'any',
                );
                $_aMetaQuery = array(
                    'relation'  => 'AND',
                    array(
                        'key'       => '_edddba_campaign_id_' . $iCampaignID,
                        'value'     => $iCampaignID,
                        'compare'   => '=',
                    ),
                    array(
                        'relation'  => 'OR',
                        array(
                            'key'       => '_edd_payment_user_email',
                            'value'     => $_oCustomer->email,
                            'compare'   => '=',
                        ),
                    ),
                );
                if ( $_oCustomer->id ) {
                    $_aMetaQuery[ 1 ][] = array(
                        'key'       => '_edd_payment_customer_id',
                        'value'     => $_oCustomer->id,
                        'compare'   => '=',
                    );
                }
                if ( $_oCustomer->user_id ) {
                    $_aMetaQuery[ 1 ][] = array(
                        'key'       => '_edd_payment_user_id',
                        'value'     => $_oCustomer->user_id,
                        'compare'   => '=',
                    );
                }
                $_aArguments[ 'meta_query' ] = $_aMetaQuery;

                return $_aArguments;
            }

            /**
             * @param $isUserIDOrEmail
             *
             * @return EDD_Customer|false
             */
            private function ___getCustomer( $isUserIDOrEmail ) {

                if ( ! EDD()->customers->installed() ) {
                    return false;
                }
                return ( $isUserIDOrEmail && is_numeric( $isUserIDOrEmail ) )
                    ? new EDD_Customer( ( integer ) $isUserIDOrEmail, true )
                    : new EDD_Customer( $isUserIDOrEmail );

            }

    /**
     *
     * @param
     * e.g.
     *  [0] => (boolean) true
     *  [1] => (integer) 342
     *  [2] => (string) CAMPAIGN_5c4ef557a6263
     *  [3] => (string) 1
     *
     * @return boolean
     * @callback    filter  edd_is_discount_valid   Responds to apply_filters( 'edd_is_discount_valid', $return, $discount_id, $code, $user );
     * @see edd_is_discount_valid()
     */
    public function isDiscountValid() {

        $_aParameters   = func_get_args();
        $_bValid        = $_aParameters[ 0 ];
        $_iDiscountID   = $_aParameters[ 1 ];
        $_sDiscountCode = $_aParameters[ 2 ];
        // $_iUserID       = $_aParameters[ 3 ];

        /**
         * If already invalid, no need to check
         */
        if ( ! $_bValid ) {
            return $_bValid;
        }

        /**
         * Check if it is of a campaign.
         * @see DiscountByAnswerForEDD_Campaign::addDiscountCode()
         */
        $_iCampaignID    = $this->getCampaignIDFromDiscountCode( $_iDiscountID );
        if ( ! $_iCampaignID ) {
            return $_bValid;
        }

        /**
         * Linear array holding discount codes in the cart.
         * @remark  it seems when no discount code is in the cart, false is returned from `edd_get_cart_discounts()` although the function documents the return value type is always array.
         * @see edd_get_cart_discounts()
         * @var false|array
         */
        $_aCartDiscounts = $this->getAsArray( edd_get_cart_discounts() );

        /**
         * If there is a discount code with the same campaign, do not allow the one currently checked.
         */
        foreach( $_aCartDiscounts as $_iIndex => $_sCartDiscountCode ) {

            if ( $_sCartDiscountCode === $_sDiscountCode ) {
                continue;   // this happens when the exact same code in the cart is entered again
            }
            $_iCartCampaignID = $this->getCampaignIDFromDiscountCode( $_sCartDiscountCode );
            if ( $_iCartCampaignID === $_iCampaignID ) {
                edd_set_error( 'edd-discount-error', __( 'You cannot use multiple discount codes of a same campaign at the same time.', 'discount-by-answer-for-easy-digital-downloads' ) );
                return false;
            }

        }
        return $_bValid;

    }


}