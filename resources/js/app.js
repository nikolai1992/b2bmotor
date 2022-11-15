
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./vendor/select2/select2.min');
//require('./src/select');
require('./src/cart');
//require('./src/client');

//window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
//
// const app = new Vue({
//     el: '#app'
// });

function mobileSubMenu() {
    $(document).on('click', '.menu .submenu_section a', function () {
        $(this).siblings('.submenu').addClass('active')
    })

    $(document).on('click', '.menu .back', function() {
        $(this).closest('.submenu').removeClass('active')
    })
}

$(document).ready(() => {
    mobileSubMenu()
});


