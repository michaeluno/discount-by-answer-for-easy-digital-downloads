<?php 
/**
	Admin Page Framework v3.8.18 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads>
	Copyright (c) 2013-2018, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class DiscountByAnswerForEDD_AdminPageFramework_Form_View___CSS_widget extends DiscountByAnswerForEDD_AdminPageFramework_Form_View___CSS_Base {
    protected function _get() {
        return $this->_getWidgetRules();
    }
    private function _getWidgetRules() {
        return ".widget .discount-by-answer-for-easy-digital-downloads-section .form-table > tbody > tr > td,.widget .discount-by-answer-for-easy-digital-downloads-section .form-table > tbody > tr > th{display: inline-block;width: 100%;padding: 0;float: right;clear: right; }.widget .discount-by-answer-for-easy-digital-downloads-field,.widget .discount-by-answer-for-easy-digital-downloads-input-label-container{width: 100%;}.widget .sortable .discount-by-answer-for-easy-digital-downloads-field {padding: 4% 4.4% 3.2% 4.4%;width: 91.2%;}.widget .discount-by-answer-for-easy-digital-downloads-field input {margin-bottom: 0.1em;margin-top: 0.1em;}.widget .discount-by-answer-for-easy-digital-downloads-field input[type=text],.widget .discount-by-answer-for-easy-digital-downloads-field textarea {width: 100%;} @media screen and ( max-width: 782px ) {.widget .discount-by-answer-for-easy-digital-downloads-fields {width: 99.2%;}.widget .discount-by-answer-for-easy-digital-downloads-field input[type='checkbox'], .widget .discount-by-answer-for-easy-digital-downloads-field input[type='radio'] {margin-top: 0;}}";
    }
    protected function _getVersionSpecific() {
        $_sCSSRules = '';
        if (version_compare($GLOBALS['wp_version'], '3.8', '<')) {
            $_sCSSRules.= ".widget .discount-by-answer-for-easy-digital-downloads-section table.mceLayout {table-layout: fixed;}";
        }
        if (version_compare($GLOBALS['wp_version'], '3.8', '>=')) {
            $_sCSSRules.= ".widget .discount-by-answer-for-easy-digital-downloads-section .form-table th{font-size: 13px;font-weight: normal;margin-bottom: 0.2em;}.widget .discount-by-answer-for-easy-digital-downloads-section .form-table {margin-top: 1em;}";
        }
        return $_sCSSRules;
    }
}
