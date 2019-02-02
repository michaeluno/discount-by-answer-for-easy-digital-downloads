<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Creates answer log.
 * @since   0.7.0
 */
class DiscountByAnswerForEDD_Answer_Event_CreateLog extends DiscountByAnswerForEDD_Event_Base {

    /**
     * Do set ups for hooks.
     * @return void
     */
    protected function _construct() {

        add_action( 'edddba_action_checkout_verified_answers', array( $this, 'doAction' ), 5, 2 );
        add_action( 'edddba_action_checkout_verified_answers', array( $this, 'scheduleOldAnswerLogDeletion' ), 100 );

        // This is triggered when a discount code is issued after an answer is logged
        add_action( 'edddba_action_campaign_issued_discount_code', array( $this, 'associateAnswerWithDiscountCode' ), 10, 3 );

    }

    /**
     * Stores answers.
     *
     * @remark  should be called before issuing a discount code when an answer is accepted
     * @return void
     * @callback    action      edddba_action_checkout_verified_answers
     */
    public function doAction() {

        $_aParameters = func_get_args() + array( null, array() );
        /**
         * @var DiscountByAnswerForEDD_Campaign
         */
        $_oCampaign = $_aParameters[ 0 ];
        $_aAnswers  = $_aParameters[ 1 ];

        // Create an answer log item.
        $_ioAnswer  = $_oCampaign->getReport( 'log' )
            ? $this->___getAnswerLogged( $_oCampaign, $_aAnswers )
            : 0;
        $_iAnswerID = is_wp_error( $_ioAnswer )
            ? 0
            : $_ioAnswer;

        do_action( 'edddba_action_answer_logged', $_oCampaign, $_iAnswerID );

    }

        /**
         * @param DiscountByAnswerForEDD_Campaign $oCampaign
         * @param array $aAnswers
         *
         * @return integer|WP_Error
         * @remark  storing answers in the `post_content` field so that it can be searchable.
         */
        private function ___getAnswerLogged( DiscountByAnswerForEDD_Campaign $oCampaign, array $aAnswers ) {

            $_iCampaignID = $oCampaign->get( 'id' );
            return $this->insertPost(
                array(
                    'post_title' => sprintf(
                        __( 'Answer for %1$s', 'discount-by-answer-for-easy-digital-downloads' ),
                        get_the_title( $oCampaign->get( 'id' ) )
                    ),
                    'post_content'        => serialize( $aAnswers ),  // works
                    'post_parent'         => $_iCampaignID,
                    '_edddba_campaign_id' => $_iCampaignID,
                ),
                DiscountByAnswerForEDD_Registry::$aPostTypes[ 'answer' ]
            );

        }

    /**
     * Schedules a WP Cron task that deletes old answer log items.
     * @param DiscountByAnswerForEDD_Campaign $oCampaign
     */
    public function scheduleOldAnswerLogDeletion( DiscountByAnswerForEDD_Campaign $oCampaign ) {
        $_iRetention = ( integer ) $oCampaign->getReport( 'log_retention_count', 0 );
        if ( $_iRetention ) {
            $this->scheduleSingleWPCronTask(
                'edddba_action_answer_delete_old_answers',
                array( $oCampaign->get( 'id' ) ),
                time()
            );
        }
    }

    /**
     * Associates the discount code with the answer.
     * @remark  Saving the both discount code and ID.
     * It is important to store the code not the ID as campaign discount codes are volatile and meant to be deleted.
     * In the answer listing table, even deleted codes should be displayed so that the user can know which one was used.
     * Also, ID is needed to query answers with alive discount codes in order to keep pending items.
     *
     * @param $sDiscountCode
     * @param $iAnswer
     * @callback    action      edddba_action_campaign_issued_discount_code
     */
    public function associateAnswerWithDiscountCode( $sDiscountCode, $iAnswerID, $oCampaign ) {

        update_post_meta( $iAnswerID, '_edddba_discount_code', $sDiscountCode );
        update_post_meta( $iAnswerID, '_edddba_discount_code_id', edd_get_discount_id_by_code( $sDiscountCode ) );

    }




}