<template>
  <div>
    <ul
      v-if="totalPages > 1"
      class="flex list-reset w-auto font-sans pagination"
    >
      <li class="hidden xl:block pagination-item">
        <Btn
          class="no-underline block hover:text-blue hover:bg-grey-lighter bg-white
          text-green-dark border-none rounded  px-1 py-1"
          type="button"
          :disabled="isInFirstPage"
          aria-label="Go to first page"
          @click="onClickFirstPage"
        >
          <template slot="text">
            <img

              :class="`mt-3px ${windowLanguage === 'ar' ? 'flip-image':'' }`"
              width="16"
              src="../../img/left-chevron_double.svg"
              alt=""
            >
          </template>
        </Btn>
      </li>
      <li class="pagination-item">
        <Btn
          class="no-underline  block hover:text-black text-black hover:bg-grey-lighter bg-transparent
          text-green-dark border-none rounded  px-1 py-1 mr-2"
          type="button"
          :disabled="isInFirstPage"
          aria-label="Go to previous page"
          @click="onClickPreviousPage"
        >
          <template slot="text">
            <img
              :class="`mt-3px ${windowLanguage === 'ar' ? 'flip-image':'' }`"
              width="16"
              src="../../img/left-chevron.svg"
              alt=""
            >
          </template>
        </Btn>
      </li>
      <li
        v-for="page in pages"
        :key="page.name"
        class="hidden lg:block pagination-item"
      >
        <Btn
          class="no-underline block  rounded  px-1 py-1 mr-2"
          type="button"
          :disabled="page.isDisabled"
          :btn-class="{ 'bg-blue-dark hover:bg-blue-dark text-white active border-none': isPageActive(page.name), 'hover:text-blue hover:bg-grey-lighter text-grey-dark border-none':!isPageActive(page.name) }"
          :aria-label="`Go to page number ${page.name}`"
          @click="onClickPage(page.name)"
        >
          <template slot="text">
            {{ page.name }}
          </template>
        </Btn>
      </li>
      <li class="pagination-item ml-auto md:ml-0">
        <Btn
          class="no-underline  block hover:text-black text-black hover:bg-grey-lighter bg-transparent
          text-green-dark border-none rounded  px-1 py-1 mr-"
          type="button"
          :disabled="isInLastPage"
          aria-label="Go to next page"
          @click="onClickNextPage"
        >
          <template slot="text">
            <img
              :class="`mt-3px ${windowLanguage === 'ar' ? 'flip-image':'' }`"
              width="16"
              src="../../img/right-chevron.svg"
              alt=""
            >
          </template>
        </Btn>
      </li>
      <li class="hidden xl:block pagination-item">
        <Btn
          class="no-underline block hover:text-blue hover:bg-grey-lighter bg-white
          border-none rounded  px-1 py-1 mr-2"
          type="button"
          :disabled="isInLastPage"
          aria-label="Go to last page"
          @click="onClickLastPage"
        >
          <template slot="text">
            <img
              :class="`mt-3px ${windowLanguage === 'ar' ? 'flip-image':'' }`"
              width="16"
              src="../../img/right-chevron_double.svg"
              alt=""
            >
          </template>
        </Btn>
      </li>

      <li
        v-if="perPageEnabled"
        class="  ml-auto sm:text-xs  lg:text-base md:flex md:items-center hidden md:block pagination-select"
      >
        <div class="mr-2">
          {{ 'irc.rows_per_page' | trans }}
        </div>
        <div>
          <CustomSelect
            :options="perPageOptions"
            track-by="value"
            placeholder="rows per page"
            :searchable="false"
            :value="perPage"
            @select="handlePerPage($event.value)"
          />
        </div>
      </li>
    </ul>
  </div>
</template>
<script>

  export default {
    name: 'Pagination',
    props: {
      perPageEnabled: {
        type: Boolean,
        default: true
      },
      maxVisibleButtons: {
        type: Number,
        required: false,
        default: 5
      },
      totalPages: {
        type: Number,
        required: true
      },
      total: {
        type: Number,
        required: true
      },
      perPage: {
        type: Number,
        required: true
      },
      currentPage: {
        type: Number,
        required: true
      },
    },
    data() {
      return {
        windowLanguage:document.documentElement.lang,
        perPageOptions: [
          {
            label: "15",
            value: 15
          },
          {
            label: "30",
            value: 30
          },
          {
            label: "50",
            value: 50
          }
        ]

      }
    },
    computed: {
      startPage() {
        if (this.currentPage === 1) {
          return 1;
        }
        if (this.currentPage === this.totalPages) {
          let total = this.totalPages - this.maxVisibleButtons + 1;
          return total < 1 ? 1 : total;
        }
        if ((this.totalPages - this.currentPage) < this.maxVisibleButtons) {
          const currentPage = (this.totalPages - this.maxVisibleButtons + 1);

          return currentPage <= 0 ? 1 : currentPage ;
        }

        return this.currentPage - 1;
      },
      endPage() {
        return Math.min(this.startPage + this.maxVisibleButtons - 1, this.totalPages);
      },
      pages() {
        const range = [];
        for (let i = this.startPage; i <= this.endPage; i += 1) {
          range.push({
            name: i,
            isDisabled: i === this.currentPage
          });
        }
        return range;
      },
      isInFirstPage() {
        return this.currentPage === 1;
      },
      isInLastPage() {
        return this.currentPage === this.totalPages;
      },
    },
    methods: {
      handlePerPage($event) {
        this.$emit('perPage', $event);

      },
      onClickFirstPage() {
        this.$emit('pagechanged', 1);
      },
      onClickPreviousPage() {
        this.$emit('pagechanged', this.currentPage - 1);
      },
      onClickPage(page) {
        this.$emit('pagechanged', page);
      },
      onClickNextPage() {
        this.$emit('pagechanged', this.currentPage + 1);
      },
      onClickLastPage() {
        this.$emit('pagechanged', this.totalPages);
      },
      isPageActive(page) {
        return this.currentPage === page;
      },
    }
  };
</script>
<style scoped>
</style>