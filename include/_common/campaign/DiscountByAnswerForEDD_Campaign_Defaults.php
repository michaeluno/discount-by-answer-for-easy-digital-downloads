<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * Provides default option values for a campaign object.
 * @since   0.7.0
 */
class DiscountByAnswerForEDD_Campaign_Defaults extends DiscountByAnswerForEDD_PluginUtility {

    static public $aDefaults = array();

    static public function setDefaults() {
        if ( self::hasBeenCalled( __METHOD__ ) ) {
            return;
        }
        self::$aDefaults = array(
                    '_edddba_campaign_status'   => true,
                    '_edddba_discount'          => array(
                        'prefix' => 'CAMPAIGN_',
                        'base_discount_code' => array(
                            'value'     => '',
                            'encoded'   => '',
                        ),
                        'lifespan' => array(
                            'size' => 3,
                            'unit' => 3600,
                        ),
                        'auto_delete'   => 1,
                    ),
                    '_edddba_labels'            => array(
                        'catch'  => __( 'Have you contributed to the project to get a discount?', 'discount-by-answer-for-easy-digital-downloads' ),
                        'title'  => __( 'Discount by Contribution', 'discount-by-answer-for-easy-digital-downloads' ),
                        'expand' => __( 'Click for details', 'discount-by-answer-for-easy-digital-downloads' ),
                        'close'  => __( 'Click to close', 'discount-by-answer-for-easy-digital-downloads' ),  // the close link is not implemented yet
                        'button' => __( 'Submit', 'discount-by-answer-for-easy-digital-downloads' ),  // the close link is not implemented yet
                        'thanks' => __( 'Thank you for your contribution! This is your discount code: <strong><code>%code%</code></strong>, which will be expired in %timescale% hours.', 'discount-by-answer-for-easy-digital-downloads' ),
                        'error'  => __( 'Your answers have an unaccepted answer.', 'discount-by-answer-for-easy-digital-downloads' ),
                    ),
                    '_edddba_requests'          => array(
                        0 => array(
                            'name'      => __( 'User Name', 'discount-by-answer-for-easy-digital-downloads' ),
                            'type'      => 'text',
                            'status'    => 1,
                            'slug'      => 'user_name',
                            'required'  => 0,
                            'text'      => array(
                                'has_acceptable_answers'    => array(
                                    0 => 1,
                                ),
                                'acceptable'    => array(
                                    0 => array(
//                                        'keywords'  => array(
//                                            0 => array(
//                                                'reference_type' => 'web',
//                                                'textarea'       => __( 'This is the text where the keyword of the answer resides.', 'discount-by-answer-for-easy-digital-downloads' ),
//                                                'url'            => 'https://core.trac.wordpress.org/log/trunk?action=stop_on_copy&mode=stop_on_copy&limit=100',
//                                                'xpaths'         => array(
//                                                    0 => '//*[@class="trac-author"]',
//                                                ),
//                                            ),
//                                        ),
                                        'reference_type' => 'web',
                                        'textarea'       => __( 'This is the text where the keyword of the answer resides.', 'discount-by-answer-for-easy-digital-downloads' ),
                                        'url'            => 'https://core.trac.wordpress.org/log/trunk?action=stop_on_copy&mode=stop_on_copy&limit=100',
                                        'xpaths'         => array(
                                            0 => '//*[@class="trac-author"]',
                                        ),
                                        'case_sensitivity'  => 1,
                                        'exclude'   => 'xxx' . PHP_EOL
                                            . 'unwanted word' . PHP_EOL
                                            . 'John Doe',
                                        'exclude_answered_keywords' => 1,
                                        'answer_specific_discount' => array(
                                            'enabled' => array(
                                                0 => 0,
                                            ),
                                            'base_discount_code' => array(
                                                'value'     => '',
                                                'encoded'   => '',
                                            ),
                                            'weight'    => 0,
                                        ),
                                    ),
                                ),
                                'disallow_converted_answers'    => 0,
                                'placeholder' => __( 'Enter the user name.', 'discount-by-answer-for-easy-digital-downloads' ),
                                'field_error_message'   => '',
                            ),
                            'textarea' => array(
                                'has_acceptable_answers' => array(
                                    0 => 0,
                                ),
                                'acceptable' => array(
                                    0 => array(
                                        'keyword' => '',
                                        'case_sensitivity'  => 1,
                                        'answer_specific_discount' => array(
                                            'enabled' => array(
                                                0 => 0,
                                            ),
                                            'base_discount_code' => array(
                                                'value'     => '',
                                                'encoded'   => '',
                                            ),
                                            'weight'    => 0,
                                        ),
                                    ),
                                ),
                                'field_error_message'   => '',
                            ),
                            'radio'    => array(
                                'items' => array(
                                    0 => array(
                                        'slug'  => 'item_1',
                                        'label' => __( 'This is a first item.', 'discount-by-answer-for-easy-digital-downloads' ),
                                    ),
                                    1 => array(
                                        'slug'  => 'item_2',
                                        'label' => __( 'This is a second item.', 'discount-by-answer-for-easy-digital-downloads' ),
                                    ),
                                ),
                                'has_acceptable_answers' => array(
                                    0 => 0,
                                ),
                                'acceptable' => array(
                                    0 => array(
                                        'value' => 'item_1',
                                        'answer_specific_discount' => array(
                                            'enabled' => array(
                                                0 => 0,
                                            ),
                                            'base_discount_code' => array(
                                                'value'     => '',
                                                'encoded'   => '',
                                            ),
                                            'weight'    => 0,
                                        ),
                                    ),
                                ),
                                'field_error_message'   => '',
                            ),
                            'select'   => array(
                                'items' => array(
                                    0 => array(
                                        'slug'  => 'item_1',
                                        'label' => __( 'Apple', 'discount-by-answer-for-easy-digital-downloads' ),
                                    ),
                                    1 => array(
                                        'slug'  => 'item_2',
                                        'label' => __( 'Banana', 'discount-by-answer-for-easy-digital-downloads' ),
                                    ),
                                ),
                                'has_acceptable_answers' => array(
                                    0 => 0,
                                ),
                                'acceptable' => array(
                                    0 => array(
                                        'value' => 'item_1',
                                        'answer_specific_discount' => array(
                                            'enabled' => array(
                                                0 => 0,
                                            ),
                                            'base_discount_code' => array(
                                                'value'     => '',
                                                'encoded'   => '',
                                            ),
                                            'weight'    => 0,
                                        ),
                                    ),
                                ),
                                'field_error_message'   => '',
                            ),
                            'checkbox' => array(
                                'label' => __( 'This is an option that the user checks.', 'discount-by-answer-for-easy-digital-downloads' ),
                                'has_acceptable_answers' => array(
                                    0 => 0,
                                ),
                                'acceptable' => array(
                                    0 => array(
                                        'checked' => 1,
                                        'answer_specific_discount' => array(
                                            'enabled' => array(
                                                0 => 0,
                                            ),
                                            'base_discount_code' => array(
                                                'value'     => '',
                                                'encoded'   => '',
                                            ),
                                            'weight'    => 0,
                                        ),
                                    ),
                                ),
                                'field_error_message'   => '',
                            ),
                            'labels' => array(
                                'description' => sprintf(
                                    __( 'Enter the user name listed on <a href="%1$s">this page</a> if you are a contributor.' ),
                                    'https://core.trac.wordpress.org/log/trunk?action=stop_on_copy&mode=stop_on_copy&limit=100'
                                ),
                            ),
                        ),
                    ),
                    '_edddba_report'            => array(
                        'send_email'             => 1,
                        'log'                    => 1,
                        'log_retention_count'    => 0,
                        'keep_converted_answers' => 1,
                    ),
                    '_edddba_associated_posts'  => array(),
                );
    }

    static public function getDefaults( $asKeys ) {
        self::setDefaults();
        return self::getElement( self::$aDefaults, $asKeys );
//        if ( ! $sKey ) {
//            return $_aDefaults;
//        }
//        return isset( $_aDefaults[ $sKey ] )
//            ? $_aDefaults[ $sKey ]
//            : null;
    }

}