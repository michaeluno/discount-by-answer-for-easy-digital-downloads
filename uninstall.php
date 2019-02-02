<?php
/**
 * Cleans up the plugin options.
 *    
 * @package      Discount by Answer for Easy Digital Downloads
 * @copyright    Copyright (c) 2019, <Michael Uno>
 * @author       Michael Uno
 * @authorurl    http://en.michaeluno.jp/
 * @since        0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/* 
 * Plugin specific constant. 
 * We are going to load the main file to get the registry class. And in the main file, 
 * if this constant is set, it will return after declaring the registry class.
 **/
if ( ! defined( 'DOING_PLUGIN_UNINSTALL' ) ) {
    define( 'DOING_PLUGIN_UNINSTALL', true  );
}

/**
 * Set the main plugin file name here.
 */
$_sMainPluginFileName  = 'discount-by-answer-for-easy-digital-downloads.php';
if ( file_exists( dirname( __FILE__ ). '/' . $_sMainPluginFileName ) ) {
   include( $_sMainPluginFileName );
}

if ( ! class_exists( 'DiscountByAnswerForEDD_Registry' ) ) {
    return;
}

// 0. Delete the temporary directory
$_sTempDirPath      = rtrim( sys_get_temp_dir(), '/' ) . '/' . DiscountByAnswerForEDD_Registry::$sTempDirBaseName;
$_sTempDirPath_Site = $_sTempDirPath . '/' . md5( site_url() );
if ( file_exists( $_sTempDirPath_Site ) && is_dir( $_sTempDirPath_Site ) ) {
    DiscountByAnswerForEDD_Utility::removeDirectoryRecursive( $_sTempDirPath_Site );
}
/// Consider other sites on the same server use the plugin
if ( is_dir( $_sTempDirPath ) && DiscountByAnswerForEDD_Utility::isDirectoryEmpty( $_sTempDirPath ) ) {
    DiscountByAnswerForEDD_Utility::removeDirectoryRecursive( $_sTempDirPath );
}

// 1. Delete transients
$_aPrefixes = array(
    DiscountByAnswerForEDD_Registry::TRANSIENT_PREFIX, // the plugin transients
    'apf_',      // the admin page framework transients
);
foreach( $_aPrefixes as $_sPrefix ) {
    if ( ! $_sPrefix ) { 
        continue; 
    }
    $GLOBALS[ 'wpdb' ]->query( "DELETE FROM `" . $GLOBALS[ 'table_prefix' ] . "options` WHERE `option_name` LIKE ( '_transient_%{$_sPrefix}%' )" );
    $GLOBALS[ 'wpdb' ]->query( "DELETE FROM `" . $GLOBALS[ 'table_prefix' ] . "options` WHERE `option_name` LIKE ( '_transient_timeout_%{$_sPrefix}%' )" );
}

// 2. Delete plugin data
$_oOption  = DiscountByAnswerForEDD_Option::getInstance();
if ( ! $_oOption->get( array( 'delete', 'delete_upon_uninstall' ) ) ) {
    return true;
}

// Options stored in the `options` table.
array_walk_recursive( 
    DiscountByAnswerForEDD_Registry::$aOptionKeys, // subject array
    'delete_option'   // function name
);

// Delete custom tables
foreach( DiscountByAnswerForEDD_Registry::$aDatabaseTables as $_aTable ) {
    if ( ! class_exists( $_aTable[ 'class_name' ] ) ) {
        continue;
    }
    $_oTable  = new $_aTable[ 'class_name' ];
    if ( ! method_exists( $_oTable, 'uninstall' ) ) {
        continue;
    }
    $_oTable->uninstall();
}

// Remove user meta keys used by the plugin
foreach( DiscountByAnswerForEDD_Registry::$aUserMetas as $_sMetaKey => $_v ) {
    delete_metadata(
        'user',    // the user meta type
        0,  // does not matter here as deleting them all
        $_sMetaKey,
        '', // does not matter as deleting
        true // whether to delete all
    );
}

// Delete custom post type posts
foreach( DiscountByAnswerForEDD_Registry::$aPostTypes as $_sKey => $_sPostTypeSlug ) {
    $_oResults   = new WP_Query(
        array(
            'post_type'      => $_sPostTypeSlug,
            'posts_per_page' => -1,     // `-1` for all
            'fields'         => 'ids',  // return only post IDs by default.
        )
    );
    foreach( $_oResults->posts as $_iPostID ) {
        wp_delete_post( $_iPostID, true );
    }
}

