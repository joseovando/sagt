var gulp              = require('gulp'),
    $                   = require('gulp-load-plugins')(),

// Non gulp specific plugins.
    browserSync         = require('browser-sync').create(),
    del                 = require('del'),
    runSequence         = require('run-sequence');

/**
 * @task styleguide
 * Generate KSS Styleguide.
 */
//var flags = [], values;
//gulp.task('styleguide', $.shell.task(
//    ['kss-node --css=\'../static/css/main.css\' --template=\'./static/kss-node-template/template\' --source=\'./static/sass/\' --source=\'../fortytwo/static/sass/\' ' ],
//    {templateData: {flags: flags.join(' ')}}
//));
var //importOnce = require('node-sass-import-once');
    path = require('path');

var options = {};

options.rootPath = {
  styleGuide  : __dirname + '/styleguide/',
  theme       : __dirname + '/static/'
};

options.theme = {
  root      : options.rootPath.theme,
  css       : options.rootPath.theme + 'css/',
  sass      : options.rootPath.theme + 'sass/',
  js        : options.rootPath.theme + 'js/',
  template  : options.rootPath.theme + 'kss-template/'
};

options.styleGuide = {
  source: [options.theme.sass, '../fortytwo/static/sass/'],
  destination: options.rootPath.styleGuide,
  template: options.theme.template,

  // The css and js paths are URLs, like '/misc/jquery.js'.
  // The following paths are relative to the generated style guide.
  css:  [path.relative(options.rootPath.styleGuide, options.theme.css + 'main.css')],
  js:   [],

  // Not working for now :-(
  //homepage:     path.relative(options.rootPath.styleGuide, options.theme.sass + 'homepage.md'),
  //homepage: options.rootPath.theme + 'sass/styleguide.md',

  title: 'FortyTwo Based Style Guide'
};

// kss-node 2.3.1 and later.
var kss = require('kss');

gulp.task('styleguide', function(cb) {
  kss(options.styleGuide, cb);
});

/**
 * @task sass
 * Do sass and reload tasks in sequence.
 */
gulp.task('sass', function () {
  runSequence('sass-compile',
      'reload');
});

gulp.task('sass-compile', function () {
  return gulp.src('static/sass/**/*.s+(a|c)ss') // Gets all files ending
      .pipe($.sass())
      .on('error', function (err) {
        console.log(err);
        this.emit('end');
      })
      .pipe($.autoprefixer({
        browsers: ['ie 8-9', 'last 2 versions']
      }))
      .pipe(gulp.dest('static/css'));
});

/**
 * @task js
 * Do javascript stuff.
 */
gulp.task('js', function () {
  return gulp.src(['static/js/**/*.js', '!static/js/lib/*.js'])
      .pipe($.jshint())
      .pipe($.jshint.reporter('default'))
      .pipe($.uglify());
});

/**
 * @task clean
 * Clean the dist folder.
 */
gulp.task('clean', function () {
  return del(['static/css/*']);
});

/**
 * @task reload
 * Refresh the page after clearing cache.
 */
gulp.task('reload', function () {
  if (config.enable_bs) {
    browserSync.reload();
  }
});

/**
 * @task watch
 * Watch files and do stuff.
 */
gulp.task('watch', ['clean', 'sass', 'js'], function () {
  gulp.watch('static/sass/**/*.+(scss|sass)', ['sass']);
  gulp.watch('static/js/**/*.js', ['js']);

  // Watch php, inc and info file changes to run drush task and reload page.
  gulp.watch('**/*.{php,inc,info}', ['clearcache'], ['reload']);
});

/**
 * @task clearcache
 * Run drush to clear the theme registry.
 */
gulp.task('clearcache', $.shell.task([
  'drush cr'
]));

/**
 * @task browser-sync
 * Launch the server.
 */
gulp.task('browser-sync', ['sass', 'js'], function () {
  if (config.enable_bs) {
    browserSync.init({
      proxy: config.localhost,
      open: false,
      socket: {
        domain: 'localhost:3000'
      }
    });
  }
});

/**
 * @task load-config
 * Load the local configuration.
 */
gulp.task('load-config', function() {
  try {
    console.log('Loading config.json. Change the values in gulp.config.json to suit your needs.');
    config = require('./gulp.config.json');
  } catch (error) {
    console.log('No local config.json found. Using the defaults.');
    console.log('Debug info: ' + error.code + ' => ' + error);
  }
});

/**
 * @task default
 * Watch files and do stuff.
 */
gulp.task('default', ['load-config', 'browser-sync', 'watch']);
