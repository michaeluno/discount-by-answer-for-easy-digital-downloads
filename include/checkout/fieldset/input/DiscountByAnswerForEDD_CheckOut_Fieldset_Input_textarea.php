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
class DiscountByAnswerForEDD_CheckOut_Fieldset_Input_textarea extends DiscountByAnswerForEDD_CheckOut_Fieldset_Input_Base {

    public $sType = 'textarea';

    public function get() {

        $_sCampaignID  = $this->getElement( $this->_aOptions, 'campaign_id' );
        $_aAttributes  = array(
            'class'       => 'edd-input edddba_input',
            'id'          => $this->getID(),
            'name'        => $this->getName(),
            'data-required' => $this->getElement( $this->_aRequest, 'required' ),   // not using the `required` attribute as it prevents the actual cart form submission
        );
        $_sAttributes  = $this->getAttributes( $_aAttributes );

        return "<div class='edd-discount-code-field-wrap {$this->sType} edddba_field_wrap'>"
            . "<textarea " . $_sAttributes . "></textarea>"
            . '</div>';

    }


}