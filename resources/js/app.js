require('./bootstrap');
window.Vue = require('vue');
import vuetify from './plugins/vuetify' // path to vuetify export

import common from './common';
Vue.use(require('vue-moment'));

Vue.mixin(common);
// import 'babel-polyfill';
require('es6-object-assign').polyfill();
require('es6-promise').polyfill();

import CKEditor from 'ckeditor4-vue';
Vue.use( CKEditor );


router.afterEach((to, from) => {
    Vue.nextTick(() => {
        document.title = to.meta.title ? to.meta.title : 'Al-Sharq';
    });
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import router from './router'
import store from './store'
Vue.component('z-dashboard', require('./components/ExampleComponent.vue').default);
const app = new Vue({
    el: '#app',
    vuetify,
    router,
    store: store,
});
