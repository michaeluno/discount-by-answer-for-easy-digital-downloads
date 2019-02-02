<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * The base class for request type components.
 * @since   0.8.0
 */
abstract class DiscountByAnswerForEDD_Campaign_RequestType_Base extends DiscountByAnswerForEDD_Campaign_RequestTypes_Utility {

    protected $_sType = 'base';
    protected $_sFieldSelector = '.base-field';


    public function __construct() {

        add_filter( 'edddba_filter_campaign_request_type_labels', array( $this, 'getLabels' ) );
        add_filter( 'edddba_filter_campaign_request_type_field_selectors', array( $this, 'getFieldSelectors' ) );
        add_filter( 'edddba_filter_campaign_request_type_slugs', array( $this, 'getSlugs' ) );
        add_filter( 'edddba_filter_campaign_request_type_fields_' . $this->_sType, array( $this, 'getFields' ) );
        add_filter( 'edddba_is_valid_campaign_request_' . $this->_sType, array( $this, 'isValidVisitorInput' ), 10, 5 );
        add_filter( 'edddba_filter_answer_format_value_' . $this->_sType, array( $this, 'formatAnswer' ), 10, 3 );
        $this->_construct();

    }

    /**
     * Each component overrides this method to do some set-ups instead of using `__construct()`.
     */
    protected function _construct() {}

    /**
     * @return  array
     */
    public function getSlugs( array $aSlugs ) {
        $aSlugs[] = $this->_sType;
        return $aSlugs;
    }

    /**
     * @return  array
     */
    public function getLabels( array $aLabels ) {
        return array(
                $this->_sType => $this->_getLabel(),
            ) + $aLabels;
    }
        protected function _getLabel() {
            return 'Base';
        }

    /**
     * @return  array
     */
    public function getFieldSelectors( array $aSelectors ) {
        return array(
            $this->_sType => $this->_sFieldSelector,
        ) + $aSelectors;
    }

    /**
     * @return  array
     */
    public function getFields( array $aFields ) {
        $_aField     = apply_filters( 'edddba_filter_campaign_request_type_field_' . $this->_sType, $this->_getField() );
        $_aContent   = $this->getElementAsArray( $_aField, 'content' );
        if ( ! empty( $_aContent ) ) {
            $_aContent[] = $this->___getField_ErrorMessage();
            $_aField[ 'content' ] = $_aContent;
        }
        $aFields[]   = $_aField;
        return $aFields;
    }
        protected function _getField() {
            return array(
                'field_id'  => '_my_field_id',
                'type'      => 'text',
            );
        }
        /**
         * @return array
         */
        private function ___getField_ErrorMessage() {
            return array(
                'field_id'      => 'field_error_message',
                'title'         => __( 'Error Message', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'   => __( 'The error message to show to the customer when unacceptable answer is entered. If left empty, the system defined message will be shown.', 'discount-by-answer-for-easy-digital-downloads' ),
                'type'          => 'text',
                'default'       => '',
                'class'           => array(
                    'fieldset'  => 'edddba_acceptable edddba_field_labels',
                ),
            );
        }

    /**
     * @param boolean $bValid
     * @param mixed $mSubmit
     * @param array $aRequest
     * @param  DiscountByAnswerForEDD_Campaign $oCampaign
     * @throws Exception
     */
    public function isValidVisitorInput( $bValid, $mSubmit, array $aRequest, DiscountByAnswerForEDD_Campaign $oCampaign, $oEvent ) {
        return $this->_isValidVisitorInput( $bValid, $mSubmit, $aRequest, $oCampaign, $oEvent );
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
            return $bValid;
        }


    public function validateUserInputs() {

    }
        protected function _validateUserInputs() {
        }


    /**
     * @param   string|array    $asRawAnswer
     * @param   array   $aRequest
     * @param   DiscountByAnswerForEDD_Campaign $oCampaign
     * @since   0.9.0
     */
    public function formatAnswer( $asRawAnswer, array $aRequest, DiscountByAnswerForEDD_Campaign $oCampaign ) {
        return $this->_formatAnswer( $asRawAnswer, $aRequest, $oCampaign );
    }
        /**
          * Makes the submitted value human readable.
          * Mainly converting slugs to labels.
          * @param $asRawAnswer
          * @param array $aRequest
          * @param DiscountByAnswerForEDD_Campaign $oCampaign
          *
          * @return mixed
          * @since   0.9.0
          */
        protected function _formatAnswer( $asRawAnswer, array $aRequest, DiscountByAnswerForEDD_Campaign $oCampaign ) {
            return $asRawAnswer;
        }

}