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
class DiscountByAnswerForEDD_CheckOut_Fieldset_Input_checkbox extends DiscountByAnswerForEDD_CheckOut_Fieldset_Input_Base {

    public $sType = 'checkbox';

    public function get() {
// var_dump( $this->_aOptions );
        $_sSlug = $this->getElement( $this->_aRequest, 'slug', '' );
        $_sID   = $this->getID();
        $_sName = $this->getName();
        $_aAttributesLabel = array(
            'for'   => $_sID,
        );
        $_aAttributesHidden = array(
            'id'    => $this->getID( array( $_sSlug, 'hidden' ) ),
            'type'  => 'hidden',
            'class' => 'edd-input edddba_input',
            'name'  => $_sName,
            'value' => 0,
        );
        $_aAttributesInput = array(
            'id'    => $_sID,
            'type'  => $this->sType,
            'class' => 'edd-input edddba_input',
            'name'  => $_sName,
            'value' => 1,
            'data-required' => $this->getElement( $this->_aRequest, 'required' ),   // not using the `required` attribute as it prevents the actual cart form submission
        );
        return "<div class='edd-discount-code-field-wrap {$this->sType} edddba_field_wrap'>"
                . "<label " . $this->getAttributes( $_aAttributesLabel ) . " >"
                . "<input " . $this->getAttributes( $_aAttributesHidden ) . " />"
                . "<input " . $this->getAttributes( $_aAttributesInput ) . " />"
                . "<span>"
                    . $this->getElement( $this->_aOptions, 'label' )
                . "</span>"
                . "</label>"
            . "</div>";

    }



}