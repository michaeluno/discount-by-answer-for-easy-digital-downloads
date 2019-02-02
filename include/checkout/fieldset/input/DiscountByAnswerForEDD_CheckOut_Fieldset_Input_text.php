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
class DiscountByAnswerForEDD_CheckOut_Fieldset_Input_text extends DiscountByAnswerForEDD_CheckOut_Fieldset_Input_Base {

    public $sType = 'text';

    public function get() {

        $_aAttributes  = array(
            'class'         => 'edd-input edddba_input',
            'type'          => $this->sType,
            'id'            => $this->getID(),
            'name'          => $this->getName(),
            'placeholder'   => $this->getElement( $this->_aOptions, array( 'placeholder' ) ),
            'data-required' => $this->getElement( $this->_aRequest, 'required' ),   // not using the `required` attribute as it prevents the actual cart form submission
        );
        $_sAttributes  = $this->getAttributes( $_aAttributes );
        return "<div class='edd-discount-code-field-wrap {$this->sType} edddba_field_wrap'>"
            . "<input " . $_sAttributes . " />"
            . '</div>';

    }

}