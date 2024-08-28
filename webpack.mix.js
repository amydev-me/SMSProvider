let mix = require('laravel-mix');

// mix.js('data/user/assets/app.js', 'public/js/user')
// .styles([
// 	'public/css/vue-multiselect.min.css',
// 	'public/css/notification.css',
// 		'public/css/bootstrap-datetimepicker.min.css'
// ], 'public/css/csstyles.css')
// .js([
// 	'public/js/notify.min.js',
// 	'public/js/notify-metro.js',
// 	'public/js/notifications.js'
// ],'public/js/user/common.js')
// .extract(['vue', 'axios', 'vee-validate', 'chart.js', 'vue-multiselect', '@johmun/vue-tags-input', 'vue-bootstrap-datetimepicker', 'vue-js-toggle-button'])
// .autoload({ jQuery: 'jquery', $: 'jquery', jquery: 'jquery' })
// .webpackConfig({
// 	output: {
// 		chunkFilename: mix.inProduction() ? 'js/user/chunks/[name].[chunkhash].js' : 'js/user/chunks/[name].js'
// 	},
// });

// mix.js('resources/web/app.js', 'public/js/web/webapp.js')
// .extract(['vue','axios','vee-validate'],'public/js/web/webvendor.js')
// .autoload({ jQuery: 'jquery', $: 'jquery', jquery: 'jquery' })
// .webpackConfig({
// 	output: {
// 		chunkFilename: mix.inProduction() ? 'js/web/chunks/[name].[chunkhash].js' : 'js/web/chunks/[name].js'
// 	},
// });

mix.js('data/admin/assets/app.js', 'public/js/admin/admin_app.js')
.js([
		'public/js/notify.min.js',
		'public/js/notify-metro.js',
		'public/js/notifications.js',
		'public/js/jquery.dataTables.min.js'
	], 'public/js/admin/common.js')
.styles([
		'public/css/vue-multiselect.min.css',
		'public/css/notification.css',
		'public/css/jquery.dataTables.min.css'
	], 'public/css/admin/styles.css')
.extract(['vue', 'axios', 'vee-validate', 'vue-multiselect', 'pusher-js', 'vue-bootstrap-datetimepicker'], 'public/js/admin/admin_vendor.js')
.autoload({ jQuery: 'jquery', $: 'jquery', jquery: 'jquery' })
.webpackConfig({
	output: {
		chunkFilename: mix.inProduction() ? 'js/admin/chunks/[name].[chunkhash].js' : 'js/admin/chunks/[name].js'
	}
});