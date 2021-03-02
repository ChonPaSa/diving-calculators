// Load Gulp...of course
const { src, dest, task, parallel } = require('gulp');

// CSS related plugins
var autoprefixer = require( 'gulp-autoprefixer' );

// Utility plugins
var rename       = require( 'gulp-rename' );
var sourcemaps   = require( 'gulp-sourcemaps' );
var cleanCSS	 = require('gulp-clean-css');
var wpPot = require('gulp-wp-pot');


// Project related variables
var styleSRC     = './public/css/diving-calculators-public.css';
var styleURL     = './public/css/';
var mapURL       = './';

//Translation Source
var transSRC	 = './**/*.php';


function css(done) {
	src( styleSRC )
		.pipe( sourcemaps.init() )
		.pipe( autoprefixer({ browsers: [ 'last 2 versions', '> 5%', 'Firefox ESR' ] }) )
		.pipe( cleanCSS() )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( sourcemaps.write( mapURL ) )
		.pipe( dest( styleURL ) );
	done();
};


function translate() {
    return src( transSRC )
        .pipe(wpPot( {
            domain: 'diving-calculators',
            package: 'Diving_Calculators'
        } ))
        .pipe(dest('languages/diving-calculators.pot'));
}

task("css", css);
task("translate", translate);
task("default", parallel(css, translate));

