<?php
/**
 * Discount by Answer For Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2018 Michael Uno
 *
 */

/**
 * Enables the post action of `renew`.
 * @deprecated unused
 * @since   0.0.1
 */
class DiscountByAnswerForEDD_PostType_PostAction_Renew extends DiscountByAnswerForEDD_PostType_PostAction_Base {

    protected $_sActionSlug = 'fz_renew';

    /**
     * @return string
     */
    protected function _getActionLabel() {
        return __( 'Renew', 'discount-by-answer-for-easy-digital-downloads' );
    }

    /**
     * @return string
     * @see get_delete_post_link()
     */
    protected function _getActionLink( $oPost ) {

        $_oWPPostType = get_post_type_object( $this->_sPostTypeSlug );
        if ( ! $_oWPPostType ) {
            return '';
        }
        $_sActionLink = add_query_arg(
            array(
                'action'    => $this->_sActionSlug,
            ),
            admin_url( sprintf( $_oWPPostType->_edit_link, $oPost->ID ) )
        );
        return sprintf(
            '<a href="%1$s">' . $this->_getActionLabel() . '</a>',
            esc_url( wp_nonce_url( $_sActionLink, "$this->_sActionSlug-post_{$oPost->ID}" ) )
        );

    }

    public function _doAction( array $aPostIDs ) {
        foreach( $aPostIDs as $_iPostID ) {
            $_sURL = get_post_meta( $_iPostID, '_fz_feed_url', true );
            $_iCacheDuration = get_post_meta( $_iPostID, '_fz_cache_duration', true );
            $_iCacheDuration = $_iCacheDuration ? $_iCacheDuration : 3600;
            $_aArguments  = array(
                $_sURL,
                $_iCacheDuration,
                array(),    // HTTP arguments
                'feed'      // the type must be `feed`
            );
            DiscountByAnswerForEDD_PluginUtility::scheduleSingleWPCronTask(
                'feed_zapper_action_http_cache_renewal',
                $_aArguments,
                time() - 1 // now
            );
            DiscountByAnswerForEDD_PluginUtility::scheduleSingleWPCronTask(
                'feed_zapper_action_create_feed_posts',
                array( $_sURL )
            );
        }
        DiscountByAnswerForEDD_PluginUtility::accessWPCron();
    }


}