var gulp = require('gulp'),
	path = require('path'),

	// For reloading the browser when files are written to
	browserSync = require('browser-sync').create(),
	
	// For compiling SASS files
	sass = require('gulp-sass');

gulp.task('serve', function () {

	browserSync.init({
				proxy: "localhost"
		});

	// When I update SASS files, call the 'sass' task
	gulp.watch('dev/scss/*.scss').on('change', gulp.series('sass'));

	// When I've updated web-ready dev files
	gulp.watch([
		'dev',
		'!dev/sass'
		// Run the copy task and reload browser window(s) 
		]).on('change', gulp.series('copy', browserSync.reload));

});

gulp.task('copy', function () {

	return gulp.src([
		'dev/**', // 1. Include everything
		'!dev/scss/*', // 2. Exclude SASS dev files
		'!dev/scss'])

			.pipe(gulp.dest('build'));

});

// SASS compilation
gulp.task('sass', function() {

	return gulp.src('dev/scss/*.scss')

		// Log the SASS compiler's errors
		.pipe(sass().on('error', sass.logError))
	
		.pipe(gulp.dest('build/css'))

		// Inject CSS into browsers
		.pipe(browserSync.stream());

});

gulp.task('default', gulp.series('serve'));
