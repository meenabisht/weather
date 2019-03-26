var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();
var imagemin = require('gulp-imagemin');

gulp.task('mycompiler', function() {
  console.log('Hello meena');
});

gulp.task('newfile', function(){
  return gulp.src('custom/weather_boot/sass/header.scss')
    .pipe(sass()) 
    .pipe(gulp.dest('custom/weather_boot/css'))
    .pipe(browserSync.reload({stream: true}))
});

gulp.task('browserSync', function() {
  browserSync.init({
    open: 'external',
    hostname: 'localhost',
    proxy: 'http://localhost:8888/weather/web/'
  })
});

gulp.task('images', function(){
  return gulp.src('custom/weather_boot/css/images/**/*.+(png|jpg|gif|svg)')
  .pipe(imagemin())
  .pipe(gulp.dest('custom/weather_boot/myimages'))
});

gulp.task('watch',  gulp.parallel('browserSync',function() {
  gulp.watch('custom/weather_boot/sass/header.scss',gulp.series('newfile'));
}));