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
class DiscountByAnswerForEDD_FormElement_Fields_Labels extends DiscountByAnswerForEDD_FormElement_Base {

    protected function _getDefinition() {
        return array(
            $this->sID ? $this->sID : array(),  // the section to belong to
            array(
                'field_id'  => 'catch',
                'type'      => 'text',
                'tip'       => '<img src="'
                    . $this->getSRCFromPath( DiscountByAnswerForEDD_Registry::$sDirPath . '/include/_common/form/image/catch.jpg' )
                    . '" />',
                'title'     => __( 'Catch', 'discount-by-answer-for-easy-digital-downloads' ),
                'class'           => array(
                    'fieldrow'  => 'edddba_labels',
                ),
            ),
            array(
                'field_id'  => 'title',
                'type'      => 'text',
                'title'     => __( 'Title', 'discount-by-answer-for-easy-digital-downloads' ),
                'tip'       => '<img src="'
                    . $this->getSRCFromPath( DiscountByAnswerForEDD_Registry::$sDirPath . '/include/_common/form/image/title.jpg' )
                    . '" />',
                'class'           => array(
                    'fieldrow'  => 'edddba_labels',
                ),
            ),
            array(
                'field_id'  => 'expand',
                'type'      => 'text',
                'title'     => __( 'Expand Link', 'discount-by-answer-for-easy-digital-downloads' ),
                'tip'       => '<img src="'
                    . $this->getSRCFromPath( DiscountByAnswerForEDD_Registry::$sDirPath . '/include/_common/form/image/expand.jpg' )
                    . '" />',
                'class'           => array(
                    'fieldrow'  => 'edddba_labels',
                ),
            ),
            array(
                'field_id'  => 'close',
                'type'      => 'text',
                'title'     => __( 'Hide Link', 'discount-by-answer-for-easy-digital-downloads' ),
                'tip'       => '<img src="'
                    . $this->getSRCFromPath( DiscountByAnswerForEDD_Registry::$sDirPath . '/include/_common/form/image/close.jpg' )
                    . '" />',
                'class'           => array(
                    'fieldrow'  => 'edddba_labels',
                ),
            ),
            array(
                'field_id'  => 'button',
                'type'      => 'text',
                'title'     => __( 'Button', 'discount-by-answer-for-easy-digital-downloads' ),
                'tip'       => '<img src="'
                    . $this->getSRCFromPath( DiscountByAnswerForEDD_Registry::$sDirPath . '/include/_common/form/image/button.jpg' )
                    . '" />',
                'class'           => array(
                    'fieldrow'  => 'edddba_labels',
                ),
            ),
            array(
                'field_id'      => 'thanks',
                'type'          => 'text',
                'title'         => __( 'Thanks', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'   => array(
                    __( 'Do not remove the <code>%code%</code> notation as it is replaced with the actual discount code.', 'discount-by-answer-for-easy-digital-downloads' ),
                    __( 'Supported variables', 'discount-by-answer-for-easy-digital-downloads' ) . ': <code>%code%</code>, <code>%timescale%</code>, <code>%time%</code>',
                ),
                'tip'       => '<img src="'
                    . $this->getSRCFromPath( DiscountByAnswerForEDD_Registry::$sDirPath . '/include/_common/form/image/thanks.jpg' )
                    . '" />',
                'class'           => array(
                    'fieldrow'  => 'edddba_labels',
                ),
            ),
            array(
                'field_id'      => 'error',
                'type'          => 'text',
                'title'         => __( 'Error Message', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'   => __( 'The message to be shown when a user enters inappropriate values.', 'discount-by-answer-for-easy-digital-downloads' ),
                'tip'       => '<img src="'
                    . $this->getSRCFromPath( DiscountByAnswerForEDD_Registry::$sDirPath . '/include/_common/form/image/error.jpg' )
                    . '" />',
                'class'           => array(
                    'fieldrow'  => 'edddba_labels',
                ),
            ),
        );

    }

}