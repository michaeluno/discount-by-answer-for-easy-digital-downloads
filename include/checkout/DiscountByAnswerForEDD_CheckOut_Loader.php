<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * The `Checkout` component loader.
 *
 * Deals with displaying the plugin campaign form in the checkout page.
 *
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_CheckOut_Loader {

    /**
     * Stores the component directory path.
     * @remark  mostly used to access resource files.
     * @var string
     */
    static public $sDirPath = '';

    public function __construct() {

        self::$sDirPath = dirname( __FILE__ );

        // Front-end form elements
        new DiscountByAnswerForEDD_CheckOut_Fieldset_CheckString;
        new DiscountByAnswerForEDD_CheckOut_Fieldset_Resources;

        // Events
        /// Ajax calls from the request fields
        new DiscountByAnswerForEDD_CheckOut_Event_Ajax_VerifyAnswers;


    }

}