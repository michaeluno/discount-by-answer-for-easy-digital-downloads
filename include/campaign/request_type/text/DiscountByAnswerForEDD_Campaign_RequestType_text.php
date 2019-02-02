<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.8.0
 */
class DiscountByAnswerForEDD_Campaign_RequestType_text extends DiscountByAnswerForEDD_Campaign_RequestType_Base {

    protected $_sType          = 'text';
    protected $_sFieldSelector = '.edddba_requests_text';

    protected function _construct() {

        add_filter(
            'edddba_filter_campaign_request_answer_has_keyword_reference_text',
            array( $this, 'hasKeyword_text' ),
            10,
            5
        );
        add_filter(
            'edddba_filter_campaign_request_answer_has_keyword_reference_web',
            array( $this, 'hasKeyword_web' ),
            10,
            5
        );
        add_action( "set_up_DiscountByAnswerForEDD_Campaign_MetaBox_Requests", array( $this, 'loadFieldResources' ) );

    }

    /**
     * @param $oMetaBox
     * @callback    action  set_up_{metabox class name}
     */
    public function loadFieldResources( $oMetaBox ) {
        $_sScriptPath = $oMetaBox->oUtil->isDebugMode()
            ? dirname( __FILE__ ) . '/js/edddba-campaign-metabox.js'
            : dirname( __FILE__ ) . '/js/edddba-campaign-metabox.min.js';
        $oMetaBox->enqueueScript(
            $_sScriptPath,
            $oMetaBox->oProp->aPostTypes,    // post type slug
            array(
                'handle_id'     => 'edddbaMetaBox',
                'translation'   => array(
                    'spinnerURL' => admin_url( 'images/wpspin_light.gif' ),
                    'ajaxURL'    => admin_url( 'admin-ajax.php' ),
                    'campaignID' => $this->___getPostIDInPostEditPage(),
                    'labels'     => array(
                        'empty_string'      => __( 'The string is empty!', 'discount-by-answer-for-easy-digital-downloads' ),
                        'ajax_error'        => __( 'The Ajax request failed.', 'discount-by-answer-for-easy-digital-downloads' ),
                        'answered_keywords' => __( 'Answered Keywords for Request Field Slug', 'discount-by-answer-for-easy-digital-downloads' ),
                        'loading'           => __( 'Loading..', 'discount-by-answer-for-easy-digital-downloads' ),
                    ),
                ),
            )
        );
        new DiscountByAnswerForEDD_Campaign_RequestType_text_Event_Ajax_TestSearchKeyword;
        new DiscountByAnswerForEDD_Campaign_RequestType_text_Event_Ajax_LoadAnsweredKeywords;

    }
        private function ___getPostIDInPostEditPage() {

            if (isset($GLOBALS['post']->ID)) {
                return $GLOBALS['post']->ID;
            }
            if (isset($_GET['post'])) {
                return $_GET['post'];
            }
            if (isset($_POST['post_ID'])) {
                return $_POST['post_ID'];
            }
            return 0;
        }

    /**
     * @return  string
     */
    protected function _getLabel() {
        return __( 'Text', 'discount-by-answer-for-easy-digital-downloads' );
    }

