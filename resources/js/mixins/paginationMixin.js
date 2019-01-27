export default {
  data() {
    return {
      pagination: {
        total: 0,
        lastPage: 1,
        perPage: 15,
        currentPage: 1
      }
    }
  },
  methods:{

    setPaginationFromMeta(meta){
      this.pagination = {
        total: meta.total,
        lastPage: meta.last_page,
        perPage: parseInt(meta.per_page),
        currentPage: meta.current_page
      };
    }

  }
}