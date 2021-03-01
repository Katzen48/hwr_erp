<template>
    <div class="login">
        <form action="#" @submit.prevent="submit">
            <div class="form-group">
                <label>Email address</label>
                <input type="email" name="email" class="form-control form-control-lg" v-model="form.email" />
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control form-control-lg" v-model="form.password" />
            </div>

            <button type="submit" class="btn btn-dark btn-lg btn-block">Sign In</button>

            <p class="forgot-password text-right mt-2 mb-4">
                <router-link to="/forgot-password">Forgot password ?</router-link>
            </p>
        </form>
    </div>
</template>

<script>
import {mapActions} from 'vuex';

    export default {
        data() {
            return {
              form: {
                email: '',
                password: ''
              }
            }
        },
      methods: {
        ...mapActions({
          signIn: 'signIn'
        }),
        async submit() {
            await this.signIn(this.form);

            await this.$store.dispatch('loadRoutes', this.$router);
            await this.$router.replace('/');
        }
      }
    }
</script>

<style>
  .login {
    width: 30%;
    margin: auto;
  }
</style>
