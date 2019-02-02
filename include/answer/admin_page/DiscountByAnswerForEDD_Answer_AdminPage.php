<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */


/**
 * Creates an admin page that shows campaign answers.
 *
 * @since    0.0.1
 */
class DiscountByAnswerForEDD_Answer_AdminPage extends DiscountByAnswerForEDD_AdminPageFramework {

    /**
     * Sets up admin pages.
     */
    public function setUp() {

        $this->setRootMenuPageBySlug( 'edit.php?post_type=download' );

        // Add pages
        new DiscountByAnswerForEDD_Answer_AdminPage__Page_ViewAnswer( $this );

        $this->setPluginSettingsLinkLabel( '' );    // disable the `Settings` action link

    }

}