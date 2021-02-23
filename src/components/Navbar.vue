<template>
  <div>
    <p style="text-align:left; padding: 1em">ChickClothes</p>
    <b-nav tabs fill>
      <b-nav-item exact exact-active-class="active" v-for="navItem in navItems" :key="navItem.title" :to="navItem.route">
        {{ navItem.title }}
      </b-nav-item>
      <b-nav-item to="suche" exact exact-active-class="active">
        <b-icon-search></b-icon-search>
      </b-nav-item>
      <b-nav-item to="konto" exact exact-active-class="active">
        <b-icon-gear></b-icon-gear>
      </b-nav-item>
    </b-nav>
  </div>
</template>

<script>
export default {

    computed: {
        navItems() {

            let navItems = []
            let entity = this.$store.state.application.menu

            for (const [key, value] of Object.entries(entity)) {
                if (!value.parent) {
                    navItems.push({
                        route: '/' + key, 
                        title: value.title,
                    });
                }
            }

            return navItems
        }
    },
}
</script>
