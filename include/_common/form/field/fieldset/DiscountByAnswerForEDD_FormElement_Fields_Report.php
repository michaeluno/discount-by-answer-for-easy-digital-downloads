<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.8.0
 */
class DiscountByAnswerForEDD_FormElement_Fields_Report extends DiscountByAnswerForEDD_FormElement_Base {

    protected function _getDefinition() {
        return array(
            $this->sID ? $this->sID : array(),  // the section to belong to
//            array(
//                'field_id'      => 'send_email',
//                'type'          => 'checkbox',
//                'title'         => __( 'Email', 'discount-by-answer-for-easy-digital-downloads' ),
//                'label'         => __( "Send an Email when a discount code is newly issued after a customer's answer is accepted.", 'discount-by-answer-for-easy-digital-downloads' ),
//                'tip'           => __( 'For the format of the Email, go to Dashboard -> Downloads -> Discount by Answer -> Notification.', 'discount-by-answer-for-easy-digital-downloads' ),
//                'default'       => true,
//            ),
            array(
                'field_id'      => 'log',
                'type'          => 'checkbox',
                'title'         => __( 'Log', 'discount-by-answer-for-easy-digital-downloads' ),
                'label'         => __( 'Keep submitted answers.', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'   => __( 'Answers will be logged only when a discount code is issued.', 'discount-by-answer-for-easy-digital-downloads' ),
                'default'       => true,
            ),
            array(
                'field_id'      => 'log_retention_count',
                'type'          => 'number',
                'title'         => __( 'Log Retention Count', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'   => __( 'How many log answers to keep. Leave it 0 for no limit.', 'discount-by-answer-for-easy-digital-downloads' ),
                'default'       => 0,
            ),
            array(
                'field_id'      => 'keep_converted_answers',
                'type'          => 'checkbox',
                'title'         => __( 'Keep Converted', 'discount-by-answer-for-easy-digital-downloads' ),
                'label'         => __( 'Do not delete converted answers.', 'discount-by-answer-for-easy-digital-downloads' ),
                'default'       => 1,
            ),
        );

    }

}