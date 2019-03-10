import Toasted from 'vue-toasted';
import VTooltip from 'v-tooltip';
import Popper from 'vue-popperjs';
import moment from 'moment';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);


Vue.use(Toasted, {
  position: 'bottom-right',
  duration: 3000,
  iconPack: 'custom-class',
  theme: 'bubble',
  className: 'clipboard-custom-class'
});

Vue.use(VTooltip);

// Screens
Vue.component('Builder',require('./components/Builder.vue').default);
Vue.component('Dashboard',require('./components/Dashboard.vue').default);
Vue.component('FirmProfile',require('./components/Firm.vue').default);
Vue.component('Firm',require('./components/Firm.vue').default);
Vue.component('CaseListing',require('./components/CaseListing.vue').default);
Vue.component('SeekerProfile',require('./components/JobSeeker.vue').default);
Vue.component('JobSeeker',require('./components/JobSeeker.vue').default);
Vue.component('UsersListing',require('./components/UserListing.vue').default);
Vue.component('UserView',require('./components/UserView.vue').default);
Vue.component('JobOpeningMatch', require('./components/JobOpeningMatch.vue').default);
Vue.component('JobOpeningSavedMatches', require('./components/JobOpeningSavedMatches.vue').default);
Vue.component('NotesList', require('./components/NotesList').default);


// UI components
Vue.component('Breadcrumbs',require('./components/Breadcrumbs.vue').default);
Vue.component('Btn',require('./components/Button.vue').default);
Vue.component('Icon',require('./components/Icon.vue').default);
Vue.component('Modal',require('./components/Modal.vue').default);
Vue.component('Panel',require('./components/Panel.vue').default);
Vue.component('Spinner',require('./components/Spinner.vue').default);
Vue.component('Notebox',require('./components/Notebox.vue').default);
Vue.component('Datatable',require('./components/Datatable.vue').default);
Vue.component('CheckboxField',require('./components/Checkbox.vue').default);
Vue.component('TextInput',require('./components/TextInput.vue').default);
Vue.component('ButtonGroup',require('./components/ButtonGroup.vue').default);
Vue.component('JobOpening',require('./components/JobOpening.vue').default);
Vue.component('PageLoader',require('./components/PageLoader.vue').default);
Vue.component('AnchorLink',require('./components/Link.vue').default);
Vue.component('CustomSelect',require('./components/CustomSelect.vue').default);
Vue.component('RangeFilter',require('./components/RangeFilter.vue').default);
Vue.component('CheckboxGroup',require('./components/CheckboxGroup.vue').default);
Vue.component('ValueCard',require('./components/MetricCard.vue').default);
Vue.component('AddNoteModal',require('./components/AddNoteModal.vue').default);
Vue.component('Clipboard',require('./components/Clipboard.vue').default);
Vue.component('ListItem',require('./components/ListItem.vue').default);
Vue.component('EmptyState',require('./components/EmptyState.vue').default);
Vue.component('Filters',require('./components/Filters.vue').default);
Vue.component('Pagination',require('./components/Pagination.vue').default);
Vue.component('Screenbox',require('./components/Screenbox.vue').default);
Vue.component('ViewNoteModal',require('./components/ViewNotesModal.vue').default);
Vue.component('Alert',require('./components/Alert.vue').default);
Vue.component('Popper',Popper);


Vue.filter('trans', function (key,value) {
  return window.Lang.get(key);
});

Vue.filter('formatted_date', function (value) {
    return moment(value).locale(window.locale).format('dddd DD MMMM');
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  el: '#app',
});
