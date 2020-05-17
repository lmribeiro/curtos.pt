/**
 * Gulp file to automate the various tasks
 */

const autoprefixer = require('gulp-autoprefixer');
const browserSync = require('browser-sync').create();
const cleanCss = require('gulp-clean-css');
const del = require('del');
const htmlmin = require('gulp-htmlmin');
const cssbeautify = require('gulp-cssbeautify');
const npmDist = require('gulp-npm-dist');
const gulp = require('gulp');
const sass = require('gulp-sass');
const wait = require('gulp-wait');
const sourcemaps = require('gulp-sourcemaps');
const fileinclude = require('gulp-file-include');

// Define paths
const paths = {
    dist: {
        base: './dist',
        front: {
            base: './dist/',
            css: './dist/css',
            html: './dist/pages',
            assets: './dist/assets',
            partials: './dist/partials/',
            scss: './dist/scss'
        },
        vendor: './dist/vendor'
    },
    dev: {
        base: './html&css/',
        front: {
            base: './html&css/front/',
            css: './html&css/front/css',
            html: './html&css/front/pages',
            assets: './html&css/front/assets',
            partials: './html&css/front/partials/',
            scss: './html&css/front/scss'
        },
        vendor: './html&css/vendor'
    },
    base: {
        base: './',
        node: './node_modules'
    },
    src: {
        html: './src/*.html',
        front: {
            base: './src/front/',
            css: './src/front/css',
            html: './src/front/pages/**/*.html',
            assets: './src/front/assets/**/*.*',
            partials: './src/front/partials',
            scss: './src/front/scss'
        },
        node_modules: './node_modules/',
        vendor: './vendor'
    },
    temp: {
        base: './.temp/',
        front: {
            base: './.temp/front',
            css: './.temp/front/css',
            html: './.temp/front/pages',
            assets: './.temp/front/assets'
        },
        vendor: './.temp/vendor'
    }
};

// Compile SCSS
gulp.task('scss-front', function () {
    return gulp.src([paths.src.front.scss + '/front/**/*.scss', paths.src.front.scss + '/front.scss'])
            .pipe(wait(500))
            .pipe(sourcemaps.init())
            .pipe(sass().on('error', sass.logError))
            .pipe(autoprefixer({
                overrideBrowserslist: ['> 1%']
            }))
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest(paths.temp.front.css))
            .pipe(browserSync.stream());
});

gulp.task('scss', gulp.series('scss-front'));

gulp.task('html-front', function () {
    return gulp.src([paths.src.front.html])
            .pipe(fileinclude({
                prefix: '@@',
                basepath: paths.src.front.partials
            }))
            .pipe(gulp.dest(paths.temp.front.html))
            .pipe(browserSync.stream());
});

gulp.task('html-base', function () {
    return gulp.src([paths.src.html])
            .pipe(fileinclude({
                prefix: '@@',
                basepath: paths.src.front.partials
            }))
            .pipe(gulp.dest(paths.temp.base))
            .pipe(browserSync.stream());
});

gulp.task('html', gulp.series('html-front', 'html-base'));

gulp.task('assets-front', function () {
    return gulp.src([paths.src.front.assets])
            .pipe(gulp.dest(paths.temp.front.assets))
            .pipe(browserSync.stream());
});

gulp.task('assets', gulp.series('assets-front'));

gulp.task('vendor', function () {
    return gulp.src(npmDist(), {base: paths.src.node_modules})
            .pipe(gulp.dest(paths.temp.vendor));
});

gulp.task('serve', gulp.series('scss', 'html', 'assets', 'vendor', function () {
    browserSync.init({
        server: paths.temp.base
    });

    gulp.watch([paths.src.front.scss + '/front/**/*.scss', paths.src.front.scss + '/front.scss'], gulp.series('scss-front'));

    gulp.watch([paths.src.html], gulp.series('html-base'));
    gulp.watch([paths.src.front.html, paths.src.front.base + '*.html', paths.src.front.partials + '/**/*.html'], gulp.series('html-front', 'html'));

    gulp.watch([paths.src.front.assets], gulp.series('assets-front'));

    gulp.watch([paths.src.vendor], gulp.series('vendor'));
}));

// Beautify CSS
gulp.task('beautify-front:css', function () {
    return gulp.src([
        paths.dev.front.css + '/front.css'
    ])
            .pipe(cssbeautify())
            .pipe(gulp.dest(paths.dev.front.css));
});

gulp.task('beautify:css', gulp.series('beautify-front:css'));

// Minify CSS
gulp.task('minify-front:css', function () {
    return gulp.src([
        paths.dist.front.css + '/front.css'
    ])
            .pipe(cleanCss())
            .pipe(gulp.dest(paths.dist.front.css));
});

gulp.task('minify:css', gulp.series('minify-front:css'));

// Clean
gulp.task('clean:dist', function () {
    return del([paths.dist.base]);
});

gulp.task('clean:dev', function () {
    return del([paths.dev.base]);
});

// Compile and copy scss/css
gulp.task('copy-front:dist:css', function () {
    return gulp.src([paths.src.front.scss + '/front/**/*.scss', paths.src.front.scss + '/front.scss'])
            .pipe(wait(500))
            .pipe(sourcemaps.init())
            .pipe(sass().on('error', sass.logError))
            .pipe(autoprefixer({
                overrideBrowserslist: ['> 1%']
            }))
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest(paths.dist.front.css));
});

gulp.task('copy:dist:css', gulp.series('copy-front:dist:css'));

gulp.task('copy-front:dev:css', function () {
    return gulp.src([paths.src.front.scss + '/front/**/*.scss', paths.src.front.scss + '/front.scss'])
            .pipe(wait(500))
            .pipe(sourcemaps.init())
            .pipe(sass().on('error', sass.logError))
            .pipe(autoprefixer({
                overrideBrowserslist: ['> 1%']
            }))
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest(paths.dev.front.css));
});

gulp.task('copy:dev:css', gulp.series('copy-front:dev:css'));



// Copy Html
gulp.task('copy-front:dev:html', function () {
    return gulp.src([paths.src.front.html])
            .pipe(fileinclude({
                prefix: '@@',
                basepath: paths.src.front.partials
            }))
            .pipe(gulp.dest(paths.dev.front.html))
            .pipe(browserSync.stream());
});

gulp.task('copy-base:dev:html', function () {
    return gulp.src([paths.src.html])
            .pipe(fileinclude({
                prefix: '@@',
                basepath: paths.src.front.partials
            }))
            .pipe(gulp.dest(paths.dev.base))
            .pipe(browserSync.stream());
});

gulp.task('copy:dev:html', gulp.series('copy-front:dev:html', 'copy-base:dev:html'));

// Copy assets
gulp.task('copy-front:dev:assets', function () {
    return gulp.src(paths.src.front.assets)
            .pipe(gulp.dest(paths.dev.front.assets));
});

gulp.task('copy:dev:assets', gulp.series('copy-front:dev:assets'));

// Copy node_modules

gulp.task('copy:dev:vendor', function () {
    return gulp.src(npmDist(), {base: paths.src.node_modules})
            .pipe(gulp.dest(paths.dev.vendor));
});

gulp.task('build:dev', gulp.series('clean:dev', 'copy:dev:css', 'copy:dev:html', 'copy:dev:assets', 'beautify:css', 'copy:dev:vendor'));
gulp.task('build:dist', gulp.series('clean:dist', 'copy:dist:css', 'minify:css'));

// Default
gulp.task('default', gulp.series('serve'));
