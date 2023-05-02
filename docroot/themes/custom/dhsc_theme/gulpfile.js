"use strict";
const gulp = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const babel = require("gulp-babel");
const minify = require("gulp-minify");


gulp.task("sass", function () {
  return gulp
    .src("./stories/**/*.scss")
    .pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
    .pipe(gulp.dest("./dist/"));
});

gulp.task("js", () => {
  return gulp
    .src(["stories/**/*.js", "!stories/**/*.stories.js"])
    .pipe(
      babel({
        presets: ["@babel/preset-env"],
      })
    )
    .pipe(
      minify({
        ext: {
          min: ".min.js",
        },
      })
    )
    .pipe(gulp.dest("./dist/"));
});

gulp.task("watch:all", async function () {
  gulp.watch("./stories/**/*.scss", gulp.series("sass"));
  gulp.watch(["stories/**/*.js", "!stories/**/*.stories.js"], gulp.series("js"));
});
