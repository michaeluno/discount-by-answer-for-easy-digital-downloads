( function($){

    // Thick box
    $( document ).ready( function() {

        $( document ).on( 'discount-by-answer-for-easy-digital-downloads_repeated_field', handleFieldRepeatForShowingAnsweredKeywords );
        $( '.edddba_answered_keywords_open' ).click( showAnsweredKeywords );

        function handleFieldRepeatForShowingAnsweredKeywords( event, iCallType, $sections ) {
            var $_section = $( event.target ).closest( '.discount-by-answer-for-easy-digital-downloads-section' );
            var $_loadButton = $_section.find( '.edddba_answered_keywords_open' );
            if ( $_loadButton.attr( 'data-boundClick' ) ) {
                // 'already bound'
                return;
            }
            $_loadButton.on( 'click', showAnsweredKeywords );
            $_loadButton.attr( 'data-boundClick', true );
        }
        function showAnsweredKeywords( event ) {
            event.preventDefault();
            var _sID = 'answered_keywords';
            $( '#' + _sID ).remove();   // remove one previously shown
            var $_content = $(
                '<div id="' + _sID + '" class="edddba_think_box_converted_answers" style="display:none;">'
                    + '<div id="edddba_thikbox_loading">'
                        + '<p>' + edddbaMetaBox.labels.loading + ' <img src="' + edddbaMetaBox.spinnerURL + '" /></p>'
                    + '</div>'
                + '</div>'
            );
            $( this ).parent().append( $_content );

            var $_section = $( this ).closest( '.discount-by-answer-for-easy-digital-downloads-section' );
            var _sSlug    = $_section.find( 'input[name*="[slug]"]' ).val();
            var _iCampaignID = edddbaMetaBox.campaignID;

            tb_show(
                edddbaMetaBox.labels.answered_keywords + ': ' + _sSlug,
                "?TB_inline?&width=600&height=550&inlineId=" + _sID
            );

            $.ajax({
                type: "POST",
                data: {
                    action: 'edddba_load_answered_keywords',   // WP Cron action slug suffix for `wp_ajax_{...}` and `wp_ajax_wp_ajax_nopriv_{...}`
                    campaignID: _iCampaignID,
                    slug: _sSlug,
                },
                dataType: "json",
                url: edddbaMetaBox.ajaxURL,
                // xhrFields: {
                //     withCredentials: true
                // },
                success: function ( response ) {
                    $( '#edddba_thikbox_loading' ).replaceWith( response.message );
                }
            }).fail(function (data) {
                if ( window.console && window.console.log ) {
                    console.log( 'The Ajax request failed.' );
                    console.log( data );
                }
                $( '#edddba_thikbox_loading' ).html( '<p>Failed to load...</p>' );
            }).complete( function( jqXHR, textStatus ){
            } );
            return false;
        }

    } );

    // Test Keyword Search
    $( document ).ready( function() {
        $( '.edddba_test' ).on( 'click', '.fieldset-button', searchAnswerForTest );
        $( document ).on( 'discount-by-answer-for-easy-digital-downloads_repeated_field', handleCheckButtonOnFieldRepeat );
    } );

    /**
     * Handles the check button event binding when the section is repeated.
     * @param event
     * @param iCallType
     * @param $sections
     */
    function handleCheckButtonOnFieldRepeat( event, iCallType, $sections ) {

        var $_field       = $( event.target )
            .closest( '.discount-by-answer-for-easy-digital-downloads-field' );
        var $_checkButton = $_field.find( '.fieldset-button' );
        if ( $_checkButton.attr( 'data-boundClick' ) ) {
            // 'already bound'
            return;
        }
        $_checkButton.on( 'click', searchAnswerForTest );
        $_checkButton.attr( 'data-boundClick', true );

        // some clean-ups for the left over from the original section
        $_field.find( '.edddba_test_check_message' ).remove();

    }

    var _bProcessingSearch = false;

    function searchAnswerForTest( event ) {

        event.preventDefault();

        if ( _bProcessingSearch ) {
            // console.log( 'still searching' );
            return false;
        }

        var $_target        = $( event.target );
        var $_container     = $_target.closest( '.discount-by-answer-for-easy-digital-downloads-field' )
            .parent()
            .closest( '.discount-by-answer-for-easy-digital-downloads-field' );
        var $_testFieldset  = $_container.find( '.edddba_test' );
        var _sKeyword       = $_testFieldset.find( 'input[type=text]' ).val();
        var _sExcludes      = $_container.find( 'textarea[name*="[exclude]"]' ).val();
        var _bCaseSensitive = $_container.find( '.edddba_case_sensitivity input[type=checkbox]' ).is( ':checked' );
        var $_messageWrap   = $_container.find( '.test-message-container' );

        // Remove previous ones to prevent duplicates
        $_container.find( '.edddba_test_check_message' ).remove();
        $_container.find( '.edded_spinner' ).remove();

        // First retrieve the search areas
        var _aSearchAreas = [];
        $_container.find( 'fieldset.edddba_url' ).each( function( index, value ){
            var $_field = $( this ).closest( '.discount-by-answer-for-easy-digital-downloads-field' );
            var _aXPaths = [];
            $_field.find( 'fieldset.edddba_xpaths input[type=text]' ).each( function( index, value ){
                _aXPaths.push( {
                    id: $( this ).attr( 'id' ),
                    value: $( this ).val(),
                });
            } );
            var $_inputTextArea = $_field.find( '.edddba_textarea textarea' );
            var $_inputURL = $_field.find( '.edddba_url input[type=url]' );
            var _aSearch = {
                type: $_field.find( '.edddba_reference_type :selected' ).val(),
                text: {
                    id: $_inputTextArea.attr( 'id' ),
                    value: $_inputTextArea.val()
                },
                url: {
                    id: $_inputURL.attr( 'id' ),
                    value: $_inputURL.val()
                },
                xpaths: _aXPaths
            };
            _aSearchAreas.push( _aSearch );
        } );

        // Error checking for empty inputs
        if ( undefined === typeof _sKeyword || '' === _sKeyword ) {
            var _sIcon  = 'dashicons dashicons-warning';
            var $_error = $( "<span class='edddba_test_check_message edddba_error'><span class='" + _sIcon + "'></span>" + edddbaMetaBox.labels.empty_string + "</span>" );
            $_messageWrap.prepend( $_error );
            return false;
        }

        // Start processing
        _bProcessingSearch = true;
        var $_spinner = $( "<img class='edded_spinner' src='" + edddbaMetaBox.spinnerURL + "' />" );

        $.ajax({
            type: "POST",
            data: {
                action: 'edddba_search_answer_check_test',   // WP Cron action slug suffix for `wp_ajax_{...}` and `wp_ajax_wp_ajax_nopriv_{...}`
                answer: _sKeyword,
                searchAreas: _aSearchAreas,
                exclude: _sExcludes,
                caseSensitive: _bCaseSensitive ? 1 : 0, // somehow a boolean value gets sent as a string value
            },
            dataType: "json",
            url: edddbaMetaBox.ajaxURL,
            beforeSend: function( xhr ) {
                $_spinner.insertAfter( $_target );
            },
            // xhrFields: {
            //     withCredentials: true
            // },
            success: function ( response ) {

                var _sClass = response.code
                    ? 'edddba_error'
                    : 'edddba_success';
                var _sIcon  = response.code
                    ? 'dashicons dashicons-warning'
                    : 'dashicons dashicons-yes';
                var $_message = $( '<span class="edddba_test_check_message ' + _sClass + '"><span class="' + _sIcon + ' "></span>' + response.message + '</span>' );

                $_messageWrap.prepend( $_message );

                $.each( response.field_errors, function( key, value ) {
                    var $_fieldError = $( '<span class="edddba_test_check_message edddba_error"><span class="dashicons dashicons-warning"></span>' + value + '</span>' );
                    $_fieldError.insertAfter( $( '#' + key ).parent().parent() );
                });

            }
        }).fail(function (data) {
            if ( window.console && window.console.log ) {
                console.log( 'The Ajax request failed.' );
                console.log( data );
            }
            var $_error = $( '<span class="edddba_test_check_message edddba_error">' + edddbaMetaBox.labels.ajax_error + '</span>' );
            $_messageWrap.prepend( $_error );
        }).complete( function( jqXHR, textStatus ){
            $_spinner.remove();
            _bProcessingSearch = false;
        } );

        return false;
    }   // searchAnswer()

}(jQuery));