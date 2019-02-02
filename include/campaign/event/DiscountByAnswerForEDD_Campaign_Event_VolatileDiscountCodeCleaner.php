<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Handles clean-ups of expired and unused discount codes issued by this plugin.
 * @since   0.6.0
 */
class DiscountByAnswerForEDD_Campaign_Event_VolatileDiscountCodeCleaner extends DiscountByAnswerForEDD_Event_Base {

    /**
     * @remark  It is important to make the callback priority low
     * as the campaign counter routine also uses this hook
     * and if the discount post is deleted at that time, the campaign ID cannot be referred.
     */
    protected function _construct() {
        add_action(
            'edd_discount_increase_use_count',
            array( $this, 'doAction' ),
            999,    // important to make it late as the campaign counter routine also uses this hook
            3
        );
    }

    /**
     * Deletes a discount code issued by this plugin after being used.
     *
     * @remark  responds to `do_action( 'edd_discount_increase_use_count', $uses, $id, $code );`
     */
    public function doAction() {
// @todo test
        $_aParameters         = func_get_args();
        $_iDiscountCodeID     = $_aParameters[ 1 ];

        // @deprecated use the `_edddba_campaign_id` meta value
//        $_bIsCampaignDiscount = metadata_exists('post', $_iDiscountCodeID, '_edddba_discount_auto_delete' );
//        if ( ! $_bIsCampaignDiscount ) {
//            return;
//        }
        if ( ! get_post_meta( $_iDiscountCodeID, '_edddba_campaign_id', true ) ) {
            return;
        }
        if ( ! edd_is_discount_maxed_out( $_iDiscountCodeID ) ) {
            return;
        }
        $_bAutoDelete = ( boolean ) get_post_meta( $_iDiscountCodeID, '_edddba_discount_auto_delete', true );
        if ( $_bAutoDelete ) {
            wp_delete_post( $_iDiscountCodeID, true );
        }

    }

}