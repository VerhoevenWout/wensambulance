var gulp = require('gulp');
var $ = require('gulp-load-plugins')({
          pattern: ['gulp-*', 'del']
        });
var browserSync = require('browser-sync');
var reload = browserSync.reload;
var pngquant = require('imagemin-pngquant');
var shell = require('gulp-shell');
var runSequence = require('gulp-run-sequence');
var wiredep = require('wiredep').stream;

var iconfont = require('gulp-iconfont');
var iconfontCss = require('gulp-iconfont-css');


// Proxy
var proxy = "http://wensambulance.loc/";


/* DEFAULT
   ============================= */

gulp.task('default', ['build_tasks'], function() {

  browserSync({
    notify: true,
    port: 9000,
    proxy: proxy,
    ui: {
      port: 9001
    }
  });

  // watch for changes
  gulp.watch([
    'dev/assets/js/*.js',
    'dev/assets/img/**/*',
    'dev/assets/fonts/**/*',
    'dev/**/*.php',
    '!dev/footer.php', 
    '!dev/header.php'
    ])
    .on('change', reload);

  gulp.watch('dev/assets/sass/**/*.scss', ['css']);
  gulp.watch('dev/assets/js/**/*.js', ['js']);
  gulp.watch('dev/assets/fonts/**/*', ['fonts']);
  gulp.watch('dev/assets/img/**/*', ['images']);
  gulp.watch('dev/assets/*.{png,ico}', ['favicons']);
  gulp.watch(['bower.json', 'dev/footer.php', 'dev/header.php'], ['wiredep']);
  gulp.watch(['dev/**/*.php', '!dev/footer.php', '!dev/header.php'], ['php']);
});


/* BUILD
   ============================= */

// 'Clean' moet volledig gedaan zijn voordat aan andere taken begonnen mag worden.
gulp.task('build_tasks', ['iconfont', 'css', 'js', 'images', 'wiredep', 'favicons', 'php', 'ajax-loader']);

gulp.task('build', ['clean'], function () {
  gulp.start('build_tasks');
});

/* Styles
   ============================= */
gulp.task('ajax-loader', function () {
  gulp.src('dev/assets/css/ajax-loader.gif')
  .pipe(gulp.dest('dist/css'));
});

gulp.task('css', function() {

  var SassOptions = {
    indentedSyntax: false, // false = SCSS | true = SASS
    outputStyle: 'compressed', // (nested | compact | compressed)
    precision: 10, // # decimalen
    includePaths: ['.'],
    onError: function(err) {
      $.util.beep();
      $.notify().write({ message: '\nMessage: ' + err.message + '\nin file: ' + err.file });
    }
  };

  // set minifier to false to keep Sass sourcemaps support
  var PleeeaseOptions = {
    sourcemaps: {
      "map": {
        "inline": false
      }
    },
    sass: true,
    minifier: true,
    autoprefixer: {"browsers": ["last 10 versions", "ios 6"]},
    filters: { "oldIE": true },
    rem: ["10px"],
    opacity: true,
    mqpacker: true,
    calc: true,
  };


  gulp.src('dev/assets/sass/**/*.scss')
    .pipe($.plumber())
    .pipe($.sourcemaps.init())
    .pipe($.cssGlobbing({
      extensions: ['.scss']
    }))
    .pipe($.sass(SassOptions))
    .pipe($.pleeease(PleeeaseOptions))

    // FIX VOOR INLINE SOURCE MAPS TE VERWIJDEREN
    // worden ingevoerd door pleeease 
    // te optimaliseren...
    .pipe($.deleteLines({ 
      'filters': [
      "sourceMappingURL=data"
      ]
    }))

    .pipe($.sourcemaps.write('maps'))
    .pipe(gulp.dest('dist/css'))
    .pipe(reload({stream: true}));
});


/* Js task
   ============================= */

gulp.task('js', function() {
  return gulp.src('dev/assets/js/*')
    .pipe($.plumber())
    .pipe($.sourcemaps.init())
    .pipe(reload({stream: true, once: true}))
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.notify(function (file) {
      if (file.jshint.success) {
        // Don't show something if success
        return false;
      }

      var errors = file.jshint.results.map(function (data) {
        if (data.error) {
          return "(" + data.error.line + ':' + data.error.character + ') ' + data.error.reason;
        }
      }).join("\n");
      return file.relative + " (" + file.jshint.results.length + " errors)\n" + errors;
    }))
    .pipe($.uglify())
    .pipe($.sourcemaps.write('maps'))
    .pipe(gulp.dest('dist/js'));
});


/* IconFont
   ============================= */

gulp.task('iconfont', function(){
  gulp.src(['dev/assets/icons/**/*.svg'])
    .pipe(iconfontCss({
      fontName: 'VoltaFont',
      formats: ['ttf', 'eot', 'woff', 'woff2', 'svg'],
      path: 'dev/assets/css/_icons.scss',
      targetPath: '../../dev/assets/sass/custom/volta/_font.scss',
      fontPath: '../fonts/'
    }))
    .pipe(iconfont({
      fontName: 'VoltaFont'
     }))
    .pipe(gulp.dest('dist/fonts'));
});


