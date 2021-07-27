/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel. 
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import RecentMatches from './components/RecentMatches';
import LoadMatches from './components/LoadMatches';
import GetFreeCredits from './components/User/GetFreeCredits';
import UserMessages from './components/User/UserMessages';
import EditProfileSettings from './components/User/EditProfileSettings';

// register globally
import infiniteScroll from 'vue-infinite-scroll';
Vue.use(infiniteScroll);

Vue.filter('formatMoney', function (money) {
  return !!money === true
    ? parseFloat(money)
        .toFixed(2)
        .replace(/\d(?=(\d{3})+\.)/g, '$&,')
    : '0.00';
});

const vueElement = !!window.location.vueElement ? window.location.vueElement : '#app';

const app = new Vue({
  el: vueElement,
  components: {
    RecentMatches,
    LoadMatches,
    GetFreeCredits,
    UserMessages,
    EditProfileSettings
  }
});
