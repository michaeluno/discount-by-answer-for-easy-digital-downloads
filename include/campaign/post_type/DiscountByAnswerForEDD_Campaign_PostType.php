<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @since   0.1.0
 */
class DiscountByAnswerForEDD_Campaign_PostType extends DiscountByAnswerForEDD_Campaign_PostType_ListTable {

    public function setUp() {

        new DiscountByAnswerForEDD_PostType_Campaign_PostAction_ToggleStatus( $this->oProp->sPostType );
        new DiscountByAnswerForEDD_PostType_PostAction_Delete( $this->oProp->sPostType );
        new DiscountByAnswerForEDD_PostType_Campaign_PostAction_ResetCount( $this->oProp->sPostType );

//        $_oOption = DiscountByAnswerForEDD_Option::getInstance();
        $this->setArguments(
            array(            // @see http://codex.wordpress.org/Function_Reference/register_post_type#Arguments
                'labels'                => $this->___getLabels(),
                'taxonomies'            => array( '' ), // associated taxonomies
                'hierarchical'          => false,

                // Visibility
                'public'                => false,
                'publicly_queryable'    => false,   // Whether queries can be performed on the front end as part of parse_request().
                'has_archive'           => false,
                'exclude_from_search'   => true,    // Whether to exclude posts with this post type from front end search results.
                'can_export'            => true,

                // UI
                'menu_position'         => 120,
                'supports'              => array( 'title' ), // the title input field will be removed for the Add New page e.g. array( 'title', 'editor', 'comments', 'thumbnail' ),
                'show_in_nav_menus'     => true,
                'show_ui'               => true,
                'show_in_menu'          => 'edit.php?post_type=' . 'download',
                'show_admin_column'     => true, // [3.5+ core] this is for custom taxonomies to automatically add the column in the listing table.
                'menu_icon'             => 'dashicons-megaphone',
//                'menu_icon'             => $this->oProp->bIsAdmin
//                    ? (
//                        version_compare( $GLOBALS[ 'wp_version' ], '3.8', '>=' )
//                            ? 'dashicons-rss'
//                            : plugins_url( 'asset/image/wp-logo_16x16.png', ..._Registry::$sFilePath )
//                    )
//                    : null, // do not call the function in the front-end.

                // (framework specific) this sets the screen icon for the post type for WordPress v3.7.1 or below.
                // a file path can be passed instead of a url, plugins_url( 'asset/image/wp-logo_32x32.png', APFDEMO_FILE )
                'screen_icon' => $this->oProp->bIsAdmin
                    ? (
                        version_compare( $GLOBALS[ 'wp_version' ], '3.8', '>=' )
                            ? 'dashicons-megaphone'
                            : plugins_url( 'asset/image/wp-logo_32x32.png', DiscountByAnswerForEDD_Registry::$sFilePath )
                    )
                    : null, // do not call the function in the front-end.

                // (framework specific) [3.5.10+] default: true
                'show_submenu_add_new'  => true,

                // (framework specific) [3.7.4+]
                'submenu_order_manage' => 80,   // default 5
                'submenu_order_addnew' => 21,   // default 10

            )
        );

        add_action( 'current_screen', array( $this, 'replyToSetPostTypeAreaSpecificHooks' ) );

        DiscountByAnswerForEDD_PluginUtility::isDebugMode();

        $_sCSSPath  = $this->oUtil->isDebugMode()
            ? DiscountByAnswerForEDD_Registry::$sDirPath . '/include/campaign/css/edit.css'
            : DiscountByAnswerForEDD_Registry::$sDirPath . '/include/campaign/css/edit.min.css';
        $this->enqueueStyle( $_sCSSPath, $this->oProp->aPostTypes );

    }
    /**
     * @return      array
     */
    private function ___getLabels() {
        return array(
            'name'               => __( 'Campaigns', 'discount-by-answer-for-easy-digital-downloads' ),
            'singular_name'      => __( 'Campaign', 'discount-by-answer-for-easy-digital-downloads' ),
            'menu_name'          => __( 'Discount Campaigns', 'discount-by-answer-for-easy-digital-downloads' ),
            // this changes the root menu name
            'add_new'            => __( 'Add New Campaign', 'discount-by-answer-for-easy-digital-downloads' ),
            'add_new_item'       => __( 'Add New Campaign', 'discount-by-answer-for-easy-digital-downloads' ),
            'edit'               => __( 'Edit', 'discount-by-answer-for-easy-digital-downloads' ),
            'edit_item'          => __( 'Edit Campaign', 'discount-by-answer-for-easy-digital-downloads' ),
            'new_item'           => __( 'New Campaign', 'discount-by-answer-for-easy-digital-downloads' ),
            'view'               => __( 'View', 'discount-by-answer-for-easy-digital-downloads' ),
            'view_item'          => __( 'View Campaign', 'discount-by-answer-for-easy-digital-downloads' ),
            'search_items'       => __( 'Search Campaigns', 'discount-by-answer-for-easy-digital-downloads' ),
            'not_found'          => __( 'No campaigns found', 'discount-by-answer-for-easy-digital-downloads' ),
            'not_found_in_trash' => __( 'No Campaigns Found in Trash', 'discount-by-answer-for-easy-digital-downloads' ),
            'parent'             => __( 'Parent Campaign', 'discount-by-answer-for-easy-digital-downloads' ),

            // framework specific
            'plugin_action_link' => __( 'Campaigns', 'discount-by-answer-for-easy-digital-downloads' ),
        );


    }


