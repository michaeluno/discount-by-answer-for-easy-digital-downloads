<?php
/**
 * Created by PhpStorm.
 * User: Internet
 * Date: 1/20/2019
 * Time: 5:39 AM
 */

/**
 * Handles Ajax Requests for searching user giving web-string.
 *
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_CheckOut_Event_Ajax_VerifyAnswers extends DiscountByAnswerForEDD_Event_Ajax_Base {

    /**
     * Stores field errors when a request field input is not acceptable to show it in front-end.
     * @var array
     */
    private $___aFieldErrors = array();

    /**
     * Stores an issued discount code.
     * @var string
     */
    private $___sDiscountCode = '';

    protected $_sActionName = 'edddba_search_answer';
    protected $_bAllowGuests = true;

    /**
     * @remark  Based on `edd_ajax_apply_discount()`.
     * @since   1.0.0
     */
    public function doAction() {

        parse_str( $_POST[ 'form' ], $_aCartForm );
        $_aReturn     = array(
            'message'  => 'This is a test message and should not be displayed.',
            'code'     => 0,
        );
        try {

            if ( ! $this->_shouldProceed() ) {
                throw new Exception(
                    __( 'Invalid request. Missing arguments.', 'discount-by-answer-for-easy-digital-downloads' ),
                    10
                );
            }

            $_iCampaignID    = $this->getElement( $_POST, 'campaignID' );
            $_oCampaign      = new DiscountByAnswerForEDD_Campaign( $_iCampaignID );
            if ( ! $_oCampaign->isActive() || ! $_oCampaign->get( array( '_edddba_discount', 'base_discount_code', 'value' ) ) ) {
                throw new Exception(
                    __( 'The campaign data could not be properly retrieved.', 'discount-by-answer-for-easy-digital-downloads' ),
                    40
                );
            }
            $_aAnswers = $this->getElementAsArray( $_aCartForm, array( 'edddba', $_iCampaignID ) );
            $this->___checkSubmission( $_oCampaign, $_aAnswers );

            // At this point. the customer's submitting answers were accepted.
            add_action( 'edddba_action_campaign_issued_discount_code', array( $this, 'setIssuedDiscountCode' ) );
            do_action( 'edddba_action_checkout_verified_answers', $_oCampaign, $_aAnswers );

            // All set.
            $_aReturn[ 'message' ] = $this->___getThanksMessage( $_oCampaign, $this->___sDiscountCode );

            remove_action( 'edddba_action_campaign_issued_discount_code', array( $this, 'setIssuedDiscountCode' ) );

            do_action( 'edddba_action_checkout_all_set', $_oCampaign, $_aAnswers );

        } catch ( Exception $_oException ) {
            $_aReturn[ 'message' ]      = $_oException->getMessage();
            $_aReturn[ 'code' ]         = $_oException->getCode();
            $_aReturn[ 'field_errors' ] = $this->___aFieldErrors;
        }

        echo json_encode( $_aReturn );
        edd_die();

    }
        /**
         * @since   0.9.0
         */
        private function ___getThanksMessage( DiscountByAnswerForEDD_Campaign $oCampaign, $sDiscountCode ) {

            // Get the options
            $_iSize           = ( integer ) $oCampaign->getDiscount( array( 'lifespan', 'size' ) );
            $_iDiscountID     = ( integer ) edd_get_discount_id_by_code( $sDiscountCode );

            // Get the expiration time
            $_sExpirationTime = get_post_meta( $_iDiscountID, '_edd_discount_expiration', true );
            $_iExpiration     = strtotime( $_sExpirationTime );
            $_sExpirationTime = date(get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $_iExpiration );

            // Get the time zone string
            $_iGMTOffset      = get_option( 'gmt_offset' );
            $_sGMTOffset      = $_iGMTOffset < 0
                ? 'UTC'  . $_iGMTOffset
                : 'UTC+' . $_iGMTOffset;
            $_sTimeZone       = get_option( 'timezone_string' );
            $_sTimeZone       = $_sTimeZone
                ? $_sTimeZone
                : $_sGMTOffset;

            // Output
            return str_replace(
                array( '%code%', '%timescale%', '%time%' ),
                array(
                    $sDiscountCode,
                    $_iSize,
                    $_sExpirationTime . ' ' . $_sTimeZone ),
                $oCampaign->getLabel( 'thanks' )
            );

        }

        /**
         * @callback
         */
        public function setIssuedDiscountCode( $sDiscountCode ) {
            $this->___sDiscountCode = $sDiscountCode;
        }

        /**
         * @param array $aCampaignFormData
         * @remark  At the moment, the only request type that requires certain expected answers is `text`.
         * @throws Exception
         * @remark
         *   might be necessary to sanitize inputs
         *   sanitize_text_field()
         *   html_entity_decode( edd_currency_filter( edd_format_amount( $total ) ), ENT_COMPAT, 'UTF-8' ),
         *   $user = urldecode( $form['edd_email'] );
         */
        private function ___checkSubmission( DiscountByAnswerForEDD_Campaign $oCampaign, array $aSubmit ) {
            $_aRequests     = $oCampaign->getRequests();
            $_aErrors       = array();
            foreach( $_aRequests as $_iIndex => $_aRequest ) {
                $_sType        = $this->getElement( $_aRequest, 'type', '' );
                $_sSlug        = $this->getElement( $_aRequest, 'slug', '' );
                $_aTypeRequest = $this->getElementAsArray( $_aRequest, $_sType );
                $_mTypeSubmit  = $this->getElement( $aSubmit, $_sSlug );
                $_bValid       = ( boolean ) apply_filters( 'edddba_is_valid_campaign_request_' . $_sType, true, $_mTypeSubmit, $_aRequest, $oCampaign, $this );
                if ( ! $_bValid ) {
                    $_sFieldErrorMessage = $this->getElement( $_aTypeRequest, 'field_error_message', '' );
                    $_aErrors[ $_sSlug ] = $_sFieldErrorMessage;
                }
            }
            if ( ! empty( $_aErrors ) ) {
                $this->setFieldErrors( $_aErrors );
                throw new Exception(
                    $oCampaign->getLabel( 'error' ),
                    30 + $_iIndex
                );
            }
        }

    /**
     * Sets field errors.
     * ```
     * array(
     *      request slug a => error message,
     *      request slug b => error message,
     *      ...
     * )
     * ```
     * @remark this is for each request type component to set field errors within its callback.
     * @param array $aErrors
     */
    public function setFieldErrors( array $aErrors ) {
         $this->___aFieldErrors = $this->___aFieldErrors + $aErrors;
    }
    public function setFieldError( $sRequestSlug, $sMessage ) {
        $this->___aFieldErrors[ $sRequestSlug ] = $sMessage;
    }

    /**
     * @return  boolean
     * @todo    if no class extends this class, make this private
     */
    protected function _shouldProceed() {
        if ( ! isset( $_POST[ 'form' ], $_POST[ 'campaignID' ] ) ) {
            return false;
        }
        return true;
    }

}