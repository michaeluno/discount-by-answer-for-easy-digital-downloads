<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.5.0
 */
class DiscountByAnswerForEDD_FormElement_Fields_Requests extends DiscountByAnswerForEDD_FormElement_Base {

    protected function _getDefinition() {
        $_aFields = array(
            $this->sID ? $this->sID : array(),  // the section to belong to
            array(
                'field_id'         => 'name',
                'type'             => 'section_title',
                'before_input'     => ''
                    . "<strong>"
                    . __( 'Name', 'discount-by-answer-for-easy-digital-downloads' )
                    . "</strong>:&nbsp; ",
                'attributes'       => array(
                    'style'         => 'min-width: 60px;',
                    'placeholder'   => __( 'Enter a request name', 'discount-by-answer-for-easy-digital-downloads' ),
                ),
            ),
            array(
                'field_id'         => 'type',
                'type'             => 'revealer2',
                'select_type'      => 'select',
                'placement'        => 'section_title',
                'before_input'     => ''
                    . "<strong>"
                        . __( 'Type', 'discount-by-answer-for-easy-digital-downloads' )
                    . "</strong>:&nbsp; ",
                'label'            => apply_filters( 'edddba_filter_campaign_request_type_labels', array() ),
                'selectors'        => apply_filters( 'edddba_filter_campaign_request_type_field_selectors', array() ),
                'default'          => 'text',
            ),
            array(
                'field_id'         => 'status',
                'type'             => 'radio',
                'label'            => array(
                    1    => __( 'On', 'discount-by-answer-for-easy-digital-downloads' ),
                    0    => __( 'Off', 'discount-by-answer-for-easy-digital-downloads' ),
                ),
                'placement'        => 'section_title',
                'label_min_width'  => 0,
                'default'          => 0,
                'class'           => array(
                    'fieldset' => 'edddba_request_fields_status',
                ),
            ),
            array(
                'field_id'         => 'slug',
                'type'             => 'text',
                'title'            => __( 'Slug', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'      => __( 'Accepts alphanumeric values. This is used internally to identify field elements.', 'discount-by-answer-for-easy-digital-downloads' ),
                'attributes'    => array(
                    'pattern'     => '[a-zA-Z0-9-_]+',
                ),
            ),
            array(
                'field_id'         => 'required',
                'type'             => 'checkbox',
                'title'            => __( 'Required', 'discount-by-answer-for-easy-digital-downloads' ),
                'label'            => __( 'Check this when this request field must be fulfilled.', 'discount-by-answer-for-easy-digital-downloads' ),
                'default'          => 0,
            )
        );
        // Type specific fields
        $_aTypeSlugs = apply_filters( 'edddba_filter_campaign_request_type_slugs', array() );
        foreach( $this->getAsArray( $_aTypeSlugs ) as $_sTypeSlug ) {
            if ( ! $_sTypeSlug ) {
                continue;
            }
            $_aTypeFields = $this->getAsArray(
                apply_filters( 'edddba_filter_campaign_request_type_fields_' . $_sTypeSlug, array() )
            );
            $_aFields = array_merge( $_aFields, $_aTypeFields );
        }
        $_aFields[] = array(
            'field_id'  => 'labels',
            'title'     => __( 'Field Labels', 'discount-by-answer-for-easy-digital-downloads' ),
            'content'   => array(
                array(
                    'field_id'  => 'description',
                    'type'      => 'textarea',
                    'title'     => __( 'Description', 'discount-by-answer-for-easy-digital-downloads' ),
                    'tip'       => __( 'Describe your request to the customer.', 'discount-by-answer-for-easy-digital-downloads' ),
                    'rich'      => array(
                        'media_buttons' => false,
                        'tinymce'       => false,
                    ),
                    'attributes' => array(
                        'rows' => 2,
                    ),
                    'default'   => __( 'Please enter the answer for the request. If you fulfill this form with an acceptable answer, a discount code will be displayed.', 'discount-by-answer-for-easy-digital-downloads' ),
                ),
            ),
            'class'           => array(
                'fieldset'  => 'edddba_field_labels',
            ),
        );
        return $_aFields;
    }

    /**
     * @return array
     * @deprecated  not implemented at the moment yet
     */
    private function ___getFields_AnswerSpecificDiscount() {
        return array(
            'field_id'  => 'answer_specific_discount',
            'class'            => array(
                'fieldset' => 'edddba_answer_specific',
            ),
            'content'   => array(
                array(
                    'field_id'      => 'enabled',
                    'type'          => 'revealer2',
                    'select_type'   => 'checkbox',
                    'label'         => array(
                        0   => __( 'Enable answer specific discount.', 'discount-by-answer-for-easy-digital-downloads' ),
                    ),
                    'selectors' => array(
                        0   => '.edddba_answer_specific_weight, .edddba_answer_specific_base_discount_code',
                    ),
                    'default'       => array(
                        0 => false,
                    ),
                ),
                array(
                    'field_id'        => 'base_discount_code',
                    'type'            => 'select2',
                    'title'           => __( 'Base Discount Code', 'discount-by-answer-for-easy-digital-downloads' ),
                    'description'     => __( 'By setting a different base discount code for each request item, different discounts can be applied based on the answer.', 'discount-by-answer-for-easy-digital-downloads' )
                        . ' ' . __( 'If nothing is specified. the one in the <code>Discount</code> section will be applied.', 'discount-by-answer-for-easy-digital-downloads' ),
                    'options'         => array(
                        'minimumInputLength' => 2,
                        'width' => '33%',
                    ),
                    'callback'        => array(
                        // If the `search` callback is set, the field will be AJAX based.
                        'search'    => 'DiscountByAnswerForEDD_PluginUtility::getActiveDiscountCodes',
                    ),
                    'class'            => array(
                        'fieldset' => 'edddba_answer_specific_base_discount_code',
                    ),
                ),
                array(
                    'field_id'        => 'weight',
                    'type'            => 'number',
                    'title'           => __( 'Weight', 'discount-by-answer-for-easy-digital-downloads' ),
                    'description'     => __( 'When having multiple requests, if there are more than one answer-specific base discount codes being set, the one with the highest value will be used.', 'discount-by-answer-for-easy-digital-downloads' ),
                    'default'         => 0,
                    'class'            => array(
                        'fieldset' => 'edddba_answer_specific_weight',
                    ),
                ),
            )
        );
    }

}