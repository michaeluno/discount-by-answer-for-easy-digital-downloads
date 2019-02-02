<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Provides an abstract base for adding pages.
 * 
 * @since       0.0.3
 */
abstract class DiscountByAnswerForEDD_AdminPage__Element_Base extends DiscountByAnswerForEDD_PluginUtility {
    
    protected $_oFactory;
    
    protected $_aArguments = array();
    
    /* Common Protected Methods which should be overridden. */
    protected function _construct( $oFactory ) {}
    
    protected function _getArguments( $oFactory ) {
        return array();
    }    
        
    protected function _load( $oFactory ) {}
    
    /* Common Internal Methods */
    /**
     * Called when the in-page tab loads.
     */
    public function replyToLoad( $oFactory ) {
        $this->_load( $oFactory );
    }        
    
}