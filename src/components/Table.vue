<template>
  <div>
    <b-button-toolbar v-if="entity.edit">
      <b-button-group class="mx-1">
        <b-button @click="createItem" variant="primary">Neu</b-button>
        <b-button @click="edit_mode = true" :disabled="!selectedItem" variant="secondary">Bearbeiten</b-button>
        <b-button @click="deleteSelectedItems" :disabled="deleting" variant="danger">
          {{ !deleting ? 'Löschen' : '' }}
          <b-spinner small v-if="deleting"></b-spinner>
          <span class="sr-only" v-if="deleting">Löschen...</span>
        </b-button>
      </b-button-group>

      <b-button-group class="mx-1">
        <b-button @click="openChildEntity(key)" v-for="(value, key) in childEntities" v-bind:key="key" variant="info">
          {{ value.title }}
        </b-button>
      </b-button-group>
    </b-button-toolbar>
    <vue-editable-grid
      class="my-grid-class"
      ref="grid"
      :id="gridId"
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

    <card v-if="edit_mode || create_mode" :entity="entity.edit" :selectedItem="selectedItem" :fields="entity.edit.fields" :base_url="base_url" :mode="edit_mode ? 'edit' : 'create'" @close="editModeClosed"></card>

    <div v-if="shownChildEntity && selectedItem">
      <b-modal size="xl" :id="shownChildEntityModalId" ref="childEntityModal" @hide="editModeClosed" :visible="true" :title="childEntities[shownChildEntity].title">
        <list :nestedEntity="childEntities[shownChildEntity]" :parentName="nestedEntityName || this.$route.name.toLowerCase()" :parentId="selectedItem[entity.primary_key]" :nestedEntityName="shownChildEntity"></list>
      </b-modal>
    </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            gridId: Math.random().toString(12).substring(3),
            fields: [],
            items: [],
            entity: {},
            base_url: 'https://erp.katzen48.de',
            edit_mode: false,
            selectedItem: null,
            create_mode: false,
            deleting: false,
            childEntities: {},
            shownChildEntity: null,
            shownChildEntityModalId: Math.random().toString(12).substring(3),
        }
    },

    props: {
        nestedEntity: Object,
        nestedEntityName: String,
        parentName: String,
        parentId: {
          default: null,
        },
    },

    mounted() {
        this.entity = this.nestedEntity || this.$store.state.application.menu[this.$route.name.toLowerCase()];

        if(this.nestedEntity) {
            this.entity.api_url = this.entity.api_url.replace(`{${this.parentName}}`, this.parentId);

            if(this.entity.edit) {
              this.entity.edit.api_url = this.entity.edit.api_url.replace(`{${this.parentName}}`, this.parentId);
            }
        }

        this.fields = this.entity.fields;

        let primaryKey = this.fields.find(field => field.field == this.entity.primary_key);
        if(primaryKey) {
          primaryKey.type = 'link';
        }

        this.refresh().then(() => {

                if(this.$refs.grid) {
                  this.$refs.grid.$nextTick(() => {
                    document.getElementById('cell0-0').click();
                  });
                }

                document.addEventListener('keydown', this.onKeyDown);
            });

        Object.keys(this.$store.state.application.menu)
            .filter(key => this.$store.state.application.menu[key].parent === (this.nestedEntityName || this.$route.name.toLowerCase()))
            .forEach(key => this.childEntities[key] = this.$store.state.application.menu[key]);
        //this.$set(this, 'shownChildEntity', this.childEntities[0]);
    },

    computed: {
        editableFields() {
          return this.fields.filter(field => field.editable).map(field => field.field);
        }
    },

    methods: {

        cellUpdated(cell) {
            if(!this.editableFields.includes(cell.column.field)) {
                console.log(this.items[cell.rowIndex]);
                return;
            }

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
            if(event.colData.field === this.entity.primary_key) {
              this.selectedItem = event.rowData;
              this.edit_mode = true;
            }
        },

        openChildEntity(childEntity) {
            this.$set(this, 'shownChildEntity', childEntity);
        },

        createItem() {
            this.selectedItem = {};
            this.create_mode = true;
        },

        editModeClosed() {
            this.edit_mode = false;
            this.create_mode = false;
            this.shownChildEntity = null;
            this.refresh();
            document.getElementById('cell0-0').click();
        },

        /**
         *
         * @param event KeyboardEvent
         */
        onKeyDown(event) {
          if(event.ctrlKey) {
            if(event.key === 'e') {
              if (this.selectedItem) {
                this.edit_mode = true;
              }
              event.preventDefault();
            }

            if(event.key === 'Delete') {
              this.deleteSelectedItems();
              event.preventDefault();
              event.stopPropagation();
            }
          }
        },

      deleteSelectedItems() {
          if(!this.$refs.grid.selStart || !this.$refs.grid.selEnd || (this.$refs.grid.selStart.length < 1) || (this.$refs.grid.selEnd.length < 0)) {
            return;
          }

          let min = Math.min(this.$refs.grid.selStart[0], this.$refs.grid.selEnd[0]);
          let max = Math.max(this.$refs.grid.selStart[0], this.$refs.grid.selEnd[0]);

          this.deleting = true;
          let deletePromises = [];
          for(let i = min ; i <= max ; i++) {
              let item = this.items[i];

              deletePromises.push(this.$axios.delete(this.base_url + this.entity.api_url + '/' + item[this.entity.primary_key]));
          }

          Promise.all(deletePromises).then(() => {
            this.deleting = false;
          }).catch((err) => {
            this.deleting = false;

            if(err.response.data && err.response.data.message) {
                alert(err.response.data.message)
            }
          }).finally(() => this.refresh());
      },

        refresh() {
          return this.$axios.get(this.base_url + this.entity.api_url)
              .then(res => {
                  this.$set(this, 'items', res.data.data);
              })
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
