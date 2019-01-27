export default {
  data() {
    return {
      filters: [],
      userFilters: [],

    }
  },
  methods: {
    userFiltersToParams() {
      return this.userFilters.reduce((acc, filter) => {
        if (filter.filterValue || filter.filterValue === "") {
          acc[filter.name] = filter.filterValue
        }
        return acc
      }, {})
    },
    filterSelect({name}, callback) {
      const filterIndex = this.filters.findIndex(filter => filter.name === name)
      const userFilterIndex = this.userFilters.findIndex(filter => filter.name === name)

      if (userFilterIndex === -1) {
        if(this.filters[filterIndex].type === 'trigger'){
          let triggerFilter = {...this.filters[filterIndex]};
          triggerFilter.filterValue = {from:'',to:''};
          this.userFilters.push(triggerFilter)
        }
        else{
          this.userFilters.push({
            ...this.filters[filterIndex]
          })
        }

      } else {
        const removedFilter = this.userFilters[userFilterIndex];

        this.userFilters.splice(userFilterIndex, 1)

        const filterHasValue = removedFilter.filterValue && String(removedFilter.filterValue).trim() !== '';

        if (filterHasValue && typeof callback === 'function') {
          callback()
        }
      }
    },
    filterChange(event, loadData) {
      if (this.filterTimeout) {
        clearTimeout(this.filterTimeout)
      }

      this.userFilters = this.userFilters.map(filter => {
        if (filter.name === event.name) {
            if(filter.type !== 'trigger'){
              return {
                ...filter,
                filterValue: event.value
              }
            }
            else{
              return {
                ...filter,
                filterValue: {
                  from: event.value.from,
                  to: event.value.to
                }
              }
            }
          }
        return filter
      })

      this.filterTimeout = setTimeout(() => {
        if (loadData && typeof loadData === 'function') {
          loadData({page: 1})
        }
      }, 600)
    },
    initialUserFilters(filtersList, filtersObject) {

      if (Object.keys(filtersObject).length === 0) {
        return filtersList.slice(0, 3)
      }

      const userFilters = filtersList.filter(filter => {
        const filterValue = filtersObject[filter.name];
        return typeof filterValue !== "undefined"
      }).map(filter => {
        return {
          ...filter,
          filterValue: filtersObject[filter.name]
        }
      })

      if (userFilters.length > 0) {
        return userFilters
      }
      return filtersList.slice(0)
    },
  },
}