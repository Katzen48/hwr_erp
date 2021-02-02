import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

// TODO change to correct host
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

export default router
