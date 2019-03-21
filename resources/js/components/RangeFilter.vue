<template>
  <div class="range-container flex">
    <div class="w-1/2">
      <TextInput
        input-class="height-align p-2 text-grey-darkest font-bold
        w-full bg-grey-lighter border-r-2 border-grey-light rounded-l"
        :has-remove="true"
        :placeholder="`${filter.label} from`"
        :value="filter.filterValue.from"
        @clear="rangeInputClear('from')"
        @input="rangeInputChange($event,'from')"
      />
    </div>
    <div class="w-1/2 pr-2">
      <TextInput
        input-class="height-align p-2 text-grey-darkest font-bold
        w-full bg-grey-lighter rounded-r"
        :has-remove="true"
        :placeholder="`${filter.label} to`"
        :value="filter.filterValue.to"
        @clear="rangeInputClear('to')"
        @input="rangeInputChange($event,'to')"
      />
    </div>

    <!--wrapper-class="w-full sm:w-full md:w-1/2 lg:w-1/3  xl:w-1/5   pr-2 h-50"-->
  </div>
</template>

<script>
  export default {
    props: {
      filter: {
        type: Object,
        default: () => {
          {
          }
        }
      }
    },
    data() {
      return {}
    },
    computed: {},
    methods: {
      rangeInputChange(value, rangeIndicator) {
        let rangeValue = {
          from: rangeIndicator === 'from' ? parseInt(value) : this.filter.filterValue.from,
          to: rangeIndicator === 'to' ? parseInt(value) : this.filter.filterValue.to
        };
        this.$emit('input', rangeValue)
      },
      rangeInputClear(rangeIndicator) {
        let rangeValue = {
          from: rangeIndicator === 'from' ? '' : this.filter.filterValue.from,
          to: rangeIndicator === 'to' ? '' : this.filter.filterValue.to
        };
        this.$emit('clear', rangeValue)
      }
    },
  }
</script>

<style scoped>

</style>
