<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.3.0
 */
class DiscountByAnswerForEDD_Campaign_MetaBox_Requests extends DiscountByAnswerForEDD_Campaign_MetaBox_Base {

    protected $_sSectionID    = '_edddba_requests';
    protected $_sSectionClass = 'DiscountByAnswerForEDD_FormElement_Section_Requests';
    protected $_sFieldsClass  = 'DiscountByAnswerForEDD_FormElement_Fields_Requests';
    protected $_aSection      = array();

    /**
     *
     */
    public function validate( $aInputs, $aOldInputs, $oFactory ) {

        $aInputs    = $this->oUtil->getAsArray( $aInputs );
        $_aRequests = $this->oUtil->getElementAsArray( $aInputs, '_edddba_requests' );
        $_aErrors   = array();

        try {
            // Slugs must be filled.
            $_aRequests = $this->___getRequestSlugsFilled( $_aRequests );
            $_aRequests = $this->___getItemSlugsFilled( $_aRequests );

            $_aRequests = $this->___getUnusedOptionsDropped( $_aRequests );

        } catch ( Exception $_oException ) {

            // An invalid value is found. Set a field error array and an admin notice and return the old values.
            $oFactory->setFieldErrors( $_aErrors );
            $oFactory->setSettingNotice( $_oException->getMessage() );
            return $aOldInputs;

        }
        $aInputs[ '_edddba_requests' ] = $_aRequests;
        return $aInputs;

    }
        /**
         *  Drops irrelevant type options.
         * @param $aRequests
         */
        private function ___getUnusedOptionsDropped( array $aRequests ) {

            $_aAddedRequestTypes = apply_filters( 'edddba_filter_campaign_request_type_slugs', array() );
            foreach( $aRequests as $_iIndex => $_aRequest ) {
                $_sRequestType = $this->oUtil->getElement( $_aRequest, 'type', '' );
                if ( ! $_sRequestType ) {
                    continue;
                }
                $_aUnsetTypes  = array_diff( $_aAddedRequestTypes, array( $_sRequestType ) );
                foreach( $_aUnsetTypes as $_sType ) {
                    unset( $_aRequest[ $_sType ] );
                }
                $aRequests[ $_iIndex ] = $_aRequest;
            }
            return $aRequests;

        }

        private function ___getItemSlugsFilled( array $aRequests ) {

            foreach( $aRequests as $_iIndex => $_aRequest ) {
                // Select, radio, 
                $_aRequest   = $this->___getItemSlugsFilledByType( $_aRequest, 'select' );
                $_aRequest   = $this->___getItemSlugsFilledByType( $_aRequest, 'radio' );
                $aRequests[ $_iIndex ]   = $_aRequest;
            }
            return $aRequests;
        }
            /**
             * Select/Radio
                [select/radio] => Array (
                    [items] => Array (
                            [0] => Array (
                                [slug] => (string, length: 0)
                                [label] => (string, length: 8) Option A
                            )
                    )
                )
             */
            private function ___getItemSlugsFilledByType( array $aRequest, $sType ) {

                $_aSlugs        = array();
                $_aExpectations = $this->oUtil->getElementAsArray( $aRequest, array( $sType, 'items' ) );
                foreach( $_aExpectations as $_iIndex => $_aExpectation ) {
                    $_sSlug = $this->oUtil->getElement( $_aExpectation, array( 'slug' ), '' );
                    if ( ! $_sSlug ) {
                        $_sSlug = 'item_1';
                        $_aExpectations[ $_iIndex ][ 'slug' ] = $_sSlug;
                    }
                    $_aSlugs[ $_iIndex ] = $_sSlug;
                }

                $_oDuplicateRenamer = new DiscountByAnswerForEDD_DuplicateRenamer( $_aSlugs );
                $_aSlugs = $_oDuplicateRenamer->get();
                foreach( $_aSlugs as $_iIndex => $_sSlug ) {
                    $_aExpectations[ $_iIndex ][ 'slug' ] = $_sSlug;
                }

                $this->oUtil->setMultiDimensionalArray(
                    $aRequest,
                    array( $sType, 'items' ), $_aExpectations );
                return $aRequest;

            }

        /**
         * @param array $_aRequests
         * @return array
         */
        private function ___getRequestSlugsFilled( array $aRequests ) {

            // Make sure no empty values for a slug
            $_aSlugs = array();
            foreach( $aRequests as $_iIndex => $_aRequest ) {
                $_sType = $this->oUtil->getElement( $_aRequest, array( 'type' ), ''  );
                $_sSlug = $this->oUtil->getElement( $_aRequest, array( 'slug' ), ''  );
                if ( ! $_sSlug ) {
                    $_sSlug = $_sType . '_1';
                    $_aRequests[ $_iIndex ] = $_sSlug;
                }
                $_aSlugs[ $_iIndex ] = $_sSlug;
            }

            $_oDuplicateRenamer = new DiscountByAnswerForEDD_DuplicateRenamer( $_aSlugs );
            $_aSlugs = $_oDuplicateRenamer->get();

            // Reassign them
            foreach( $_aSlugs as $_iIndex => $_sSlug ) {
                $aRequests[ $_iIndex ][ 'slug' ] = $_sSlug;
            }

            return $aRequests;

        }


}