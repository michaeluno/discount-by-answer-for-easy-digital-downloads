<?php
/**
 * Discount by Answer for Easy Digital Downloads
 * 
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 * 
 */

/**
 * Provides utility methods that uses WordPerss built-in functions.
 *
 * @package  Discount by Answer for Easy Digital Downloads
 * @since    0.0.1
 */
class DiscountByAnswerForEDD_WPUtility extends DiscountByAnswerForEDD_Utility {

    /**
     * Schedules a WP Cron single event.
     * @since       0.0.1
     * @return      boolean     True if scheduled or already scheduled, false otherwise.
     */
    static public function scheduleSingleWPCronTask( $sActionName, array $aArguments, $iTime=0 ) {

        if ( wp_next_scheduled( $sActionName, $aArguments ) ) {
            return true;
        }
        $_bCancelled = wp_schedule_single_event(
            $iTime ? $iTime : time(), // now
            $sActionName,   // an action hook name which gets executed with WP Cron.
            $aArguments    // must be enclosed in an array. The callback function receives the parameters inside the most outer array.
        );
        return false !== $_bCancelled;

    }

    /**
     * Schedules the Feed Renew event.
     *
     * The feed renew event lists up expired feeds and creates actual feed post from the retrieved items.
     *
     * @remark  The passed action arguments must be an empty array. If passed arguments are different, the next schedule check fails.
     * @since   0.0.1
     * @return  boolean     return false for failure. True if scheduled. True also if already scheduled.
     */
    static public function schedulePeriodicEvent( $sActionHookName, $sInterval='daily', $aArguments=array() ) {

        if ( wp_next_scheduled( $sActionHookName, $aArguments ) ) {
            return true;
        }
        $_bvResult = wp_schedule_event(
            time(), // time stamp
            $sInterval, // interval slug
            $sActionHookName,
            $aArguments
        );
        return false !== $_bvResult;

    }

    /**
     * @param $iPostID
     * @param string $sKey
     * @return  mixed      If a meta key is not given, the entire meta values will be returned as an array.
     */
    static public function getPostMeta( $iPostID, $sKey='' ) {

        if ( $sKey ) {
            return get_post_meta( $iPostID, $sKey, true );
        }

        $_aPostMeta = array();
        foreach( ( array ) get_post_custom_keys( $iPostID ) as $_sKey ) {
            $_aPostMeta[ $_sKey ] = get_post_meta( $iPostID, $_sKey, true );
        }
        return $_aPostMeta;

    }

    /**
     * Creates a post of a specified custom post type with unit option meta fields.
     *
     * @return      integer|WP_Error
     */
    public static function insertPost( array $aPostMeta, $sPostTypeSlug, $iAuthorID=0 ) {

        // If the database objects were not ready. Do nothing.
        if ( ! is_object( $GLOBALS[ 'wpdb' ] ) || ! is_object( $GLOBALS[ 'wp_rewrite' ] ) ) {
            return new WP_Error( 'wpdb_not_established', __( 'WPDB is not established.', 'task-scheduler' ) );
        }

        static $_iUserID;
        $_iUserID = isset( $_iUserID ) ? $_iUserID : get_current_user_id();

        $_aDefaults = array(
            // Plugin specific default values.
            'post_type'             => $sPostTypeSlug,
            'post_date'             => '',
            'post_date_gmt'         => '',
            'comment_status'        => 'closed',
            'ping_status'           => 'closed',
            'post_status'           => 'publish',
            'post_modified'         => '',
            'post_modified_gmt'     => '',
        ) + array(
            // WordPress built-in wp_insert_post() function's default values.
            'post_author'           => $iAuthorID ? $iAuthorID : $_iUserID,
            'post_parent'           => 0,
            'menu_order'            => 0,
            'to_ping'               => '',
            'pinged'                => '',
            'post_password'         => '',
            'guid'                  => '',
            'post_content_filtered' => '',
            'post_excerpt'          => '',
            'import_id'             => 0,
            'post_content'          => '',
            'post_title'            => '',
            'tax_input'             => null,    // should be an array
        );

        // Construct the post arguments array.
        $_aPostArguments = array();
        foreach( $_aDefaults as $_sKey => $_sValue ) {
            $_aPostArguments[ $_sKey ] = isset( $aPostMeta[ $_sKey ] )
                ? $aPostMeta[ $_sKey ]
                : $_sValue;
        }

        // Without modifying capabilities, taxonomy terms cannot be set.
        if ( ! empty( $_aPostArguments[ 'tax_input' ] ) ) {
            add_filter( 'user_has_cap', array( __CLASS__, '_replyToAddCapabilities' ), 10, 4 );
        }

        /**
         * Setting a modified date is not allowed in wp_insert_post()
         * @see https://wordpress.stackexchange.com/questions/224161/cant-edit-post-modified-in-wp-insert-post-bug
         */
        if ( ! empty( $_aPostArguments[ 'post_modified' ] ) ) {
            if ( empty( $_aPostArguments[ 'post_modified_gmt' ] ) ) {
                $_aPostArguments[ 'post_modified_gmt' ] = gmdate(
                    'Y-m-d H:i:s',
                    strtotime( $_aPostArguments[ 'post_modified' ] ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS )
                );
            }
            add_filter( 'wp_insert_post_data', array( __CLASS__, '_replyToAlterPostModificationTime' ), 99, 2 );
        }

        // Create a custom post if it's a new unit.
        $_ioPostID = wp_insert_post( $_aPostArguments, true );

        remove_filter( 'wp_insert_post_data', array( __CLASS__, '_replyToAlterPostModificationTime' ), 99 );
        remove_filter( 'user_has_cap', array( __CLASS__, '_replyToAddCapabilities' ), 10 );

        if ( is_wp_error( $_ioPostID ) || ! $_ioPostID ) {
            return $_ioPostID;
        }

        // Remove the default post arguments. See the definition of wp_insert_post() in post.php.
        foreach( $_aDefaults as $_sKey => $_sFieldKey ) {
            unset( $aPostMeta[ $_sKey ] );
        }

        // Custom meta data needs to be updated as the wp_insert_post() cannot handle them.
        if ( ! empty( $aPostMeta ) ) {
            self::updatePostMeta( $_ioPostID, $aPostMeta );
        }

        return $_ioPostID;

    }

