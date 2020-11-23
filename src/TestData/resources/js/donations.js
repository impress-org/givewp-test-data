import API, { CancelToken } from './api';

const { __, sprintf } = wp.i18n;
const donationStatuses = document.querySelectorAll( 'input[name*="give_test_data_status"]' );
const generateDonationsBtn = document.querySelector( '#give-test-data-generate-donations' );

const handleStatusSelect = ( e ) => {
	const element = e.currentTarget;
	const status = element.dataset.status;

	document
		.querySelectorAll( `input[name*="give_test_data_count[${ status }]"], input[name*="give_test_data_revenue[${ status }]"]` )
		.forEach( ( input ) => {
			input.toggleAttribute( 'disabled', ! element.checked );
		} );
};

/**
 * @param  {HTMLElement} element Selected donation element
 * @return {{revenue: number, statusName: string, count: number, status: string}} element state
 */
const getSelectedStatusState = ( element ) => {
	const { status, name } = element.dataset;
	return {
		status,
		statusName: name,
		count: parseInt( document.querySelector( `input[name*="give_test_data_count[${ status }]"]` ).value ),
		revenue: parseInt( document.querySelector( `input[name*="give_test_data_revenue[${ status }]"]` ).value ),
	};
};

const processHasErrors = () => document.querySelector( '.give-test-data-process-error' );

const donationsGenerationStart = () => {
	showGenerateButton( false );
	updateDescription( 'Initializing...' );
	updateProgerssBar( 0 );
};

const showGenerateButton = ( show ) => {
	// Update title
	document
		.querySelector( '.give-popup-form-button' )
		.classList.toggle( 'give-hidden', ! show );
};

const updateDescription = ( description ) => {
	const descriptionElement = document.querySelector( '.give-test-data-description-container' );

	// Create element if not exist
	if ( ! descriptionElement ) {
		const element = document.createElement( 'p' );
		element.classList.add( 'give-test-data-description-container' );
		element.innerHTML = description;
		document.querySelector( '.give-modal__title' ).after( element );
	} else {
		descriptionElement.innerHTML = description;
	}
};

const showRequestError = ( error ) => {
	const element = document.querySelector( '.give-modal__description' );
	const descriptionElement = document.querySelector( '.give-test-data-description-container' );

	if ( element ) {
		element.innerHTML = `<p class="give-test-data-process-error" style="color: red;">${ error }</p>`;
	}

	if ( descriptionElement ) {
		descriptionElement.remove();
	}
};

const updateProgerssBar = ( percent ) => {
	const element = document.querySelector( '.give-modal__description' );

	if ( element ) {
		element.innerHTML = `<div class="give-progress"><div style="width:${ parseInt( percent ) }%;"></div></div>`;
	}
};

const getSelectedDonationsStatusesData = () => {
	const data = {};
	const selectedStatuses = document.querySelectorAll( 'input[name*="give_test_data_status"]:checked' );
	let totalDonationsCount = 0;

	for ( const selectedStatus of selectedStatuses ) {
		const { status, count, revenue, statusName } = getSelectedStatusState( selectedStatus );
		data[ status ] = { count, revenue, statusName };
		totalDonationsCount += count;
	}

	return { data, totalDonationsCount };
};

const generateDonations = async( e ) => {
	e.preventDefault();

	const { data, totalDonationsCount } = getSelectedDonationsStatusesData();

	// Check for selected donations
	if ( ! Object.keys( data ).length ) {
		// eslint-disable-next-line no-undef
		return new Give.modal.GiveWarningAlert( {
			modalContent: {
				title: __( 'No donations are selected', 'give-test-data' ),
				desc: __( 'You must select at least one donation status to generate', 'give-test-data' ),
				cancelBtnTitle: __( 'OK', 'give-test-data' ),
			},
		} ).render();
	}

	// Check each status individually
	for ( const [ , statusData ] of Object.entries( data ) ) {
		if ( ! statusData.count ) {
			// eslint-disable-next-line no-undef
			return new Give.modal.GiveWarningAlert( {
				modalContent: {
					title: sprintf( __( 'Enter donation count for status %s', 'give-test-data' ), statusData.statusName ),
					desc: __( 'You must enter donations count for each status', 'give-test-data' ),
					cancelBtnTitle: __( 'OK', 'give-test-data' ),
				},
			} ).render();
		}
	}

	// eslint-disable-next-line no-undef
	new Give.modal.GiveFormModal( {
		modalContent: {
			title: __( 'Generate donations', 'give-test-data' ),
			desc: sprintf( __( 'Generate %s donations?', 'give-test-data' ), totalDonationsCount ),
			cancelBtnTitle: __( 'Close', 'give-test-data' ),
			confirmBtnTitle: __( 'Generate', 'give-test-data' ),
			link: '',
			link_text: '',
		},
		async successConfirm() {
			donationsGenerationStart();

			for ( const [ status, statusData ] of Object.entries( data ) ) {
				if ( statusData.revenue ) {
					statusData.revenue = parseInt( statusData.revenue ) / parseInt( statusData.count );
				}
				await generateDonationsRequest( {
					status,
					count: statusData.count,
					revenue: statusData.revenue,
					statusName: statusData.statusName,
					total: statusData.count,
				} );
			}

			if ( ! processHasErrors() ) {
				window.location.reload( true );
			}
		},
	} ).render();
};

const generateDonationsRequest = ( { status, count, revenue, statusName, total } ) => {
	return API.post( '/generate-donations', { status, count, revenue }, { cancelToken: CancelToken.token } )
		.then( async( response ) => {
			updateProgerssBar( ( total - count ) / total * 100 );
			// Update description only once
			if ( count === total ) {
				updateDescription( sprintf( __( 'Generating donations with status <string>%s</string>', 'give-test-data' ), statusName ) );
			}
			// Check if it has more donations to process
			if ( response.data.status && response.data.hasMore ) {
				await generateDonationsRequest( {
					status,
					count: response.data.hasMore,
					revenue,
					statusName,
					total,
				} );
			}
		} )
		.catch( ( err ) => {
			if ( err.response ) {
				// eslint-disable-next-line no-console
				console.error( err.response.data );
				showRequestError( err.response.data.message );
			}

			CancelToken.cancel();
		} );
};

// Generate donations
if ( generateDonationsBtn ) {
	generateDonationsBtn.addEventListener( 'click', generateDonations, false );
}

// Show/hide donations count for status
donationStatuses.forEach( ( button ) => {
	button.addEventListener( 'click', handleStatusSelect, false );
} );
