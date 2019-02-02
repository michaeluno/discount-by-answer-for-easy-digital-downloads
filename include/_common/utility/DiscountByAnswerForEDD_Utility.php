<?php
/**
 * Discount by Answer for Easy Digital Downloads
 * 
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 * 
 */

/**
 * Provides utility methods.
 * @since    0.0.1       Changed the name from `DiscountByAnswerForEDD_Utilities`.
 */
class DiscountByAnswerForEDD_Utility extends DiscountByAnswerForEDD_AdminPageFramework_WPUtility {

    /**
     * @return array
     * @since   0.9.0
     */
    static public function getTimeUnitLabels( $iKey=0 ) {

        $_aLabels = array(
            60       => __( 'minute(s)', 'discount-by-answer-for-easy-digital-downloads' ),
            3600     => __( 'hour(s)', 'discount-by-answer-for-easy-digital-downloads' ),
            86400    => __( 'day(s)', 'discount-by-answer-for-easy-digital-downloads' ),
            604800   => __( 'week(s)', 'discount-by-answer-for-easy-digital-downloads' ),
        );
        return isset( $_aLabels[ $iKey ] )
            ? $_aLabels[ $iKey ]
            : $_aLabels;

    }

    /**
     * @param $sNeedle
     * @param $sHaystack
     * @param bool $bCaseSensitive
     *
     * @return bool
     * @since   0.1.0
     */
    static public function hasSubstring( $sNeedle, $sHaystack, $bCaseSensitive=false ) {
        if ( '' === $sNeedle ) {
            return false;
        }
        return $bCaseSensitive
            ? false !== strpos( $sHaystack, $sNeedle )
            : false !== stripos( $sHaystack, $sNeedle );
    }

    /**
     * @remark  used upon plugin uninstall.
     * @param   string $sDirectoryPath
     * @return  bool|null
     * @since   0.0.8
     */
    static public function isDirectoryEmpty( $sDirectoryPath ) {
        if ( ! is_readable( $sDirectoryPath ) ) {
            return null;
        }
        return ( count( scandir( $sDirectoryPath ) ) == 2 );
    }

    /**
     * @remark  used upon plugin uninstall.
     * @param   $sDirectoryPath
     * @since   0.0.8
     */
    static public function removeDirectoryRecursive( $sDirectoryPath ) {

        if ( ! is_dir( $sDirectoryPath ) ) {
            return;
        }
        $_aItems = scandir( $sDirectoryPath );
        foreach( $_aItems as $_sItem ) {
            if ( $_sItem !== "." && $_sItem !== ".." ) {
                if (is_dir($sDirectoryPath . "/" . $_sItem ) ) {
                    self::removeDirectoryRecursive($sDirectoryPath . "/" . $_sItem );
                    continue;
                }
                unlink($sDirectoryPath . "/" . $_sItem );
            }
        }
        rmdir( $sDirectoryPath );

    }

}