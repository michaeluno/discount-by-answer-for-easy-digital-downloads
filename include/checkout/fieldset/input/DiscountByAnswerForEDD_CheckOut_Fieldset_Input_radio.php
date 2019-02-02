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
class DiscountByAnswerForEDD_CheckOut_Fieldset_Input_radio extends DiscountByAnswerForEDD_CheckOut_Fieldset_Input_Base {

    public $sType = 'radio';

    public function get() {
        return "<div class='edd-discount-code-field-wrap {$this->sType} edddba_field_wrap'>"
            . $this->_getEach( $this->getElementAsArray( $this->_aOptions, array( 'items' ) ) )
            . '</div>';
    }
        protected function _getEach( array $aItems ) {

            $_sOutput       = '';
            foreach( $aItems as $_iIndex => $_aItem ) {

                $_sID   = $this->getID( array( $_iIndex ) );
                $_aAttributesLabel = array(
                    'for'   => $_sID,
                );
                $_aAttributesInput = array(
                    'id'    => $_sID,
                    'type'  => $this->sType,
                    'class' => 'edd-input edddba_input',
                    'name'  => $this->getName(),
                    'value' => $this->getElement( $_aItem, 'slug' ),
                );
                // Check the first item.
                if ( ( boolean ) ! $_sOutput ) {
                    $_aAttributesInput[ 'checked' ] = 'checked';
                }
                $_sOutput .= "<label " . $this->getAttributes( $_aAttributesLabel ) . " >"
                        . "<input " . $this->getAttributes( $_aAttributesInput ) . " />"
                        . "<span>"
                            . $this->getElement( $_aItem, 'label' )
                        . "</span>"
                    . "</label>";
            }
            return $_sOutput;
        }

}