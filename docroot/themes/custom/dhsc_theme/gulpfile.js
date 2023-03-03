'use strict';
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));


gulp.task('sass', function () {
	return gulp.src('./stories/**/*.scss')
	.pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
	.pipe(gulp.dest('./dist/styles/'))
});

gulp.task('sass:watch', function () {
	gulp.watch('./stories/**/*.scss', ['sass']);
});