    /**
     * @return  array
     */
    protected function _getField() {
        add_thickbox();
        return array(
            'field_id'        => 'text',
            'class'           => array(
                'fieldrow' => 'edddba_requests_text edddba_requests',   // revealer field sees this
            ),
            'content'         => array(
                array(
                    'field_id'      => 'has_acceptable_answers',
                    'type'          => 'revealer2',
                    'select_type'   => 'checkbox',
                    'label'         => array(
                        0   => __( 'There are acceptable answers.', 'discount-by-answer-for-easy-digital-downloads' ),
                    ),
                    'selectors' => array(
                        0   => '.edddba_acceptable',
                    ),
                    'default'       => array(
                        0 => true,
                    ),
                ),
                array(
                    'field_id'        => 'acceptable',
                    'title'           => __( 'Acceptable Answers', 'discount-by-answer-for-easy-digital-downloads' ),
                    'repeatable'      => true,
                    'sortable'        => true,
                    'class'           => array(
                        'fieldset' => 'edddba_acceptable',
                    ),
                    'content'         => array(
                        array(
                            'field_id'      => 'reference_type',
                            'title'         => __( 'Type', 'discount-by-answer-for-easy-digital-downloads' ),
                            'type'          => 'revealer2',
                            'select_type'   => 'select',
                            'label'         => array(
                                'web'   => __( 'Web Page', 'discount-by-answer-for-easy-digital-downloads' ),
                                'text'  => __( 'Predefined Text', 'discount-by-answer-for-easy-digital-downloads' ),
                            ),
                            'selectors'         => array(
                                'web'   => 'fieldset.edddba_url, fieldset.edddba_xpaths',
                                'text'  => '.edddba_textarea',
                            ),
                            'class'           => array(
                                'fieldset'  => 'edddba_reference_type',
                            ),
                        ),
                        array(
                            'field_id'        => 'text',
                            'type'            => 'textarea',
                            'title'           => __( 'Text', 'discount-by-answer-for-easy-digital-downloads' ),
                            'description'     => __( 'The provided keyword will be searched in this set text. If empty, any keywords will be accepted.', 'discount-by-answer-for-easy-digital-downloads' ),
                            'class'           => array(
                                'fieldset'  => 'edddba_textarea',
                            ),
                        ),
                        array(
                            'field_id'        => 'url',
                            'type'            => 'url',
                            'title'           => __( 'URL', 'discount-by-answer-for-easy-digital-downloads' ),
                            'tip'             => __( 'The target web page URLs to look for the string that the customer provides.', 'discount-by-answer-for-easy-digital-downloads' )
                                . ' e.g. https://core.trac.wordpress.org/log/trunk?action=stop_on_copy&mode=stop_on_copy&limit=100',
                            'description'     => 'e.g. <code>https://core.trac.wordpress.org/log/trunk?action=stop_on_copy&mode=stop_on_copy&limit=100</code>',
                            'class'           => array(
                                'fieldset'  => 'edddba_url edddba_full_width',
                            ),
                        ),
                        array(
                            'field_id'        => 'xpaths',
                            'type'            => 'text',
                            'title'           => __( 'Element XPaths', 'discount-by-answer-for-easy-digital-downloads' ),
                            'tip'             => __( 'The target element to search. If empty, the entire web page contents will be searched.', 'discount-by-answer-for-easy-digital-downloads' ),
                            'description'     => 'e.g. <code>//*[@class="trac-author"]</code>',
                            'repeatable'      => true,
//                            'sortable'        => true,
                            'class'           => array(
                                'fieldset'  => 'edddba_xpaths',
                            ),
                        ),
                        array(
                            'field_id'         => 'case_sensitivity',
                            'type'             => 'checkbox',
                            'title'            => __( 'Case-sensitivity', 'discount-by-answer-for-easy-digital-downloads' ),
                            'label'            => __( 'The provided keyword must be in a case-sensitive manner.', 'discount-by-answer-for-easy-digital-downloads' ),
                            // 'label_min_width'  => 0,
                            'default'          => 0,
                            'class' => array(
                                'fieldset' => 'edddba_case_sensitivity'
                            ),
                        ),
                        array(
                            'field_id'         => 'exclude',
                            'type'             => 'textarea',
                            'title'            => __( 'Excluded Keywords', 'discount-by-answer-for-easy-digital-downloads' ),
                            'description'      => __( 'The keyword listed here will be excluded from acceptable answers. Even if the customer gives one of these words, a discount code will not be issued.', 'discount-by-answer-for-easy-digital-downloads' )
                                . ' ' . __( 'Set them one per line.', 'discount-by-answer-for-easy-digital-downloads' ),
                            // 'label_min_width'  => 0,
                            'class'            => array(
                            ),
                        ),
                        array(
                            'field_id'        => '_test',
                            'type'            => 'inline_mixed',
                            'title'           => __( 'Test', 'discount-by-answer-for-easy-digital-downloads' ),
                            'save'            => false,
                            'content'         => array(
                                array(
                                    'field_id' => 'answer',
                                    'type'     => 'text',
                                    'attributes' => array(
                                        'placeholder' => __( 'Enter a keyword to test.', 'discount-by-answer-for-easy-digital-downloads' ),
                                    ),
                                    'class'    => array(
                                        'fieldset' => 'fieldset-text'
                                    ),
                                ),
                                '<span class="fieldset-button"><a class="button button-secondary button-small">'
                                    . __( 'Check', 'discount-by-answer-for-easy-digital-downloads' )
                                    . '</a></span>',
                                '<p class="test-message-container"></p>',
                            ),
                            'class'           => array(
                                'fieldset'  => 'edddba_test',
                            ),
                        ),
//                        $this->___getFields_AnswerSpecificDiscount(),
                    ),
                ),
                apply_filters(
                    'edddba_filter_campaign_text_request_field_disallow_converted_answers',
                    array(
                        'field_id'      => 'disallow_converted_answers',
                        'title'         => __( 'Disallow Converted Answers', 'discount-by-answer-pro-for-easy-digital-downloads' )
                            . ' <span class="pro-feature">('
                                . '<a href="' . esc_url( DiscountByAnswerForEDD_Registry::PRO_URL ) . '" target="_blank">'
                                    . __( 'Pro Feature', 'discount-by-answer-pro-for-easy-digital-downloads' )
                                . '</a>'
                            . ')</span>',
                        'type'          => 'checkbox',
                        'label'         => __( 'Do not allow previously used keywords of answers whose discount code is used for a purchase.', 'discount-by-answer-pro-for-easy-digital-downloads' ),
                        'description'   => __( 'This takes precedence over acceptable items.', 'discount-by-answer-pro-for-easy-digital-downloads' )
                            . ' ' . __( "Check this when you want your contributors to enter their names but not somebody else's.", 'discount-by-answer-pro-for-easy-digital-downloads' ),
                        'class'         => array(
                            'fieldset' => 'edddba_requires_pro',
                        ),
//                        'attributes'    => array(
//                            'disabled'  => 'disabled',
//                        ),
                        'default'       => 0,
                    )
                ),
                array(
                    'field_id'      => '_load_converted_answers',
                    'save'          => false,
                    'title'         => __( 'Show Converted Answers', 'discount-by-answer-pro-for-easy-digital-downloads' ),
                    'content'       => '<span class="load_converted_text_answers"><a class="button button-secondary button-small edddba_answered_keywords_open">'
                                . __( 'Load', 'discount-by-answer-for-easy-digital-downloads' )
                            . '</a></span>',
                ),
                array(
                    'field_id'    => 'placeholder',
                    'type'        => 'text',
                    'title'       => __( 'Input Label', 'discount-by-answer-for-easy-digital-downloads' )
                        . ' (' . __( 'placeholder', 'discount-by-answer-for-easy-digital-downloads' ) . ')',
                    'description' => __( 'This is shown in where the customer gives the answer.', 'discount-by-answer-for-easy-digital-downloads' ),
                    'default'     => __( 'Enter your answer.', 'discount-by-answer-for-easy-digital-downloads' ),
                    'class'           => array(
                        'fieldset'  => 'edddba_field_labels',
                    ),
                ),
            ),
        );
    }

