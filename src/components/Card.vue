<template>
  <div>

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
        fetch(this.base_url + this.entity.api_url)
            .then(res => res.json())
            .then(res => {
                this.items = res.data
            })
    },

    methods: {

        cellUpdated(cell) {

            let url = this.base_url 
                + this.entity.api_url + '/'
                + cell.row[this.entity.primary_key]

            cell.markAsPending()

            let column = cell.column.field
            let payload = cell.row
            payload[column] = cell.value

            fetch(url, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                method: 'PUT',
                body: JSON.stringify(payload),
            })
                .then(res => {
                    let status = res.status
                    if (status === 200 || status === 422) {
                        res.json()
                            .then(res => {
                                if (status === 422) {
                                    cell.markAsFailed(res.errors[column][0])
                                    cell.confirm()
                                } else {
                                    cell.markAsSuccess()
                                    cell.confirm()
                                    this.items[cell.rowIndex] = res.data
                                    cell.row = res.data
                                }
                            })
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
