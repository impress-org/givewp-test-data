( function() {
	const { __, sprintf } = wp.i18n;
	const donationStatuses = document.querySelectorAll( 'input[name*="give_test_data_status"]' );

	const handleStatusSelect = ( e ) => {
		const element = e.currentTarget;
		const status = element.dataset.status;

		document
			.querySelectorAll( `input[name*="give_test_data_count[${ status }]"], input[name*="give_test_data_revenue[${ status }]"]` )
			.forEach( ( input ) => {
				input.toggleAttribute( 'disabled', ! element.checked );
			} );
	};

	// Show/hide donations count for status
	donationStatuses.forEach( ( button ) => {
		button.addEventListener( 'click', handleStatusSelect, false );
	} );
}() );
