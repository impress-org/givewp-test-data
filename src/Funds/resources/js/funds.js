import API, { CancelToken } from '../../../TestData/resources/js/api';
import GiveModal from '../../../TestData/resources/js/modal';
// Common
import {
	updateDescription,
	showRequestError,
	updateProgerssBar,
	generationStart,
	AppState,
} from '../../../TestData/resources/js/utils';

const { __, sprintf } = wp.i18n;
const generateFundsBtn = document.querySelector( '#give-test-data-generate-funds' );
// App state
const State = new AppState( {
	error: false,
} );

const getFundsCount = () => {
	const data = {
		count: 0,
		params: {},
	};

	const foundCount = document.querySelector( '#give-test-data-funds-count' );
	const additionalInputs = document.querySelectorAll( '#give-mainform input[name^="donation"]' );

	// get all additional inputs
	additionalInputs.forEach( ( input ) => {
		data.params = {
			...data.params,
			[ input.name ]: ( 'checkbox' === input.type ) ? input.checked : input.value,
		};
	} );

	if ( foundCount ) {
		data.count = parseInt( foundCount.value );
	}

	return data;
};

const generateFunds = ( e ) => {
	e.preventDefault();

	const { count, params } = getFundsCount();

	// Check the funds count
	if ( ! count ) {
		return new GiveModal( {
			type: 'warning',
			title: __( 'Enter number of funds', 'give-test-data' ),
			content: __( 'You must enter the number of funds to generate', 'give-test-data' ),
			cancelButton: __( 'OK', 'give-test-data' ),
		} );
	}

	new GiveModal( {
		type: 'form',
		title: __( 'Generate funds', 'give-test-data' ),
		content: sprintf( __( 'Generate %s funds?', 'give-test-data' ), count ),
		cancelButton: __( 'Close', 'give-test-data' ),
		confirmButton: __( 'Generate', 'give-test-data' ),
		onConfirm: async() => {
			generationStart();

			await generateFundsRequest( {
				count,
				params,
				total: count,
			} );

			// If there is no errors, reload page
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

const generateFundsRequest = ( { count, params, total } ) => {
	return API.post( '/generate-funds', {
		count, params,
	}, { cancelToken: CancelToken.token } ).then( async( response ) => {
		// Update description only once
		if ( count === total ) {
			updateDescription( __( 'Generating funds', 'give-test-data' ) );
		}
		// Check status
		if ( response.data.status ) {
			// Check if it has more forms to process
			if ( response.data.hasMore ) {
				updateProgerssBar( ( total - count ) / total * 100 );

				await generateFundsRequest( {
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

// Generate funds
if ( generateFundsBtn ) {
	generateFundsBtn.addEventListener( 'click', generateFunds, false );
}

