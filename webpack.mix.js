const mix = require( 'laravel-mix' );
const wpPot = require( 'wp-pot' );

mix
	.setPublicPath( 'public' )
	.sourceMaps( false )

	// admin assets
	.js( 'src/TestData/resources/js/give-test-data-admin.js', 'public/js/' )
	.sass( 'src/TestData/resources/css/give-test-data-admin.scss', 'public/css' )

	// images
	.copy( 'src/TestData/resources/images/*.{jpg,jpeg,png,gif}', 'public/images' );

mix.webpackConfig( {
	externals: {
		$: 'jQuery',
		jquery: 'jQuery',
	},
} );

if ( mix.inProduction() ) {
	wpPot( {
		package: 'Give Test Data',
		domain: 'give-test-data',
		destFile: 'languages/give-test-data.pot',
		relativeTo: './',
		bugReport: 'https://github.com/impress-org/give-test-data/issues/new',
		team: 'GiveWP <info@givewp.com>',
	} );
}
