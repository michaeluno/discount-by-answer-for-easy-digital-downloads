<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * The `Campaign` component loader.
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_Campaign_Loader {

    public function __construct() {

        // Post type
        new DiscountByAnswerForEDD_Campaign_PostType(
            DiscountByAnswerForEDD_Registry::$aPostTypes[ 'campaign' ],  // slug
            null,   // post type argument. This is defined in the class.
            DiscountByAnswerForEDD_Registry::$sFilePath   // script path
        );

        // Post Meta Boxes
        new DiscountByAnswerForEDD_Campaign_MetaBox_Discount(
            null,   // meta box ID - can be null.
            __( 'Discount', 'discount-by-answer-for-easy-digital-downloads' ), // title
            array( DiscountByAnswerForEDD_Registry::$aPostTypes[ 'campaign' ] ), // post type slugs: post, page, etc.
            'normal', // context: normal|side|advanced
            'high', // priority: low|high|default|core
            'manage_options'  // capability
        );
        new DiscountByAnswerForEDD_Campaign_MetaBox_Labels(
            null,   // meta box ID - can be null.
            __( 'Field Labels', 'discount-by-answer-for-easy-digital-downloads' ), // title
            array( DiscountByAnswerForEDD_Registry::$aPostTypes[ 'campaign' ] ), // post type slugs: post, page, etc.
            'normal', // context: normal|side|advanced
            'default', // priority: low|high|default|core
            'manage_options'  // capability
        );
        new DiscountByAnswerForEDD_Campaign_MetaBox_Requests(
            null,   // meta box ID - can be null.
            __( 'Request Form Fields', 'discount-by-answer-for-easy-digital-downloads' ), // title
            array( DiscountByAnswerForEDD_Registry::$aPostTypes[ 'campaign' ] ), // post type slugs: post, page, etc.
            'advanced', // context: normal|side|advanced
            'low', // priority: low|high|default|core
            'manage_options'  // capability
        );
        new DiscountByAnswerForEDD_Campaign_MetaBox_Submit(
            null,   // meta box ID - can be null.
            __( 'Submit', 'discount-by-answer-for-easy-digital-downloads' ), // title
            array( DiscountByAnswerForEDD_Registry::$aPostTypes[ 'campaign' ] ), // post type slugs: post, page, etc.
            'side', // context: normal|side|advanced
            'high', // priority: low|high|default|core
            'manage_options'  // capability
        );
        new DiscountByAnswerForEDD_Campaign_MetaBox_Misc(
            null,   // meta box ID - can be null.
            __( 'Misc', 'discount-by-answer-for-easy-digital-downloads' ), // title
            array( DiscountByAnswerForEDD_Registry::$aPostTypes[ 'campaign' ] ), // post type slugs: post, page, etc.
            'side', // context: normal|side|advanced
            'default', // priority: low|high|default|core
            'manage_options'  // capability
        );
        new DiscountByAnswerForEDD_Campaign_MetaBox_Report(
            null,   // meta box ID - can be null.
            __( 'Report', 'discount-by-answer-for-easy-digital-downloads' ), // title
            array( DiscountByAnswerForEDD_Registry::$aPostTypes[ 'campaign' ] ), // post type slugs: post, page, etc.
            'side', // context: normal|side|advanced
            'low', // priority: low|high|default|core
            'manage_options'  // capability
        );
        // @deprecated as the base discount code settings can be used
//        new DiscountByAnswerForEDD_Campaign_MetaBox_AssociatedPosts(
//            null,   // meta box ID - can be null.
//            __( 'Associated Posts', 'discount-by-answer-for-easy-digital-downloads' ), // title
//            array( DiscountByAnswerForEDD_Registry::$aPostTypes[ 'campaign' ] ), // post type slugs: post, page, etc.
//            'side', // context: normal|side|advanced
//            'low', // priority: low|high|default|core
//            'manage_options'  // capability
//        );
        new DiscountByAnswerForEDD_Campaign_MetaBox_Information(
            null,   // meta box ID - can be null.
            __( 'Information', 'discount-by-answer-for-easy-digital-downloads' ), // title
            array( DiscountByAnswerForEDD_Registry::$aPostTypes[ 'campaign' ] ), // post type slugs: post, page, etc.
            'side', // context: normal|side|advanced
            'low', // priority: low|high|default|core
            'manage_options'  // capability
        );

        // Request type components
        new DiscountByAnswerForEDD_Campaign_RequestTypes_Loader;

        // Events
        /// Issue campaign discount codes
        new DiscountByAnswerForEDD_Campaign_Event_IssueDiscountCode;

        /// Discount Code Clean-ups
        new DiscountByAnswerForEDD_Campaign_Event_WPCron_UnusedDiscountCodeCleaner;

        /// Triggered when a purchase is made and a discount code is used
        new DiscountByAnswerForEDD_Campaign_Event_DiscountCodeUsageCounter;
        new DiscountByAnswerForEDD_Campaign_Event_VolatileDiscountCodeCleaner;

        /// EDD events
        new DiscountByAnswerForEDD_Campaign_EDD_Event_VerifyDiscountCode;
        new DiscountByAnswerForEDD_Campaign_EDD_Event_AssociateCustomer;

    }

}