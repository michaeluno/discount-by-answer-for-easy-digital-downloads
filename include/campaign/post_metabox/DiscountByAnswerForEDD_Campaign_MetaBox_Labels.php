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
class DiscountByAnswerForEDD_Campaign_MetaBox_Labels extends DiscountByAnswerForEDD_Campaign_MetaBox_Base {

    protected $_sSectionID    = '_edddba_labels';
    protected $_sSectionClass = 'DiscountByAnswerForEDD_FormElement_Section_Labels';
    protected $_sFieldsClass  = 'DiscountByAnswerForEDD_FormElement_Fields_Labels';

    /**
     *
     */
    public function validate( $aInputs, $aOldInputs, $oFactory ) {
        return $aInputs;
    }

}