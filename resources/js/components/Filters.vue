<template>
  <div class="flex flex-wrap mb-8">
    <template v-for="filter in userFilters">
      <CustomSelect
        v-if="filter.type === 'select'"
        :key="filter.name+'-'+filter.type"
        label="label"
        :multiple="true"
        track-by="value"
        :has-remove="true"
        :value="getOptionValue(filter)"
        :options="options(filter.options)"
        :placeholder="filter.label"
        :wrapper-class="`w-full pr-2
        ${getOptionValue(filter) && getOptionValue(filter).length > 2 ? 'md:w-full lg:w-full xl:w-1/3':' md:w-1/2 lg:w-1/3 xl:w-1/5 '}`"
        custom-class="mb-2 note-select filter-input multiselect-with-remove"
        @clear="handleClear(filter.name)"
        @select="handleSelect(filter, $event)"
        @remove="handleRemove(filter, $event)"
      />
      <DatePicker
        v-else-if="filter.type === 'date'"
        :key="filter.name+'-'+filter.type"
        lang="en"
        :value="filter.filterValue"
        wrapper-class="w-full sm:w-full md:w-1/2 lg:w-1/3  xl:w-1/5  pr-2 h-50"
        input-class="height-align text-sm
        mb-2 mr-2 p-2 text-grey-darkest font-bold
        w-full bg-grey-lighter rounded"
        :clear-button="true"
        format="yyyy-MM-dd"
        :placeholder="filter.label"
        clear-button-icon="icon-X_x40_2xpng_2"
        custom-class="mb-2 note-select filter-input multiselect-with-remove"
        @cleared="handleClear(filter.name)"
        @input="handleTextInput(filter.name,$event)"
      />
      <RangeFilter
        v-else-if="filter.type === 'trigger'"
        :key="filter.name+'-'+filter.type"
        :filter="filter"
        :value="filter.filterValue"
        @clear="handleClear(filter.name,$event)"
        @input="handleTextInput(filter.name,$event)"
      />
      <TextInput
        v-else
        :key="filter.name+'-'+filter.type"
        input-class="height-align
        mb-2 mr-2 p-2 text-grey-darkest font-bold
        w-full bg-grey-lighter rounded"
        wrapper-class="w-full sm:w-full md:w-1/2 lg:w-1/3  xl:w-1/5   pr-2 h-50"
        :has-remove="true"
        :placeholder="filter.label"
        :value="filter.filterValue"
        @clear="handleClear(filter.name)"
        @input="handleTextInput(filter.name,$event)"
      />
    </template>
    <FilterSelect
      label="label"
      track-by="name"
      :filters="filters"
      :user-filters="userFilters"
      :placeholder="'irc.more' | trans"
      :multiple="true"
      custom-class="mb-2 note-select pr-2 w-full sm:w-full md:w-1/2 lg:w-1/3  xl:w-1/5 filter-selector"
      @select="handleFilterSelect"
    />
  </div>
</template>

<script>
  import FilterSelect from './FilterSelect'
  import HasFilters from "../mixins/HasFilters";
  import DatePicker from 'vuejs-datepicker'

  export default {
    /**
     * all props have their needed types
     * and are passed using the mixin
     */
    components: {FilterSelect, DatePicker},
    mixins: [HasFilters],
    data() {
      return {
        vall: ''
      }
    },
    methods: {
      options(options) {
        return options.filter(option => {
          return option.label !== ""
        })
      },
      inputDate(eve) {
        this.val = eve;
      },
      handleSelect(filter, selected) {
        const value = Array.isArray(filter.filterValue) ? [...filter.filterValue] : []
        const index = value.indexOf(selected.value);
        if (index === -1) {
          value.push(selected.value);
        } else {
          value.splice(index, 1);
        }
        this.$emit('change', {
          value,
          name: filter.name
        })
      },
      handleRemove(filter, removed) {
        const value = [...filter.filterValue];
        const indexToRemove = value.indexOf(removed.value);
        value.splice(indexToRemove, 1);
        this.$emit('change', {
          value,
          name: filter.name
        })
      },
      handleClear(name, value = null) {
        this.$emit('change', {
          name,
          value: value
        })
      },
      handleTextInput(name, value) {
        this.$emit('change', {
          name,
          value
        })
      },
      handleFilterSelect(select) {
        this.$emit('filterSelect', {
          name: select.name
        })
      },
      getOptionValue(filter) {
        if (!filter.filterValue) {
          return undefined
        }

        const filterValue = Array.isArray(filter.filterValue) ? filter.filterValue : [filter.filterValue]

        let selected = filterValue.reduce((selected, value) => {
          const index = filter.options.findIndex(option => option.value == value)
          if (index !== -1) {
            selected.push(filter.options[index])
          }
          return selected;
        }, []);

        if (!selected.length) {
          return undefined
        }
        return selected
      }
    }

  }
</script>

<style lang="scss">

</style>