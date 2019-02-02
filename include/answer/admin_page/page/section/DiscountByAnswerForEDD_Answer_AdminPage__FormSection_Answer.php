<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno; Licensed under <LICENSE_TYPE>
 */

/**
 * Adds the 'Answer' form section.
 *
 * @since    0.9.0
 */
class DiscountByAnswerForEDD_Answer_AdminPage__FormSection_Answer extends DiscountByAnswerForEDD_AdminPage__FormSection_Base {

    /**
     *
     * @since   0.9.0
     */
    protected function _getArguments( $oFactory ) {
        return array(
            'section_id'    => 'answer',
            'title'         => __( 'Answer', 'discount-by-answer-for-easy-digital-downloads' ),
            'collapsible'   => array(
                'is_collapsed'  => false,
                'container'     => 'section',
            ),
        );
    }

    /**
     * Get adding form fields.
     * @since    0.9.0
     * @return   array
     */
    protected function _getFields( $oFactory ) {

        $_iAnswerID   = ( integer ) $this->getElement( $_GET, 'answer' );
        $_aAnswer     = $this->getAsArray( maybe_unserialize( get_post_field('post_content', $_iAnswerID ) ) );
        $_iCampaignID = ( integer ) get_post_meta( $_iAnswerID, '_edddba_campaign_id', true );
        $_oCampaign   = new DiscountByAnswerForEDD_Campaign( $_iCampaignID );
        $_aRequests   = $_oCampaign->getRequests();
        $_aAnswer     = $this->___getAnswerFormatted( $_aAnswer, $_aRequests, $_oCampaign );
        return DiscountByAnswerForEDD_Answer_Utility::getFieldsFromArray( $_aAnswer );

    }

        /**
         * @param array $aRawAnswer
         * @param array $aRequests
         * @param DiscountByAnswerForEDD_Campaign $oCampaign
         *
         * @return array
         * @remark  known limitation: not supporting nested form fields at the moment.
         */
        private function ___getAnswerFormatted( array $aRawAnswer, array $aRequests, DiscountByAnswerForEDD_Campaign $oCampaign ) {

            $_aSlugIndices = $this->___getRequestSlugIndices( $aRequests );
            $_aAnswer = array();
            foreach( $aRawAnswer as $_sRequestSlug => $_asRawAnswer ) {
                $_iIndex   = ( integer ) $this->getElement( $_aSlugIndices, $_sRequestSlug );
                $_aRequest = $this->getElementAsArray( $aRequests, array( $_iIndex ) );
                $_sName    = $this->getElement( $_aRequest, array( 'name' ), '' );
                $_sType    = $this->getElement( $_aRequest, array( 'type' ), '' );
                $_aAnswer[ $_sName ] = apply_filters( 'edddba_filter_answer_format_value_' . $_sType, $_asRawAnswer, $_aRequest, $oCampaign );
            }
            return $_aAnswer;

        }
            private function ___getRequestSlugIndices( array $aRequests ) {
                $_aSlugs = array();
                foreach( $aRequests as $_iIndex => $_aRequest ) {
                    $_sSlug = $this->getElement( $_aRequest, 'slug', '' );
                    if ( '' === $_sSlug ) {
                        continue;
                    }
                    $_aSlugs[ $_sSlug ] = $_iIndex;
                }
                return $_aSlugs;
            }

}