<template>
  <div>
    <Panel
      custom-class=""
    >
      <slot name="header" />

      <div class="flex justify-end">
        <Btn
          v-if="exportAllowed && !loading"
          btn-class="bg-transparent pr-2 hover:bg-grey-lightest flex items-center text-xs lg:text-sm text-blue-dark font-semibold
          py-2 px-2 border rounded-full border-blue rounded mb-3"
          :loading="exportLoading"
          :disabled="exportLoading"
          @click="exportData"
        >
          <span
            slot="extra-button"
            class="mr-2"
          >
            <img
              width="15"
              src="../../img/excel_icon.svg"
              alt=""
            >
          </span>
          <span slot="text">
            {{ 'irc.export' | trans }}
          </span>
        </Btn>
      </div>


      <Filters
        v-if="hasFilters && filters.length > 0"
        :filters="filters"
        :user-filters="userFilters"
        @change="filterChange($event, loadData)"
        @filterSelect="filterSelect($event, loadData)"
      />
      <Datatable
        v-if="!loading"
        :header="headers"
        :case-type="type"
        :rows="rows"
        :pagination="pagination"
        :sorting="sorting"
        :permissions="permissions"
        @pagechanged="loadData({page: $event})"
        @perPage="loadData({perPage: $event})"
        @sort="handleSort($event, loadData)"
      >
        <template
          v-if="$scopedSlots['start-td']"
          slot="head-start-td"
        >
          <td class="pb-2 text-xs max-w-150 relative cursor-pointer">
            <slot
              name="head-start-td"
            />
          </td>
        </template>

        <template
          v-if="$scopedSlots['start-td']"
          slot="start-td"
          slot-scope="{row}"
        >
          <td class="pl-2">
            <slot
              :row="row"
              name="start-td"
            />
          </td>
        </template>

        <template
          v-if="$scopedSlots['end-td']"
          slot="end-td"
          slot-scope="{row}"
        >
          <td>
            <slot
              :row="row"
              :load-data="loadData"
              name="end-td"
            />
          </td>
        </template>

        <td
          slot="extra"
          slot-scope="{row}"
          class="mr-2 pr-2"
        >
          <button
            v-if="permissions.notes === true"
            v-tooltip="{placement: 'left',content:$options.filters.trans('irc.view_notes'),classes:['tooltip-datatable']}"
            class="flex-1 text-xl  text-green-dark"
            @click="viewNotes(row.id)"
          >
            <i class="icon-Page_1_x40_2xpng_2" />
          </button>
        </td>
      </Datatable>

      <PageLoader v-else />
    </Panel>

    <ViewNoteModal
      v-if="caseId"
      :show-modal="showNotesModal"
      :case-type="type"
      :case-id="caseId"
      @close="closeModalNote"
    />
  </div>
</template>

<script>
  import {
    get as getListing,
    getByUrl as getListingByUrl,
    exportData as exportDataByType,
    exportDataByUrl
  } from '../API/caseListing'
  import FiltersProvider from "../mixins/FiltersProvider";
  import queryString from '../helpers/QueryString'
  import sortingProvider from "../mixins/sortingProvider";
  import exportDataHelper from '../helpers/ExportData'
  import paginationMixin from "../mixins/paginationMixin";

  export default {
    mixins: [FiltersProvider, sortingProvider, paginationMixin],
    props: {
      type: {
        type: String,
        required: true
      },
      endPoint: {
        type: String,
        default: ""
      },
      exportAllowed: {
        type: Boolean,
        default: true
      },
      changeUrl: {
        type: Boolean,
        default: true
      },
      hasFilters: {
        type: Boolean,
        default: true
      },
      perPage: {
        type: Number,
        default: 0
      }
    },
    data() {
      return {
        caseId: 0,
        showNotesModal: false,
        loading: false,
        rows: [],
        headers: [],
        permissions: {},
        exportLoading: false,
        perPageData: 0,
      }
    },
    mounted() {
      const queryStringObject = queryString.parse();
      this.perPageData = this.perPage;
      this.loadData({
        page: queryStringObject.page,
        filters: queryStringObject.filters,
        sorting: queryStringObject.sorting,
        perPage: queryStringObject.perPage,
        export: queryStringObject.export
      });
    },
    methods: {
      loadData({filters = {}, page = null, sorting = {}, perPage = this.perPageData} = {}) {
        filters = filters && typeof filters === "object" ? filters : {}
        sorting = sorting && typeof sorting === "object" ? sorting : {}
        const params = {
          filters: {
            ...filters,
            ...this.userFiltersToParams()
          },
          page: !isNaN(parseInt(page, 10)) ? page : this.pagination.currentPage,
          perPage: !isNaN(parseInt(perPage, 15)) && perPage != 0 ? perPage : this.pagination.perPage,
          sorting: {
            ...this.sorting,
            ...sorting,
          }
        };
        this.loading = true
        let apiResponse = this.apiRequest(params);

        return apiResponse.then(({data}) => {

          if (this.changeUrl) {
            this.changeUrlUsingParams(params);
          }
          this.rows = data.data;

          this.$emit('fetch', data)

          this.headers = data.headers;
          this.filters = data.filters;
          this.sorting = data.sorting;

          if (this.userFilters.length === 0 && this.hasFilters) {
            this.userFilters = this.initialUserFilters(data.filters, filters);
          }

          this.pagination = {
            total: data.meta.total,
            lastPage: data.meta.last_page,
            perPage: parseInt(data.meta.per_page),
            currentPage: data.meta.current_page
          };

          if (this.pagination.perPage != this.perPageData) {
            this.perPageData = this.pagination.perPage;
          }
          this.permissions = data.permissions || {}
          this.loading = false;
        }).catch(error => {
          console.log('Error : ', error);
        });
      },
      changeUrlUsingParams(params) {

        let serializedQueryString = queryString.serialize(params);

        serializedQueryString = serializedQueryString !== '' ? '?' + serializedQueryString : '';

        const url = window.location.protocol + "//" + window.location.host + window.location.pathname + serializedQueryString;

        history.pushState({}, document.title, url);
      },
      exportData() {
        this.exportLoading = true;
        this.exportRequest({
          filters: {
            ...this.userFiltersToParams(),
          },
          export: true,
          paginate: "false"
        }).then(resp => {
          this.exportLoading = false;
          exportDataHelper.exportCallback(resp)
        })
      },
      viewNotes(caseId) {
        this.showNotesModal = true;
        this.caseId = caseId;
      },
      closeModalNote() {
        this.showNotesModal = false;

      },
      apiRequest(params = {}) {
        if (this.endPoint.trim() !== "") {
          return getListingByUrl(this.endPoint, params)
        } else {
          return getListing(this.type, params)
        }
      },
      exportRequest(params = {}) {
        if (this.endPoint.trim() !== "") {
          return exportDataByUrl(this.endPoint, params)
        } else {
          return exportDataByType(this.type, params)
        }
      }
    }
  }
</script>