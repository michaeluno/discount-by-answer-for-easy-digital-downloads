<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno; Licensed under <LICENSE_TYPE>
 */

/**
 * Adds the 'Basic Information' form section.
 *
 * @since    0.9.0
 */
class DiscountByAnswerForEDD_Answer_AdminPage__FormSection_BasicInformation extends DiscountByAnswerForEDD_AdminPage__FormSection_Base {

    /**
     *
     * @since   0.9.0
     */
    protected function _getArguments( $oFactory ) {
        return array(
            'section_id'    => 'basic_information',
            'title'         => __( 'Basic Information', 'discount-by-answer-for-easy-digital-downloads' ),
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
        $_iCampaignID = ( integer ) get_post_meta( $_iAnswerID, '_edddba_campaign_id', true );
        return array(
            array(
                'field_id'  => 'campaign_title',
                'title'     => __( 'Campaign', 'discount-by-answer-for-easy-digital-downloads' ),
                'content'   => $this->___getCampaignTitle( $_iCampaignID ),
            ),
            array(
                'field_id'  => 'discount_code',
                'title'     => __( 'Discount Code', 'discount-by-answer-for-easy-digital-downloads' ),
                'content'   => $this->___getDiscountCode( $_iAnswerID ),
            ),
            array(
                'field_id'  => 'base_discount_code',
                'title'     => __( 'Base Discount Code', 'discount-by-answer-for-easy-digital-downloads' ),
                'content'   => $this->___getBaseDiscountCode( $_iCampaignID ),
            ),
            array(
                'field_id'  => 'time',
                'title'     => __( 'Answered', 'discount-by-answer-for-easy-digital-downloads' ),
                'content'   => $this->___getAnsweredTime( $_iAnswerID ),
            ),
        );

    }

        private function ___getDiscountCode( $iAnswerID ) {
            $_sCode   = get_post_meta( $iAnswerID, '_edddba_discount_code', true );;
            $_iCodeID = edd_get_discount_id_by_code( $_sCode );
            $_bExists = edd_discount_exists( $_iCodeID );
            if ( ! $_bExists ) {
                return $_sCode;
            }
            $_sURL                  = add_query_arg(
                array(
                    'post_type'  => 'download',
                    'page'       => 'edd-discounts',
                    'edd-action' => 'edit_discount',
                    'discount'   => $_iCodeID,
                ),
                admin_url( 'edit.php' )
            );
            return "<a href='" . esc_url( $_sURL ) . "'>"
                    . $_sCode
                . "</a>";
        }
        private function ___getBaseDiscountCode( $iCampaignID ) {
            $_oCampaign             = new DiscountByAnswerForEDD_Campaign( $iCampaignID );
            $_iBaseDiscountCodeID   = $_oCampaign->getDiscount( array( 'base_discount_code', 'value' ) );
            $_sURL                  = add_query_arg(
                array(
                    'post_type'  => 'download',
                    'page'       => 'edd-discounts',
                    'edd-action' => 'edit_discount',
                    'discount'   => $_iBaseDiscountCodeID,
                ),
                admin_url( 'edit.php' )
            );
            return "<a href='" . esc_url( $_sURL ) . "'>"
                    . edd_get_discount_code( $_iBaseDiscountCodeID )
                . "</a>";
        }
        private function ___getCampaignTitle( $_iCampaignID ) {
            $_sURL = DiscountByAnswerForEDD_Answer_Utility::getCampaignEditLink( $_iCampaignID );
            return "<a href='" . esc_url( $_sURL ) . "'>"
                   . get_the_title( $_iCampaignID )
                   . "</a>";
        }
        private function ___getAnsweredTime( $iAnswerID ) {
            $_sTime     = get_post_field( 'post_date', $iAnswerID, 'raw' );
            $_iTime     = strtotime( $_sTime );
            $_sTime     = date( get_option( 'date_format' ) . ' H:i:s', $_iTime );
            $_sTimeDiff = human_time_diff( $_iTime, current_time( 'timestamp', true  ) ) . " " . __( 'ago' );
            $_sTimeDiff = esc_attr( $_sTimeDiff );
            return "<span title='" . esc_attr( $_sTimeDiff ) . "'>"
                    . $_sTime
                . "</span>";
        }
}