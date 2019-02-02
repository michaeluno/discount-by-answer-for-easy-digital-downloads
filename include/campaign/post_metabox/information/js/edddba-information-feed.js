( function($) {

    $(document).ready(function () {

        if ( 'undefined' === typeof edddbaMetaBoxInfo ) {
            return;
        }

        // Pro features
        $( '.edddba_requires_pro input, .edddba_requires_pro select, .edddba_requires_pro textarea' ).click( showProNotification );
        $( document ).on( 'discount-by-answer-for-easy-digital-downloads_repeated_field', initializeProInputs );

        function initializeProInputs( event, iCallType, $sections ) {
            var $_section = $( event.target ).closest( '.discount-by-answer-for-easy-digital-downloads-section' );
            if ( $_section.attr( 'data-boundProAlert' ) ) {
                // 'already bound'
                return;
            }
            $_section.find( '.edddba_requires_pro input, .edddba_requires_pro select, .edddba_requires_pro textarea' ).on( 'click', showProNotification );
            $_section.attr( 'data-boundProAlert', true );
        }

        function showProNotification( event ) {
            var _sID = 'edddba_pro_notification';
            event.preventDefault();
            alert( edddbaMetaBoxInfo.labels.getPro );
            return false;
        }

        // Information Feed
        var _sTargerID    = '#edddba_information_feed';
        var _sInfoFeedURL = $( _sTargerID ).data( 'url' );
        $.ajax({
            type: "POST",
            data: {
                action: 'edddba_load_information_feed',   // WP Cron action slug suffix for `wp_ajax_{...}` and `wp_ajax_wp_ajax_nopriv_{...}`
                url: _sInfoFeedURL
            },
            dataType: "json",
            url: edddbaMetaBoxInfo.ajaxURL,
            // xhrFields: {
            //     withCredentials: true
            // },
            success: function ( response ) {
                $( _sTargerID ).replaceWith( response.message );
            }
        }).fail(function (data) {
            if ( window.console && window.console.log ) {
                console.log( 'The Ajax request failed.' );
                console.log( data );
            }
        }).complete( function( jqXHR, textStatus ){
        } );
        return false;

    });

}(jQuery));