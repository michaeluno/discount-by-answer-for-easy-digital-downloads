<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Store converted answers in the campaign post metas, apart from the answer log.
 *
 * These data will be referred later, for example, in order to prevent the visitor from submitting same answers.
 *
 * @since   0.9.0
 */
class DiscountByAnswerForEDD_Answer_Event_StoreConverted extends DiscountByAnswerForEDD_Event_Base {

    /**
     *
     */
    protected function _construct() {

        // Fired when a purchase is completed
        add_action( 'edddba_action_answer_converted', array( $this, 'scheduleCron' ), 10, 5 );

        // Fired by WP Cron
        add_action( 'edddba_action_log_each_converted_answer', array( $this, 'storeAnswers' ), 10, 5 );

    }

    public function storeAnswers( $iAnswerID, $iCampaignID, $sDiscountCode, $iPaymentID, $aUserInfo ) {
        $_aAnswer     = $this->getAsArray( maybe_unserialize( get_post_field('post_content', $iAnswerID ) ) );
        foreach( $_aAnswer as $_sSlug => $asAnswer ) {
            update_post_meta( $iAnswerID, '_edddba_converted_' . $_sSlug, $asAnswer );
        }
    }

    /**
     * Schedules a task that stores answers in campaign post meta.
     *
     * Not doing it right away in order not to slow down the purchase process.
     *
     * @remark  responds to `do_action( 'edddba_action_answer_converted', $_iAnswerID, $_iCampaignID, $_sDiscountCode, $_iPaymentID, $_aUserInfo );`
     */
    public function scheduleCron() {
        $this->scheduleSingleWPCronTask(
            'edddba_action_log_each_converted_answer',
            func_get_args(),    // parameters passed to the action
            time()
        );
    }

}