        /**
         * Allows post modification time to be set.
         * @param array $aData
         * @param array $aPost
         *
         * @return array
         * @callback    filter      wp_insert_post_data
         */
        static public function _replyToAlterPostModificationTime( $aData, $aPost ) {
            if ( ! empty( $aPost[ 'post_modified' ] ) && ! empty( $aPost[ 'post_modified_gmt' ] ) ) {
                $aData[ 'post_modified' ]     = $aPost[ 'post_modified' ];
                $aData[ 'post_modified_gmt' ] = $aPost[ 'post_modified_gmt' ];
            }
            return $aData;
        }
        /**
         * This allows taxonomy terms to be set with wp_insert_post()
         * @param $aAllCapabilities
         * @param $aMetaCapabilities
         * @param $aArguments
         * @param $oUser
         *
         * @return array
         * @remark  in `wp-cron.php` the current user ID becomes 0.
         * For this, dynamically setting custom capabilities based on the permitted user roles in the other callback (in the post type class) does not work.
         * So here granting the capabilities with the versatile hook which is removed immediately after the routine is done.
         */
        static public function _replyToAddCapabilities( $aAllCapabilities, $aMetaCapabilities, $aArguments, $oUser ) {
            foreach( $aMetaCapabilities as $_sCapability ) {
                $aAllCapabilities[ $_sCapability ] = true;
            }
            return $aAllCapabilities;
        }

    /**
     * Updates post meta by the given ID and the array holding the meta data.
     */
    static public function updatePostMeta( $iPostID, array $aPostMeta ) {
        foreach( $aPostMeta as $_sFieldID => $_vValue ) {
            update_post_meta( $iPostID, $_sFieldID, $_vValue );
        }
    }


    /**
     * Deletes transient items by prefix of a transient key.
     *
     * @since    0.0.1
     * @remark  for the deactivation hook. Also used by the Clear Caches submit button.
     */
    public static function cleanTransients( $asPrefixes=array( 'CSB' ) ) {

        // This method also serves for the deactivation callback and in that case, an empty value is passed to the first parameter.
        $_aPrefixes = is_array( $asPrefixes )
            ? $asPrefixes
            : ( array ) $asPrefixes;

        foreach( $_aPrefixes as $_sPrefix ) {
            $GLOBALS['wpdb']->query( "DELETE FROM `" . $GLOBALS['table_prefix'] . "options` WHERE `option_name` LIKE ( '_transient_%{$_sPrefix}%' )" );
            $GLOBALS['wpdb']->query( "DELETE FROM `" . $GLOBALS['table_prefix'] . "options` WHERE `option_name` LIKE ( '_transient_timeout_%{$_sPrefix}%' )" );
        }

    }

}