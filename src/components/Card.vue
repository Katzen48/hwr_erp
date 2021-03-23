<template>
  <div>
    <b-modal size="lg" :id="modalId" :title="title" ref="modal" :visible="true" ok-only @hide="handleOk">
      <b-container :id="modalId + '-' + 'header'" fluid>
        <b-row v-for="field in fields" :key="field.field">
          <b-col sm="4">
            <label :for="modalId + '-' + 'header' + '-' + field.field">{{ field.headerName }}</label>
          </b-col>
          <b-col sm="8">
            <b-form-input :value="item[field.field]" :id="modalId + '-' + 'header' + '-' + field.field" @change.native="fieldUpdated" :disabled="!field.editable" :ref="'field_' + field.field"></b-form-input>
          </b-col>
        </b-row>
      </b-container>
    </b-modal>
  </div>
</template>

<script>
export default {

    data() {
        return {
          modalId: '',
          title: '',
          types: {},
          item: {},
        }
    },

    computed: {
        editableFields() {
          return this.fields.filter(field => field.editable).map(field => field.field);
        }
    },

  props: {
    entity: Object,
    selectedItem: Object,
    fields: Array,
    base_url: String,
  },

    created() {
      /*
      this.entity = this.$store.state.application.menu[this.$route.name]
      this.fields = this.entity.fields
      this.$axios.get(this.base_url + this.entity.api_url)
          .then(res => {
            this.items = res.data.data
          })
       */

      this.item = this.selectedItem;
      this.modalId = Math.random().toString(12).substring(3);
      this.title = `${this.entity.title} - ${this.item[this.entity.primary_key]}`

      let firstSelected = false;
      for (let field of this.fields) {
        if(!firstSelected && field.editable) {
            //this.$refs['field_' + field.field].focus();
            firstSelected = false;
        }

        this.types[field.field] = this.item[field.field].constructor;
      }
    },

    methods: {

        fieldUpdated(event) {
            let field = event.target.id.substring(event.target.id.lastIndexOf('-') + 1);

            if(!this.editableFields.includes(field)) {
              return;
            }

            //this.item[field] =

            let url = this.base_url
                + this.entity.api_url + '/'
                + this.item[this.entity.primary_key]

            //cell.markAsPending()

            let payload = this.item
            payload[field] = (this.types[field])(event.target.value);

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
                      //cell.markAsFailed(res.data.errors[column][0])
                      this.$forceUpdate();
                    } else {
                      this.item = res.data.data;
                      this.$forceUpdate();
                    }
                  } else {
                    //cell.markAsFailed(res.statusText)
                    this.$forceUpdate();
                  }
                })
                .catch(err => {
                  console.log(err);
                  //cell.markAsFailed(err)
                  this.$forceUpdate();
                })
        },

        handleOk() {
            this.$emit('close');
        },
    },
}
</script>

<style>
.my-grid-class {
  height: 400px;
}
</style>
