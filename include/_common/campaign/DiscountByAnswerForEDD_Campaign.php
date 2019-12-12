<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Creates campaign objects to deal with campaign data.
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_Campaign extends DiscountByAnswerForEDD_PluginUtility {

    public $iID = 0;
    public $aMeta = array();

    public function __construct( $iID ) {
        $this->iID = $iID;
        $this->aMeta[ 'id' ] = $iID;
    }

    /**
     * Issues a new discount code
     * @return  integer|WP_Error     The newly created discount code ID. O for failure.
     * @todo complete this method.
     * @remark  The structure of EDD discount post meta.
     * ```
     *  [_edd_discount_code] => 100PERCENT
     *  [_edd_discount_name] => 100 Percent
     *  [_edd_discount_status] => active
     *  [_edd_discount_uses] => 0
     *  [_edd_discount_max_uses] => 0
     *  [_edd_discount_amount] => 100
     *  [_edd_discount_start] => 01/11/2019 00:00:00
     *  [_edd_discount_expiration] => 01/31/2019 23:59:59
     *  [_edd_discount_type] => percent
     *  [_edd_discount_min_price] => 0
     *  [_edd_discount_product_reqs] => Array( [0] => 0 )
     *  [_edd_discount_product_condition] => all
     *  [_edd_discount_excluded_products] => Array( [0] => 13 )
     *  [_edd_discount_is_not_global] => 0
     *  [_edd_discount_is_single_use] => 1
     * ```
     * The argument structure for `edd_store_discount()`
     * ```
     *  'code'              =>  '',
     *  'name'              =>  '',
     *  'status'            =>  'active',
     *  'uses'              =>  '',
     *  'max_uses'          =>  '',
     *  'amount'            =>  '',
     *  'start'             =>  '',
     *  'expiration'        =>  '',
     *  'type'              =>  '',
     *  'min_price'         =>  '',
     *  'product_reqs'      =>  array(),
     *  'product_condition' =>  '',
     *  'excluded_products' =>  array(),
     *  'is_not_global'     =>  false,
     *  'is_single_use'     =>  false,
     * ```
     * @prarm   array   $aMeta EDD discount specific meta to override. (not the raw post meta keys)
     * @remark  some keys do not correspond to post meta keys
     * @see EDD_Discount::build_meta() method
     * @return integer The discount ID of the discount code, 0 on failure.
    */
    public function addDiscountCode( array $aMeta=array() ) {

        $_iBaseDiscountCode = ( integer ) $this->getDiscount( array( 'base_discount_code', 'value' ), 0 );
        if ( ! $_iBaseDiscountCode ) {
            return $_iBaseDiscountCode;
        }

        $_aBaseMeta     = $this->___getBaseDiscountMeta( $_iBaseDiscountCode );
        $_iExpiryTime   = $this->___getExpiryTime();
        $_sExpiryTime   = date( 'm/d/Y H:i:s', $_iExpiryTime );
        $_aMeta         = $aMeta + array(
            'name'                 => get_the_title( $this->iID ),
            // 'max_uses'             => 1, // inherit from base
            'uses'                 => '',
            'start'                => date( 'm/d/Y H:i:s', current_time( 'timestamp', 1 ) ),
            'status'               => 'active',
            'code'                 => $this->___getDiscountCodeName( $_iExpiryTime ),
            'expiration'           => $_sExpiryTime,
            'is_single_use'        => true,
            // Some required keys do not correspond to the post meta keys
            'max'                  => $this->getElement( $_aBaseMeta, 'max_uses' ),
            'products'             => $this->getElement( $_aBaseMeta, 'product_reqs' ),
            'excluded-products'    => $this->getElement( $_aBaseMeta, 'excluded_products' ),
            'not_global'           => $this->getElement( $_aBaseMeta, 'is_not_global' ),
            'use_once'             => $this->getElement( $_aBaseMeta, 'is_single_use' ),

        ) + $_aBaseMeta;

        $_biDiscountID = edd_store_discount( $_aMeta );
        if ( ! $_biDiscountID ) {
            return 0;
        }

        // Store plugin specific options.
        update_post_meta( $_biDiscountID, '_edddba_discount_auto_delete', $this->getDiscount( 'auto_delete', true ) );
        update_post_meta( $_biDiscountID, '_edddba_campaign_id', $this->get( 'id' ) ); // referred when a discount is used to update the campaign usage count and also validate campaign discount codes
        wp_update_post(
            array(
                'ID'          => $_biDiscountID,
                'post_parent' => $this->get( 'id' ),
            )
        );

        // Fix - somehow EDD changes the date to the very last second of the day
        update_post_meta( $_biDiscountID, '_edd_discount_expiration', $_sExpiryTime );

        return $_biDiscountID;

    }
        /**
         * @param $iExpiryTime
         * @sicne   0.9.0
         * @return  string
         */
        private function ___getDiscountCodeName( $iExpiryTime ) {
            $_sPrefix         = $this->getDiscount( 'prefix', '' );
            return strtoupper( uniqid( $_sPrefix ) );
        }
        /**
         * @param $iDiscountID
         *
         * @return array
         */
        private function ___getBaseDiscountMeta( $iDiscountID ) {
            $_aMeta = array();
            foreach( $this->getPostMeta( $iDiscountID ) as $_sKey => $_mValue ) {
                $_sDiscountKey = $this->getPrefixRemoved( $_sKey, '_edd_discount_' );
                $_aMeta[ $_sDiscountKey ] = $_mValue;
            }
            return $_aMeta;
        }
        /**
         * @return false|string the date and time in m/d/Y H:i: format.
         * @return  integer
         */
        private function ___getExpiryTime() {
            $_iTimeStamp  = current_time( 'timestamp', 1 );
            $_iLifespan   = ( integer ) $this->get( array( '_edddba_discount', 'lifespan', 'size' ), 3 )
                * ( integer ) $this->get( array( '_edddba_discount', 'lifespan', 'unit' ), 3 );
            return ( integer )  $_iTimeStamp + $_iLifespan;
        }

    /**
     * Checks to see if a campaign has uses left.
     * @return  boolean
     */
    public function isMaxedOut() {
        $_iMax = ( integer ) $this->get( '_edddba_campaign_maximum_uses' );
        if ( ! $_iMax ) {
            return false;
        }
        $_iUseCount   = ( integer ) $this->get( '_edddba_campaign_discount_use_count' );
        return $_iMax <= $_iUseCount;
    }


    /**
     * Checks whether the downloads currently added to the cart (in the session) is associated with the campaign.
     * @return  boolean
     */
    public function isAssociatedWithCartItems() {
        return edd_discount_product_reqs_met(
            ( integer ) $this->getDiscount( array( 'base_discount_code', 'value' ), 0 ),    // discount code id
            false
        );
    }

    /**
     * @return bool
     */
    public function isActive() {
        return ( boolean ) $this->get( '_edddba_campaign_status' );
    }

    /**
     * Retrieves `discount` related options.
     * @return  mixed
     */
    public function getDiscount( $asKey, $sDefault='' ) {
        $_aKeys = $this->getAsArray( $asKey );
        array_unshift($_aKeys,'_edddba_discount' );
        return $this->get( $_aKeys, $sDefault );
    }

    /**
     * Retrieves the stored requests options.
     * @param   string $sType       The request type.
     * @return  array   An array holding stored request options.
     * If a type is given with the `$sType` parameter, it will find the request options of the type. It can be multiple of them.
     */
    public function getRequests( $sType='' ) {
        $_aRequests = $this->getArray( '_edddba_requests' );
        if ( '' === $sType ) {
            return $_aRequests;
        }
        return $this->___getRequestOptionsByType( $sType, $_aRequests );
    }
        /**
         * @param $sType
         * @param array $aRequests
         * @return array
         * @since   0.9.0
         */
        private function ___getRequestOptionsByType( $sType, array $aRequests ) {
            $_aTypeRequests = array();
            foreach( $aRequests as $_iIndex => $_aRequest ) {
                $_sThisType = $this->getElement( $_aRequest, 'type' );
                if ( $_sThisType !== $sType ) {
                    continue;
                }
                $_aTypeRequests[ $_iIndex ] = $_aRequest;
            }
            return $_aTypeRequests;
        }

//    public function getRequestBySlug() {
//
//    }

    /**
     * @param $asKey
     *
     * @return string
     */
    public function getLabel( $asKey, $sDefault='' ) {
        $_aKeys = $this->getAsArray( $asKey );
        array_unshift($_aKeys,'_edddba_labels' );
        return $this->get( $_aKeys, $sDefault );
    }

    /**
     * @param $asKey
     *
     * @return string
     */
    public function getReport( $asKey, $sDefault='' ) {
        $_aKeys = $this->getAsArray( $asKey );
        array_unshift($_aKeys,'_edddba_report' );
        return $this->get( $_aKeys, $sDefault );
    }

    /**
     * Returns meta values
     * @param $asKey
     *
     * @return mixed
     */
    public function get( $asKey, $mDefault=null ) {

        if ( is_scalar( $asKey ) ) {
            $sKey = $asKey;
            if ( isset( $this->aMeta[ $sKey ] ) ) {
                return $this->aMeta[ $sKey ];
            }
            $_mValue   = get_post_meta( $this->iID, $sKey, true );
            $_mDefault = DiscountByAnswerForEDD_Campaign_Defaults::getDefaults( $sKey );
            if ( ! is_null( $_mDefault ) ) {
                if ( is_array( $_mDefault ) ) {
                    $this->aMeta[ $sKey ] = $this->uniteArrays( $this->getAsArray( $_mValue ), $_mDefault );
                    return $this->aMeta[ $sKey ];
                }
                if ( is_null( $_mValue ) ) {
                    $this->aMeta[ $sKey ] = $_mDefault;
                    return $this->aMeta[ $sKey ];
                }
            }
            $this->aMeta[ $sKey ] = $_mValue;
            return $this->aMeta[ $sKey ];
        }

        if ( is_array( $asKey ) ) {
            $_sFirstDimension = array_shift($asKey );
            $_aMeta = $this->get( $_sFirstDimension );
            return empty( $asKey )
                ? $_aMeta
                : $this->getElement( $_aMeta, $asKey, $mDefault );
        }

        return $mDefault;

    }

    /**
     * @param $asKey
     * @param null $mDefault
     *
     * @return array
     */
    public function getArray( $asKey, $mDefault=null ) {
        return $this->getAsArray( $this->get( $asKey, $mDefault ) );
    }

}