<?php 
/**
	Admin Page Framework v3.8.18 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads>
	Copyright (c) 2013-2018, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class DiscountByAnswerForEDD_AdminPageFramework_Form_View___Attribute_SectionTableBody extends DiscountByAnswerForEDD_AdminPageFramework_Form_View___Attribute_Base {
    public $sContext = 'section_table_content';
    protected function _getAttributes() {
        $_sCollapsibleType = $this->getElement($this->aArguments, array('collapsible', 'type'), 'box');
        return array('class' => $this->getAOrB($this->aArguments['_is_collapsible'], 'discount-by-answer-for-easy-digital-downloads-collapsible-section-content' . ' ' . 'discount-by-answer-for-easy-digital-downloads-collapsible-content' . ' ' . 'accordion-section-content' . ' ' . 'discount-by-answer-for-easy-digital-downloads-collapsible-content-type-' . $_sCollapsibleType, null),);
    }
}
