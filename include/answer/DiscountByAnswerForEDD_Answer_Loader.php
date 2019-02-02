<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * A bootstrap for the discount log loader component.
 *
 * The discount log component deals with logging issued discount and submitted answers.
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_Answer_Loader {

    public function __construct() {

        // Post type
        new DiscountByAnswerForEDD_Answer_PostType(
            DiscountByAnswerForEDD_Registry::$aPostTypes[ 'answer' ],  // slug
            null,   // post type arguments, defined in the class.
            DiscountByAnswerForEDD_Registry::$sFilePath   // script path
        );

        // Events
        new DiscountByAnswerForEDD_Answer_Event_LogCleaner;
        new DiscountByAnswerForEDD_Answer_Event_CreateLog;
        new DiscountByAnswerForEDD_Answer_Event_AssociateCustomer;
        new DiscountByAnswerForEDD_Answer_Event_StoreConverted;

        // Admin Pages
        new DiscountByAnswerForEDD_Answer_AdminPage( '', DiscountByAnswerForEDD_Registry::$sFilePath );

    }

}