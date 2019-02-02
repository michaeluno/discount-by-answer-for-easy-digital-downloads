<?php
/**
 * Discount by Answer for Easy Digital Downloads
 * 
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 * 
 */

/**
 * Provides plugin specific utility methods that uses WordPerss built-in functions.
 *
 * @package     Discount by Answer for Easy Digital Downloads
 * @since       0.0.1
 */
class DiscountByAnswerForEDD_PluginUtility extends DiscountByAnswerForEDD_WPUtility {

    /**
     * @param array $aUserInformation
     *
     * @return array    numerically indexed linear array holding string values of discount codes
     * @since   0.9.0
     */
    static public function getDiscountInformationFromEDDUserInformation( array $aUserInformation ) {
        $_aDiscountInformation = array();
        if ( empty( $aUserInformation[ 'discount' ] ) ) {
            return $_aDiscountInformation;
        }
        if ( 'none' === $aUserInformation[ 'discount' ] ) {
            return $_aDiscountInformation;
        }
        return array_map(
            'trim',
            explode( ',', $aUserInformation[ 'discount' ] )
        );
    }

    /**
     *
     * @param   integer|string  $isDiscount     A discount code ID or a discount code.
     * @return  integer     The found campaign ID. 0 if not found.
     */
    static public function getCampaignIDFromDiscountCode( $isDiscount ) {

        static $_aCache = array();
        if ( isset( $_aCache[ $isDiscount ] ) ) {
            return $_aCache[ $isDiscount ];
        }

        $_iDiscountCodeID = is_numeric( $isDiscount )
            ? $isDiscount
            : ( integer ) edd_get_discount_id_by_code( $isDiscount );
        $_iCampaignID     =  ( integer ) get_post_meta( $_iDiscountCodeID, '_edddba_campaign_id', true );

        $_aCache[ $isDiscount ] = $_iCampaignID;
        return $_iCampaignID;

    }

    /**
     * Retrieves the active campaigns associated with the given downloads.
     * If downloads are not specified (not given), all the active campaigns will be returned.
     * @param   array       $aDownloadIDs
     * @return  array       Found active campaigns.
     */
    static public function getCartActiveCampaigns( array $aDownloadIDs=array() ) {
        $_aCampaignIDs = self::___getGlobalActiveCampaignIDs();
        $_aCampaigns   = array();
        foreach( $_aCampaignIDs as $_iID ) {
            $_oCampaign = new DiscountByAnswerForEDD_Campaign( $_iID );
            if ( ! $_oCampaign->isAssociatedWithCartItems() ) {
                continue;
            }
            if ( $_oCampaign->isMaxedOut() ) {
                continue;
            }
            $_aCampaigns[ $_iID ] = $_oCampaign;
        }
        return $_aCampaigns;
    }
        /**
         * @return array
         */
        static private function ___getGlobalActiveCampaignIDs() {
            static $_aCache;
            if ( isset( $_aCache ) ) {
                return $_aCache;
            }
            $_aArgs         = array(
                'post_type'      => DiscountByAnswerForEDD_Registry::$aPostTypes[ 'campaign' ],
                'posts_per_page' => -1,     // `-1` for all
                'fields'         => 'ids',  // return only post IDs by default.
                'meta_query'     => array(
                    array(
                        'key'          => '_edddba_campaign_status',
                        'value'        => true,
                        'compare'      => '='
                    )
                ),
                'post_status'    => 'publish',
            );
            $_oResult      = new WP_Query( $_aArgs );
            $_aCache = $_oResult->posts;
            return $_aCache;
        }

    /**
     * @remark  For the select2 Ajax requests.
     * @param $aQueries
     * @param $aFieldset
     *
     * @return array
     */
    static public function getActiveDiscountCodes( $aQueries, $aFieldset ) {
        $_baResult = edd_get_discounts(
            array(
                'post_status'       => 'active',
                'posts_per_page'    => 100,
                'paged'             => $aQueries[ 'page' ],
                's'                 => $aQueries[ 'q' ],
                'nopaging'          => true,

                // Exclude campaign discount codes
                'meta_query'        => array(
                    array(
                        'key'          => '_edddba_campaign_id',
                        'compare'      => 'NOT EXISTS'
                    )
                ),
            )
        );
        $_aDiscountCodes = self::getAsArray( $_baResult );
        $_aPostTitles    = array();
        foreach( $_aDiscountCodes as $_iIndex => $_oPost ) {
            $_aPostTitles[] = array(    // must be numeric
                'id'    => $_oPost->ID,
                'text'  => $_oPost->post_title,
            );
        }
        return array(
            'results'       => $_aPostTitles,
            // not supporting pagination
            'pagination'    => array(
//                'more'  => intval( $_oResults->max_num_pages ) !== intval( $_oResults->get( 'paged' ) ),
            ),
        );
    }

    /**
     * @remark  For the select2 Ajax requests.
     * @param $aQueries
     * @param $aFieldset
     *
     * @return array
     * @deprecated  Used for associated downloads fields but no longer needed
     */
    static public function getDownloads( $aQueries, $aFieldset ) {

        $_aArgs         = array(
            'post_type'         => 'download',
            'paged'             => $aQueries[ 'page' ],
            's'                 => $aQueries[ 'q' ],
            'posts_per_page'    => 30,
            'nopaging'          => false,
        );
        $_oResults      = new WP_Query( $_aArgs );
        $_aPostTitles   = array();
        foreach( $_oResults->posts as $_iIndex => $_oPost ) {
            $_aPostTitles[] = array(    // must be numeric
                'id'    => $_oPost->ID,
                'text'  => $_oPost->post_title,
            );
        }
        return array(
            'results'       => $_aPostTitles,
            'pagination'    => array(
                'more'  => intval( $_oResults->max_num_pages ) !== intval( $_oResults->get( 'paged' ) ),
            ),
        );

    }

    /**
     * @param $aQueries
     * @param $aFieldset
     *
     * @return array
     * @deprecated  Used for associated downloads fields but no longer needed
     */
    static public function getDownloadCategories( $aQueries, $aFieldset ) {

        $_aArguments = array(
            'taxonomy'   => 'download_category',
            'hide_empty' => false,
            'name__like' => $aQueries[ 'q' ],
        );
        $_aTerms = get_terms( $_aArguments );
        if ( is_wp_error( $_aTerms ) ) {
            return array(
                'results'       => array(),
            );
        }

        $_aResults   = array();
        foreach( $_aTerms as $_iIndex => $_oTerm ) {
            $_aResults[] = array(    // must be numeric
                'id'    => $_oTerm->term_id,
                'text'  => $_oTerm->name,
            );
        }

        return array(
            'results'       => $_aResults,
        );

    }

    /**
     * @param $aQueries
     * @param $aFieldset
     *
     * @return array
     * @deprecated  Used for associated downloads fields but no longer needed
     */
    static public function getDownloadTags( $aQueries, $aFieldset ) {

        $_aArguments = array(
            'taxonomy'   => 'download_tag',
            'hide_empty' => false,
            'name__like' => $aQueries[ 'q' ],
        );
        $_aTerms = get_terms( $_aArguments );
        if ( is_wp_error( $_aTerms ) ) {
            return array(
                'results'       => array(),
            );
        }

        $_aResults   = array();
        foreach( $_aTerms as $_iIndex => $_oTerm ) {
            $_aResults[] = array(    // must be numeric
                'id'    => $_oTerm->term_id,
                'text'  => $_oTerm->name,
            );
        }

        return array(
            'results'       => $_aResults,
        );

    }

}