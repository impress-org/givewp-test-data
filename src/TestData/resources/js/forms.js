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
const generateFormsBtn = document.querySelector( '#give-test-data-generate-forms' );

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
		// eslint-disable-next-line no-undef
		return new Give.modal.GiveWarningAlert( {
			modalContent: {
				title: __( 'Enter number of forms', 'give-test-data' ),
				desc: __( 'You must enter the number of forms to generate', 'give-test-data' ),
				cancelBtnTitle: __( 'OK', 'give-test-data' ),
			},
		} ).render();
	}

	// eslint-disable-next-line no-undef
	new Give.modal.GiveFormModal( {
		modalContent: {
			title: __( 'Generate Donation Forms', 'give-test-data' ),
			desc: sprintf( __( 'Generate %s Donation Forms?', 'give-test-data' ), count ),
			cancelBtnTitle: __( 'Close', 'give-test-data' ),
			confirmBtnTitle: __( 'Generate', 'give-test-data' ),
			link: '',
			link_text: '',
		},
		async successConfirm() {
			generationStart();

			await generateFormsRequest( {
				count,
				total: count,
				template,
				setGoal,
				setTC,
			} );

			if ( ! processHasErrors() ) {
				window.location.reload( true );
			}
		},
	} ).render();
};

const generateFormsRequest = ( { count, total, template, setGoal, setTC } ) => {
	return API.post( '/generate-forms', { count, template, setGoal, setTC }, { cancelToken: CancelToken.token } )
		.then( async( response ) => {
			// Update description only once
			if ( count === total ) {
				updateDescription( __( 'Generating donation forms', 'give-test-data' ) );
			}
			updateProgerssBar( ( total - count ) / total * 100 );
			// Check if it has more forms to process
			if ( response.data.status && response.data.hasMore ) {
				await generateFormsRequest( {
					count: response.data.hasMore,
					total,
					template,
					setGoal,
					setTC,
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
if ( generateFormsBtn ) {
	generateFormsBtn.addEventListener( 'click', generateForms, false );
}
