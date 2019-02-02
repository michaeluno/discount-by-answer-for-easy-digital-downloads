<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.4.0
 */
class DiscountByAnswerForEDD_Campaign_RequestType_select extends DiscountByAnswerForEDD_Campaign_RequestType_Base {

    protected $_sType = 'select';
    protected $_sFieldSelector = '.edddba_requests_select';

    protected function _construct() {}

    /**
     * @return  string
     */
    protected function _getLabel() {
        return __( 'Drop-down', 'discount-by-answer-for-easy-digital-downloads' );
    }

    /**
     * @return  array
     */
    protected function _getField() {
        return array(
            'field_id'        => $this->_sType,
            'class'           => array(
                'fieldrow' => 'edddba_requests_select edddba_requests',
            ),
            'content'         => array(
                array(
                    'title'           => __( 'Selectable Items', 'discount-by-answer-for-easy-digital-downloads' ),
                    'field_id'        => 'items',
                    'repeatable'      => true,
                    'sortable'        => true,
                    'content'         => array(
                        array(
                            'field_id'      => 'slug',
                            'title'         => __( 'Slug', 'discount-by-answer-for-easy-digital-downloads' ),
                            'type'          => 'text',
                            'description'   => __( 'Accepts alphanumeric characters. This is internally used by the plugin to distinguish added options.', 'discount-by-answer-for-easy-digital-downloads' ),
                            // @todo in the sanitization process, give a value when empty
                            'attributes'    => array(
                                'pattern'     => '[a-zA-Z0-9-_]+',
                            ),
                        ),
                        array(
                            'field_id'      => 'label',
                            'title'         => __( 'Input Label', 'discount-by-answer-for-easy-digital-downloads' ),
                            'description'   => __( 'The text which appears to the user.', 'discount-by-answer-for-easy-digital-downloads' ),
                            'type'          => 'text',
                            'default'       => __( 'Option A', 'discount-by-answer-for-easy-digital-downloads' ),
                            'class'           => array(
                                'fieldset'  => 'edddba_field_labels',
                            ),
                        ),
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
                    'repeatable'    => true,
                    'content'       => array(
                        array(
                            'field_id'    => 'value',
                            'type'        => 'text',
                            'description' => __( 'Type here the slug set above to be the acceptable answer.', 'discount-by-answer-for-easy-digital-downloads' ),
                            'attributes'  => array(
                                'pattern'     => '[a-zA-Z0-9-_]+',
                            ),
                        ),
//                        $this->___getFields_AnswerSpecificDiscount(),
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
    protected function _isValidVisitorInput( $bValid, $sSelectedSlug, array $aRequest, DiscountByAnswerForEDD_Campaign $oCampaign, $oEvent ) {

        $_bHasAcceptable = $this->getElement( $aRequest, array( $this->_sType, 'has_acceptable_answers', 0 ), false );
        if ( ! $_bHasAcceptable ) {
            return $bValid;
        }
        $_aAcceptables   = $this->getElementAsArray( $aRequest, array( $this->_sType, 'acceptable' ) );
        foreach( $_aAcceptables as $_aAcceptable ) {
            $_sShouldBeSelected = ( string ) $this->getElement( $_aAcceptable, 'value' );
            if ( $sSelectedSlug === $_sShouldBeSelected ) {
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
        $_aItems = $this->getElementAsArray( $aRequest, array( $this->_sType, 'items' ) );
        foreach( $_aItems as $_iIndex => $_aItem ) {
            $_sSlug = $this->getELement( $_aItem, 'slug' );
            if ( $_sSlug === $asRawAnswer ) {
                return $this->getELement( $_aItem, 'label' );
            }
        }
        return $asRawAnswer;
    }

}