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
      @context-menu="contextMenu"
    >
      <template v-slot:header> </template>
    </vue-editable-grid>

    <card v-if="edit_mode" :entity="entity.edit" :selectedItem="selectedItem" :fields="fields" :base_url="base_url" @close="editModeClosed"></card>
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
            edit_mode: false,
            selectedItem: null
        }
    },

    mounted() {

        this.entity = this.$store.state.application.menu[this.$route.name]
        this.fields = this.entity.fields

        this.fields.find(field => field.field == this.entity.primary_key).type = 'link';
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
                this.items = res.data.data;

                setTimeout(() => {
                    document.getElementById('cell0-0').click();
                }, 500);

                document.addEventListener('keydown', this.onKeyDown);
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

        contextMenu() {
            //console.log('Context Menu opened');
        },

        rowSelected(event) {
            this.selectedItem = event.rowData;
        },

        linkClicked(event) {
            if(event.colData.field == this.entity.primary_key) {
              this.selectedItem = event.rowData;
              this.edit_mode = true;
            }
        },

        editModeClosed() {
            this.edit_mode = false;
            this.$forceUpdate();
            document.getElementById('cell0-0').click();
        },

        /**
         *
         * @param event KeyboardEvent
         */
        onKeyDown(event) {
            if(event.ctrlKey && event.key === 'e') {
              if(this.selectedItem) {
                this.edit_mode = true;
              }
              event.preventDefault();
            }
        }
    },

    destroyed() {
        document.removeEventListener('keydown', this.onKeyDown);
    }
}
</script>

<style>
.my-grid-class {
  height: 400px;
}
</style>
