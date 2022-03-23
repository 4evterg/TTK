import Home from './components/home';
import Register from './components/register';
import Login from './components/login';
import Dashboard from './components/dashboard';
import NotFound from './components/notFound';

export default{
  mode: 'history',
  linkActiveClass: 'font-semibold',

  routes: [
    {
      path: '*',
      component: NotFound
    },
    {
      path: '/',
      component: Home,
      name: 'Home',
    },
    {
      path: '/register',
      component: Register
    },
    {
      path: '/login',
      component: Login,
      name: 'login'
    },
    {
      path: '/dashboard',
      component: Dashboard,
      name: 'Dashboard',
      beforeEnter: onEnter
    }
  ]

}

function onEnter (to, form, next) {
  axios.get('/api/athenticated').then(()=>{
      next()
  }).catch(()=>{
      return next({ name: 'Login'})
  })
}
