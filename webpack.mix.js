let mix = require('laravel-mix');

//mix.setPublicPath('public');

mix.js('resources/js/app.js', './public/js/')
	.sass('resources/sass/theme.scss', './public/css/')
	.options({
		processCssUrls: false
	})
	.copyDirectory('resources/img', './public/img')
	.copyDirectory('node_modules/font-awesome/fonts/', './public/fonts');
