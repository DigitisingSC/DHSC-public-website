'use strict';
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const babel = require('gulp-babel');
const concat = require('gulp-concat');
sass.compiler = require('sass');
const tailwindCSS = require('tailwindcss');

const PATHS = {
  TWConfig: './tailwind.config.js'
};

gulp.task('tailwindSass', function () {
  return gulp.src('./src/scss/tailwind.scss')
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(postcss([
      tailwindCSS(PATHS.TWConfig),
      require('autoprefixer')
    ]))
    .pipe(gulp.dest('./dist/css'));
});

gulp.task('componentsSass', function () {
  return gulp.src('./stories/**/*.scss')
    .pipe(concat('components.scss'))
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(postcss([
      tailwindCSS(PATHS.TWConfig),
      require('autoprefixer')
    ]))
    .pipe(gulp.dest('./dist/css'));
});

gulp.task('globalSass', function () {
  return gulp.src('./src/scss/global.scss')
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(postcss([
      tailwindCSS(PATHS.TWConfig),
      require('autoprefixer')
    ]))
    .pipe(gulp.dest('./dist/css'));
});

gulp.task('componentsJs', function () {
  return gulp.src(['./stories/**/*.js', '!./stories/**/*.stories.js'])
    .pipe(concat('components.js'))
    .pipe(babel({
      presets: ['@babel/env']
    }))
    .pipe(gulp.dest('./dist/js'));
});

gulp.task('copyAssets', function () {
  return gulp.src('./stories/assets/**/*')
    .pipe(gulp.dest('./assets'));
});

gulp.task('default', gulp.series('tailwindSass', 'componentsSass', 'globalSass', 'componentsJs', 'copyAssets'));
