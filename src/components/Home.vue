<template>
  <div class="home">
    <br/>
    <h3>Welcome to ChickClothes</h3>
    <Login />
  </div>
</template>

<script>
import Login from './Login'

export default {
  name: "Home",
  components: {
    Login: Login,
  }
}
</script>
