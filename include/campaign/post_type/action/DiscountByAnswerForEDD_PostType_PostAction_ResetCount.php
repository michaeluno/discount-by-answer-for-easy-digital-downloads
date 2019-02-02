<?php
/**
 * Discount by Answer For Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2018 Michael Uno
 *
 */

/**
 * Adds the Rest Count action link in the campaign listing table.
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_PostType_Campaign_PostAction_ResetCount extends DiscountByAnswerForEDD_PostType_PostAction_Base {

    protected $_sActionSlug = 'edddba_campaign_reset_count';

    /**
     * @return string
     */
    protected function _getActionLabel() {
        return __( 'Reset Count' );
    }

    /**
     * @return string
     * @see get_delete_post_link()
     */
    protected function _getActionLink( $oPost ) {
        return sprintf(
            '<a href="%1$s" class="submit-reset-count" aria-label="%1$s">%2$s</a>',
            $this->_getActionLinkURL( $oPost->ID ),
            __( 'Reset Count', 'discount-by-answer-for-easy-digital-downloads' )
        );
    }


    public function _doAction( array $aPostIDs ) {
        foreach( $aPostIDs as $_iPostID ) {
            update_post_meta( $_iPostID, '_edddba_campaign_discount_use_count', 0 );
            update_post_meta( $_iPostID, '_edddba_campaign_discount_issue_count', 0 );
        }
    }


}