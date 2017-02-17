var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
   

    mix.styles([
    	'css/jquery.dataTables.min.css',
    	'plugins/bootstrap/css/bootstrap.css',
    	'plugins/node-waves/waves.css',
    	'plugins/waitme/waitMe.css',
    	'plugins/bootstrap-select/css/bootstrap-select.css',
    	'plugins/animate-css/animate.css',
    	'plugins/material-design-preloader/md-preloader.css',
    	'plugins/dropzone/dropzone.css',
    	'plugins/morrisjs/morris.css',
    	'css/style.css',
    	'css/themes/theme-red.css'
    	],null,'public/AdminTemplate');

    mix.scripts([
    	'js/jquery.dataTables.min.js',
    	'plugins/bootstrap/js/bootstrap.js',
    	'plugins/bootstrap-select/js/bootstrap-select.js',
    	'plugins/jquery-countto/jquery.countTo.js',
    	'plugins/jquery-slimscroll/jquery.slimscroll.js',
    	'plugins/node-waves/waves.js',
    	'plugins/jquery-countto/jquery.countTo.js',
    	'plugins/raphael/raphael.min.js',
    	'plugins/morrisjs/morris.js',
    	'plugins/chartjs/Chart.bundle.js',
    	'plugins/flot-charts/jquery.flot.js',
    	'plugins/flot-charts/jquery.flot.resize.js',
    	'plugins/flot-charts/jquery.flot.pie.js',
    	'plugins/flot-charts/jquery.flot.categories.js',
    	'plugins/flot-charts/jquery.flot.time.js',
    	'plugins/waitme/waitMe.js',
    	'plugins/bootstrap-select/js/bootstrap-select.js',
    	'plugins/jquery-validation/jquery.validate.js',
    	'js/bootstrap-filestyle.min.js',
    	'plugins/jquery-sparkline/jquery.sparkline.js',
    	'js/admin.js',
    	'js/bootbox.min.js',
    	'js/pages/cards/basic.js',
    	'js/pages/index.js',
    	'js/pages/forms/basic-form-elements.js',
    	'plugins/dropzone/dropzone.js',
    	'js/demo.js'
    	],'public/js','public/AdminTemplate');
});
