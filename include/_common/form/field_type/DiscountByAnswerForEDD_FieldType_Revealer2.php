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
class DiscountByAnswerForEDD_FieldType_Revealer2 extends DiscountByAnswerForEDD_RevealerCustomFieldType {

    /**
     * Defines the field type slugs used for this field type.
     */
    public $aFieldTypeSlugs = array( 'revealer2', );


    /**
     * Adds the revealer jQuery plugin.
     * @since            3.0.0
     */
    public function _replyToAddRevealerjQueryPlugin() {

        $_sScript = "
( function ( $ ) {

    $( document ).ready( function() {
           
        // When the revealing fields are hidden in a collapsible section 
        // and the section is expanded, the reveal event must be called.
        $( 'body' ).on( 'click', '.discount-by-answer-for-easy-digital-downloads-collapsible-sections-title, .discount-by-answer-for-easy-digital-downloads-collapsible-section-title', function( event ){ 
            $( event.target ).closest( '.discount-by-answer-for-easy-digital-downloads-section' ).find( 'select[data-reveal], input:checked[type=radio], input:checked[type=checkbox][data-reveal]' )
                .trigger( 'change' );                 
         });
             
    } );
    
    
    /**
     * Binds the revealer event to the element.
     */
    $.fn.setDiscountByAnswerForEDD_AdminPageFrameworkRevealer = function() {

        // For collapsible sections
        this.on( 'click', function( event ){   
            var _sTagName = $( this ).prop( 'tagName' ).toLowerCase()             
            if ( 'select' === _sTagName || 'option' === _sTagName ) {
                event.preventDefault();
                event.stopPropagation();                
                return false;            
            }
        } );

        var _sLastRevealedSelector;
        var _oLastRevealed;
        this.unbind( 'change' ); // for repeatable fields
        this.change( function() {
            
            var _sTargetSelector        = $( this ).is( 'select' )
                ? $( this ).children( 'option:selected' ).data( 'reveal' )
                : $( this ).data( 'reveal' );

            // Bug: revealer field creates an artificial nested field but it causes
            // duplicated ids and results in jQuery properly not recognizing elements with closest().
            // So here using parent().
            var _oContainer = $( this )
                .closest( 'fieldset' )  // inner field set that the revealer recursively calls. for the bug that the revealer uses duplicated nested field outputs 
                .parent()
                .closest( 'fieldset' )  // the actual outer fieldset that this revealer field resides
                .closest( '.discount-by-answer-for-easy-digital-downloads-field, .discount-by-answer-for-easy-digital-downloads-section' );   // search within the nested field or normal section 
                    
            var _oElementToReveal       = _oContainer.find( _sTargetSelector );
                        
            // For check-boxes       
            if ( $( this ).is( ':checkbox' ) ) {
                if ( $( this ).is( ':checked' ) ) {
                    _oElementToReveal.fadeIn();
                } else {
                    _oElementToReveal.hide();    
                }                      
                return;
            }
            
            // For other types (select and radio).
            // Elements to hide
            var _sSelectors = $( this ).data( 'selectors' );            
            _oContainer.find( _sSelectors ).hide();

            // Hide the previously hidden element.
            _oContainer.find( _sLastRevealedSelector ).hide();
                                
            // Store the last revealed item in the local and the outer local variables.
            _sLastRevealedSelector = _sTargetSelector;
                      
//            if ( 'undefined' === _sTargetSelector ) { 
//                return; 
//            }
            _oElementToReveal.fadeIn();                                       
       
        });
        
    };
                
}( jQuery ));";

        echo "<script type='text/javascript' class='discount-by-answer-for-easy-digital-downloads-revealer-jQuery-plugin'>"
                . '/* <![CDATA[ */ '
                . $_sScript
                . ' /* ]]> */'
            . "</script>";

    }
}