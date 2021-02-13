<template>
  <div>
    <br/>
    <b-table striped hover :items="items"></b-table>
  </div>
</template>

<script>
  export default {
    data() {
      return { fields: [], items: [] }
    },
    created() {
      let api = 'https://erp.katzen48.de/api/'
      switch (this.$route.name ) {
        case 'personal':
          api += 'administration/employees'
          break;
        case 'einkaufsbestellungen':
          api += 'scm/items'
          break;
      }
      fetch(api)
      .then(res => res.json())
      .then(res => {
        this.items = res.data
      })
    }
  }
</script>
