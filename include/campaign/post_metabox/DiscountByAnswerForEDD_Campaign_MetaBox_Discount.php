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
class DiscountByAnswerForEDD_Campaign_MetaBox_Discount extends DiscountByAnswerForEDD_Campaign_MetaBox_Base {

    protected $_sSectionID    = '_edddba_discount';
    protected $_sSectionClass = 'DiscountByAnswerForEDD_FormElement_Section_Discount';
    protected $_sFieldsClass  = 'DiscountByAnswerForEDD_FormElement_Fields_Discount';

    /**
     * @return array
     */
    public function validate( $aInputs, $aOldInputs, $oFactory ) {

        $aInputs    = $this->oUtil->getAsArray( $aInputs );
        $_aDiscount = $this->oUtil->getElementAsArray( $aInputs, $this->_sSectionID );
        $_aErrors   = array();

        try {

            $_sPrefix = $this->oUtil->getElement( $_aDiscount, 'prefix', '' );
            $_sPrefix = preg_replace("/[^a-zA-Z0-9_]+/", '', $_sPrefix );
            $_aDiscount[ 'prefix' ] = $_sPrefix;

            if ( ! $this->oUtil->getElement( $_aDiscount, array( 'base_discount_code', 'value' ) ) ) {
                $_aErrors[ 'base_discount_code' ] = __( 'Cannot be empty.', 'discount-by-answer-for-easy-digital-downloads' );
                throw new Exception( __( 'A base discount code must be selected.', 'discount-by-answer-for-easy-digital-downloads' ) );
            }

        } catch ( Exception $_oException ) {

            // An invalid value is found. Set a field error array and an admin notice and return the old values.
            $_aFieldErrors = array(
                $this->_sSectionID => $_aErrors,
            );
            $oFactory->setFieldErrors( $_aFieldErrors );
            $oFactory->setSettingNotice( $_oException->getMessage() );
            return $aOldInputs;

        }

        $aInputs[ $this->_sSectionID ] = $_aDiscount;
        return $aInputs;

    }

}