    /**
     * Sets up hooks that is needed in this custom post type areas in the admin.
     *
     * Includes the following admin pages.
     *
     * - post.php?post_type=fz_feed
     * - post-new.php?post_type=fz_feed
     * - edit.php?post_type=fz_feed
     *
     * @callback    action      current_screen
     */
    public function replyToSetPostTypeAreaSpecificHooks() {

        if ( ! $this->isInThePage() ) {
            return;
        }

        add_action( 'admin_enqueue_scripts', array( $this, 'replyToDisableAutoSave' ) );
        add_action( 'do_meta_boxes', array( $this, 'replyToHidePublishMetaBox' ) );

        add_filter( 'post_updated_messages', array( $this, 'replyToModifyUpdatedMessages' ) );

        add_action( 'shutdown', array( $this, 'replyToEnsurePeriodicCheckEnabled' ) );

    }
        public function replyToEnsurePeriodicCheckEnabled() {
            DiscountByAnswerForEDD_PluginUtility::schedulePeriodicEvent( 'edddba_action_clean_unused_discount_codes_daily' );
        }

    /**
     * @param $aMessages
     *
     * @return mixed
     * @see https://wordpress.stackexchange.com/a/29254
     */
        public function replyToModifyUpdatedMessages( $aMessages ) {

            global $post, $post_ID;

            $aMessages[ 'edddba_campaign' ] = $this->oUtil->getElementAsArray( $aMessages, array( 'edddba_campaign' ) )
                + array(
//                0 => '', // Unused. Messages start at index 1.
//                1 => sprintf( __('Book updated. <a href="%s">View book</a>'), esc_url( get_permalink($post_ID) ) ),
//                2 => __('Custom field updated.'),
//                3 => __('Custom field deleted.'),
                    4 => __( 'Campaign updated', 'discount-by-answer-for-easy-digital-downloads' ),
                /* translators: %s: date and time of the revision */
//                5 => isset($_GET['revision']) ? sprintf( __('Book restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
//                6 => sprintf( __('Book published. <a href="%s">View book</a>'), esc_url( get_permalink($post_ID) ) ),
//                7 => __('Book saved.'),
//                8 => sprintf( __('Book submitted. <a target="_blank" href="%s">Preview book</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
//                9 => sprintf( __('Book scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview book</a>'),
//                // translators: Publish box date format, see http://php.net/date
//                date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
//                10 => sprintf( __('Book draft updated. <a target="_blank" href="%s">Preview book</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            );

            return $aMessages;

        }

        /**
         * @callback    action      admin_enqueue_scripts
         */
        public function replyToDisableAutoSave() {
            if ( get_post_type() !== $this->oProp->sPostType ) {
                return;
            }
            wp_dequeue_script( 'autosave' );
        }
        /**
         * @callback    action  do_meta_boxes
         */
        public function replyToHidePublishMetaBox() {
            remove_meta_box( 'submitdiv', $this->oProp->sPostType, 'side' );
        }


}