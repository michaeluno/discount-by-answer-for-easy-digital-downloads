<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.4.0
 */
class DiscountByAnswerForEDD_Campaign_RequestType_radio extends DiscountByAnswerForEDD_Campaign_RequestType_select {

    protected $_sType = 'radio';
    protected $_sFieldSelector = '.edddba_requests_radio';

    protected function _construct() {}

    /**
     * @return  string
     */
    protected function _getLabel() {
        return __( 'Radio Button', 'discount-by-answer-for-easy-digital-downloads' );
    }

    /**
     * @return  array
     */
    protected function _getField() {
        return array(
            'field_id'        => $this->_sType,
            'class'           => array(
                'fieldrow' => 'edddba_requests_radio edddba_requests',
            ),
            'content'         => array(
                array(
                    'field_id'        => 'items',
                    'title'           => __( 'Selectable Items', 'discount-by-answer-for-easy-digital-downloads' ),
                    'repeatable'      => true,
                    'sortable'        => true,
                    'content'         => array(
                        array(
                            'field_id'      => 'slug',
                            'title'         => __( 'Slug', 'discount-by-answer-for-easy-digital-downloads' ),
                            'type'          => 'text',
                            'description'   => __( 'Accepts alphanumeric characters. This is internally used by the plugin to distinguish added options.', 'discount-by-answer-for-easy-digital-downloads' ),
                            // @todo in the sanitization process, give a value when empty
                            'attributes'    => array(
                                'pattern'     => '[a-zA-Z0-9-_]+',
                            ),
                        ),
                        array(
                            'field_id'      => 'label',
                            'title'         => __( 'Input Label', 'discount-by-answer-for-easy-digital-downloads' ),
                            'description'   => __( 'The text which appears to the user.', 'discount-by-answer-for-easy-digital-downloads' ),
                            'type'          => 'text',
                            'default'       => __( 'This is an option that the user selects.', 'discount-by-answer-for-easy-digital-downloads' ),
                            'class'           => array(
                                'fieldset'  => 'edddba_field_labels',
                            ),
                        ),
                    ),
                ),
                array(
                    'field_id'      => 'has_acceptable_answers',
                    'type'          => 'revealer2',
                    'select_type'   => 'checkbox',
                    'label'         => array(
                        0   => __( 'There are acceptable answers.', 'discount-by-answer-for-easy-digital-downloads' ),
                    ),
                    'selectors' => array(
                        0   => '.edddba_acceptable',
                    ),
                    'default'       => array(
                        0 => true,
                    ),
                ),
                array(
                    'field_id'      => 'acceptable',
                    'title'         => __( 'Acceptable Answers', 'discount-by-answer-for-easy-digital-downloads' ),
                    'sortable'      => true,
                    'repeatable'    => true,
                    'content'       => array(
                        array(
                            'field_id'    => 'value',
                            'type'        => 'text',
                            'description' => __( 'Type here the slug set above to be the acceptable answer.', 'discount-by-answer-for-easy-digital-downloads' ),
                            'attributes'  => array(
                                'pattern'     => '[a-zA-Z0-9-_]+',
                            ),
                        ),
//                        $this->___getFields_AnswerSpecificDiscount(),
                    ),
                    'class'           => array(
                        'fieldset' => 'edddba_acceptable',
                    ),
                ),
            ),
        );
    }


}