<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Creates the Answer post type.
 *
 * Provides methods for the list table.
 * @since   0.4.0
 */
class DiscountByAnswerForEDD_Answer_PostType_ListTable extends DiscountByAnswerForEDD_AdminPageFramework_PostType {

    /**
     * Customize the columns of the post listing table.
     * @param $aHeaderColumns
     *
     * @return array
     */
    public function columns_edddba_answer( $aHeaderColumns ) {

        unset( $aHeaderColumns[ 'title' ], $aHeaderColumns[ 'date' ], $aHeaderColumns[ 'author' ] );
        $aHeaderColumns = $aHeaderColumns + array(
            'name'                 => __( 'Name', 'discount-by-answer-for-easy-digital-downloads' ),
//            'author'               => __( 'Customer', 'discount-by-answer-for-easy-digital-downloads' ),
//            'uses'                 => __( 'Uses', 'discount-by-answer-for-easy-digital-downloads' ),
            'customer'             => __( 'Customer', 'discount-by-answer-for-easy-digital-downloads' ),
//            'campaign'             => __( 'Campaign', 'discount-by-answer-for-easy-digital-downloads' ),
            'discount'             => __( 'Discount Code', 'discount-by-answer-for-easy-digital-downloads' ),
            'time'                 => __( 'Time', 'discount-by-answer-for-easy-digital-downloads' ),
        );

        return $aHeaderColumns;
    }
    /**
     * Customize sortable state of added columns of the listing table.
     * @param $aHeaderColumns
     * @return array
     */
    public function sortable_columns_edddba_answer( $aHeaderColumns ) {
        return $aHeaderColumns + array(
            'name'      => 'name',
            'author'    => 'author',
            'campaign'  => 'campaign',
            'discount'  => 'discount',
            'time'      => 'time',
        );
    }

    /**
     *
     * @callback        filter      cell_{post type slug}_{column slug}
     */
    public function cell_edddba_answer_name( $sCell, $iPostID ) {
        // $_sURL = DiscountByAnswerForEDD_Answer_Utility::getAnswerViewLink( $iPostID );
        $_iCampaignID = get_post_meta( $iPostID, '_edddba_campaign_id', true );
        $_sCampaignURL = DiscountByAnswerForEDD_Answer_Utility::getCampaignEditLink( $_iCampaignID );
        return $sCell
                . "<div>" . __( 'ID' ) . ': ' . $iPostID . '</div>'
                . "<div>" . __( 'Campaign', 'discount-by-answer-for-easy-digital-downloads' ) . ': '
                . "<a href='" . esc_url( $_sCampaignURL ) . "'>"
                    . get_the_title( $_iCampaignID )
                . "</a>"
               . "</div>";
    }

    /**
     *
     * @callback        filter      cell_{post type slug}_{column slug}
     */
    public function cell_edddba_answer_customer( $sCell, $iPostID ) {
        return $sCell
            . "<p>"
                . $this->___getCustomerName( $iPostID )
            . "</p>";
    }
        private function ___getCustomerName( $iAnswerID ) {

            $_iCustomerID   = ( integer ) get_post_meta( $iAnswerID, '_edddba_customer_id', true );
            if ( ! $_iCustomerID ) {
                return __( 'n/a', 'discount-by-answer-for-easy-digital-downloads' );
            }
            $_oCustomer     = new EDD_Customer( $_iCustomerID );
            $_sCustomerName = trim( $_oCustomer->name );
            if ( ! $_sCustomerName ) {
                return __( 'n/a', 'discount-by-answer-for-easy-digital-downloads' );
            }
            $_sCustomerURL = add_query_arg(
                array(
                    'post_type' => 'download',
                    'page'      => 'edd-customers',
                    'view'      => 'overview',
                    'id'        => $_iCustomerID,
                ),
                admin_url( 'edit.php' )
            );
            return "<a href='" . esc_url( $_sCustomerURL ) . "'>"
                    . $_sCustomerName
                . "</a>";

        }

