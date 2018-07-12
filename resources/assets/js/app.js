require('./bootstrap');

Vue.component('business', require('./components/BusinessComponent.vue'));

const app = new Vue({
    el: '#app'
});
