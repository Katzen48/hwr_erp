<template>
  <div id="app">
    <Navbar/>
    <router-view :key="$route.fullPath"/>
  </div>
</template>

<script>
import Navbar from './components/Navbar'

export default {
  name: "App",
  components: {
    Navbar: Navbar,
  },
  computed: {
    routes() {
      return window.menu;
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
