<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno; Licensed under <LICENSE_TYPE>
 */

/**
 * Adds the 'Customer Information' form section.
 *
 * @since    0.9.0
 */
class DiscountByAnswerForEDD_Answer_AdminPage__FormSection_CustomerInformation extends DiscountByAnswerForEDD_AdminPage__FormSection_Base {

    /**
     *
     * @since   0.9.0
     */
    protected function _getArguments( $oFactory ) {

        $_iAnswerID   = ( integer ) $this->getElement( $_GET, 'answer' );
        return array(
            'section_id'    => 'customer_information',
            'title'         => __( 'Customer Information', 'discount-by-answer-for-easy-digital-downloads' ),
            'collapsible'   => array(
                'is_collapsed'  => false,
                'container'     => 'section',
            ),
            'if'            => get_post_meta( $_iAnswerID, '_edddba_payment_id', true ),
        );
        
    }

    /**
     * Get adding form fields.
     * @since    0.9.0
     * @return   array
     */
    protected function _getFields( $oFactory ) {
        $_iAnswerID   = ( integer ) $this->getElement( $_GET, 'answer' );
        return $this->___getCustomerFields( $_iAnswerID );
    }
        private function ___getCustomerFields( $iAnswerID ) {

            $_aFields      = array();

            $_iPaymentID   = ( integer ) get_post_meta( $iAnswerID, '_edddba_payment_id', true );
            if ( $_iPaymentID ) {
                $_sPaymentURL  = add_query_arg(
                    array(
                        'post_type' => 'download',
                        'page'      => 'edd-payment-history',
                        'view'      => 'view-order-details',
                        'id'        => $_iPaymentID,
                    ),
                    admin_url( 'edit.php' )
                );
                $_aFields[] = array(
                    'field_id'  => 'payment_id',
                    'title'     => __( 'Payment ID', 'discount-by-answer-for-easy-digital-downloads' ),
                    'content'   => "<p>"
                            . "<a href='" . esc_url( $_sPaymentURL ) . "'>"
                                . $_iPaymentID
                            . "</a>"
                       . "</p>",
                );
            }

            if ( ! class_exists( 'EDD_Customer' ) ) {
                return $_aFields;
            }

            /**
             * Guests purchases do not have a user ID.
             * Also email address may be changed so first check with a user ID and then email.
             */
            $_iCustomerID   = ( integer ) get_post_meta( $iAnswerID, '_edddba_customer_id', true );
            if ( ! $_iCustomerID ) {
                return $_aFields;
            }
            $_oCustomer     = new EDD_Customer( $_iCustomerID );
            $_sCustomerName = trim( $_oCustomer->name );
            if ( $_sCustomerName ) {
                $_sCustomerURL = add_query_arg(
                    array(
                        'post_type' => 'download',
                        'page'      => 'edd-customers',
                        'view'      => 'overview',
                        'id'        => $_iCustomerID,
                    ),
                    admin_url( 'edit.php' )
                );
                $_aFields[] = array(
                    'field_id'  => 'customer_name',
                    'title'     => __( 'Customer Name', 'discount-by-answer-for-easy-digital-downloads' ),
                    'content'   => "<p>"
                                    . "<a href='" . esc_url( $_sCustomerURL ) . "'>"
                                        . $_sCustomerName
                                    . "</a>"
                                    . "</p>",
                );
            }
            $_sCustomerEmail = trim( $_oCustomer->email );
            if ( $_sCustomerEmail ) {
                $_aFields[] = array(
                    'field_id'  => 'customer_email',
                    'title'     => __( 'Customer Email', 'discount-by-answer-for-easy-digital-downloads' ),
                    'content'   => "<p>" . $_sCustomerEmail . "</p>",
                );
                $_aFields[] = array(
                    'field_id'  => 'customer_notes',
                    'title'     => __( 'Customer Notes', 'discount-by-answer-for-easy-digital-downloads' ),
                    'content'   => DiscountByAnswerForEDD_Answer_Utility::getFieldsFromArray( $_oCustomer->notes ),
                    'if'        => ! empty( $_oCustomer->notes ),
                );
            }
            return $_aFields;

        }

}