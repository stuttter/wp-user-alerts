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
			panel    = $( '#alert-excerpt .panel' );

		panel.attr( 'data-priority', priority );
	} );

	$( 'textarea.wp-editor-area' ).bind( 'input propertychange', function() {
		$( '.alert-post-content' ).html( $( this ).val() );
	} );
} );
