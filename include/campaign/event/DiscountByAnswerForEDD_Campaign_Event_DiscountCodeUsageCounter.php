<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Increases the use/issue count of the discount code and for the campaign.
 * If it reaches the maximum uses of the campaign, it deactivates the campaign.
 * @since   0.7.0
 */
class DiscountByAnswerForEDD_Campaign_Event_DiscountCodeUsageCounter extends DiscountByAnswerForEDD_Event_Base {

    /**
     *
     */
    protected function _construct() {

        // Fired when a purchase is completed
        add_action( 'edd_discount_increase_use_count', array( $this, 'doAction' ), 10, 3 );

        // Fired when a discount code is issued
        add_action( 'edddba_action_campaign_issued_discount_code', array( $this, 'increaseIssueCount' ), 20, 3 );

    }

    /**
     * Deletes a discount code issued by this plugin after being used.
     *
     * @remark  responds to `do_action( 'edd_discount_increase_use_count', $uses, $id, $code );`
     */
    public function doAction() {

        $_aParameters         = func_get_args();
        $_iDiscountCodeID     = $_aParameters[ 1 ];
        $_iCampaignID         = ( integer ) get_post_meta( $_iDiscountCodeID, '_edddba_campaign_id', true );
        $_iUsageCount         = ( integer ) get_post_meta( $_iCampaignID, '_edddba_campaign_discount_use_count', true );
        $_iCampaignMaxUses    = ( integer ) get_post_meta( $_iCampaignID, '_edddba_campaign_maximum_uses', true );
        $_iUsageCount++;

        update_post_meta( $_iCampaignID, '_edddba_campaign_discount_use_count', $_iUsageCount );
        if ( $_iCampaignMaxUses && $_iUsageCount >= $_iCampaignMaxUses ) {
            update_post_meta( $_iCampaignID, '_edddba_campaign_status', 0 );
        }

    }

    /**
     * @param string $sDiscountCode
     * @param integer $iAnswerID
     * @param DiscountByAnswerForEDD_Campaign $oCampaign
     */
    public function increaseIssueCount( $sDiscountCode, $iAnswerID, DiscountByAnswerForEDD_Campaign $oCampaign ) {

        // Increase the discount code issue count of the campaign
        $_iCampaignID = $oCampaign->get( 'id' );
        $_iIssueCount = ( integer ) get_post_meta( $_iCampaignID, '_edddba_campaign_discount_issue_count', true );
        update_post_meta( $_iCampaignID, '_edddba_campaign_discount_issue_count', ++$_iIssueCount );

    }

}