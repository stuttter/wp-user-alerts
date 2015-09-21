jQuery( document ).ready( function ( $ ) {

	/* Who tabs */
	$( 'a', '#user-alert-who-tabs' ).click( function () {
		var t = $( this ).attr( 'href' );

		$( this ).parent().addClass( 'tabs' ).siblings( 'li' ).removeClass( 'tabs' );
		$( '#user-alert-who-tabs' ).siblings( '.tabs-panel' ).hide();
		$( t ).show();

		return false;
	} );

	/* How tabs */
	$( 'a', '#user-alert-how-tabs' ).click( function () {
		var t = $( this ).attr( 'href' );

		$( this ).parent().addClass( 'tabs' ).siblings( 'li' ).removeClass( 'tabs' );
		$( '#user-alert-how-tabs' ).siblings( '.tabs-panel' ).hide();
		$( t ).show();

		return false;
	} );

	/* Preview */
	$( 'input[type=radio].alert-severity' ).change( function () {
		var severity = $( this ).data( 'severity' ),
			panel    = $( '.alert-preview .panel' );

		panel.attr( 'data-severity', severity );
	} );

	$( 'textarea.wp-editor-area' ).bind( 'input propertychange', function() {
		$( '.alert-post-content' ).html( $( this ).val() );
	} );
} );
