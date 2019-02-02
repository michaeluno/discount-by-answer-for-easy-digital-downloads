<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.8.0
 */
class DiscountByAnswerForEDD_Campaign_RequestType_checkbox extends DiscountByAnswerForEDD_Campaign_RequestType_Base {

    protected $_sType = 'checkbox';
    protected $_sFieldSelector = '.edddba_requests_checkbox';

    protected function _construct() {}

    /**
     * @return  string
     */
    protected function _getLabel() {
        return __( 'Checkbox', 'discount-by-answer-for-easy-digital-downloads' );
    }

    /**
     * @return  array
     */
    protected function _getField() {
        return array(
            'field_id'        => $this->_sType,
            'class'           => array(
                'fieldrow' => 'edddba_requests_checkbox edddba_requests',
            ),
            'content'         => array(
                array(
                    'field_id'      => 'label',
                    'title'         => __( 'Input Label', 'discount-by-answer-for-easy-digital-downloads' ),
                    'description'   => __( 'The text which appears to the user.', 'discount-by-answer-for-easy-digital-downloads' ),
                    'type'          => 'text',
                    'default'       => __( 'This is an option that the user checks.', 'discount-by-answer-for-easy-digital-downloads' ),
                    'class'           => array(
                        'fieldset'  => 'edddba_field_labels',
                    ),
                ),
                array(
                    'field_id'      => 'has_acceptable_answers',
                    'type'          => 'revealer2',
                    'select_type'   => 'checkbox',
                    'label'         => array(
                        0   => __( 'There are acceptable answers.', 'discount-by-answer-for-easy-digital-downloads' ),
                    ),
                    'selectors' => array(
                        0   => '.edddba_acceptable',
                    ),
                    'default'       => array(
                        0 => true,
                    ),
                ),
                array(
                    'field_id'      => 'acceptable',
                    'title'         => __( 'Acceptable Answers', 'discount-by-answer-for-easy-digital-downloads' ),
                    'sortable'      => true,
                    'repeatable'    => array( 'max' => 2 ),
                    'content'       => array(
                        array(
                            'field_id'  => 'checked',
                            'type'      => 'radio',
                            'label'     => array(
                                1   => __( 'Checked', 'discount-by-answer-for-easy-digital-downloads' ),
                                0   => __( 'Unchecked', 'discount-by-answer-for-easy-digital-downloads' ),
                            ),
                            'default'   => 1,
                        ),
                        // $this->___getFields_AnswerSpecificDiscount(),
                    ),
                    'class'           => array(
                        'fieldset' => 'edddba_acceptable',
                    ),
                ),
            ),
        );
    }

    /**
     * @param $bValid
     * @param $aSubmit
     * @param $aRequest
     * @param $oCampaign
     *
     * @return boolean
     * @throws Exception
     */
    protected function _isValidVisitorInput( $bValid, $mSubmit, array $aRequest, DiscountByAnswerForEDD_Campaign $oCampaign, $oEvent ) {

        $_bHasAcceptable = $this->getElement( $aRequest, array( $this->_sType, 'has_acceptable_answers', 0 ), false );
        if ( ! $_bHasAcceptable ) {
            return $bValid;
        }
        $_aAcceptables   = $this->getElementAsArray( $aRequest, array( $this->_sType, 'acceptable' ) );
        $_bChecked = ( boolean ) $mSubmit;
        foreach( $_aAcceptables as $_aAcceptable ) {
            $_bMustBeChecked = ( boolean ) $this->getElement( $_aAcceptable, 'checked' );
            if ( $_bMustBeChecked && $_bChecked ) {
                return true;
            }
            if ( ! $_bMustBeChecked && ! $_bChecked ) {
                return true;
            }
        }
        return false;

    }

    /**
     * @param $asRawAnswer
     * @param array $aRequest
     * @param DiscountByAnswerForEDD_Campaign $oCampaign
     *
     * @return mixed
     * @since   0.9.0
     */
    protected function _formatAnswer( $asRawAnswer, array $aRequest, DiscountByAnswerForEDD_Campaign $oCampaign ) {
        return $asRawAnswer
            ? __( 'Checked', 'discount-by-answer-for-easy-digital-downloads' )
            : __( 'Unchecked', 'discount-by-answer-for-easy-digital-downloads' );

    }

}