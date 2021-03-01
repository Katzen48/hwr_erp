<template>
  <div id="app">
    <Navbar v-if="authenticated"/>
    <router-view :key="$route.fullPath"/>
  </div>
</template>

<script>
import Navbar from './components/Navbar'

export default {

  name: "App",

  components: {
    Navbar,
  },

  computed: {
    authenticated() {
      return this.$store.state.application.authenticated;
    },
    routes() {
      return this.$store.state.application.menu;
    }
  },

  created: async function() {
    if (this.$store.state.application.authenticated) {
      await this.$store.dispatch('loadRoutes', this.$router);
    }
    else if(this.$route.path.toLowerCase() !== '/login') {
      await this.$router.push('signIn');
    }
  },
  methods: {

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
