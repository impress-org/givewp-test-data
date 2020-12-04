import API, { CancelToken } from './api';
import GiveModal from './modal';
// Common
import {
	updateDescription,
	showRequestError,
	updateProgerssBar,
	generationStart,
	AppState,
} from './utils';

const { __, sprintf } = wp.i18n;
const donationStatuses = document.querySelectorAll( 'input[name*="give_test_data_status"]' );
const generateDonationsBtn = document.querySelector( '#give-test-data-generate-donations' );
// App state
const State = new AppState( {
	error: false,
} );

const handleStatusSelect = ( e ) => {
	const element = e.currentTarget;
	const status = element.dataset.status;

	document.querySelectorAll( `input[name*="give_test_data_count[${ status }]"], input[name*="give_test_data_revenue[${ status }]"]` ).forEach( ( input ) => {
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

const getData = () => {
	const data = {
		total: 0,
		params: {},
	};

	const selectedStatuses = document.querySelectorAll( 'input[name*="give_test_data_status"]:checked' );

	// Get all inputs which name is starting with "donation"
	// These fields will be added as an additional parameter
	const additionalInputs = document.querySelectorAll( '#give-mainform input[name^="donation"]' );

	for ( const selectedStatus of selectedStatuses ) {
		const { status, count, revenue, statusName } = getSelectedStatusState( selectedStatus );

		data.statuses = {
			...data.statuses,
			[ status ]: {
				count,
				revenue,
				statusName,
			},
		};

		data.total += count;
	}

	data.startDate = document.querySelector( '#give-test-data-start-date' ).value;

	// get all additional inputs
	additionalInputs.forEach( ( input ) => {
		data.params = {
			...data.params,
			[ input.name ]: input.value,
		};
	} );

	return data;
};

const generateDonations = ( e ) => {
	e.preventDefault();

	const data = getData();

	// Check for selected donations
	if ( ! data.statuses ) {
		return new GiveModal( {
			type: 'warning',
			title: __( 'Select donation status', 'give-test-data' ),
			content: __( 'You must select at least one donation status to generate', 'give-test-data' ),
			cancelButton: __( 'OK', 'give-test-data' ),
		} );
	}

	// Check each status individually
	for ( const [ , statusData ] of Object.entries( data.statuses ) ) {
		if ( ! statusData.count ) {
			return new GiveModal( {
				type: 'warning',
				title: sprintf( __( 'Enter donation count for status %s', 'give-test-data' ), statusData.statusName ),
				content: __( 'You must enter donations count for each status', 'give-test-data' ),
				cancelButton: __( 'OK', 'give-test-data' ),
			} );
		}
	}

	new GiveModal( {
		type: 'form',
		title: __( 'Generate donations', 'give-test-data' ),
		content: sprintf( __( 'Generate %s donations?', 'give-test-data' ), data.total ),
		cancelButton: __( 'Close', 'give-test-data' ),
		confirmButton: __( 'Generate', 'give-test-data' ),
		onConfirm: async() => {
			generationStart();

			for ( const [ status, statusData ] of Object.entries( data.statuses ) ) {
				if ( statusData.revenue ) {
					statusData.revenue = parseInt( statusData.revenue ) / parseInt( statusData.count );
				}
				await generateDonationsRequest( {
					status,
					count: statusData.count,
					revenue: statusData.revenue,
					statusName: statusData.statusName,
					total: statusData.count,
					startDate: data.startDate,
					params: data.params,
				} );
			}

			if ( ! State.get( 'error' ) ) {
				window.location.reload( true );
			}
		},
		onClose: () => {
			CancelToken.cancel();
			window.location.reload( true );
		},
	} );
};

const generateDonationsRequest = ( { status, count, revenue, statusName, total, startDate, params } ) => {
	return API.post( '/generate-donations', {
		status,
		count,
		revenue,
		startDate,
		params,
	}, {
		cancelToken: CancelToken.token,
	} ).then( async( response ) => {
		// Update description only once
		if ( count === total ) {
			updateDescription( sprintf( __( 'Generating donations with status <string>%s</string>', 'give-test-data' ), statusName ) );
		}

		// Check status
		if ( response.data.status ) {
			// Check if it has more donations to process
			if ( response.data.hasMore ) {
				updateProgerssBar( ( total - count ) / total * 100 );

				await generateDonationsRequest( {
					status,
					count: response.data.hasMore,
					revenue,
					statusName,
					total,
					startDate,
					params,
				} );
			} else {
				updateProgerssBar( 100 );
			}
		} else {
			CancelToken.cancel();
			State.set( { error: true } );

			const message = response.data.message ? response.data.message : __( 'Something went wrong. Check the error log', 'give-test-data' );

			showRequestError( message );
		}
	} ).catch( ( err ) => {
		CancelToken.cancel();
		State.set( { error: true } );

		if ( err.response ) {
			// eslint-disable-next-line no-console
			console.error( err.response.data );
			showRequestError( err.response.data.message );
		}
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
