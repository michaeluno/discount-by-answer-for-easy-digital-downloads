<?php
/**
 * Discount by Answer For Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2018 Michael Uno
 *
 */

/**
 * Adds the `Activate`/`Deactivate` action link in the campaign listing table.
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_PostType_Campaign_PostAction_ToggleStatus extends DiscountByAnswerForEDD_PostType_PostAction_Base {

    protected $_sActionSlug = 'edddba_campaign_toggle_status';

    /**
     * @return string
     */
    protected function _getActionLabel() {
        return __( 'Toggle Status' );
    }

    /**
     * @return string
     * @see get_delete_post_link()
     */
    protected function _getActionLink( $oPost ) {
        $_bEnabled = ( boolean ) get_post_meta( $oPost->ID, '_edddba_campaign_status', true );
        return sprintf(
            '<a href="%1$s" class="submittoggle" aria-label="%1$s">%2$s</a>',
            $this->_getActionLinkURL( $oPost->ID ),
            $_bEnabled
                ? __( 'Deactivate', 'discount-by-answer-for-easy-digital-downloads' )
                : __( 'Activate', 'discount-by-answer-for-easy-digital-downloads' )
        );
    }

    public function _doAction( array $aPostIDs ) {
        foreach( $aPostIDs as $_iPostID ) {
            $_bEnabled = ( boolean ) get_post_meta( $_iPostID, '_edddba_campaign_status', true );
            update_post_meta( $_iPostID, '_edddba_campaign_status', ! $_bEnabled );
        }
    }


}