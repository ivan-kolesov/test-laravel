
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.EventBus = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('index', require('./components/IndexComponent.vue'));
Vue.component('edit-feed-popup', require('./components/EditFeedPopupComponent.vue'));
Vue.component('remove-feed-popup', require('./components/RemoveFeedPopupComponent.vue'));

import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app'
});
