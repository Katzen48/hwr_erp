<template>
  <div>
    <b-table striped hover :items="items"></b-table>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        fields: ['Nr.', 'Lieferant', 'Lieferdatum', 'Buchungsdatum', 'Bestellmenge (€)', 'Aktionen'],
        items: [
          { 'Nr.': 1001, 'Lieferant': 'Dickerson', 'Lieferdatum': new Date().toLocaleString(), 'Buchungsdatum': new Date().toLocaleString(), 'Bestellmenge (€)': 1000, 'Aktionen': null},
        ]
      }
    }
  }
</script>
