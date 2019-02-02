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
abstract class DiscountByAnswerForEDD_AdminPage__InPageTab_Base extends DiscountByAnswerForEDD_AdminPage__Element_Base {
    
    protected $_sPageSlug;
    
    protected $_sTabSlug;
        
    /**
     * Sets up hooks and properties.
     */
    public function __construct( $oFactory, $sPageSlug ) {
        
        $this->_oFactory     = $oFactory;
        $this->_sPageSlug    = $sPageSlug;
        $this->_aArguments   = $this->_getArguments( $oFactory );
        $this->_sTabSlug     = $this->getElement( $this->_aArguments, 'tab_slug', '' );
        
        $this->_construct( $oFactory );
        if ( ! $this->_sTabSlug ) {
            return;
        }
        
        $this->___addTab( $this->_sPageSlug, $this->_aArguments );
                
    }
    
        private function ___addTab( $sPageSlug, $aArguments ) {
            
            $this->_oFactory->addInPageTabs(
                $sPageSlug,
                $aArguments + array(
                    'tab_slug'          => null,
                    'title'             => null,
                    'parent_tab_slug'   => null,
                    'show_in_page_tab'  => null,
                )
            );
                
            if ( $aArguments[ 'tab_slug' ] ) {
                add_action( 
                    "load_{$sPageSlug}_{$aArguments[ 'tab_slug' ]}",
                    array( $this, 'replyToLoad' ) 
                );
                add_action(
                    "do_{$sPageSlug}_{$aArguments[ 'tab_slug' ]}",
                    array( $this, 'replyToDo' )
                );
                add_action(
                    "do_after_{$sPageSlug}_{$aArguments[ 'tab_slug' ]}",
                    array( $this, 'replyToDoAfter' )
                );
            }
            
        }   

    public function replyToDoAfter( $oFactory ) {
        $this->_doDebug( $oFactory );
    }

    public function replyToDo( $oFactory ) {
        $this->_do( $oFactory );
    }
    
    protected function _do( $oFactory ) {}
    
    
    protected function _doDebug( $oFactory ) {
        if ( ! $this->isDebugMode() ) {
            return;
        }
        echo "<h3 class='debug-title'>" . __( 'Saved Options', 'fetch-tweets' ) . "</h3>";
        echo DiscountByAnswerForEDD_AdminPageFramework_Debug::getDetails(
            $oFactory->oProp->aOptions
        );
        
    }
    
}