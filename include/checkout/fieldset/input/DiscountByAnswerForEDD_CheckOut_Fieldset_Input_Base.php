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
abstract class DiscountByAnswerForEDD_CheckOut_Fieldset_Input_Base extends DiscountByAnswerForEDD_PluginUtility {

    /**
     * @var array
     */
    protected $_aOptions = array();
    protected $_aRequest = array();
    /**
     * @var DiscountByAnswerForEDD_Campaign
     */
    protected $_oCampaign;

    /**
     * Used as the top level input name to differentiate input elements from other purchase input elements.
     * @var string
     */
    protected $_sBaseKey = 'edddba';

    /**
     * @var string
     */
    public $sType = 'base';

    public function __construct( array $aOptions, array $aRequest, DiscountByAnswerForEDD_Campaign $oCampaign ) {
        $this->_aOptions  = $aOptions;
        $this->_aRequest  = $aRequest;
        $this->_oCampaign = $oCampaign;
    }

    /**
     * @return string   The input field output.
     */
    public function get() {
        return '<p>' . $this->sType . '</p>';
    }

    /**
     * @return  string the input `ID` attribute value.
     */
    public function getID( $aSuffixes=array() ) {
        $_sSuffix = empty( $aSuffixes ) ? '' : '_' . implode( '_', $aSuffixes );
        return $this->_sBaseKey
               . '_' . $this->_oCampaign->get( 'id' )
               . '_' . $this->getElement( $this->_aRequest, 'slug' )
               . $_sSuffix;
    }

    /**
     * @return  string the input `name` attribute value.
     */
    public function getName( array $aSuffixes=array() ) {
        $_sSuffix = empty( $aSuffixes ) ? '' : '[' . implode( '][', $aSuffixes ) . ']';
        $_sSlug   = $this->getElement( $this->_aRequest, 'slug' );
        $_sSlug   = $_sSlug ? $_sSlug : $this->sType;
        return $this->_sBaseKey
               . '[' . $this->_oCampaign->get( 'id' ) . ']'
               . '[' . $_sSlug . ']'
               . $_sSuffix;
    }




}