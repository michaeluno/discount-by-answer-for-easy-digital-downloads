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
class DiscountByAnswerForEDD_FormElement_Fields_Default extends DiscountByAnswerForEDD_FormElement_Base {

    protected function _getDefinition() {
        return array(
            $this->sID ? $this->sID : array(),  // the section to belong to
        );
    }

}