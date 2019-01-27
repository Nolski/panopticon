<template>
  <!--
   Select with search that takes props
   options can be array of values or array
   of objects. multiple is either true or false
   to allow for selecting multiple objects at once
   track-by is the key the objects get tracked
   by. by default it is set to the name.
   label is the key which gets shown in the
   select dropdown
   @input and @select are events that emit the
   select object/s
   -->
  <div :class="`relative ${wrapperClass}`">
    <Multiselect
      v-tooltip="{content:placeholder,placement:'left',autoHide :true,delay:{ show: 200, hide: 500 },classes:['tooltip-datatable']}"
      :class="selectStyle"
      :options="options"
      :value="value"
      select-label=""
      deselect-label=""
      selected-label=""
      :searchable="searchable"
      :multiple="multiple"
      :track-by="trackBy"
      :label="label"
      :placeholder="placeholder"
      @input="handleInput"
      @select="handleSelect"
      @remove="handleRemove"
    >
      <template
        slot="option"
        slot-scope="{option}"
      >
        <span
          v-if="option.label && option.label.length > 13"
          v-tooltip="{offset:'50',content:option.label,classes:['tooltip-datatable']}"
          dir="auto"
        >
          {{ option.label }}
        </span>
        <span
          v-else-if="option.label"
          dir="auto"
        >
          {{ option.label }}
        </span>
        <span
          v-else
          dir="auto"
        >
          {{ option }}
        </span>
      </template>
    </Multiselect>
    <button
      v-if="hasRemove && value"
      class="clear-filter-button  flex items-center absolute pin-r pin-t mt-3 mr-3 cursor-pointer text-white text-xxs rounded-full"
      @click="$emit('clear')"
    >
      <i
        class="icon-X_x40_2xpng_2"
      />
    </button>
  </div>
</template>

<script>
  import Multiselect from 'vue-multiselect'
  import classNames from 'classnames'

  export default {
    components: {Multiselect},
    /**
     * all props have their needed types
     * and they also have their default
     * non-effecting values/default values
     */
    props: {
      label: {
        type: String,
        default: ''
      },
      value: {
        type: [String, Number, Object, Array],
        default: null
      },
      options: {
        type: Array,
        default: function () {
          return []
        }
      },
      searchable: {
        type: Boolean,
        default: true
      },
      multiple: {
        type: Boolean,
        default: false
      },
      placeholder: {
        type: String,
        default: 'select'
      },
      trackBy: {
        type: String,
        default: "name"
      },
      customClass: {
        type: String,
        default: ''
      },
      hasRemove: {
        type: Boolean,
        default: false
      },
      wrapperClass: {
        type: String,
        default: ''
      }
    },
    computed: {
      selectStyle() {
        return classNames([
          'multiselect-field', this.customClass, this.value ? 'filled':'empty']);
      }
    },
    methods: {
      handleInput(value, id) {
        this.$emit('input', value);
      },
      handleSelect(selectedOption) {
        this.$emit('select', selectedOption);
      },
      handleRemove(removedOption) {
        this.$emit('remove', removedOption);
      }
    }
  }
</script>

<style lang="scss">

</style>