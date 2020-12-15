const consistentInfo = document.querySelector( '.give-test-data-info-expand' );

if ( consistentInfo ) {
	consistentInfo.addEventListener( 'click', function( e ) {
		const info = document.querySelector( '.give-test-data-info-container' );

		if ( info ) {
			info.classList.toggle( 'give-hidden', ! e.target.checked );
		}
	}, false );
}
