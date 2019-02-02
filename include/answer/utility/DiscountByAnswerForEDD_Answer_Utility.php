<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Provides shared methods for the Answer component
 * @since   0.7.0
 */
class DiscountByAnswerForEDD_Answer_Utility extends DiscountByAnswerForEDD_PluginUtility {

    /**
     * Constructs a field definition array from an array holding key-value pairs.
     *
     * @param array $aArray
     * @return array
     * @since   0.9.0
     */
    static public function getFieldsFromArray( array $aArray ) {
        $_aFields = array();
        foreach( $aArray as $_isKey => $_asValue ) {
            if ( is_array( $_asValue ) ) {
                $_aFields[] = self::getFieldsFromArray( $_asValue );
                continue;
            }
            $_sTitle = is_numeric( $_isKey )
                ? '<span>' . ( $_isKey + 1 ) . '</span>'
                : $_isKey;
            $_aFields[] = array(
                'field_id'  => '_' . $_isKey,   // for cases of numbers, avoid the framework to throw errors by passing a string value
                'title'     => $_sTitle,
                'content'   => $_asValue,
            );
        }
        return $_aFields;
    }

    /**
     * @param $iAnswerID
     *
     * @return string
     * @since   0.7.0
     */
    static public function getAnswerViewLink( $iAnswerID ) {
        return add_query_arg(
            array(
                'post_type' => 'download',
                'page'      => DiscountByAnswerForEDD_Registry::$aAdminPages[ 'view_answer' ],
                'answer'    => $iAnswerID,
            ),
            admin_url( 'edit.php' )
        );
    }

    /**
     * @return string
     */
    static public function getCampaignEditLink( $iCampaignID ) {
        return add_query_arg(
            array(
                // wp-admin/post.php?post=244&action=edit&action=edit&post_type=edddba_campaign
                'action'    => 'edit',
                'post_type' => DiscountByAnswerForEDD_Registry::$aPostTypes['campaign'],
                'post'      => $iCampaignID,
            ),
            admin_url( 'post.php' )
        );
    }

}