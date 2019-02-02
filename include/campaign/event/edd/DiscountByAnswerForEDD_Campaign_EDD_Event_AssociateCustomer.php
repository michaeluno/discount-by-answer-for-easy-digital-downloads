<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Associates a customer with a campaign when a purchase is made.
 *
 * This is used to prevent a visitor from using multiple discount codes of a same campaign.
 * For example, a visitor answers a request and gets a discount code and repeats doing it before purchasing anything.
 * Unlimited number of temporary discount codes are able to be issued by design.
 * After one of the issued discount code is used, if the other temporary ones are still available to be used by the same person,
 * unexpected discounts will be made.
 *
 *  ## Workflow
 *  1. a purchase is made
 *  2. parse used discount codes
 *  3. find the campaign discount code from them
 *      - store the campaign ID in the `edd_payment` post as a post meta
 *          as it has the following meta values, these can be checked to prevent duplicate uses
 *              [_edd_payment_user_id] => (integer)
 *              [_edd_payment_customer_id] => (integer)
 *              [_edd_payment_user_email] => (string)
 *
 *
 * @since   0.6.0
 */
class DiscountByAnswerForEDD_Campaign_EDD_Event_AssociateCustomer extends DiscountByAnswerForEDD_Event_Base {

    /**
     * Sets ups hooks.
     * @return void
     */
    protected function _construct() {
        add_action( 'edd_pre_complete_purchase', array( $this, 'doAction' ) );
    }

    /**
     * @remark  this task should be done BEFORE increasing discount usage because there is a routine to delete the campaign discount code by itself.
     * If it is done, references to meta data including campaign ID etc. will be lost.
     * @callback    action      edd_pre_complete_purchase   responds to do_action( 'edd_pre_complete_purchase', $payment_id );
     * @see edd_complete_purchase()
     *
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
     */
    public function doAction() {

        $_aParameters   = func_get_args() + array( 0 );
        $_iPaymentID    = $_aParameters[ 0 ];
        $_aUserInfo     = $this->getAsArray( edd_get_payment_meta_user_info( $_iPaymentID ) );
        $_aDiscount     = $this->getDiscountInformationFromEDDUserInformation( $_aUserInfo );

        // Consider multiple campaigns can be associated with a single payment
        foreach( $_aDiscount as $_sDiscountCode ) {
            $_iCampaignID = $this->getCampaignIDFromDiscountCode( $_sDiscountCode );
            if ( ! $_iCampaignID ) {
                continue;
            }
            update_post_meta(
                $_iPaymentID,
                '_edddba_campaign_id_' . $_iCampaignID,
                $_iCampaignID
            );
        }

    }

}