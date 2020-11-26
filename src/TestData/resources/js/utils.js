export const showGenerateButton = ( show ) => {
	const button = document.querySelector( '.give-button--primary' );

	if ( button ) {
		button.classList.toggle( 'give-hidden', ! show );
	}
};

export const updateDescription = ( description ) => {
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

export const generationStart = () => {
	showGenerateButton( false );
	updateDescription( 'Initializing...' );
	updateProgerssBar( 0 );
};

export const showRequestError = ( error ) => {
	const element = document.querySelector( '.give-modal__description' );
	const descriptionElement = document.querySelector( '.give-test-data-description-container' );

	if ( element ) {
		element.innerHTML = `<p class="give-test-data-process-error" style="color: red;">${ error }</p>`;
	}

	if ( descriptionElement ) {
		descriptionElement.remove();
	}
};

export const updateProgerssBar = ( percent ) => {
	const element = document.querySelector( '.give-modal__description' );

	if ( element ) {
		element.innerHTML = `<div class="give-progress"><div style="width:${ parseInt( percent ) }%;"></div></div>`;
	}
};

export class AppState {
	constructor( state ) {
		this.state = state;
	}

	set( state ) {
		this.state = ( typeof state === 'function' )
			? state( this.state )
			: state;
	}

	get( key ) {
		if ( key ) {
			return this.state[ key ];
		}

		return this.state;
	}
}
