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
abstract class DiscountByAnswerForEDD_FormElement_Base extends DiscountByAnswerForEDD_PluginUtility {

    public $sID = '';

    public function __construct( $sID='' ) {
        $this->sID = $sID;
    }

    /**
     * @param array $aOverride
     *
     * @return array
     */
    public function get( $aOverride=array() ) {
        return $aOverride + $this->_getDefinition();
    }
        /**
         * @return array
         */
        protected function _getDefinition() {
            return array();
        }

}