import Vue from 'vue'
import './plugins/axios'
import App from './App.vue'
import store from './store'
import router from './router'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import VueEditableGrid from 'vue-editable-grid'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vue-editable-grid/dist/VueEditableGrid.css'

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.component('vue-editable-grid', VueEditableGrid)
Vue.component('card', (resolve) => require(['../src/components/Card'], resolve))

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