    /**
     * @param $bValid
     * @param $aSubmit
     * @param $aRequest
     * @param $oCampaign
     *
     * @return boolean
     * @throws Exception
     */
    protected function _isValidVisitorInput( $bValid, $sEnteredKeyword, array $aRequest, DiscountByAnswerForEDD_Campaign $oCampaign, $oEvent ) {

        $_sRequestSlug   = $this->getElement( $aRequest, 'slug', '' );
        $_bHasAcceptable = $this->getElement( $aRequest, array( $this->_sType, 'has_acceptable_answers', 0 ), false );
        if ( ! $_bHasAcceptable ) {
            return $bValid;
        }

        $_aAcceptables  = $this->getElementAsArray( $aRequest, array( $this->_sType, 'acceptable' ) );
        foreach( $_aAcceptables as $_aAcceptable ) {
            if ( $this->___hasKeyword( $sEnteredKeyword, $_aAcceptable, $_sRequestSlug, $oEvent ) ) {
                return true;
            }
        }
        return false;

    }
        private function ___hasKeyword( $sKeyword, array $aAcceptable, $sRequestSlug, $oEvent ) {
            $_sType  = $this->getElement( $aAcceptable, 'reference_type', '' );
            return apply_filters(
                'edddba_filter_campaign_request_answer_has_keyword_reference_' . $_sType,
                false,
                $sKeyword,
                $aAcceptable,
                $sRequestSlug,
                $oEvent
            );
        }

