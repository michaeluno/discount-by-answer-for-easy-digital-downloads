<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * @deprecated
 * @since   0.3.0
 */
class DiscountByAnswerForEDD_FormElement_Fields_AssociatedPosts extends DiscountByAnswerForEDD_FormElement_Base {

    protected function _getDefinition() {
        return array(
            $this->sID ? $this->sID : array(),  // the section to belong to
            array(
                'field_id'      => 'association_types',
                'title'         => __( 'Association Types', 'discount-by-answer-for-easy-digital-downloads' ),
                'type'          => 'revealer2',
                'select_type'   => 'checkbox',
                'label'         => array(
                    'download'  => __( 'Download', 'discount-by-answer-for-easy-digital-downloads' ),
                    'category'  => __( 'Category', 'discount-by-answer-for-easy-digital-downloads' ),
                    'tag'       => __( 'Tag', 'discount-by-answer-for-easy-digital-downloads' ),
                ),
                'selectors'         => array(
                    'download'  => '.edddba_assoc_download',
                    'category'  => '.edddba_assoc_category',
                    'tag'       => '.edddba_assoc_tag',
                ),
//                'default'       => array(
//                    'download'  => true,
//                ),
                'class'           => array(
                    'fieldrow'  => 'edddba_assoc_type',
                ),
            ),
            array(
                'field_id'        => 'downloads',
                'type'            => 'select2',
                'title'           => __( 'Associated Downloads', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'     => __( 'Enter the download titles to associate with this campaign.', 'discount-by-answer-for-easy-digital-downloads' ),
                'options'         => array(
                    'minimumInputLength' => 2,
                    'width' => '33%',
                    'is_multiple' => true,
                ),
                'callback'        => array(
                    // If the `search` callback is set, the field will be AJAX based.
                    'search'    => 'DiscountByAnswerForEDD_PluginUtility::getDownloads',
                ),
                'class'           => array(
                    'fieldrow'  => 'edddba_assoc_download',
                ),
            ),
            array(
                'field_id'        => 'categories',
                'type'            => 'select2',
                'title'           => __( 'Associated Categories', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'     => __( 'Enter the download categories to associate with this campaign.', 'discount-by-answer-for-easy-digital-downloads' ),
                'options'         => array(
                    'minimumInputLength' => 2,
                    'width' => '33%',
                    'is_multiple' => true,
                ),
                'callback'        => array(
                    // If the `search` callback is set, the field will be AJAX based.
                    'search'    => 'DiscountByAnswerForEDD_PluginUtility::getDownloadCategories',
                ),
                'class'           => array(
                    'fieldrow'  => 'edddba_assoc_category',
                ),
            ),
            array(
                'field_id'        => 'tags',
                'type'            => 'select2',
                'title'           => __( 'Associated Tags', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'     => __( 'Enter the download tags to associate with this campaign.', 'discount-by-answer-for-easy-digital-downloads' ),
                'options'         => array(
                    'minimumInputLength' => 2,
                    'width' => '33%',
                    'is_multiple' => true,
                ),
                'callback'        => array(
                    // If the `search` callback is set, the field will be AJAX based.
                    'search'    => 'DiscountByAnswerForEDD_PluginUtility::getDownloadTags',
                ),
                'class'           => array(
                    'fieldrow'  => 'edddba_assoc_tag',
                ),
            ),
            array(
                'field_id'      => 'exclusion_types',
                'title'         => __( 'Exclusion', 'discount-by-answer-for-easy-digital-downloads' ),
                'type'          => 'revealer2',
                'select_type'   => 'checkbox',
                'label'         => array(
                    'download'  => __( 'Downloads', 'discount-by-answer-for-easy-digital-downloads' ),
                    'category'  => __( 'Categories', 'discount-by-answer-for-easy-digital-downloads' ),
                    'tag'       => __( 'Tags', 'discount-by-answer-for-easy-digital-downloads' ),
                ),
                'selectors'         => array(
                    'download'  => '.edddba_disassoc_download',
                    'category'  => '.edddba_disassoc_category',
                    'tag'       => '.edddba_disassoc_tag',
                ),
            ),
            array(
                'field_id'        => 'excluded_downloads',
                'type'            => 'select2',
                'title'           => __( 'Excluded Downloads', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'     => __( 'Enter the download titles to exclude from liking to the campaign.', 'discount-by-answer-for-easy-digital-downloads' ),
                'options'         => array(
                    'minimumInputLength' => 2,
                    'width' => '33%',
                    'is_multiple' => true,
                ),
                'callback'        => array(
                    // If the `search` callback is set, the field will be AJAX based.
                    'search'    => 'DiscountByAnswerForEDD_PluginUtility::getDownloads',
                ),
                'class'           => array(
                    'fieldrow'  => 'edddba_disassoc_download',
                ),
            ),
            array(
                'field_id'        => 'excluded_categories',
                'type'            => 'select2',
                'title'           => __( 'Excluded Categories', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'     => __( 'Enter the download categories to exclude from liking to the campaign.', 'discount-by-answer-for-easy-digital-downloads' ),
                'options'         => array(
                    'minimumInputLength' => 2,
                    'width' => '33%',
                    'is_multiple' => true,
                ),
                'callback'        => array(
                    // If the `search` callback is set, the field will be AJAX based.
                    'search'    => 'DiscountByAnswerForEDD_PluginUtility::getDownloadCategories',
                ),
                'class'           => array(
                    'fieldrow'  => 'edddba_disassoc_category',
                ),
            ),
            array(
                'field_id'        => 'excluded_tags',
                'type'            => 'select2',
                'title'           => __( 'Excluded Tags', 'discount-by-answer-for-easy-digital-downloads' ),
                'description'     => __( 'Enter the download tags to exclude from liking to the campaign.', 'discount-by-answer-for-easy-digital-downloads' ),
                'options'         => array(
                    'minimumInputLength' => 2,
                    'width' => '33%',
                    'is_multiple' => true,
                ),
                'callback'        => array(
                    // If the `search` callback is set, the field will be AJAX based.
                    'search'    => 'DiscountByAnswerForEDD_PluginUtility::getDownloadTags',
                ),
                'class'           => array(
                    'fieldrow'  => 'edddba_disassoc_tag',
                ),
            ),
        );

    }

}