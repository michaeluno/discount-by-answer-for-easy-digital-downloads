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
class DiscountByAnswerForEDD_FormElement_Fields_Discount extends DiscountByAnswerForEDD_FormElement_Base {

    protected function _getDefinition() {
        return array(
            $this->sID ? $this->sID : array(),  // the section to belong to
            array(
                'field_id'        => 'prefix',
                'type'            => 'text',
				'title'           => __( 'Discount Code Prefix', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'     => array(
                    __( 'Accepts only alphanumeric characters and underscores.', 'discount-by-answer-for-easy-digital-downloads' )
                    . ' '.  __( 'Every time the defined requests are fulfilled, a new unique discount code will be issued and this value will be prefixed to the generated discount code.', 'discount-by-answer-for-easy-digital-downloads' ),
                    'e.g. <code>CAMPAIGN_</code>',
                ),
                'attributes'        => array(
                    'required'    => 'required',
                    'pattern'     => '[a-zA-Z0-9-_]+',
                ),
                // @remark  As this field is required, if the user leaves the meta-box fields untouched and presses the submit button,
                // the browser may not allow it to proceed. So it is important to set a value anyway.
                'default'         => 'CAMPAIGN_',
            ),
            array(
                'field_id'        => 'base_discount_code',
                'type'            => 'select2',
                'title'           => __( 'Base Discount Code', 'discount-by-answer-for-easy-digital-downloads' )
                    . '<span class="required">*</span>',
                'description'     => __( 'Newly issued discount codes by this campaign will be issued based on this selected one. If this selected discount code becomes inactive, expired, or deleted, this campaign also becomes invisible in the front-end.', 'discount-by-answer-for-easy-digital-downloads' ),
                'options'         => array(
                    'minimumInputLength' => 2,
                    'width' => '33%',
                ),
                'callback'        => array(
                    // If the `search` callback is set, the field will be AJAX based.
                    'search'    => 'DiscountByAnswerForEDD_PluginUtility::getActiveDiscountCodes',
                ),
            ),
            array(
                'field_id'          => 'lifespan',
                'type'              => 'size',
                'title'             => __( 'Lifespan', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'       => __( 'The expiry period of newly created discount codes.', 'discount-by-answer-for-easy-digital-downloads' ),
                'units'             => $this->getTimeUnitLabels(),
                'default'           => array(
                    'size'      => 3,
                    'unit'      => 3600
                ),
            ),
            array(
                'field_id'          => 'auto_delete',
                'type'              => 'checkbox',
                'title'             => __( 'Auto-delete', 'discount-by-answer-for-easy-digital-downloads' ),
                'label'             => __( 'Delete the code once when it becomes inactive or expired.', 'discount-by-answer-for-easy-digital-downloads' ),
                'tip'               => __( 'If you want to let the customer use it only once, set <code>1</code> for the <code>Max Uses</code> option of the base discount code.', 'discount-by-answer-for-easy-digital-downloads' ),
                'default'           => 1,
            ),
        );

    }

}