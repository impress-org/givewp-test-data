import axios from 'axios';

export const CancelToken = axios.CancelToken.source();

export default axios.create( {
	baseURL: window.GiveTestData.apiRoot,
	headers: {
		'Content-Type': 'application/json',
		'X-WP-Nonce': window.GiveTestData.apiNonce,
	},
} );
