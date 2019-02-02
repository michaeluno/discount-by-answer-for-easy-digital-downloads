<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Loads resource files for the checkout form field component.
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_CheckOut_Fieldset_Resources extends DiscountByAnswerForEDD_PluginUtility {

    private $___sComponentDirPath = '';

    public function __construct() {
        $this->___sComponentDirPath = DiscountByAnswerForEDD_CheckOut_Loader::$sDirPath;
        add_action( 'wp_enqueue_scripts', array( $this, 'replyToAssResources' ) );
    }

    /**
     * @callback    action wp_enqueue_scripts
     */
    public function replyToAssResources() {
        $this->___addScripts();
        $this->___addStyles();
    }
        private function ___addScripts() {
            $_sPath     = $this->___sComponentDirPath . "/js/answer-check-field.js";
            $_sMinPath  = $this->___sComponentDirPath . "/js/answer-check-field.min.js";
            $_sPath     = ! $this->isDebugMode() && file_exists( $_sMinPath )
                ? $_sMinPath
                : $_sPath;
            $_sURL      = $this->getSRCFromPath( $_sPath );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script(
                'answer-check-field',
                $_sURL,     // src
                array( 'jquery' ),   // dependencies
                '',    // version number
                true    // insert in footer
            );
            wp_localize_script(
                'answer-check-field',
                'edddbaRequests',
                array(
                    'labels'            => array(
                        'missingRequired'     => __( 'Some required fields are missing.', 'discount-by-answer-for-easy-digital-downloads' ),
                    ),
                )
            );
        }
        /**
         * @remark  based on `edd_load_head_styles()`
         */
        private function ___addStyles() {
            if ( ! $this->___shouldLoadStyles() ) {
                return;
            }
            $_sPath    = $this->___sComponentDirPath . "/css/answer-check-field.css";
            $_sMinPath = $this->___sComponentDirPath . "/css/answer-check-field.min.css";
            $_sPath    = ! $this->isDebugMode() && file_exists( $_sMinPath )
                ? $_sMinPath
                : $_sPath;
            $_sURL     = $this->getSRCFromPath( $_sPath );
            wp_register_style( "answer-check-field-style", $_sURL );
            wp_enqueue_style( "answer-check-field-style" );
        }
            private function ___shouldLoadStyles() {
                if ( ! is_object( $GLOBALS[ 'post' ] ) ) {
                    return false;
                }
                if ( ! function_exists( 'edd_get_option' ) ) {
                    return false;
                }
               	if ( edd_get_option( 'disable_styles', false )  ) {
               		return false;
               	}
               	return true;
            }

}