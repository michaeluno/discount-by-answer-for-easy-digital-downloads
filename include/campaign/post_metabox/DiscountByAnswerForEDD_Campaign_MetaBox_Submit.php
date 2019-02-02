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
class DiscountByAnswerForEDD_Campaign_MetaBox_Submit extends DiscountByAnswerForEDD_AdminPageFramework_MetaBox {

    /*
     * Use the setUp() method to define settings of this meta box.
     */
    public function setUp() {

        /**
         * Adds setting fields in the meta box.
         */
        $this->addSettingFields(
//            array(
//                'field_id'         => '_edddba_campaign_maximum_uses',
//                'type'             => 'number',
//                'title'            => __( 'Maximum Uses', 'discount-by-answer-for-easy-digital-downloads' ),
//                'description'      => __( 'Apart form the discount code maximum uses, this sets maximum usage cap for the campaign. This will be useful tu run a campaign on a first-come-first-served basis.', 'discount-by-answer-for-easy-digital-downloads' )
//                    . ' ' . __( 'Leave it 0 for no limit.', 'discount-by-answer-for-easy-digital-downloads' ),
//                'label_min_width'  => 0,
//            ),
            array(
                'field_id'         => '_edddba_campaign_status',
                'type'             => 'radio',
                'title'            => __( 'Status', 'discount-by-answer-for-easy-digital-downloads' ),
                'label'            => array(
                    1    => __( 'On', 'discount-by-answer-for-easy-digital-downloads' ),
                    0    => __( 'Off', 'discount-by-answer-for-easy-digital-downloads' ),
                ),
                'label_min_width'  => 0,
                'default'          => 1,
            ),
            array(
                'field_id'          => '_submit',
                'type'              => 'submit',
                'save'              => false,
                'value'             => isset( $_GET[ 'action' ] ) && 'edit' === $_GET[ 'action' ]
                    ? __( 'Update', 'discount-by-answer-for-easy-digital-downloads' )
                    : __( 'Add', 'discount-by-answer-for-easy-digital-downloads' ),
                'attributes'        => array(
                    'field'    => array(
                        'style' => 'text-align:center; width: 100%;'
                    ),
                ),
            )
        );

        // Hook to wp_insert_post_data
        add_filter( 'wp_insert_post_data', array( $this, 'replyToForcePublished' ), 10, 2 );
        add_action( 'admin_enqueue_scripts', array( $this, 'replyToDisablePostAutoSave' ) );

    }

    public function replyToDisablePostAutoSave() {
        if ( ! in_array( get_post_type(), $this->oProp->aPostTypes ) ) {
            return;
        }
        wp_dequeue_script( 'autosave' );
    }

    /**
     * Sets the post status to published
     *
     * @see https://wordpress.stackexchange.com/a/147187
     * @callback    filter      wp_insert_post_data
     */
    public function replyToForcePublished( $aData, $aPost ) {

        if ( ! in_array( $aData[ 'post_type' ], $this->oProp->aPostTypes ) ) {
            return $aData;
        }

        if ( in_array( $aData[ 'post_status' ], array( 'trash', 'auto-draft', ) ) ) {
            return $aData;
        }

        $aData[ 'post_status' ] = 'publish';
        return $aData;

    }

    /**
     * @param $sContent
     *
     * @return string
     */
    public function content( $sContent ) {
        $_sURL = admin_url( 'edit.php?post_type=' . DiscountByAnswerForEDD_Registry::$aPostTypes[ 'campaign' ] );
        return "<p>"
                . '<span class="dashicons dashicons-arrow-right-alt"></span> '
                . sprintf(
                    __( 'Go back to %1$s', 'discount-by-answer-for-easy-digital-downloads' ),
                    '<a href="' . esc_url( $_sURL ) . '">'
                            . __( 'Discount Campaigns', 'discount-by-answer-for-easy-digital-downloads' )
                        . '</a>'
                )
            . "</p>"
            . $sContent;
    }


}