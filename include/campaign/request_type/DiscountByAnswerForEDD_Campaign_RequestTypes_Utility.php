<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 ** @since   0.8.0
 */
class DiscountByAnswerForEDD_Campaign_RequestTypes_Utility extends DiscountByAnswerForEDD_PluginUtility {

    /**
     * Finds a keywords to exclude from acceptable answers.
     * @param   string $sKeyword
     * @param   array $aExcludes
     * @param   boolean $bCaseSensitive
     *
     * @return bool
     */
    static public function hasExcludedKeyword( $sKeyword, array $aExcludes, $bCaseSensitive ) {
        foreach( $aExcludes as $_sExclude ) {
            $_sExclude = ( string ) trim( $_sExclude );
            if ( '' === $_sExclude ) {
                continue;
            }
            if ( self::hasSubstring( $_sExclude, $sKeyword, $bCaseSensitive ) ) {
                return true;
            }
        }
        return false;
    }

}