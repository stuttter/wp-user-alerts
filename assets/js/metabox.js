jQuery( document ).ready( function ( $ ) {

	// Hides the tab content.
	jQuery( '.wp-vertical-tabs .tab-content' ).hide();

	// Shows the first tab's content.
	jQuery( '.wp-vertical-tabs .tab-content:first-child' ).show();

	// Makes the 'aria-selected' attribute true for the first tab nav item.
	jQuery( '.tab-nav :first-child' ).attr( 'aria-selected', 'true' );

	// Copies the current tab item title to the box header.
	jQuery( '.which-tab' ).text( jQuery( '.tab-nav :first-child a' ).text() );

	// When a tab nav item is clicked.
	jQuery( '.tab-nav li a' ).click(
		function( j ) {

			// Prevent the default browser action when a link is clicked.
			j.preventDefault();

			// Get the `href` attribute of the item.
			var href = jQuery( this ).attr( 'href' );

			// Hide all tab content.
			jQuery( this ).parents( '.wp-vertical-tabs' ).find( '.tab-content' ).hide();

			// Find the tab content that matches the tab nav item and show it.
			jQuery( this ).parents( '.wp-vertical-tabs' ).find( href ).show();

			// Set the `aria-selected` attribute to false for all tab nav items.
			jQuery( this ).parents( '.wp-vertical-tabs' ).find( '.tab-title' ).attr( 'aria-selected', 'false' );

			// Set the `aria-selected` attribute to true for this tab nav item.
			jQuery( this ).parent().attr( 'aria-selected', 'true' );

			// Copy the current tab item title to the box header.
			jQuery( '.which-tab' ).text( jQuery( this ).text() );
		}
	); // click()

	/* People tabs */
	$( 'a', '#user-alert-who-tabs' ).click( function () {
		var t = $( this ).attr( 'href' );

		$( this ).parent().addClass( 'tabs' ).siblings( 'li' ).removeClass( 'tabs' );
		$( '#user-alert-who-tabs' ).siblings( '.tabs-panel' ).hide();
		$( t ).show();

		return false;
	} );

	/* Delivery tabs */
	$( 'a', '#user-alert-how-tabs' ).click( function () {
		var t = $( this ).attr( 'href' );

		$( this ).parent().addClass( 'tabs' ).siblings( 'li' ).removeClass( 'tabs' );
		$( '#user-alert-how-tabs' ).siblings( '.tabs-panel' ).hide();
		$( t ).show();

		return false;
	} );

	/* Preview */
	$( 'input[type=radio].alert-priority' ).change( function () {
		var priority = $( this ).data( 'priority' ),
			panel    = $( '.tab-content .panel' );

		panel.attr( 'data-priority', priority );
	} );

	var the_editor  = $( 'textarea.wp-editor-area' ),
		the_message = $( 'textarea.alert-message'  ),
		the_preview = $( '.alert-post-content'     ),
		the_pickers = $( '.alerts-picker'          ),
		the_length  = $( '.alert-message-length'   ),
		the_height  = 200,
		the_content = '';

	the_editor.bind( 'input propertychange', function() {
		if ( 0 === the_message.val().length ) {
			do_preview( the_editor.val() );
		}
	} );

	the_message.bind( 'input propertychange', function() {
		do_preview( the_message.val() );
	} );

	$( '#user-alert-message' ).on( 'click', function() {
		do_preview();
	} );

	window.do_preview = function do_preview( the_content ) {
		if ( the_content.length ) {
			the_content = the_content.replace( /(\r\n|\n|\r)/gm, '<br />' );
			the_content = the_content.substring( 0, 100 );
		}

		the_preview.html( the_content );
		the_length.html( the_content.length );

		the_height = $( the_message ).height() + $( the_preview ).height() + 94;
		the_pickers.height( the_height );
	}
} );
