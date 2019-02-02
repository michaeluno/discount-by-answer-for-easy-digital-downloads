
( function($){
    $( document ).ready( function() {

        if ( 'undefined' === typeof edd_global_vars ) {
            return;
        }

        // Form field visibility
        $( '.edddba_show' ).show();
        $( '.edddba_wrap' ).hide();

        // When the expand link is clicked, expand the wrap container
        $( 'body' ).on( 'click', '.edddba_show > .edddba_link', function( e ) {
            e.preventDefault();
            $( this ).closest( '.edddba_show' ).hide();
            var $_fieldset = $( this ).closest( 'fieldset' );
            $_fieldset.find( '.edddba_hide' ).show();
            $_fieldset.find( '.edddba_wrap' ).show();
            $_fieldset.find( 'input.edddba_input' ).focus();
        });
        $( 'body' ).on( 'click', '.edddba_hide > .edddba_link', function( e ) {
            e.preventDefault();
            var $_fieldset = $( this ).closest( 'fieldset' );
            $_fieldset.find( '.edddba_hide' ).hide();
            $_fieldset.find( '.edddba_wrap' ).hide();
            $_fieldset.find( '.edddba_show' ).show();
        });

        $( '.edddba_wrap' ).on( 'click', '.edd-apply-discount', searchAnswer );

    } );

    var _bProcessingSearch = false;

    function searchAnswer( event ) {

        event.preventDefault();

        if ( _bProcessingSearch ) {
            return false;
        }

        var $_container     = $( event.target ).closest( 'fieldset' );
        var _sCampaignID    = $_container.find( 'input.edddba_data' ).attr( 'data-campaignID' );
        // var $_spinner       = $_container.find( '.edddba_loader' );
        var $_spinner       = $_container.find( '.edddba_loader' )
            .html( $( $( '#edd-discount-loader' )[0].outerHTML ).show() );

        // Check required fields
        $_container.find( '.edddba_errored' )
            .filter( function() {
                if ( 'checkbox' === $( this ).attr( 'type' ) && ( ! $( this ).is( ':checked' ) ) ) {
                    return true;    // checkbox and not checked
                }
                return $(this).val() != "";
            })
            .removeClass( 'edddba_errored' );
        var $_requiredInputs = $_container
            .find( 'input[type=text][data-required=1], textarea[data-required=1],input[type=checkbox][data-required=1]:not(:checked)' )
            .filter( function() {
                return ( 'checkbox' === $(this).attr( 'type' ) ) || ( $(this).val() == "" );
            });
        if ( $_requiredInputs.length ) {
            $_container.find( '.edddba_error_wrap' )
                .html( '<span class="edd_error">' + edddbaRequests.labels.missingRequired + '</span>' )
                .show();
            $_requiredInputs.addClass( 'edddba_errored' );
            return false;
        }

        // Send an ajax request
        var postData = {
            action: 'edddba_search_answer',   // WP Cron action slug suffix for `wp_ajax_{...}` and `wp_ajax_wp_ajax_nopriv_{...}`
            // answer: _sAnswer,
            // downloadIDs: _sDownloadIDs,
            campaignID: _sCampaignID,
            form: $( '#edd_purchase_form' ).serialize()
        };

        $_container.find( '.edddba_error_wrap, .edddba_notice_wrap' ).html( '' ).hide();
        $_spinner.show();

        _bProcessingSearch = true;
        $.ajax({
            type: "POST",
            data: postData,
            dataType: "json",
            url: edd_global_vars.ajaxurl,
            xhrFields: {
                withCredentials: true
            },
            success: function ( response ) {
                if( ! response ) {
                    if ( window.console && window.console.log ) {
                        console.log( response );
                    }
                    return;
                }

console.log( 'response succeeded', response );
                if ( ! response.code ) {
                    $_container.find( '.edddba_notice_wrap' )
                        .html( '<span class="edd_error">' + response.message + '</span>' )
                        .show();
                    return;
                }

                // The main error message.
                if ( response.message ) {
                    $_container.find( '.edddba_error_wrap' )
                        .html( '<span class="edd_error">' + response.message + '</span>' )
                        .show();
                }

                // Invalid field errors
                $_container.find( '.edddba_field_error_wrap' ).remove();    // previously displayed items
                var $_fieldErrorCopy = $_container.find( '.edddba_error_wrap' )
                    .first().clone()
                    .addClass( 'edddba_field_error_wrap' ).attr( 'id', '' ).hide();

                $.each( response.field_errors, function( sSlug, sMessage ) {

                    // Mark input borders in red
                    $_container.find( 'input[name*="[' + sSlug + ']"],textarea[name*="[' + sSlug + ']"],select[name*="[' + sSlug + ']"]' )
                        .addClass( 'edddba_errored' );

                    // Show a field error message
                    if ( ! sMessage ) {
                        return true;    // continue
                    }
                    var $_fieldErrorCopyEach = $_fieldErrorCopy.clone().show().attr( 'style', '' ); // for unknown reasons style: inline-block gets inserted so remove it.
                    $_fieldErrorCopyEach.html( '<span class="edd_error">' + sMessage + '</span>' );
                    $_container.find( '.edddba_error_wrap' ).last().after( $_fieldErrorCopyEach );

                });
                $_fieldErrorCopy.remove();
            }
        }).fail(function (data) {
            if ( window.console && window.console.log ) {
                console.log( 'failed edddba request' );
                console.log( data );
            }
        }).complete( function( jqXHR, textStatus ){
            $_spinner.hide();
            _bProcessingSearch = false;
        } );

        return false;
    }   // searchAnswer()

}(jQuery));
