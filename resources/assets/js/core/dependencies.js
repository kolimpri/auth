/*
 * Load Vue & Vue-Resource.
 *
 * Vue is the JavaScript framework used by Auth.
 */
window.Vue = require('vue');

require('vue-resource');
Vue.http.headers.common['X-CSRF-TOKEN'] = kAuth.csrfToken;

/*
 * Load Underscore.js, used for map / reduce on arrays.
 */
window._ = require('underscore');

/*
 * Load Moment.js, used for date formatting and presentation.
 */
window.moment = require('moment');

/*
 * Load jQuery and Bootstrap jQuery, used for front-end interaction.
 */
window.$ = window.jQuery = require('jquery');
require('bootstrap-sass/assets/javascripts/bootstrap');

