export default {
  data() {
    return {
      sorting: {
        type: null,
        column: null
      }
    }
  },
  methods: {
    handleSort(column,callback) {

      this.sorting = {
        column,
        type: this.sorting.type === 'asc' ? 'desc' : 'asc'
      }

      if (callback && typeof callback === 'function') {
        callback()
      }

    }
  },
}