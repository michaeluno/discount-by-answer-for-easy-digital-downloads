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
class DiscountByAnswerForEDD_Campaign_MetaBox_AssociatedPosts extends DiscountByAnswerForEDD_Campaign_MetaBox_Base {

    protected $_sSectionID    = '_edddba_associated_posts';
    protected $_sSectionClass = 'DiscountByAnswerForEDD_FormElement_Section_AssociatedPosts';
    protected $_sFieldsClass  = 'DiscountByAnswerForEDD_FormElement_Fields_AssociatedPosts';

    /**
     * @return array
     */
    public function validate( $aInputs, $aOldInputs, $oFactory ) {

        $aInputs         = $this->oUtil->getAsArray( $aInputs );
        $_aSectionInputs = $this->oUtil->getElementAsArray( $aInputs, $this->_sSectionID );
        $_aErrors        = array();

        try {

//            $_aAssociationdTypes = $this->oUtil->getElementAsArray( $_aSectionInputs, array( 'association_types' ) );
//            $_aAssociationdTypes = array_filter( $_aAssociationdTypes );
//            if ( empty( $_aAssociationdTypes ) ) {
//                $_aErrors[ 'association_types' ] = __( 'At least one must be checked.', 'discount-by-answer-for-easy-digital-downloads' );
//                throw new Exception( __( 'Associated downloads need to be set..', 'discount-by-answer-for-easy-digital-downloads' ) );
//            }


        } catch ( Exception $_oException ) {

            // An invalid value is found. Set a field error array and an admin notice and return the old values.
            $_aFieldErrors = array(
                $this->_sSectionID => $_aErrors,
            );
            $oFactory->setFieldErrors( $_aFieldErrors );
            $oFactory->setSettingNotice( $_oException->getMessage() );
            return $aOldInputs;

        }

        $aInputs[ $this->_sSectionID ] = $_aSectionInputs;
        return $aInputs;

    }

}