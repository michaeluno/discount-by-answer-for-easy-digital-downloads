<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_WebStringSearch extends DiscountByAnswerForEDD_PluginUtility {

    private $___bCaseSensitive = false;

    public function __construct( $bCaseSensitive=false ) {
        $this->___bCaseSensitive = $bCaseSensitive;
    }

    /**
     * @return  boolean|WP_Error
     */
    public function hasString( $sNeedle, $sURL, array $aXPaths ) {

        $_sHaystack  = '';
        $_oaResponse = wp_remote_get(
            $sURL,
            array( 'timeout' => 10, 'httpversion' => '1.1' )
        );
        if ( is_wp_error( $_oaResponse ) ) {
            return $_oaResponse;
        }
        $_sHTTPBody = wp_remote_retrieve_body( $_oaResponse );
        $_sHTTPBody = function_exists( 'mb_convert_encoding' )
            ? mb_convert_encoding( $_sHTTPBody, "HTML-ENTITIES", 'auto' )
            : $_sHTTPBody;

        // set error level
        $_bInternalErrors = libxml_use_internal_errors(true );
        $_oDoc       = new DOMDocument( '1.0', get_bloginfo( 'charset' ) );

        $_oDoc->loadHTML( $_sHTTPBody );
        $_nodeBodies = $_oDoc->getElementsByTagName('body' );
        if ( $_nodeBodies && 0 < $_nodeBodies->length ) {
            $_nodeBody  = $_nodeBodies->item( 0 );
            $_sHaystack = $_nodeBody->textContent;
        }

        // Restore error level
        libxml_use_internal_errors( $_bInternalErrors );

        // If XPath pattern is not given, search from the entire body
        if ( empty( $aXPaths ) ) {
            return $this->hasSubstring( $sNeedle, $_sHaystack, $this->___bCaseSensitive );
        }
        // Otherwise, check with each element found with the xpath
        $_oWPError = null;
        foreach( $aXPaths as $_iIndex => $_sXPath ) {
            $_sXPath  = str_replace( '\\', '', $_sXPath );
            $_oXPath  = new DOMXPath( $_oDoc );
            $_oNodes  = @$_oXPath->query( $_sXPath );
            if ( false === $_oNodes ) {
                $_oWPError = new WP_Error(
                    100,    // @todo create a list of plugin error code
                    __( 'XPath is not valid.', 'task-scheduler' )
                );
            }
            foreach( $_oNodes as $_oNode ) {
                if ( $this->hasSubstring( $sNeedle, $_oNode->textContent, $this->___bCaseSensitive ) ) {
                    return true;
                }
            }
        }
        if ( is_wp_error( $_oWPError ) ) {
            return $_oWPError;
        }
        return false;

    }

        /**
         * @param DOMNode $element
         *
         * @return string
         * @deprecated
         */
        private function ___getDOMInnerHTML( DOMNode $element ) {
            $innerHTML = "";
            $children  = $element->childNodes;

            foreach ($children as $child)
            {
                $innerHTML .= $element->ownerDocument->saveHTML($child);
            }

            return $innerHTML;
        }

}