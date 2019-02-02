<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Associates payment and customer information with an answer when a purchase is made.
 * @since   0.6.0
 */
class DiscountByAnswerForEDD_Answer_Event_AssociateCustomer extends DiscountByAnswerForEDD_Event_Base {

    /**
     * Sets ups hooks.
     * @return void
     */
    protected function _construct() {
        add_action( 'edd_pre_complete_purchase', array( $this, 'doAction' ) );
    }

    /**
     * ### User Info Array Structure
     *  Array (
     *      [id] => (integer) 1 / 0 (for guests)
     *      [email] => (string) test@somedomain.com
     *      [first_name] => (string) Tester
     *      [last_name] => (string)
     *      [discount] => (string) CAMPAIGN_5c4f1847be007, TEST_A_5c4f19b8b8361
     *      [address] => (boolean) false
     *  )
     * ### Discount Array Structure
     *  Array (
     *      [0] => (string) CAMPAIGN_5c4f1847be007
     *      [1] => (string) TEST_A_5c4f19b8b8361
     *      [2] ...
     *  )
     * @callback    action  edd_pre_complete_purchase
     */
    public function doAction() {

        $_aParameters      = func_get_args() + array( 0 );
        $_iPaymentID       = $_aParameters[ 0 ];
        $_aUserInfo        = $this->getAsArray( edd_get_payment_meta_user_info( $_iPaymentID ) );
        $_aDiscountCodes   = $this->getDiscountInformationFromEDDUserInformation( $_aUserInfo );

        foreach( $_aDiscountCodes as $_iIndex => $_sDiscountCode ) {
            $_iCampaignID = $this->getCampaignIDFromDiscountCode( $_sDiscountCode );
            if ( ! $_iCampaignID ) {
                continue;
            }
            $_iAnswerID = $this->___addMetaToAnswer( $_sDiscountCode, $_aUserInfo, $_iPaymentID );
            do_action( 'edddba_action_answer_converted', $_iAnswerID, $_iCampaignID, $_sDiscountCode, $_iPaymentID, $_aUserInfo );
        }

    }
        /**
         * @param $sDiscountCode
         * @param array $aUserInfo
         * @param $iPaymentID
         *
         * @return int  answer id
         */
        private function ___addMetaToAnswer( $sDiscountCode, array $aUserInfo, $iPaymentID ) {

            $_iAnswerID = $this->___getAnswerIDByDiscountCode( $sDiscountCode );
            $_sEmail    = $this->getElement( $aUserInfo, 'email' );
            update_post_meta( $_iAnswerID, '_edddba_payment_id', $iPaymentID );
            if ( ! class_exists( 'EDD_Customer' ) ) {
                return $_iAnswerID;
            }
            $_oCustomer = new EDD_Customer( $_sEmail );
            update_post_meta( $_iAnswerID, '_edddba_customer_id', $_oCustomer->id );

            // Change the author of the answer with the user ID.
            if ( $_oCustomer->user_id ) {
                wp_update_post(
                    array(
                        'ID'          => $_iAnswerID,
                        'post_author' => $_oCustomer->user_id,
                    )
                );
            }
            return $_iAnswerID;

        }
            /**
             * @param string $sDiscountCode The subject discount code, not an ID
             *
             * @return int
             */
            private function ___getAnswerIDByDiscountCode( $sDiscountCode ) {

                $_oQuery    = new WP_Query(
                    array(
                        'fields'         => 'ids',
                        'post_type'      => DiscountByAnswerForEDD_Registry::$aPostTypes[ 'answer' ],
                        'posts_per_page' => 1,     // only one answer per campaign discount code
                        'meta_query'     => array(
                            array(
                                'key'       => '_edddba_discount_code',
                                'value'     => $sDiscountCode,
                                'compare'   => '=',
                            ),
                        ),
                        'post_status'    => 'any',
                    )
                );
                foreach( $_oQuery->posts as $_iAnswerID ) {
                    return $_iAnswerID;
                }
                return 0;
            }

}