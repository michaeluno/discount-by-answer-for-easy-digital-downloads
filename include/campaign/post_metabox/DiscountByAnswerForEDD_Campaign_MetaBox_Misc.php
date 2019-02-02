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
class DiscountByAnswerForEDD_Campaign_MetaBox_Misc extends DiscountByAnswerForEDD_AdminPageFramework_MetaBox {

    /*
     * Use the setUp() method to define settings of this meta box.
     */
    public function setUp() {

        /**
         * Adds setting fields in the meta box.
         */
        $this->addSettingFields(
            array(
                'field_id'         => '_edddba_campaign_maximum_uses',
                'type'             => 'number',
                'title'            => __( 'Maximum Uses', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'      => __( 'Apart form the discount code maximum uses, this sets maximum usage cap for the campaign. This will be useful tu run a campaign on a first-come-first-served basis.', 'discount-by-answer-for-easy-digital-downloads' )
                    . ' ' . __( 'Leave it 0 for no limit.', 'discount-by-answer-for-easy-digital-downloads' ),
                'label_min_width'  => 0,
            )
        );

    }


}