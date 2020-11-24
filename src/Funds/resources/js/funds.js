import API, { CancelToken } from '../../../TestData/resources/js/api';
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
	const input = document.querySelector( '#give-test-data-funds-count' );

	if ( input ) {
		return parseInt( input.value );
	}

	return 0;
};

const generateFunds = ( e ) => {
	e.preventDefault();

	const count = getFundsCount();

	// Check the funds count
	if ( ! count ) {
		// eslint-disable-next-line no-undef
		return new Give.modal.GiveWarningAlert( {
			modalContent: {
				title: __( 'Enter number of funds', 'give-test-data' ),
				desc: __( 'You must enter the number of funds to generate', 'give-test-data' ),
				cancelBtnTitle: __( 'OK', 'give-test-data' ),
			},
		} ).render();
	}

	// eslint-disable-next-line no-undef
	new Give.modal.GiveFormModal( {
		modalContent: {
			title: __( 'Generate funds', 'give-test-data' ),
			desc: sprintf( __( 'Generate %s funds?', 'give-test-data' ), count ),
			cancelBtnTitle: __( 'Close', 'give-test-data' ),
			confirmBtnTitle: __( 'Generate', 'give-test-data' ),
			link: '',
			link_text: '',
		},
		async successConfirm() {
			generationStart( CancelToken );

			await generateFundsRequest( {
				count,
				total: count,
			} );

			// If there is no errors, reload page
			if ( ! State.get( 'error' ) ) {
				window.location.reload( true );
			}
		},
	} ).render();
};

const generateFundsRequest = ( { count, total } ) => {
	return API.post( '/generate-funds', { count }, { cancelToken: CancelToken.token } )
		.then( async( response ) => {
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
		} )
		.catch( ( err ) => {
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
if ( generateFundsBtn ) {
	generateFundsBtn.addEventListener( 'click', generateFunds, false );
}
