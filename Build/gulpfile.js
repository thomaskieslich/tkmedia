var gulp = require("gulp"),
    sass = require("gulp-sass"),
    cleanCSS = require("gulp-clean-css"),
    gutil = require("gulp-util"),
    autoprefixer = require("gulp-autoprefixer"),
    sourcemaps = require('gulp-sourcemaps');

var paths = {
    private: '../Resources/Private/',
    public: '../Resources/Public/'
};

gulp.task('sass', function () {
    return gulp.src(paths.private + 'Styles/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .on("error", gutil.log)
        .pipe(autoprefixer(["last 2 versions"], {cascade: true}))
        .pipe(cleanCSS())
        .pipe(sourcemaps.write('./maps'))
        .pipe(gulp.dest(paths.public + 'Styles/'));
});


gulp.task('default', function () {
    gulp.watch(paths.private + 'Styles/**/*.scss', ['sass']);
});
