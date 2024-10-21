// Load Gulp
import { src, dest, task, parallel } from 'gulp';

// CSS-related plugins
import autoprefixer from 'gulp-autoprefixer';
import cleanCSS from 'gulp-clean-css';
import sourcemaps from 'gulp-sourcemaps';
import rename from 'gulp-rename';

// Translation plugin
import wpPot from 'gulp-wp-pot';


// Project related variables
var styleSRC     = './public/css/diving-calculators-public.css';
var styleURL     = './public/css/';
var mapURL       = './';

//Translation Source
var transSRC	 = './**/*.php';


function css(done) {
	src( styleSRC )
		.pipe( sourcemaps.init() )
		.pipe( autoprefixer() )
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

