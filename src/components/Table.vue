<template>
  <div>
    <vue-editable-grid
      class="my-grid-class"
      ref="grid"
      id="mygrid"
      :column-defs="fields"
      :row-data="items"
      row-data-key="entity.primary_key"
      @cell-updated="cellUpdated"
      @row-selected="rowSelected"
      @link-clicked="linkClicked"
    >
      <template v-slot:header> </template>
    </vue-editable-grid>
  </div>
</template>

<script>
export default {

    data() {
        return {
            fields: [],
            items: [],
            entity: {},
            base_url: 'https://erp.katzen48.de',
        }
    },

    mounted() {

        this.entity = this.$store.state.application.menu[this.$route.name]
        this.fields = this.entity.fields
/*
        this.fields.push({
            editable: false,
            field: 'Edit',
            filter: false,
            headerName: '',
            sortable: false,
        })
*/
        this.$axios.get(this.base_url + this.entity.api_url)
            .then(res => {
                this.items = res.data.data
            })
    },
/*
    updated() {

        let idx = this.fields.length - 1
        let len = this.items.length

        for (let i = 0; i < len; i++) {
            let cell = document.getElementById('cell' + i + '-' + idx)
            cell.innerHTML = "<b-icon-gear></b-icon-gear>"
            cell.innerHTML = "<a onclick='console.log(window)'>&#9997;</a>"
        }

    },
*/
    methods: {

        cellUpdated(cell) {

            let url = this.base_url
                + this.entity.api_url + '/'
                + cell.row[this.entity.primary_key]

            cell.markAsPending()

            let column = cell.column.field
            let payload = cell.row
            payload[column] = cell.value

            this.$axios.put(url, payload, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
                .then(res => {
                    let status = res.status
                    if (status === 200 || status === 422) {
                      if (status === 422) {
                          cell.markAsFailed(res.data.errors[column][0])
                          cell.confirm()
                      } else {
                          cell.markAsSuccess()
                          cell.confirm()
                          this.$set(this.items, cell.rowIndex, res.data.data)
                      }
                    } else {
                        cell.markAsFailed(res.statusText)
                        cell.confirm()
                    }
                })
                .catch(err => {
                    cell.markAsFailed(err)
                    cell.confirm()
                })

        },

        rowSelected() {

        },

        linkClicked() {

        },
    },
}
</script>

<style>
.my-grid-class {
  height: 400px;
}
</style>
