<?php
/**
 * Discount by Answer for Easy Digital Downloads
 *
 * http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/
 * Copyright (c) 2019 Michael Uno
 *
 */

/**
 * A utility class that deals with strings to detect and rename duplicate values.
 * @since   0.2.0
 */
class DiscountByAnswerForEDD_DuplicateRenamer {

    public $aNames = array();

    public function __construct( array $aNames ) {
        $this->aNames = $aNames;
    }

    /**
     * @return array
     */
    public function get() {

        // Make sure they are all unique
        $_aNames = $this->aNames;
        While( $this->___hasDuplicates( $_aNames ) ) {
            $_aNames = $this->___getDuplicatesRenamed( $_aNames );
        }
        return $_aNames;

    }
        private function ___hasDuplicates( array $a ) {
           // streamline per @Felix
           return count( $a ) !== count(array_unique( $a ) );

        }

        private function ___getDuplicatesRenamed( array $aSlugs ) {
            $_aParsed = array();
            foreach( $aSlugs as $_iIndex => $_sSlug ) {
                if ( in_array( $_sSlug, $_aParsed ) ) {
                    $aSlugs[ $_iIndex ] = $this->___getLastDigitIncremented( $_sSlug );
                }
                $_aParsed[] = $_sSlug;
            }
            return $aSlugs;
        }
            private function ___getLastDigitIncremented( $sString ) {
                $sString = $this->___isLastCharacterNumeric( $sString )
                    ? $sString
                    : $sString . '_0';
                return preg_replace_callback(
                    "/(.*?)(\d+)$/",
                    array( $this, '___getMatchIncremented' ),
                    $sString
                );
            }
                private function ___isLastCharacterNumeric( $sString ) {
                    return is_numeric( substr( $sString , -1, 1 ) );
                }
                private function ___getMatchIncremented( $aMatches ) {
                    return $aMatches[ 1 ] . ++$aMatches[ 2 ] ;
                }



}