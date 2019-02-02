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
class DiscountByAnswerForEDD_Campaign_MetaBox_Information extends DiscountByAnswerForEDD_Campaign_MetaBox_Base {

    /*
     * Use the setUp() method to define settings of this meta box.
     */
    public function setUp() {

        $_sScriptPath = $this->oUtil->isDebugMode()
            ? dirname( __FILE__ ) . '/js/edddba-information-feed.js'
            : dirname( __FILE__ ) . '/js/edddba-information-feed.min.js';
        $this->enqueueScript(
            $_sScriptPath,
            $this->oProp->aPostTypes,    // post type slug
            array(
                'handle_id'     => 'edddbaMetaBoxInfo',
                'translation'   => array(
                    'spinnerURL' => admin_url( 'images/wpspin_light.gif' ),
                    'ajaxURL'    => admin_url( 'admin-ajax.php' ),
                    'proURL'     => DiscountByAnswerForEDD_Registry::PRO_URL,
                    'labels'     => array(
                        'getPro'            => __( 'Get Pro to enable this feature!', 'discount-by-answer-for-easy-digital-downloads' ),
//                        'requiresPro'       => __( 'This feature will be supported with Pro.', 'discount-by-answer-for-easy-digital-downloads' ),
                    ),
                ),
            )
        );
        new DiscountByAnswerForEDD_Campaign_MetaBox_Information_Event_Ajax_Feed;

    }

    public function content( $sContent ) {
        $_sURL = esc_url( DiscountByAnswerForEDD_Registry::INFO_FEED_URL );
        return $sContent
            . "<p id='edddba_information_feed' data-url='{$_sURL}'>"
               . __( 'Loading...', 'discount-by-answer-for-easy-digital-downloads' )
            . "</p>"
            . '<div id="edddba_pro_notification" class="edddba_pro_notification" style="display:none;">'
                 . '<p>'
                     . '<a href="' . DiscountByAnswerForEDD_Registry::PRO_URL . '" target="_blank">'
                         . __( 'This feature will be supported with Pro.', 'discount-by-answer-for-easy-digital-downloads' )
                     . '</a>'
                 . '</p>'
             . '</div>';
               
    }
    
    

}