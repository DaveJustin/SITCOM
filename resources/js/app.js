/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */
 window.$ = window.jQuery = require('jquery');
// require('./bootstrap');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

 window.axios = require('axios');

 window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

 let token = document.head.querySelector('meta[name="csrf-token"]');

 if (token) {
     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
 } else {
     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
 }
// require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./views/HomeLogin');
require('./views/Auth/LoginLayout');
require('./views/Auth/RegisterLayout');

require('./views/Coordinator/Dashboard');
require('./views/Coordinator/Profile');
require('./views/Coordinator/Students');

/**
 * After requiring bootstrap we can not add our socket io here
 */

//  require('./client-socket.js');

/**
 * Font 
 */

require('typeface-poppins')