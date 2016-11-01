// ==========================================================================
// Gulp build script
// ==========================================================================
/*global require, __dirname,Buffer*/
/*jshint -W079 */

var fs = require("fs"),
	path = require("path"),
	gulp = require("gulp"),
	gutil = require("gulp-util"),
	concat = require("gulp-concat"),
	uglify = require("gulp-uglify"),
	less = require("gulp-less"),
	sass = require("gulp-sass"),
	cleanCSS = require("gulp-clean-css"),
	run = require("run-sequence"),
	autoprefixer = require("gulp-autoprefixer"),
	svgstore = require("gulp-svgstore"),
	svgmin = require("gulp-svgmin"),
	rename = require("gulp-rename"),
	s3 = require("gulp-s3"),
	replace = require("gulp-replace"),
	open = require("gulp-open"),
	size = require("gulp-size"),
	sourcemaps = require('gulp-sourcemaps'),
	through = require("through2");

var paths = {
		private: 'tkmedia/Resources/Private/',
		public: 'tkmedia/Resources/Public/'
	};

gulp.task('sass', function () {
	return gulp.src(paths.private + 'Styles/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass())
		.on("error", gutil.log)
		.pipe(autoprefixer(["last 2 versions"], { cascade: true }))
		.pipe(cleanCSS())
		.pipe(sourcemaps.write('./maps'))
		.pipe(gulp.dest(paths.public + 'Styles/'));
});


gulp.task('default', function () {
	gulp.watch(paths.private + 'Styles/**/*.scss', ['sass']);
});
