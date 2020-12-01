const { __ } = wp.i18n;

// eslint-disable-next-line no-undef
export default class Modal extends Give.modal.GiveModal {
	constructor( config ) {
		const defaultConfig = {
			type: 'confirm',
			title: __( 'Confirm', 'give-test-data' ),
			content: '',
			cancelButton: __( 'Cancel', 'give-test-data' ),
			confirmButton: __( 'OK', 'give-test-data' ),
			link: '',
			link_text: '',
			onConfirm: () => {},
			onClose: () => {},
		};

		const {
			type,
			onConfirm,
			cancelButton,
			confirmButton,
			onClose,
			content,
			...modalContent
		} = Object.assign( defaultConfig, config );

		super( {
			type,
			modalContent: {
				...modalContent,
				desc: content,
			},
			successConfirm: onConfirm,
			cancelBtnTitle: cancelButton,
			confirmBtnTitle: confirmButton,
			callbacks: {
				afterClose: onClose,
			},
		} );

		this.init();
		this._setType( type );
		this.render();
	}

	_setType( type ) {
		switch ( type ) {
			case 'warning':
			case 'error':
			case 'success':
				this.config.classes.modalWrapper = `give-modal--${ type }`;
				break;
			default:
				this.config.classes.modalWrapper = 'give-modal--notice';
		}
	}
}
