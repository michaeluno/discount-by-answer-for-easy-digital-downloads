<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno; Licensed under <LICENSE_TYPE>
 *
 */

/**
 * Adds the `View` page.
 *
 * @since    0.6.0
 */
class DiscountByAnswerForEDD_Answer_AdminPage__Page_ViewAnswer extends DiscountByAnswerForEDD_AdminPage__Page_Base {

    /**
     * @param  $oFactory
     * @return array
     */
    protected function _getArguments( $oFactory ) {
        return array(
            'page_slug'     => DiscountByAnswerForEDD_Registry::$aAdminPages[ 'view_answer' ],
            'title'         => __( 'View Answer', 'discount-by-answer-for-easy-digital-downloads' ),
            'show_in_menu'  => false,
            'order'         => 82,      // the post type `submenu_order` argument is 80
            // 'screen_icon'   => DiscountByAnswerForEDD_Registry::getPluginURL( "asset/image/screen_icon_32x32.png" ),
            'style'         => '.go-back{ float:right; } .discount-by-answer-for-easy-digital-downloads-sctionsets > .discount-by-answer-for-easy-digital-downloads-sectionset { margin-bottom: 0; }'
        );
    }

    protected function _load( $oFactory ) {

        $oFactory->addSettingFields(
            array(
                'field_id'    => 'go_back',
                'content'     => $this->___getGoBackLink(),
            )
        );

        new DiscountByAnswerForEDD_Answer_AdminPage__FormSection_BasicInformation( $oFactory, $this->_sPageSlug );
        new DiscountByAnswerForEDD_Answer_AdminPage__FormSection_CustomerInformation( $oFactory, $this->_sPageSlug );
        new DiscountByAnswerForEDD_Answer_AdminPage__FormSection_Answer( $oFactory, $this->_sPageSlug );

    }

        private function ___getGoBackLink() {
            $_sAnswersURL = add_query_arg(
                array(
                    'post_type' => DiscountByAnswerForEDD_Registry::$aPostTypes[ 'answer' ],
                ),
                admin_url( 'edit.php' )
            );
            return '<div class="go-back">'
                . '<span class="dashicons dashicons-arrow-right-alt"></span> '
                . sprintf(
                    __( 'Go back to %1$s', 'discount-by-answer-for-easy-digital-downloads' ),
                    '<a href="' . esc_url( $_sAnswersURL ) . '">'
                        . __( 'Answers', 'discount-by-answer-for-easy-digital-downloads' )
                    . '</a>'
                )
                . '</div>';
        }


}