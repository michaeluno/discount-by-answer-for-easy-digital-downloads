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
class DiscountByAnswerForEDD_Campaign_MetaBox_Base extends DiscountByAnswerForEDD_AdminPageFramework_MetaBox {

    protected $_sSectionID    = '';
    protected $_sSectionClass = '';
    protected $_sFieldsClass  = '';

    protected $_aSection      = array(
        'collapsible' => false,
        'title' => '',
    );
    /*
     * Use the setUp() method to define settings of this meta box.
     */
    public function setUp() {

        new DiscountByAnswerForEDD_Select2CustomFieldType( $this->oProp->sClassName );
        new DiscountByAnswerForEDD_FieldType_Revealer2( $this->oProp->sClassName );

        $_sSectionID      = $this->_sSectionID;
        $_oSection        = new $this->_sSectionClass( $_sSectionID );
        $this->addSettingSections( $_oSection->get( $this->_aSection ) );

        $_aFieldsClasses = array(
            $_sSectionID       => $this->_sFieldsClass,
        );
        foreach( $_aFieldsClasses as $_sSectionID => $_sClass ) {
            $_oFields = new $_sClass( $_sSectionID );
            call_user_func_array( array( $this, 'addSettingFields' ), $_oFields->get() );
        }

        $this->___enqueueResources();

        add_filter(
            'options_' . $this->oProp->sClassName,
            array( $this, 'getOptions' )
        );

    }

        private function ___enqueueResources() {

            if ( $this->oUtil->hasBeenCalled( 'EDDDBA_CAMPAIGN_METABOX_RESOURCES' ) ) {
                return;
            }

            $_sCSSPath = $this->oUtil->isDebugMode()
                ? DiscountByAnswerForEDD_Registry::$sDirPath . '/include/_common/form/css/edddba-campaign-metabox.css'
                : DiscountByAnswerForEDD_Registry::$sDirPath . '/include/_common/form/css/edddba-campaign-metabox.min.css';
            $this->enqueueStyle( $_sCSSPath, $this->oProp->aPostTypes );

            if ( $this->isInThePage() ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'replyToFixTinyMCEError' ) );
                add_action( 'admin_notices', array( $this, 'replyToFixSettingNotices' ) );
            }

        }

    /**
     * @internal
     */
    public function replyToFixSettingNotices() {

        // Fix that setting notifications are not displayed
        $_oNotice = new DiscountByAnswerForEDD_AdminPageFramework_Form___SubmitNotice;
        $_oNotice->render();

    }

    /**
     * Fixes the error `Uncaught ReferenceError: tinyMCE is not defined`.
     * @see https://stackoverflow.com/a/37768685
     */
    public function replyToFixTinyMCEError(){

        wp_enqueue_script( 'tinymce_js_main', includes_url() . 'js/tinymce/tinymce.min.js' );
        wp_enqueue_script( 'tinymce_js_plugin', includes_url() . 'js/tinymce/plugins/compat3x/plugin.min.js' );

    }

    /**
     * Sets the default option values for the setting form.
     * @return      array       The options array.
     */
    public function getOptions( $aOptions ) {
        return $this->oUtil->uniteArrays(
            $aOptions,
            array(
                $this->_sSectionID => DiscountByAnswerForEDD_Campaign_Defaults::getDefaults( $this->_sSectionID )
            )
        );
    }

}