var gulp = require('gulp');
var concat = require('gulp-concat');
var minify = require('gulp-minify');
var cleanCss = require('gulp-clean-css');

/*gulp.task('minify', function () {
   gulp.src('js/*.js')
      .pipe(uglify())
      .pipe(gulp.dest('build'))
});*/
 
gulp.task('pack-js', function () {	
	return gulp.src(['js/*.js'])
		.pipe(concat('bundle.js'))
		.pipe(minify())
		.pipe(gulp.dest('js'));
});
 
gulp.task('pack-css', function () {	
	return gulp.src(['css/*.css'])
		.pipe(concat('style.css'))
		.pipe(cleanCss())
   .pipe(gulp.dest('css'));
});
 
gulp.task('default', ['pack-js', 'pack-css']);