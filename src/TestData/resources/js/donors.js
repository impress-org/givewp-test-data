import API, { CancelToken } from './api';
// Common
import {
	processHasErrors,
	updateDescription,
	showRequestError,
	updateProgerssBar,
	generationStart,
} from './utils';

const { __, sprintf } = wp.i18n;
const generateDonorsBtn = document.querySelector( '#give-test-data-generate-donors' );

const getDonorsCount = () => {
	const input = document.querySelector( '#give-test-data-donors-count' );

	if ( input ) {
		return parseInt( input.value );
	}

	return false;
};

const generateDonors = ( e ) => {
	e.preventDefault();

	const count = getDonorsCount();

	// Check the donors count
	if ( ! count ) {
		// eslint-disable-next-line no-undef
		return new Give.modal.GiveWarningAlert( {
			modalContent: {
				title: __( 'Enter number of donors', 'give-test-data' ),
				desc: __( 'You must enter the number of donors to generate', 'give-test-data' ),
				cancelBtnTitle: __( 'OK', 'give-test-data' ),
			},
		} ).render();
	}

	// eslint-disable-next-line no-undef
	new Give.modal.GiveFormModal( {
		modalContent: {
			title: __( 'Generate donors', 'give-test-data' ),
			desc: sprintf( __( 'Generate %s donors?', 'give-test-data' ), count ),
			cancelBtnTitle: __( 'Close', 'give-test-data' ),
			confirmBtnTitle: __( 'Generate', 'give-test-data' ),
			link: '',
			link_text: '',
		},
		async successConfirm() {
			generationStart();

			await generateDonorsRequest( {
				count,
				total: count,
			} );

			if ( ! processHasErrors() ) {
				window.location.reload( true );
			}
		},
	} ).render();
};

const generateDonorsRequest = ( { count, total } ) => {
	return API.post( '/generate-donors', { count }, { cancelToken: CancelToken.token } )
		.then( async( response ) => {
			// Update description only once
			if ( count === total ) {
				updateDescription( __( 'Generating donors', 'give-test-data' ) );
			}
			updateProgerssBar( ( total - count ) / total * 100 );
			// Check if it has more donors to process
			if ( response.data.status && response.data.hasMore ) {
				await generateDonorsRequest( {
					count: response.data.hasMore,
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

// Generate donors
if ( generateDonorsBtn ) {
	generateDonorsBtn.addEventListener( 'click', generateDonors, false );
}
