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

	var the_editor  = $( 'textarea.wp-editor-area' ),
		the_excerpt = $( 'textarea.alert-excerpt'  ),
		the_preview = $( '.alert-post-content'     ),
		the_pickers = $( '.alerts-picker'          ),
		the_length  = $( '.alert-excerpt-length'   ),
		the_height  = 200,
		the_content = '';

	the_editor.bind( 'input propertychange', function() {
		if ( 0 === the_excerpt.val().length ) {
			do_preview( the_editor.val() );
		}
	} );

	the_excerpt.bind( 'input propertychange', function() {
		do_preview( the_excerpt.val() );
	} );

	function do_preview( the_content ) {
		if ( the_content.length ) {
			the_content = the_content.replace( /(\r\n|\n|\r)/gm, '<br />' );
		}
		the_preview.html( the_content );
		the_length.html( the_content.length );

		the_height  = $( the_excerpt ).height() + $( the_preview ).height() + 94;
		console.log( $( the_excerpt ).height() + $( the_preview ).height() );
		the_pickers.height( the_height );
	}
} );
