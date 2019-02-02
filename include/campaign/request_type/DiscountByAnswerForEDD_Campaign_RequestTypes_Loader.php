<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * The base class for request type components.
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_Campaign_RequestTypes_Loader extends DiscountByAnswerForEDD_PluginUtility {

    public function __construct() {

        new DiscountByAnswerForEDD_Campaign_RequestType_checkbox;
        new DiscountByAnswerForEDD_Campaign_RequestType_radio;
        new DiscountByAnswerForEDD_Campaign_RequestType_select;
        new DiscountByAnswerForEDD_Campaign_RequestType_textarea;
        new DiscountByAnswerForEDD_Campaign_RequestType_text;

    }

}