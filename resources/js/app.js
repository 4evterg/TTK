 require('./bootstrap');


 window.Vue = require('vue').default;
 
 import Form from './Form';
 window.Form = Form;
 
 import VueRouter from 'vue-router';
 import routes from './routes'
 
 Vue.use(VueRouter);

 import HeaderComponent from './components/header-component.vue';
 Vue.component('header-component', HeaderComponent);
 
 Vue.component('home-component', require('./components/home.vue').default);
 
 const app = new Vue({
     el: '#app',
     router: new VueRouter(routes)
 });
 