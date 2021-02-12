<template>
  <div class="home">
    <br/>
    <h4>Welcome to ChickClothes</h4>
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
