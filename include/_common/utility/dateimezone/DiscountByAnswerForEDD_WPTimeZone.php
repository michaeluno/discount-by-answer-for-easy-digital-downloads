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
 * @deprecated Nor supported in PHP 5.2.x
 */
class DiscountByAnswerForEDD_WPTimeZone extends DateTimeZone {

    /**
     * Determine time zone from WordPress options and return as object.
     *
     * @return self
     */
    public static function getWpTimeZone() {

        $timezone_string = get_option( 'timezone_string' );

        if ( ! empty( $timezone_string ) ) {
            return new self( $timezone_string );
        }

        $offset  = get_option( 'gmt_offset' );
        $hours   = (int) $offset;
        $minutes = ( $offset - floor( $offset ) ) * 60;
        $offset  = sprintf( '%+03d:%02d', $hours, $minutes );
        return new self( $offset );

    }

}