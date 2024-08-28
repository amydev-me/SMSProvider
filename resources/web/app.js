require('./bootstrap');

window.Vue = require('vue');

window.Notification = require('./core/VueNotification');

Vue.component('signup', resolve => require(['./components/signup'], resolve));
Vue.component('pricing', resolve => require(['./components/pricing'], resolve));

Vue.use(require('vee-validate'));
const app = new Vue({
	el: '#vue-web'
});