/* Images
   ============================= */

gulp.task('images', function () {
  return gulp.src('dev/assets/img/**/*')
    .pipe($.changed('dist/img'))
    .pipe($.imagemin({
      progressive: true,
      svgoPlugins: [{removeViewBox: false, cleanupIDs: false}],
      use: [pngquant()]
    }))
    .pipe(gulp.dest('dist/img'));
});


/* PHP
   ============================= */

gulp.task('php', function () {
  return gulp.src(['dev/**/*.php', '!dev/footer.php', '!dev/header.php'])
    .pipe($.changed('./'))
    //.pipe($.if('*.php', $.minifyHtml({conditionals: true, loose: true})))
    .pipe(gulp.dest(''));
});


/* favicons
   ============================= */

gulp.task('favicons', function () {
  return gulp.src('dev/assets/*.{png,ico}')
    .pipe($.changed('dist/'))
    .pipe($.imagemin({
      progressive: true,
      svgoPlugins: [{removeViewBox: false, cleanupIDs: false}],
      use: [pngquant()]
    }))
    .pipe(gulp.dest('dist/'))
    .pipe(reload({stream: true}));
});

/* WireDep (bevat ook zelfde acties als php taak)
   ============================= */
gulp.task('wiredep', function () {
  
  var wireDepOptions = {
    ignorePath: "/^(\.\.\/)*\.\./"
  };

  // var assets = $.useref.assets();
  useref = require('gulp-useref');

  return gulp.src(['dev/footer.php', 'dev/header.php'])
    .pipe(wiredep(wireDepOptions))
    .pipe(useref())
    .pipe($.if('*.js', $.uglify()))
    .pipe($.if('*.css', $.csso()))
    .pipe($.useref())
    .pipe($.replace(
      "dist/js/vendor.js",
      "<?php bloginfo('template_url'); ?>/dist/js/vendor.js"
    ))
    .pipe($.replace(
      "dist/css/vendor.css",
      "<?php bloginfo('template_url'); ?>/dist/css/vendor.css"
    ))
    //.pipe($.if('*.php', $.minifyHtml({conditionals: true, loose: true})))
    .pipe(gulp.dest(''))
    .pipe(reload({stream: true}));
});


/* Clean
   ============================= */
gulp.task('clean', function(done) {
    $.del(['dist', '*.php'], done);
});

/* Notify function
   ============================= */

function notify(msg) {
  $.notify().write({ message: '\nMessage: ' + msg });
}


/* Vaprobash
   ============================= */

gulp.task('vaprobash', function(){
   runSequence('download-vaprobash','copy-scripts');
});

gulp.task('download-vaprobash', shell.task([
  'curl -L http://bit.ly/vaprobash > Vagrantfile'
], { cwd: '../../..' }));

gulp.task('copy-scripts', shell.task([
  'mv vagrant_scripts ../../..'
]));

/* Deployment
   ============================= */

var deployOptions = {
  repo: 'git@git.volta.be:wp-volta-theme.git',
  staging: 'Combell ssh :subsites/staging',
  stagingBranch: 'development',
  productions: 'Combell ssh :www',
  exclude: ['.DS_Store', '.git', '.gitignore', 'wp-content/uploads']
};
  
gulp.task('deploy', function(){
  return gulp.src('*')
  .pipe(prompt.prompt({
        type: 'input',
        name: 'type',
        message: 'What you like to deploy? [staging/production]'
    }, function(res){
        var sources;
        if(res.type === 'staging'){
          runSequence('git-pull-staging','deploy-staging');
        }
        if(res.type === 'production'){
          runSequence('git-pull-production','deploy-production');
        }
    }));
});

//Create build folders en init git branches
gulp.task('deploy-init', shell.task([
  'git clone '+deployOptions.repo+' --branch '+deployOptions.stagingBranch+' --single-branch builds/staging',
  'git clone '+deployOptions.repo+' --branch master --single-branch builds/master'
]));

//pull lastest version from development branch
gulp.task('git-pull-staging', shell.task([
  'git pull'
], { cwd: 'builds/staging' }));

//pull lastest version from master branch 
gulp.task('git-pull-production', shell.task([
  'git pull'
], { cwd: 'builds/master' }));

//Copy latest build to staging env server 
gulp.task('deploy-staging', function() {
  rsync({
    ssh: true,
    src: './builds/staging/',
    dest: deployOptions.staging,
    recursive: true,
    exclude: deployOptions.exclude,
    delete: true,
    args: ['--verbose']
  }, function(error, stdout, stderr, cmd) {
      util.log(stdout);
  });
});

//Copy latest build to production env server
gulp.task('deploy-production', function() {
  rsync({
    ssh: true,
    src: './builds/master/',
    dest: deployOptions.staging,
    recursive: true,
    exclude: deployOptions.productions,
    delete: true,
    args: ['--verbose']
  }, function(error, stdout, stderr, cmd) {
      util.log(stdout);
  });
});

