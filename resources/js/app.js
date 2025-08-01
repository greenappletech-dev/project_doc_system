/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from "vue";
import VueSimpleAlert from "vue-simple-alert";
import { ClientTable } from 'vue-tables-2';
Vue.use(ClientTable, {}, false, 'bootstrap4');
window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('deliveries-component', require('./components/DeliveriesComponent.vue').default);
Vue.component('delivery_history-component', require('./components/DeliveryHistoryComponent.vue').default);
Vue.component('view_acount_per_beneficiary-component', require('./components/ViewAccountDocumentationPerBeneficiary.vue').default);

Vue.component('demand-notice-component', require('./components/DemandNoticeComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
