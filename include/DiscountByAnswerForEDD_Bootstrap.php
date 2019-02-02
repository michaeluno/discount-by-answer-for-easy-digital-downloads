<?php
/**
 * Discount by Answer for Easy Digital Downloads
 * 
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 * 
 */

/**
 * Loads the plugin.
 * 
 * @since       0.0.1
 */
final class DiscountByAnswerForEDD_Bootstrap extends DiscountByAnswerForEDD_AdminPageFramework_PluginBootstrap {
    
    /**
     * User constructor.
     */
    protected function construct()  {}        

    /**
     * Register classes to be auto-loaded.
     * 
     * @since    0.0.1
     */
    public function getClasses() {
        
        // Include the include lists. The including file reassigns the list(array) to the $_aClassFiles variable.
        $_aClassFiles   = array();
        $_bLoaded       = include( dirname( $this->sFilePath ) . '/include/class-list.php' );
        if ( ! $_bLoaded ) {
            return $_aClassFiles;
        }
        return $_aClassFiles;
                
    }

    /**
     * Sets up constants.
     */
    public function setConstants() {
    }    
    
    /**
     * Sets up global variables.
     */
    public function setGlobals() {
    }    
    
    /**
     * The plugin activation callback method.
     */    
    public function replyToPluginActivation() {

        $this->_checkRequirements();

        DiscountByAnswerForEDD_PluginUtility::schedulePeriodicEvent(
            'edddba_action_clean_unused_discount_codes_daily'
        );

    }
        
        /**
         * 
         * @since            3
         */
        private function _checkRequirements() {

            $_oRequirementCheck = new DiscountByAnswerForEDD_AdminPageFramework_Requirement(
                DiscountByAnswerForEDD_Registry::$aRequirements,
                DiscountByAnswerForEDD_Registry::NAME
            );
            
            if ( $_oRequirementCheck->check() ) {            
                $_oRequirementCheck->deactivatePlugin( 
                    $this->sFilePath, 
                    __( 'Deactivating the plugin', 'discount-by-answer-for-easy-digital-downloads' ),  // additional message
                    true    // is in the activation hook. This will exit the script.
                );
            }        
             
        }    

        
    /**
     * The plugin activation callback method.
     */    
    public function replyToPluginDeactivation() {
        
        DiscountByAnswerForEDD_WPUtility::cleanTransients(
            array(
                DiscountByAnswerForEDD_Registry::TRANSIENT_PREFIX,
                'apf_',
            )
        );
        
    }        
    
        
    /**
     * Load localization files.
     * 
     * @callback    action      init
     */
    public function setLocalization() {
        
        // This plugin does not have messages to be displayed in the front-end.
        if ( ! $this->bIsAdmin ) { 
            return; 
        }
        
        load_plugin_textdomain( 
            DiscountByAnswerForEDD_Registry::TEXT_DOMAIN,
            false, 
            dirname( plugin_basename( $this->sFilePath ) ) . '/' . DiscountByAnswerForEDD_Registry::TEXT_DOMAIN_PATH
        );
        
    }        
    
    /**
     * Loads the plugin specific components. 
     * 
     * @remark        All the necessary classes should have been already loaded.
     */
    public function setUp() {
        
        // This constant is set when `uninstall.php` is loaded.
        if ( defined( 'DOING_PLUGIN_UNINSTALL' ) && DOING_PLUGIN_UNINSTALL ) {
            return;
        }

        if ( ! function_exists( 'EDD' ) ) {
            return;
        }

        // Option Object - must be done before the template object.
        // The initial instantiation will handle formatting options.
        DiscountByAnswerForEDD_Option::getInstance();

        // Both front-end and back-end
        new DiscountByAnswerForEDD_CheckOut_Loader;
        new DiscountByAnswerForEDD_Campaign_Loader;
        new DiscountByAnswerForEDD_Answer_Loader;


    }

}