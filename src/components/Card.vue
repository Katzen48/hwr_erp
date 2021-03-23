<template>
  <div>
    <b-modal size="xl" :id="modalId" :title="title" ref="modal" :visible="true" ok-only @hide="handleOk">
      <b-container :id="modalId + '-' + 'header'" fluid>
        <b-row v-for="field in fields" :key="field.field" class="mb-3">
          <b-col sm="3" lg="2">
            <label :for="modalId + '-' + 'header' + '-' + field.field">{{ field.headerName }}</label>
          </b-col>
          <b-col sm="9" lg="10">
            <div v-if="formTypes[field.field] === 'boolean'">
                <b-form-checkbox :id="modalId + '-' + 'header' + '-' + field.field"
                                 @change.native="checkboxChecked" :disabled="!field.editable" :ref="'field_' + field.field"
                                 :aria-describedby="modalId + '-' + 'header' + '-' + field.field + '-feedback'"
                                 :state="feedbackState[field.field]" :checked="edit ? item[field.field] : false" switch>
                </b-form-checkbox>
            </div>

            <div v-else>
                <b-form-input :value="edit ? item[field.field] : ''" :id="modalId + '-' + 'header' + '-' + field.field"
                              @change.native="fieldUpdated" :disabled="!field.editable" :ref="'field_' + field.field"
                              :aria-describedby="modalId + '-' + 'header' + '-' + field.field + '-feedback'"
                              :state="feedbackState[field.field]" :type="formTypes[field.field] || 'text'">
                </b-form-input>
            </div>

            <b-form-invalid-feedback :id="modalId + '-' + 'header' + '-' + field.field + '-feedback'">
              {{ feedback[field.field] }}
            </b-form-invalid-feedback>

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
          formTypes: {},
          item: {},
          feedbackState: {},
          feedback: {},
          edit: true,
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
    mode: {
      type: String,
      default: 'edit',
    }
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

        this.edit = (this.mode === 'edit');
        this.item = this.selectedItem;
        this.modalId = Math.random().toString(12).substring(3);
        this.title = this.edit ? `${this.entity.title} - ${this.item[this.entity.primary_key]}` : this.entity.title;

        for (let field of this.fields) {
            switch (field.type) {
                case 'date':
                    this.types[field.field] = Date;
                    this.formTypes[field.field] = 'date';
                    break;
                case 'currency':
                    this.types[field.field] = Number;
                    this.formTypes[field.field] = 'number';
                    break;
                case 'boolean':
                    this.types[field.field] = Boolean;
                    this.formTypes[field.field] = 'boolean';
                    break;
                case 'percent':
                    this.types[field.field] = Number;
                    this.formTypes[field.field] = 'number';
                    break;
                case 'numeric':
                    this.types[field.field] = Number;
                    this.formTypes[field.field] = 'number';
                    break;
                default:
                    this.types[field.field] = String;
                    this.formTypes[field.field] = 'text';
                    break;
            }
        }
    },

    methods: {

        fieldUpdated(event) {
            let field = event.target.id.substring(event.target.id.lastIndexOf('-') + 1);

            if(!this.editableFields.includes(field)) {
              return;
            }

            let payload = this.item
            payload[field] = (this.types[field])(event.target.value);

            this.update(payload, field);
        },

        checkboxChecked(event) {
            let field = event.target.id.substring(event.target.id.lastIndexOf('-') + 1);

            if(!this.editableFields.includes(field)) {
                return;
            }

            let payload = this.item
            payload[field] = (this.types[field])(event.target.checked);

            this.update(payload, field);
        },

        update(payload, field) {
            let url = this.base_url
                + this.entity.api_url + '/';

            if(this.edit) {
                url += this.item[this.entity.primary_key];
            }

            let method = this.edit ? this.$axios.put : this.$axios.post;

            method.apply(this, [url, payload, {
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
              }
            }])
                .then(res => {
                  let status = res.status
                  if (status === 200 || status === 422 || status === 201) {
                    if (status === 422) {
                      this.$set(this.feedback, field, res.data.errors[field][0]);
                      this.$set(this.feedbackState, field, false);
                    } else {
                      this.$set(this, 'item', res.data.data);
                      this.$set(this.feedback, field, null);
                      this.feedbackState[field] = true;

                      if(!this.edit) {
                        this.edit = true;
                      }
                    }
                  } else {
                    console.log('Status: ' + status);
                    this.$set(this.feedbackState, field, false);
                  }
                })
                .catch(err => {
                  if(err.response && err.response.data && (field in err.response.data.errors) && err.response.data.errors[field].length > 0) {
                    this.$set(this.feedback, field, err.response.data.errors[field][0]);
                  }

                  this.$set(this.feedbackState, field, false);
                })
        },

        handleOk() {
            this.$emit('close');
        },

        refresh() {
          this.$axios.get(this.base_url + this.entity.api_url + `/${this.item[this.entity.primary_key]}`)
              .then(res => {
                this.item = res.data.data;
              })
        }
    },
}
</script>

<style>
.my-grid-class {
  height: 400px;
}
</style>
