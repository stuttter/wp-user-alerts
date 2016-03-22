jQuery( document ).ready( function ( $ ) {

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
			panel    = $( '#alert-message .panel' );

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

	function do_preview( the_content ) {
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
