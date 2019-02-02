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
class DiscountByAnswerForEDD_CheckOut_Fieldset_Input_select extends DiscountByAnswerForEDD_CheckOut_Fieldset_Input_radio {

    public $sType = 'select';

    protected function _getEach( array $aItems ) {

        $_aAttributesSelect = array(
            'id'    => $this->getID(),
            'name'  => $this->getName(),
            'class' => 'edddba_input',
        );

        $_sOutput       = '';
        foreach( $aItems as $_iIndex => $_aItem ) {

            $_aAttributesOption = array(
                'id'     => $this->getID( array( $_iIndex ) ),
                'value'  => $this->getElement( $_aItem, 'slug' ),
            );
            $_sOutput .= "<option " . $this->getAttributes( $_aAttributesOption ) . " >"
                    . $this->getElement( $_aItem, 'label' )
                . "</option>";

        }
        return "<select " . $this->getAttributes( $_aAttributesSelect ). ">"
                . $_sOutput
            . "</select>";
    }

}