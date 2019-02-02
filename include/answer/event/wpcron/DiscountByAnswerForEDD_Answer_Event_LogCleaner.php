<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Handles clean-ups for the answer log.
 * @since   0.5.0
 */
class DiscountByAnswerForEDD_Answer_Event_LogCleaner extends DiscountByAnswerForEDD_Event_Base {

    /**
     * Do set ups for hooks.
     * @return void
     */
    protected function _construct() {

        add_action( 'edddba_action_answer_delete_old_answers', array( $this, 'doAction' ), 10, 1 );

    }

    /**
     * @return void
     */
    public function doAction() {

        $_aParameters    = func_get_args();
        $_iCampaignID    = $_aParameters[ 0 ];
        $_oCampaign      = new DiscountByAnswerForEDD_Campaign( $_iCampaignID );
        $_iRetention     = ( integer ) $_oCampaign->getReport( 'log_retention_count', 0 );
        $_bKeepConverted = ( boolean ) $_oCampaign->getReport( 'keep_converted_answers', true );

        /**
         * Working SQL Query Example
         * ```
         *  SELECT SQL_CALC_FOUND_ROWS wptests_posts.ID FROM wptests_posts
         *  LEFT JOIN wptests_postmeta AS mt3 ON ( wptests_posts.ID = mt3.post_id AND mt3.meta_key = '_edddba_discount_code_id' )
         *  LEFT JOIN wptests_posts    AS p2  ON ( mt3.meta_key = '_edddba_discount_code_id' AND mt3.meta_value = p2.ID )
         *  WHERE 1=1
         *  AND (
         *      mt3.meta_key IS NOT NULL
         *      AND
         *      p2.ID IS NULL
         *  )
         *  AND wptests_posts.post_type = 'edddba_answer'
         *  AND ((wptests_posts.post_status <> 'trash' AND wptests_posts.post_status <> 'auto-draft'))
         *  GROUP BY wptests_posts.ID
         * ```
         */

        $_sPosts        = $GLOBALS[ 'wpdb' ]->posts;
        $_sPostMeta     = $GLOBALS[ 'wpdb' ]->postmeta;
        $_sNotSelectPaymentAssociated = $_bKeepConverted ? "
            AND                             
            mt2.meta_key IS NULL"
            : "";
        $_sSQLQuery = "
            SELECT SQL_CALC_FOUND_ROWS {$_sPosts}.ID FROM {$_sPosts}
            LEFT JOIN {$_sPostMeta} As mt1 ON ( {$_sPosts}.ID = mt1.post_id )
            LEFT JOIN {$_sPostMeta} AS mt2 ON ( {$_sPosts}.ID = mt2.post_id AND mt2.meta_key = '%s' )                    
            LEFT JOIN {$_sPostMeta} AS mt3 ON ( {$_sPosts}.ID = mt3.post_id AND mt3.meta_key = '%s' )
            LEFT JOIN {$_sPosts}    AS p2  ON ( mt3.meta_key = '%s' AND mt3.meta_value = p2.ID )
            WHERE 1=1  
            AND (
                -- specify which campaign answers to select
                (
                    mt1.meta_key = '%s'
                    AND
                    mt1.meta_value = '%s'
                )
                -- do not select answers without a payment associated
                {$_sNotSelectPaymentAssociated}    
                AND 
                -- select answers with a deleted discount code associated
                (
                    ( mt3.meta_key IS NOT NULL
                      AND
                      p2.ID IS NULL
                    )
                    OR 
                    mt3.meta_value is NULL                       
                )                
            )
            -- the answer post type
            AND {$_sPosts}.post_type = '%s'
            -- any post status 
            AND (({$_sPosts}.post_status <> 'trash' AND {$_sPosts}.post_status <> 'auto-draft'))
            GROUP BY {$_sPosts}.ID
            -- DESC is need with LIMIT n to keep newest n items. (ASC will get the newest and drop the oldest) 
            ORDER BY {$_sPosts}.post_date DESC
            -- Offset to keep certain newest answers 
            LIMIT %d, 18446744073709551615
        ";
        $_aResults = $GLOBALS[ 'wpdb' ]->get_results(
            $GLOBALS[ 'wpdb' ]->prepare( $_sSQLQuery,
            	'_edddba_payment_id',
                '_edddba_discount_code_id',
                '_edddba_discount_code_id',
                '_edddba_campaign_id',
                $_iCampaignID,               // campaign id
                DiscountByAnswerForEDD_Registry::$aPostTypes[ 'answer' ], // post type
                $_iRetention                 // (integer) offset (retention count)
            )
            , 'ARRAY_A'
        );
        $_aFoundIDs = wp_list_pluck( $_aResults, 'ID' );
        foreach( $_aFoundIDs as $_iPostID ) {
            wp_delete_post( $_iPostID, true );
        }
    }

}
