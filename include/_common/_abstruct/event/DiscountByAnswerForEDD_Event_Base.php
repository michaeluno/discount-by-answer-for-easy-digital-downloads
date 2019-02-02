<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * An abstract base for event classes, providing common members.
 * @since   0.0.1
 */
abstract class DiscountByAnswerForEDD_Event_Base extends DiscountByAnswerForEDD_PluginUtility {

    public function __construct() {

        /**
         * Hooked actions do not need to be called multiple times per event.
         * Such cases occur when a form element needs an Ajax response and uses an event.
         * And the form element called from multiple components.
         */
        if ( $this->hasBeenCalled( get_class( $this ) ) ) {
            return;
        }

        $this->_construct();

    }

    /**
     * Do set ups for hooks.
     * @return void
     */
    protected function _construct() {}

    /**
     * @return void
     */
    public function doAction() {

    }

    /**
     * @return bool
     */
    protected function _shouldProceed() {
        return true;
    }

}