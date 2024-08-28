require('./bootstrap');

window.Vue = require('vue');

window.Notification=require('./core/VueNotification');
window.Helper=require('./core/Helper');



Vue.component('compose', resolve => require(['./components/compose'], resolve));
Vue.component('multiselect', require('vue-multiselect').default);
Vue.component('tokenList', resolve => require(['./components/apk-key'], resolve));
Vue.component('groupList', resolve => require(['./components/group'], resolve));
Vue.component('createContact', resolve => require(['./components/contacts/create_contact'], resolve));
Vue.component('contactList', resolve => require(['./components/contacts/contact_list'], resolve));
Vue.component('groupDetail', resolve => require(['./components/group_detail'], resolve));
Vue.component('contactDetail', resolve => require(['./components/contacts/contact-detail'], resolve));
Vue.component('operatorChart', resolve => require(['./components/dashboard/operator'], resolve));
Vue.component('deliveryChart', resolve => require(['./components/dashboard/deliver'], resolve));
Vue.component('productUsage', resolve => require(['./components/dashboard/product_usage'], resolve));
Vue.component('lastWeek', resolve => require(['./components/dashboard/lastweek'], resolve));
Vue.component('importContact', resolve => require(['./components/import'], resolve));
Vue.component('userProfile', resolve => require(['./components/profile'], resolve));
Vue.component('userSetting', resolve => require(['./components/setting'], resolve));
Vue.component('smsLogs', resolve => require(['./components/sms_log'], resolve));
Vue.component('intlBuy', resolve => require(['./components/intl-buy'], resolve));
Vue.component('intlConfirm', resolve => require(['./components/intl-confirm'], resolve));
Vue.component('smsUsage', resolve => require(['./components/dashboard/daily_volumnes'], resolve));
Vue.component('buy', resolve => require(['./components/buy'], resolve));

Vue.use(require('vee-validate'));

import ToggleButton from 'vue-js-toggle-button';
Vue.use(ToggleButton);

const app = new Vue({
    el: '#app',
    data: function () {
        return {
            // is_verify: false
        }
    },
    methods: {
        // isUserVerified() {
        //     axios.get('/check-verifyemail').then(({data}) => {
        //             this.is_verify=data;
        //
        //     }).catch(error => {
        //         if (error.response.status == 401 || error.response.status == 419) {
        //             window.location.href = '/login';
        //         } else {
        //             Notification.error('Error occured while checking data.');
        //         }
        //     });
        //
        // },
    },
    mounted() {
        // _currentPath = window.location.pathname;
        // if (_currentPath != '/resend-mail') {
        //     this.isUserVerified();
        // }

    }
});

