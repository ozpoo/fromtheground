const gulp = require('gulp');
const uglify = require("gulp-uglify");
const jshint = require("gulp-jshint");
const sass = require('gulp-sass');
const sassLint = require('gulp-sass-lint');
const cleanCSS = require('gulp-clean-css');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssdeclsort = require('css-declaration-sorter');
const concat = require("gulp-concat");

gulp.task('js', function () {
	gulp.src('assets/js/source/script.js')
	.pipe(jshint())
	.pipe(jshint.reporter('fail'))
	.pipe(concat('script.js'))
	.pipe(uglify())
	.pipe(gulp.dest('assets/js/build'));
});

gulp.task('scss', function () {
  gulp.src('assets/css/source/style.scss')
	.pipe(sassLint())
  .pipe(sassLint.format())
  .pipe(sassLint.failOnError())
	.pipe(postcss([ cssdeclsort({order: 'smacss'}) ]))
	.pipe(gulp.dest('assets/css/source'))
	.pipe(sass().on('error', sass.logError))
  .pipe(postcss([ autoprefixer() ]))
  .pipe(concat('style.css'))
	.pipe(cleanCSS({compatibility: 'ie8'}))
  .pipe(gulp.dest('assets/css/build'));
});

gulp.task('watch', function () {
  gulp.watch(['assets/css/source/style.scss'], ['scss']);
	gulp.watch(['assets/js/source/script.js'], ['js']);
});
