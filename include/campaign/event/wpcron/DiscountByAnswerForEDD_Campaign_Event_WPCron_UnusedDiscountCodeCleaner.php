<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Handles clean-ups of expired and unused discount codes issued by this plugin.
 * @since   0.5.0
 */
class DiscountByAnswerForEDD_Campaign_Event_WPCron_UnusedDiscountCodeCleaner extends DiscountByAnswerForEDD_Event_Base {

    /**
     */
    protected function _construct() {

        add_action( 'edddba_action_clean_unused_discount_codes_daily', array( $this, 'doAction' ) );
        add_action( 'edddba_action_clean_unused_discount_codes', array( $this, 'doAction' ) );

        // Fired when an answer is submitted and everything is all set including logging answer and issuing discount code
        add_action( 'edddba_action_checkout_all_set', array( $this, 'scheduleCleanup' ) );

    }

    /**
     * Deletes unused and expired discount codes issued by this plugin.
     * Called daily and when a new campaign discount code is issued.
     */
    public function doAction() {

        $_aArguments         = array(
            'post_type'      => 'edd_discount',
            'posts_per_page' => -1,     // `-1` for all
            'fields'         => 'ids',  // return only post IDs by default.
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'           => '_edddba_discount_auto_delete',
                    'value'         => true,
                    'compare'       => '=',
                ),
                array(
                    'relation' => 'OR',
                    array(
                            'key'       => '_edd_discount_status',
                            'value'     => 'inactive',
                            'compare'   => '=',
                    ),
                    array(
                        'relation' => 'AND',
                        array(
                            'key'     => '_edd_discount_expiration',
                            'value'   => '',
                            'compare' => '!=',
                        ),
                        array(
                            'key'     => '_edd_discount_expiration',
                            'value'   => date( 'm/d/Y H:i:s', current_time( 'timestamp' ) ),
                            'compare' => '<',   // @see edd_discount_status_cleanup()
                        ),
                    ),
		        ),
            ),
            'post_status'    => 'any',
        );
        $_oQuery      = new WP_Query( $_aArguments );
        foreach( $_oQuery->posts as $_iPostID ) {
            wp_delete_post( $_iPostID, true );
        }
    }

    /**
     * @callback    action  edddba_action_checkout_all_set
     */
    public function scheduleCleanup() {
        $this->scheduleSingleWPCronTask(
            'edddba_action_clean_unused_discount_codes',
            array(),    // parameters passed to the action
            time()
        );
    }

}