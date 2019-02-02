<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Handles Ajax Requests for searching user giving web-string for tests in the plugin meta box.
 *
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_Campaign_RequestType_text_Event_Ajax_TestSearchKeyword extends DiscountByAnswerForEDD_Event_Ajax_Base {

    protected $_sActionName = 'edddba_search_answer_check_test';
    protected $_bAllowGuests = false;

    /**
     * @remark  Based on `edd_ajax_apply_discount()`.
     * @since   0.1.0
     */
    public function doAction() {

        $_aReturn     = array(
            'message'      => __( 'The keyword was found!', 'discount-by-answer-for-easy-digital-downloads' ),
            'code'         => 0,
            'field_errors' => array(),
        );
        try {

            if ( ! $this->_shouldProceed() ) {
                throw new Exception(
                    __( 'Invalid request. Missing arguments.', 'discount-by-answer-for-easy-digital-downloads' ),
                    10
                );
            }
            $_oUtil          = new DiscountByAnswerForEDD_Campaign_RequestTypes_Utility;

            $_bCaseSensitive = ( boolean ) $this->getElement( $_POST, array( 'caseSensitive' ) );
            $_sKeyword       = trim( $_POST[ 'answer' ] );

            $_aExcludes      = explode( PHP_EOL, $this->getElement( $_POST, 'exclude', '' ) );
            $_bHasExcludes   = $_oUtil->hasExcludedKeyword( $_sKeyword, $_aExcludes, $_bCaseSensitive );
            $_bHasExcludes   = apply_filters( 'edddba_filter_campaign_request_validation_text_has_excludes', $_bHasExcludes, $_sKeyword, $_aExcludes, $_bCaseSensitive );
            if ( $_bHasExcludes ) {
                throw new Exception(
                    __( 'Has excluding keywords.', 'discount-by-answer-for-easy-digital-downloads' ),
                    11
                );
            }
            $_aSearchAreas   = $this->getElementAsArray( $_POST, 'searchAreas' );

            // Check field errors.
            $_aFieldErrors = $this->___getFieldErrors( $_aSearchAreas );
            if ( ! empty( $_aFieldErrors ) ) {
                $_aReturn[ 'field_errors' ] = $_aFieldErrors;
                throw new Exception(
                    __( 'Some inputs are not valid. Please check again.', 'discount-by-answer-for-easy-digital-downloads' ),
                    20
                );
            }
            if ( ! $this->_hasAnswer( $_sKeyword, $_aSearchAreas, $_bCaseSensitive ) ) {
                throw new Exception(
                    __( 'The keyword could not be found.', 'discount-by-answer-for-easy-digital-downloads' ),
                    30
                );
            }


        } catch ( Exception $_oException ) {
            $_aReturn[ 'message' ] = $_oException->getMessage();
            $_aReturn[ 'code' ]    = $_oException->getCode();
        }
        exit( json_encode( $_aReturn ) );

    }

        /**
         * @param array $aSearche
         * @return array
         */
        private function ___getFieldErrors( array $aSearchAreas ) {

            $_aFieldErrors = array();
            $_oDoc    = new DOMDocument;
            $_oXPath  = new DOMXPath( $_oDoc );
            foreach( $aSearchAreas as $_iIndex => $_aSearch ) {
                $_sType = $this->getElement( $_aSearch, array( 'type' ), '' );
                if ( 'text' === $_sType ) {
                    $_sID   = $this->getElement( $_aSearch, array( 'text', 'id' ), '' );
                    $_sText = $this->getElement( $_aSearch, array( 'text', 'value' ), '' );
                    if ( empty( $_sText ) ) {
                        $_aFieldErrors[ $_sID ] = __( 'The text is empty.', 'discount-by-answer-for-easy-digital-downloads'  );
                    }
                    continue;
                }
                // URL
                $_sID  = $this->getElement( $_aSearch, array( 'url', 'id' ), '' );
                $_sURL = $this->getElement( $_aSearch, array( 'url', 'value' ), '' );
                $_sURL = trim( $_sURL );
                if ( ! filter_var( $_sURL, FILTER_VALIDATE_URL ) ) {
                    $_aFieldErrors[ $_sID ] = __( 'The URL is not valid.', 'discount-by-answer-for-easy-digital-downloads'  );
                }
                // XPath
                $_aXPaths = $this->getElementAsArray( $_aSearch, array( 'xpaths' ) );
                foreach( $_aXPaths as $_iIndexXPath => $_aXPath ) {
                    $_sIDXpath = $this->getElement( $_aXPath, array( 'id' ), '' );
                    $_sXPath   = $this->getElement( $_aXPath, array( 'value' ), '' );
                    $_sXPath   = trim( $_sXPath );
                    $_sXPath   = str_replace( '\\', '', $_sXPath );
                    if ( empty( $_sXPath ) ) {
                        continue;
                    }
                    if ( false === @$_oXPath->query( $_sXPath ) ) {
                        $_aFieldErrors[ $_sIDXpath ] = __( 'The XPath is not valid.', 'discount-by-answer-for-easy-digital-downloads'  );
                    }
                }
            }
            return $_aFieldErrors;
        }

    /**
      * @param $sAnswer
      * @param array $aSearches
      *
      * @return bool
      * @throws Exception
      */
    protected function _hasAnswer( $sAnswer, array $aSearchAreas, $bCaseSensitive ) {

        $_oAnswerSearch = new DiscountByAnswerForEDD_WebStringSearch( $bCaseSensitive );
        foreach( $aSearchAreas as $_iIndex => $_aSearch ) {
            $_sAreaType  = $this->getElement( $_aSearch, array( 'type' ), '' );
            if ( 'web'=== $_sAreaType ) {
                $_sURL       = $this->getElement( $_aSearch, array( 'url', 'value' ), '' );
                $_aXPaths    = wp_list_pluck( $this->getElementAsArray( $_aSearch, array( 'xpaths' ) ), 'value' );
                $_aXPaths    = array_filter( $_aXPaths ); // drop empty values
                $_boHasString = $_oAnswerSearch->hasString( $sAnswer, $_sURL, $_aXPaths );
                if ( is_wp_error( $_boHasString ) ) {
                    throw new Exception(
                        $_boHasString->get_error_message() . ' url: ' . $_sURL,
                        40
                    );
                }
                if ( $_boHasString ) {
                    return true;
                }
                continue;
            }
            if ( 'text' === $_sAreaType ) {
                $_sText      = $this->getElement( $_aSearch, array( 'text', 'value' ), '' );
                if ( $this->hasSubstring( $sAnswer, $_sText, $bCaseSensitive ) ) {
                    return true;
                }
                continue;
            }

        }
        return false;
    }

    /**
     * @return  boolean
     */
    protected function _shouldProceed() {
        if ( ! isset( $_POST[ 'answer' ], $_POST[ 'searchAreas' ] ) ) {
            return false;
        }
        if ( ! $_POST[ 'searchAreas' ] ) {
            return false;
        }
        return true;
    }

}
