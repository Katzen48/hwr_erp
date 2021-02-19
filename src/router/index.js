import Vue from 'vue'
import VueRouter from 'vue-router'
import Table from '../components/Table'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../components/Home.vue')
  },
  {
    path: '/about',
    name: 'About',
    component: () => import('../views/About.vue')
  },
  {
    path: '/einkaufsbestellungen',
    name: 'einkaufsbestellungen',
    component: Table
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../components/Login.vue')
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

// TODO change to correct host
/*
Vue.axios.get('http://localhost:8000/api/application/structure').then(result => {
  window.menu = result.data;

  result.data.forEach(route => {
    router.addRoute({
      path: '/' + route.title.toLocaleLowerCase(),
      name: route.title,
      component: () => import('../views/About.vue')
    })
  })
});
*/

export default router
