//导入工具包 require('node_modules里对应模块')
var gulp = require('gulp'), //本地安装gulp所用到的地方
    sass = require('gulp-sass'),
    cleanCSS = require('gulp-clean-css'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    plumber = require('gulp-plumber'),
    notify = require('gulp-notify');

var baseUrl = './app/design/frontend/TemplateMonster/theme059/web/css';
var config = {
    src: baseUrl + '/source/*.scss',
    dest: baseUrl
}

// Error message
var onError = function(err) {
    notify.onError({
        title: 'Gulp',
        subtitle: 'Failure!',
        message: 'Error: <%= error.message %>',
        sound: 'Beep'
    })(err);

    this.emit('end');
};

// Compile CSS
gulp.task('build', function() {
    var stream = gulp
        .src([config.src])
        .pipe(sourcemaps.init())
        .pipe(plumber({ errorHandler: onError }))
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS())
        .pipe(autoprefixer({
            browsers: ['> 5%'],
            cascade: false
        }))
        .pipe(sourcemaps.write('./', { includeContent: false }));
    return stream
        .pipe(gulp.dest(baseUrl));
});

gulp.task('watch', function() {
    // Watch .scss files  
    gulp.watch([config.src], ['build']);
});
// 监听任务时先执行一次编译  
gulp.task('default', function() {
    gulp.start('build', 'watch');
});