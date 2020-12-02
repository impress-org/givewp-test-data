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

const { __ } = wp.i18n;
const generatePagesBtn = document.querySelector( '#give-test-data-generate-page' );
// App state
const State = new AppState( {
	error: false,
} );

const generateDemonstrationPage = ( e ) => {
	e.preventDefault();

	new GiveModal( {
		type: 'form',
		title: __( 'Generate page', 'give-test-data' ),
		content: __( 'Generate demonstration page', 'give-test-data' ),
		cancelButton: __( 'Close', 'give-test-data' ),
		confirmButton: __( 'Generate', 'give-test-data' ),
		onConfirm: async() => {
			generationStart();

			await generateDemonstrationPagesRequest();

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

const generateDemonstrationPagesRequest = () => {
	return API.post( '/generate-demonstration-page', {}, {
		cancelToken: CancelToken.token,
	} ).then( async( response ) => {
		updateDescription( __( 'Generating demonstration page', 'give-test-data' ) );
		// Check status
		if ( response.data.status ) {
			updateProgerssBar( 100 );
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

// Generate donations
if ( generatePagesBtn ) {
	generatePagesBtn.addEventListener( 'click', generateDemonstrationPage, false );
}
