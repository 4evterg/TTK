 require('./bootstrap');


 window.Vue = require('vue').default;
 
 import Form from './Form';
 window.Form = Form;
 
 import VueRouter from 'vue-router';
 import routes from './routes';
 import VuePaginate from 'vue-paginate';
 
 Vue.use(VueRouter);
 Vue.use(VuePaginate);

 import HeaderComponent from './components/header-component.vue';
 Vue.component('header-component', HeaderComponent);
 
 import FooterComponent from './components/footer-component.vue';
 Vue.component('footer-component', FooterComponent);

 Vue.component('home-component', require('./components/home.vue').default);
 

 const app = new Vue({
     el: '#app',
     router: new VueRouter(routes)
 });
 