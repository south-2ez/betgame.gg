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
import AdminMatchReport from './components/MatchManagers/AdminMatchReport';
import LoadMatches from './components/LoadMatches';
import CreateGiftCodes from './components/Admin/CreateGiftCodes';
import GiftCodesList from './components/Admin/GiftCodesList';
import GetFreeCredits from './components/User/GetFreeCredits';
import UserMessages from './components/User/UserMessages';
import ArrangeHomePageOutrightsDisplay from './components/MatchManagers/ArrangeHomePageOutrightsDisplay';
// import AddBankAccount from './components/Admin/AddBankAccount';
// import BankAccountLists from './components/Admin/BankAccountLists';
import ManageBankAccounts from './components/Admin/ManageBankAccounts';
import EditProfileSettings from './components/User/EditProfileSettings';
import MatchBndAutoBetSettings from './components/MatchManagers/MatchBndAutoBetSettings';
// import TrickOrTreatEvent from './components/User/TrickOrTreatEvent';
// import ChristmasEvent from './components/User/ChristmasEvent';
// import WinwheelRoulette from './components/User/WinwheelRoulette';
// import ChineseNewYearFlipCards from './components/User/ChineseNewYearFlipCards';
import EasterEggEvent from './components/User/EasterEggEvent.vue';

// register globally
import infiniteScroll from 'vue-infinite-scroll';
Vue.use(infiniteScroll);

Vue.filter('formatMoney', function(money) {
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
		AdminMatchReport,
		MatchBndAutoBetSettings,
		LoadMatches,
		CreateGiftCodes,
		GiftCodesList,
		GetFreeCredits,
		UserMessages,
		ArrangeHomePageOutrightsDisplay,
		// AddBankAccount,
		// BankAccountLists,
		ManageBankAccounts,
		EditProfileSettings,
		// TrickOrTreatEvent,
		// WinwheelRoulette,
		// ChineseNewYearFlipCards
		EasterEggEvent
	}
});
