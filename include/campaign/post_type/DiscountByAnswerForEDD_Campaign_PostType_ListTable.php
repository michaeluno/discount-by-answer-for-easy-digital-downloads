<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */


/**
 * Provides methods dealing with the Campaign list table elements.
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_Campaign_PostType_ListTable extends DiscountByAnswerForEDD_AdminPageFramework_PostType {

    /**
     * Customize the columns of the post listing table.
     * @param $aHeaderColumns
     *
     * @return array
     */
    public function columns_edddba_campaign( $aHeaderColumns ) {
        $aHeaderColumns = $aHeaderColumns + array(
            'status'               => __( 'Status', 'discount-by-answer-for-easy-digital-downloads' ),
            'discount'             => __( 'Base Discount Code', 'discount-by-answer-for-easy-digital-downloads' ),
            'conversion'           => __( 'Conversion', 'discount-by-answer-for-easy-digital-downloads' ),
        );
        unset( $aHeaderColumns[ 'date' ] );
        return $aHeaderColumns;
    }

    /**
     *
     * @callback        filter      cell_{post type slug}_{column slug}
     */
    public function cell_edddba_campaign_status( $sCell, $iPostID ) {
        $_bEnabled = get_post_meta( $iPostID, '_edddba_campaign_status', true );
        $_sStatus  = $_bEnabled
            ? __( 'On', 'discount-by-answer-for-easy-digital-downloads' )
            : __( 'Off', 'discount-by-answer-for-easy-digital-downloads' );
        return $sCell
            . "<p>" . $_sStatus . "</p>";
    }

    /**
     *
     * @callback        filter      cell_{post type slug}_{column slug}
     */
    public function cell_edddba_campaign_conversion( $sCell, $iPostID ) {
        $_iUseCount   = ( integer ) get_post_meta( $iPostID, '_edddba_campaign_discount_use_count', true );
        $_iIssueCount = ( integer ) get_post_meta( $iPostID, '_edddba_campaign_discount_issue_count', true );
        $_iMaxUses    = ( integer ) get_post_meta( $iPostID, '_edddba_campaign_maximum_uses', true );
        $_sUses       = $_iMaxUses
            ? "<span>" . $_iUseCount . "</span>/<span>" . $_iMaxUses . "</span>"
            : "<span>" . $_iUseCount . "</span>";
        $_iConversion = $_iIssueCount
                ? round( ( $_iUseCount / $_iIssueCount ) * 100, 2 )
                : 0;
        return $sCell
               . "<div><span>" . __( 'Used', 'discount-by-answer-for-easy-digital-downloads' ) . "</span>: <span>" . $_sUses . "</span></div>"
               . "<div><span>" . __( 'Issued', 'discount-by-answer-for-easy-digital-downloads' ) . "</span>: <span>" . $_iIssueCount . "</span></div>"
               . "<div><span>" . __( 'Rate', 'discount-by-answer-for-easy-digital-downloads' ) . "</span>: <span>" . $_iConversion . "%</span></div>"
            ;
        ;
    }

    /**
     *
     * @callback        filter      cell_{post type slug}_{column slug}
     * @see EDD_Discount_Codes_Table::column_name()
     */
    public function cell_edddba_campaign_discount( $sCell, $iPostID ) {
        $_aDiscount         = $this->oUtil->getAsArray( get_post_meta( $iPostID, '_edddba_discount', true ) );
        $_iDiscountCodeID   = $this->oUtil->getElement( $_aDiscount, array( 'base_discount_code', 'value' ) );
        $_sURL              = add_query_arg(
            array(
                'post_type'  => 'download',
                'page'       => 'edd-discounts',
                'edd-action' => 'edit_discount',
                'discount'   => $_iDiscountCodeID,
            ),
            admin_url( 'edit.php' )
        );
        return $sCell
            . "<p>"
                . '<a href="' . esc_url( $_sURL ) . '">'
                    . edd_get_discount_code( $_iDiscountCodeID )
                . '</a>'
            . "</p>";
    }

    /**
     * @param $aActionLinks
     * @param $oPost
     *
     * @return string
     */
    public function action_links_edddba_campaign( $aActionLinks, $oPost ) {
        unset(
            $aActionLinks[ 'view' ],
            $aActionLinks['inline hide-if-no-js'],
            $aActionLinks['trash']
        );
        return $aActionLinks;
    }

}