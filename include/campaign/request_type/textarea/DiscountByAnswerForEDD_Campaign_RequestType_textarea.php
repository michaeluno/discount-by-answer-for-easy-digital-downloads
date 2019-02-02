<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.5.0
 */
class DiscountByAnswerForEDD_Campaign_RequestType_textarea extends DiscountByAnswerForEDD_Campaign_RequestType_Base {

    protected $_sType = 'textarea';
    protected $_sFieldSelector = '.edddba_requests_textarea';

    protected function _construct() {}

    /**
     * @return  string
     */
    protected function _getLabel() {
        return __( 'Text Area', 'discount-by-answer-for-easy-digital-downloads' );
    }

    /**
     * @return  array
     */
    protected function _getField() {
        return array(
            'field_id'        => $this->_sType,
            'class'           => array(
                'fieldrow' => 'edddba_requests_textarea edddba_requests',
            ),
            'content'         => array(
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
                    'repeatable'    => true,
                    'content'       => array(
                        array(
                            'field_id'      => 'keyword',
                            'type'          => 'text',
                            'title'         => __( 'Keyword', 'discount-by-answer-for-easy-digital-downloads' ),
                            'description'   => __( 'The keyword that must be included in the provided text by the user.', 'discount-by-answer-for-easy-digital-downloads' ),
                            'class'         => array(
                                'fieldset'  => 'edddba_full_width',
                            ),
                            'attributes'    => array(
                                'placeholder' => __( 'Replace this with your answer keyword!', 'discount-by-answer-for-easy-digital-downloads' ),
                            ),
                        ),
                        array(
                            'field_id'         => 'case_sensitivity',
                            'type'             => 'checkbox',
                            'title'            => __( 'Case-sensitivity', 'discount-by-answer-for-easy-digital-downloads' ),
                            'label'            => __( 'The provided keyword must be in a case-sensitive manner.', 'discount-by-answer-for-easy-digital-downloads' ),
                            // 'label_min_width'  => 0,
                            'default'          => 0,
                            'class' => array(
                                'fieldset' => 'edddba_case_sensitivity'
                            ),
                        ),
          //              $this->___getFields_AnswerSpecificDiscount(),
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
    protected function _isValidVisitorInput( $bValid, $sEnteredText, array $aRequest, DiscountByAnswerForEDD_Campaign $oCampaign, $oEvent ) {

        $_bHasAcceptable = $this->getElement( $aRequest, array( $this->_sType, 'has_acceptable_answers', 0 ), false );
        if ( ! $_bHasAcceptable ) {
            return $bValid;
        }
        $_aAcceptables   = $this->getElementAsArray( $aRequest, array( $this->_sType, 'acceptable' ) );
        foreach( $_aAcceptables as $_aAcceptable ) {
            $_sSearchKeyword = ( string ) trim( $this->getElement( $_aAcceptable, 'keyword' ) );
            $_bCaseSensitive = ( boolean ) $this->getElement( $_aAcceptable, 'case_sensitivity' );

            if ( $this->hasSubstring( $_sSearchKeyword, $sEnteredText, $_bCaseSensitive ) ) {
                return true;
            }
        }
        return false;

    }

}