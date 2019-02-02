<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.3.0
 */
class DiscountByAnswerForEDD_FormElement_Section_Labels extends DiscountByAnswerForEDD_FormElement_Base {


    protected function _getDefinition() {
        return array(
            'section_id'    => $this->sID,
            'title'         => __( 'Field Labels', 'discount-by-answer-for-easy-digital-downloads' ),
            'collapsible'       => array(
//                    'toggle_all_button' => array( 'top-left', 'bottom-left' ),
                'container'         => 'section',
                'is_collapsed'      => false,
            ),
        );
    }

}