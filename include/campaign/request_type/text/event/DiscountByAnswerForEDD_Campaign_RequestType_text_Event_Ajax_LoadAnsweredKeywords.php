<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Handles Ajax Requests for searching user giving web-string for tests in the plugin meta box.
 *
 * @since   0.9.0
 */
class DiscountByAnswerForEDD_Campaign_RequestType_text_Event_Ajax_LoadAnsweredKeywords extends DiscountByAnswerForEDD_Event_Ajax_Base {

    protected $_sActionName = 'edddba_load_answered_keywords';
    protected $_bAllowGuests = false;

    /**
     * @since   0.9.0
     */
    public function doAction() {

        $_aReturn     = array(
            'message'      => __( 'The keyword was found!', 'discount-by-answer-for-easy-digital-downloads' ),
            'code'         => 0,
        );
        try {

            if ( ! $this->_shouldProceed() ) {
                throw new Exception(
                    __( 'Invalid request. Missing arguments.', 'discount-by-answer-for-easy-digital-downloads' ),
                    10
                );
            }

            $_aReturn[ 'message' ] = $this->___getAnsweredKeywords( $_POST[ 'campaignID' ], $_POST[ 'slug' ] );

        } catch ( Exception $_oException ) {
            $_aReturn[ 'message' ] = $_oException->getMessage();
            $_aReturn[ 'code' ]    = $_oException->getCode();
        }
        exit( json_encode( $_aReturn ) );

    }

        /**
         * @return  string HTML portion
         * @since   0.9.0
         */
        private function ___getAnsweredKeywords( $iCampaignID, $sRequestSlug ) {

            $_sResponse = '';
            $_sPosts        = $GLOBALS[ 'wpdb' ]->posts;
            $_sPostMeta     = $GLOBALS[ 'wpdb' ]->postmeta;
            $_sSQLQuery     = "SELECT mt1.meta_value
                FROM {$_sPosts}  
                INNER JOIN {$_sPostMeta} ON ( {$_sPosts}.ID = {$_sPostMeta}.post_id )  
                INNER JOIN {$_sPostMeta} AS mt1 ON ( {$_sPosts}.ID = mt1.post_id ) 
                WHERE 1=1  
                AND ( 
                  ( {$_sPostMeta}.meta_key = '_edddba_campaign_id' AND {$_sPostMeta}.meta_value = '%d' ) 
                  AND 
                  mt1.meta_key = '%s'
                ) 
                AND {$_sPosts}.post_type = 'edddba_answer' 
                AND (({$_sPosts}.post_status <> 'trash' 
                AND {$_sPosts}.post_status <> 'auto-draft')) 
                GROUP BY {$_sPosts}.ID 
                ORDER BY {$_sPosts}.post_date DESC";
            $_aQuery = $GLOBALS[ 'wpdb' ]->get_results(
                $GLOBALS[ 'wpdb' ]->prepare(
                    $_sSQLQuery,
                    $iCampaignID,
                    '_edddba_converted_' . $sRequestSlug
                ),
                'ARRAY_A'
            );
            $_aResults = wp_list_pluck( $_aQuery, 'meta_value' );
            foreach( $_aResults as $_sValue ) {
                $_sResponse .= $_sValue . '<br />';
            }
            return $_sResponse
                ? $_sResponse
                : '<em>'
                    . __( 'Keywords not found', 'discount-by-answer-for-easy-digital-downloads' )
                  . '</em>';

        }

    /**
     * @return  boolean
     */
    protected function _shouldProceed() {
        if ( ! isset( $_POST[ 'campaignID' ], $_POST[ 'slug' ] ) ) {
            return false;
        }
        return true;
    }

}