    /**
     *
     * @callback        filter      cell_{post type slug}_{column slug}
     */
    public function cell_edddba_answer_uses( $sCell, $iPostID ) {
        $_iPaymentID   = get_post_meta( $iPostID, '_edddba_payment_id', true );
        $_sPaymentURL  = add_query_arg(
            array(
                'post_type' => 'download',
                'page'      => 'edd-payment-history',
                'view'      => 'view-order-details',
                'id'        => $_iPaymentID,
            ),
            admin_url( 'edit.php' )
        );
        $_sViewPayment = $_iPaymentID
            ? "<a href='" . esc_url( $_sPaymentURL ) . "'>"
                    . __( 'View payment', 'discount-by-answer-for-easy-digital-downloads' )
                . "</a>"
            : '';
        return $sCell
            . "<p>" . $_sViewPayment . "</p>";
    }

    /**
     *
     * @callback        filter      cell_{post type slug}_{column slug}
     */
    public function cell_edddba_answer_discount( $sCell, $iPostID ) {
        $_sCode   = get_post_meta( $iPostID, '_edddba_discount_code', true );;
        $_iCodeID = edd_get_discount_id_by_code( $_sCode );
        $_bExists = edd_discount_exists( $_iCodeID );
        $_sOutput = '';
        // Discount code is deleted
        if ( ! $_bExists ) {
            $_sOutput = '<p class="">'
//                    . '<em>'
                        . get_post_meta( $iPostID, '_edddba_discount_code', true  )
//                   . '</em>'
                . '</p>';
        } else {
            $_sURL                  = add_query_arg(
                array(
                    'post_type'  => 'download',
                    'page'       => 'edd-discounts',
                    'edd-action' => 'edit_discount',
                    'discount'   => $_iCodeID,
                ),
                admin_url( 'edit.php' )
            );
            $_sOutput = '<p class="">'
                    // . '<em>'
                        . "<a href='" . esc_url( $_sURL ) . "'>"
                            . $_sCode
                        . "</a>"
                   // . '</em>'
                . '</p>';
        }
        return $sCell . $_sOutput
            . "<p>"
                . __( 'Base', 'discount-by-answer-for-easy-digital-downloads' ) . ': '
                . $this->___getBaseDiscountCode( $iPostID )
            . "</p>";
    }
        private function ___getBaseDiscountCode( $iAnswerID ) {
            $_iCampaignID           = get_post_meta( $iAnswerID, '_edddba_campaign_id', true );
            if ( ! $_iCampaignID ) {
                return '';
            }
            $_oCampaign             = new DiscountByAnswerForEDD_Campaign( $_iCampaignID );
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

    /**
     *
     * @callback        filter      cell_{post type slug}_{column slug}
     */
    public function cell_edddba_answer_time( $sCell, $iPostID ) {
        $_sTime  = get_post_field( 'post_date', $iPostID, 'raw' );
        $_iTime  = strtotime( $_sTime );
        $_sTime  = date( get_option( 'date_format' ) . ' H:i:s', $_iTime );
        $_sTimeDiff = human_time_diff( $_iTime, current_time( 'timestamp', true  ) ) . " " . __( 'ago' );
        $_sTimeDiff = esc_attr( $_sTimeDiff );
        return $sCell . "<p title='{$_sTimeDiff}'>"
            . '<em>'. $_sTime .'</em>'
            . '</p>';
    }

    /**
     * @param $aActionLinks
     * @param $oPost
     * @callback    filter  action_links_{post type slug}
     */
    public function action_links_edddba_answer( $aActionLinks, $oPost ) {

        unset(
            $aActionLinks[ 'inline hide-if-no-js' ],
            $aActionLinks[ 'trash' ],
            $aActionLinks[ 'edit' ]
        );

        // `View` shows the answer in a modal window
        $aActionLinks[ 'view' ] = sprintf(
            '<a href="%1$s">' . __( 'View' ) . '</a>',
            DiscountByAnswerForEDD_Answer_Utility::getAnswerViewLink( $oPost->ID )
        );

        return $aActionLinks;
    }

    
}