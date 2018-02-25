const gulp = require('gulp');
const uglify = require("gulp-uglify");
const jshint = require("gulp-jshint");
const sass = require('gulp-sass');
const sassLint = require('gulp-sass-lint');
const minifyCss = require("gulp-minify-css");
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssdeclsort = require('css-declaration-sorter');
const concat = require("gulp-concat");
const stylish = require('jshint-stylish');

var watchJS = true;
var watchSCSS = true;

gulp.task('js', function () {
	if(watchJS) {
		watchJS = false;
		gulp.src('assets/js/src/*')
		.pipe(jshint())
		.pipe(jshint.reporter(stylish))
		.pipe(concat('build.js'))
		.pipe(uglify())
		.pipe(gulp.dest('assets/js/build'));
		watchJS = true;
	}
});

gulp.task('scss', function () {
	if(watchSCSS) {
		watchSCSS = false;
	  gulp.src('assets/css/src/*')
		.pipe(sassLint())
	  .pipe(sassLint.format())
	  .pipe(sassLint.failOnError())
		.pipe(postcss([ cssdeclsort({order: 'smacss'}) ]))
		.pipe(gulp.dest('./'))
		.pipe(sass().on('error', sass.logError))
	  .pipe(postcss([ autoprefixer() ]))
	  .pipe(concat('build.css'))
		.pipe(minifyCss())
	  .pipe(gulp.dest('assets/css/build'));
		watchSCSS = true;
	}
});

gulp.task('watch', function () {
  gulp.watch(['assets/css/src/*'], ['scss']);
	gulp.watch(['assets/js/src/*'], ['js']);
});
