<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * An abstract base for Ajax event classes, providing common members.
 * @since   0.1.0
 */
abstract class DiscountByAnswerForEDD_Event_Ajax_Base extends DiscountByAnswerForEDD_Event_Base {

    protected $_sActionName  = '';

    protected $_bAllowGuests = true;

    public function __construct() {

        if ( $this->_sActionName ) {
            add_action( 'wp_ajax_' . $this->_sActionName, array( $this, 'doAction' ) );
            if ( $this->_bAllowGuests ) {
                add_action( 'wp_ajax_nopriv_' . $this->_sActionName, array( $this, 'doAction' ) );
            }
        }
        parent::__construct();

    }

    protected function _construct() {}

    public function doAction() {

    }

}