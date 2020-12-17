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
const generateDonorsBtn = document.querySelector( '#give-test-data-generate-donors' );
// App state
const State = new AppState( {
	error: false,
} );

const getDonorsCount = () => {
	const data = {
		count: 0,
		params: {},
	};

	const donorCount = document.querySelector( '#give-test-data-donors-count' );
	const additionalInputs = document.querySelectorAll( '#give-mainform input[name^="donation"]' );

	// get all additional inputs
	additionalInputs.forEach( ( input ) => {
		data.params = {
			...data.params,
			[ input.name ]: ( 'checkbox' === input.type ) ? input.checked : input.value,
		};
	} );

	if ( donorCount ) {
		data.count = donorCount.value;
	}

	return data;
};

const generateDonors = ( e ) => {
	e.preventDefault();

	const { count, params } = getDonorsCount();

	// Check the donors count
	if ( ! count ) {
		return new GiveModal( {
			type: 'warning',
			title: __( 'Enter number of donors', 'give-test-data' ),
			content: __( 'You must enter the number of donors to generate', 'give-test-data' ),
			cancelButton: __( 'OK', 'give-test-data' ),
		} );
	}

	// eslint-disable-next-line no-undef
	new GiveModal( {
		type: 'form',
		title: __( 'Generate donors', 'give-test-data' ),
		content: sprintf( __( 'Generate %s donors?', 'give-test-data' ), count ),
		cancelButton: __( 'Close', 'give-test-data' ),
		confirmButton: __( 'Generate', 'give-test-data' ),
		onConfirm: async() => {
			generationStart();

			await generateDonorsRequest( {
				count,
				params,
				total: count,
			} );

			if ( ! State.get( 'error' ) ) {
				window.location.reload( true );
			}
		},
		onClose: () => {
			CancelToken.cancel();
			window.setTimeout( () => window.location.reload( true ), 300 );
		},
	} );
};

const generateDonorsRequest = ( { count, params, total } ) => {
	return API.post( '/generate-donors', {
		count, params,
	}, { cancelToken: CancelToken.token } ).then( async( response ) => {
		// Update description only once
		if ( count === total ) {
			updateDescription( __( 'Generating donors', 'give-test-data' ) );
		}

		if ( response.data.status ) {
			// Check if it has more donors to process
			if ( response.data.hasMore ) {
				updateProgerssBar( ( total - count ) / total * 100 );

				await generateDonorsRequest( {
					count: response.data.hasMore,
					params,
					total,
				} );
			} else {
				updateProgerssBar( 100 );
			}
		} else {
			CancelToken.cancel();
			State.set( { error: true } );

			const message = response.data.message
				? response.data.message
				: __( 'Something went wrong. Check the error log', 'give-test-data' );

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

// Generate donors
if ( generateDonorsBtn ) {
	generateDonorsBtn.addEventListener( 'click', generateDonors, false );
}
