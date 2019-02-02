<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Issues a discount code when answers are accepted.
 * @since   0.7.0
 */
class DiscountByAnswerForEDD_Campaign_Event_IssueDiscountCode extends DiscountByAnswerForEDD_Event_Base {

    /**
     *
     */
    protected function _construct() {

        add_action( 'edddba_action_answer_logged', array( $this, 'doAction' ), 10, 2 );

    }

    /**
     * @remark  Do this after an answer log item is created when an answer is accepted
     * @throws  Exception
     */
    public function doAction() {

        $_aParameters = func_get_args() + array( null, array() );
        /**
         * @var DiscountByAnswerForEDD_Campaign $_oCampaign
         */
        $_oCampaign      = $_aParameters[ 0 ];
        $_iAnswerID      = $_aParameters[ 1 ];
        $_iCampaignID    = $_oCampaign->get( 'id' );
        $_sAnswerInTitle = ! $_iAnswerID
            ? __( 'Error: Failed to Log', 'discount-by-answer-for-easy-digital-downloads' )
            : sprintf(
                __( 'Answer ID: %1$s', 'discount-by-answer-for-easy-digital-downloads' ),
                $_iAnswerID
            );

        // Issue a discount code
        $_iBaseCodeID       = $_oCampaign->getDiscount( array( 'base_discount_code', 'value' ) );
        $_sBaseDiscountCode = edd_get_discount_code( $_iBaseCodeID );
        $_iDiscountCode     = $_oCampaign->addDiscountCode(
            array(
                'name' => get_the_title( $_iCampaignID )
                    . ' ('
                    . sprintf( __( 'Base: %1$s', 'discount-by-answer-for-easy-digital-downloads' ), $_sBaseDiscountCode )
                        . ' ' . $_sAnswerInTitle
                    . ')'
            )
        );

        if ( ! $_iDiscountCode ) {
            throw new Exception(
                __( 'Failed to issue a discount code.', 'discount-by-answer-for-easy-digital-downloads' ),
                50
            );
        }
        $_sDiscountCode = edd_get_discount_code( $_iDiscountCode );
        if ( ! $_sDiscountCode ) {
            throw new Exception(
                __( 'Failed to access a discount code.', 'discount-by-answer-for-easy-digital-downloads' ),
                51
            );
        }

        do_action( 'edddba_action_campaign_issued_discount_code', $_sDiscountCode, $_iAnswerID, $_oCampaign );

    }

}