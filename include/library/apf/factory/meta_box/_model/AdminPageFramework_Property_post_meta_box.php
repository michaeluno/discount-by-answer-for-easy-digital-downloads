<?php 
/**
	Admin Page Framework v3.8.18 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads>
	Copyright (c) 2013-2018, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class DiscountByAnswerForEDD_AdminPageFramework_Property_post_meta_box extends DiscountByAnswerForEDD_AdminPageFramework_Property_Base {
    public $_sPropertyType = 'post_meta_box';
    public $sMetaBoxID = '';
    public $aPostTypes = array();
    public $aPages = array();
    public $sContext = 'normal';
    public $sPriority = 'default';
    public $sClassName = '';
    public $sCapability = 'edit_posts';
    public $sThickBoxTitle = '';
    public $sThickBoxButtonUseThis = '';
    public $sStructureType = 'post_meta_box';
    public $_sFormRegistrationHook = 'admin_enqueue_scripts';
    public function __construct($oCaller, $sClassName, $sCapability = 'edit_posts', $sTextDomain = 'discount-by-answer-for-easy-digital-downloads', $sStructureType = 'post_meta_box') {
        parent::__construct($oCaller, null, $sClassName, $sCapability, $sTextDomain, $sStructureType);
    }
    protected function _getOptions() {
        return array();
    }
}
