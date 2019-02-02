<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.9.0
 */
class DiscountByAnswerForEDD_Campaign_MetaBox_Information_Event_Ajax_Feed extends DiscountByAnswerForEDD_Event_Ajax_Base {

    protected $_sActionName = 'edddba_load_information_feed';
    protected $_bAllowGuests = false;

    public function doAction() {

        $_aReturn     = array(
            'message'      => __( 'The keyword was found!', 'discount-by-answer-for-easy-digital-downloads' ),
            'code'         => 0,
        );
        try {

            if ( ! $this->_shouldProceed() ) {
                throw new Exception(
                    __( 'Invalid request. Missing arguments.', 'discount-by-answer-for-easy-digital-downloads' ),
                    10
                );
            }

            $_sURL  = $_POST[ 'url' ];
            $_oFeed = fetch_feed( $_sURL );
            if ( is_wp_error( $_oFeed ) ) {
                throw new Exception(
                    $_boHasString->get_error_message() . ' url: ' . $_sURL,
                    40
                );
            }
            $_aReturn[ 'message' ] = $this->___getFeedOutput( $_oFeed );


        } catch ( Exception $_oException ) {
            $_aReturn[ 'message' ] = $_oException->getMessage();
            $_aReturn[ 'code' ]    = $_oException->getCode();
        }
        exit( json_encode( $_aReturn ) );

    }
        private function ___getFeedOutput( SimplePie $oFeed ) {

            $_sOutput   = '';
            $_iMaxItems = $oFeed->get_item_quantity( 20 );
            $_aItems    = $oFeed->get_items( 0, $_iMaxItems );
            foreach( $_aItems as $_oItem ) {
                if ( ! ( $_oItem instanceof SimplePie_Item ) ) {
                    continue;
                }
                $_sImageURL = '';
                if ( $_oEnclosure = $_oItem->get_enclosure() ) {
                    $_sImageURL = $_oEnclosure->get_thumbnail();
                }
                $_sOutput .= "<div class='edddba_feed_item'>"
                        . ( $_sImageURL ? "<img src='" . esc_url( $_sImageURL ) . "'/>" : '' )
                        . "<a href='" . esc_url( $_oItem->get_permalink() ) . "' target='_blank' >"
                            . "<h4>" . $_oItem->get_title() . "</h4>"
                        . "</a>"
                        . "<p>" . $_oItem->get_description() . "</p>"
                    . "</div>";
            }
            return $_sOutput;

        }

    /**
     * @return  boolean
     * @todo    if no class extends this class, make this private
     */
    protected function _shouldProceed() {
        if ( ! isset( $_POST[ 'url' ] ) ) {
            return false;
        }
        return true;
    }


}