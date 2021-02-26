<template>
  <div id="app">
    <Navbar/>
    <router-view :key="$route.fullPath"/>
  </div>
</template>

<script>
import Navbar from './components/Navbar'
import Table from './components/Table'
import Card from './components/Card'

export default {

  name: "App",

  components: {
    Navbar,
  },

  computed: {
    routes() {
      return window.menu;
    }
  },

  created: async function() {

    let res = await fetch('https://erp.katzen48.de/api/application/structure')
    res = await res.json()
    this.$store.commit("setMenu", res)

    for (const [key, value] of Object.entries(res)) {
        if (!value.parent) {
            this.$router.addRoute({
                path: '/' + key, 
                name: key,
                component: value.type == 'List' ? Table : Card,
            });
        }
    }
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
</style>