    /**
     * @param   boolean $bFound Whether the keyword is found or not
     * @param   string  $sKeyword the subject keyword to search (needle)
     * @param   array   $aAcceptable the acceptable item options
     * @param   objct   $oEvent Event object
     * @callback    filter  edddba_filter_campaign_request_answer_has_keyword_reference_text
     */
    public function hasKeyword_text( $bFound, $sKeyword, $aAcceptable, $sRequestSlug, DiscountByAnswerForEDD_CheckOut_Event_Ajax_VerifyAnswers $oEvent ) {

        // If somebody else checked it an a keyword is found, no need to check more.
        if ( $bFound ) {
            return $bFound;
        }

        $_sHaystack      = trim( $this->getElement( $aAcceptable, 'text', '' ) );
        $_bCaseSensitive = ( boolean ) $this->getElement( $aAcceptable, 'case_sensitivity' );

        if ( $this->___hasExcludes( $sKeyword, $aAcceptable, $_bCaseSensitive ) ) {
            return false;
        }

        /**
         * Allowing an empty needle to allow any strings but excluded keywords
         * when the haystack is empty.
         */
        return '' === $sKeyword && '' === $_sHaystack
            ? true
            : $this->hasSubstring( $sKeyword, $_sHaystack, $_bCaseSensitive );

    }
        /**
         * Checks if excluded keywords is entered, return false
         * @param $sKeyword
         * @param array $aAcceptable
         * @param $bCaseSensitive
         *
         * @return bool
         */
        private function ___hasExcludes( $sKeyword, array $aAcceptable, $bCaseSensitive ) {
            $_aExcludes      = explode( PHP_EOL, $this->getElement( $aAcceptable, 'exclude', '' ) );
            $_bHasExcludes   = $this->hasExcludedKeyword( $sKeyword, $_aExcludes, $bCaseSensitive );
            $_bHasExcludes   = apply_filters( 'edddba_filter_campaign_request_validation_text_has_excludes', $_bHasExcludes, $sKeyword, $_aExcludes, $bCaseSensitive );
            return ( boolean ) $_bHasExcludes;
        }
        private function ___hasExcludedKeywordInHistory( $sKeyword, array $aExcludes, $bCaseSensitive ) {
return false;
        }

    /**
     * @param   boolean $bFound Whether the keyword is found or not
     * @param   string  $sKeyword the subject keyword to search (needle)
     * @param   array   $aAcceptable the acceptable item options
     * @param   object   $oEvent Event object
     * @callback    filter  edddba_filter_campaign_request_answer_has_keyword_reference_web
     */
    public function hasKeyword_web( $bFound, $sKeyword, $aAcceptable, $sRequestSlug, DiscountByAnswerForEDD_CheckOut_Event_Ajax_VerifyAnswers $oEvent ) {

        if ( $bFound ) {
            return $bFound;
        }
        $_bCaseSensitive = ( boolean ) $this->getElement( $aAcceptable, 'case_sensitivity' );

        if ( $this->___hasExcludes( $sKeyword, $aAcceptable, $_bCaseSensitive ) ) {
            return false;
        }

        // Start searching in the web page
        $_oAnswerSearch  = new DiscountByAnswerForEDD_WebStringSearch( $_bCaseSensitive );
        $_sURL           = $this->getElement( $aAcceptable, array( 'url' ), '' );
        $_aXPaths        = $this->getElementAsArray( $aAcceptable, array( 'xpaths' ) );
        $_aXPaths        = array_filter( $_aXPaths ); // drop empty values
        $_boHasString    = $_oAnswerSearch->hasString( $sKeyword, $_sURL, $_aXPaths );
        if ( is_wp_error( $_boHasString ) ) {
            $oEvent->setFieldError( $sRequestSlug, $_boHasString->get_error_message() );
            return false;
        }
        return $_boHasString;

    }


}