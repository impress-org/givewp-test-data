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
const generateFormsBtn = document.querySelector( '#give-test-data-generate-forms' );
// App state
const State = new AppState( {
	error: false,
} );

const getData = () => {
	return {
		count: parseInt( document.querySelector( '#give-test-data-forms-count' ).value ),
		template: getSelectedTemplate(),
		setGoal: document.querySelector( '#give-test-data-forms-set-goal' ).checked,
		setTC: document.querySelector( '#give-test-data-forms-set-tc' ).checked,
	};
};

const getSelectedTemplate = () => {
	const templates = document.querySelectorAll( 'input[name*="give_test_data_form_template"]:checked' );

	if ( templates.length >= 2 ) {
		return 'random';
	}

	return templates[ 0 ].value;
};

const generateForms = ( e ) => {
	e.preventDefault();

	const { count, template, setGoal, setTC } = getData();

	// Check the donors count
	if ( ! count ) {
		return new GiveModal( {
			type: 'warning',
			title: __( 'Enter number of forms', 'give-test-data' ),
			content: __( 'You must enter the number of forms to generate', 'give-test-data' ),
			cancelButton: __( 'OK', 'give-test-data' ),
		} );
	}

	new GiveModal( {
		type: 'form',
		title: __( 'Generate Donation Forms', 'give-test-data' ),
		content: sprintf( __( 'Generate %s Donation Forms?', 'give-test-data' ), count ),
		cancelButton: __( 'Close', 'give-test-data' ),
		confirmButton: __( 'Generate', 'give-test-data' ),
		onConfirm: async() => {
			generationStart();

			await generateFormsRequest( {
				count,
				total: count,
				template,
				setGoal,
				setTC,
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

const generateFormsRequest = ( { count, total, template, setGoal, setTC } ) => {
	return API.post( '/generate-forms', {
		count, template, setGoal, setTC,
	}, { cancelToken: CancelToken.token } ).then( async( response ) => {
		// Update description only once
		if ( count === total ) {
			updateDescription( __( 'Generating donation forms', 'give-test-data' ) );
		}

		if ( response.data.status ) {
			// Check if it has more forms to process
			if ( response.data.hasMore ) {
				updateProgerssBar( ( total - count ) / total * 100 );
				await generateFormsRequest( {
					count: response.data.hasMore,
					total,
					template,
					setGoal,
					setTC,
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

// Generate forms
if ( generateFormsBtn ) {
	generateFormsBtn.addEventListener( 'click', generateForms, false );
}
