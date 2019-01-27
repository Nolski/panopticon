<template>
  <div class="">
    <div
      v-if="rows.length > 0"
      class="table-container-main overflow-auto pl-2 mb-2"
    >
      <table
        :class="[`main-table w-full text-left`,{'table-striped' : striped,'scrollable-fixed-header' : fixedHeader} ,'mb-8']"
      >
        <thead>
          <tr class="font-bold text-green-theme">
            <slot name="head-start-td" />
            <th
              v-for="head in header"
              :key="head.name"
              class="pl-6 text-xs  whitespace-no-wrap relative cursor-pointer"
              @click="$emit('sort',head.name)"
            >
              <span>
                {{ head.label }}
              </span>

              <i
                :class="`icon-Down_Arrow_4_1 absolute pin-l pin-t ml-2
                ${sorting.column != head.name ? 'hidden':''}
                ${sorting.column === head.name && sorting.type === 'asc' ? 'icon-Up_Arrow_4_1' :'icon-Down_Arrow_4_1'}`"
              />
            </th>
            <th class="px-4 pl-1 max-w-150 " />
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="row in rows"
            :key="row.id"
            class="font-bold text-black  border-grey-light border-b-2"
          >
            <slot
              :row="row"
              name="start-td"
            />
            <td
              v-for="head in header"
              :key="head.name"
              class="py-4 pl-6 text-sm "
            >
              <Component
                :is="row[head.name].component"
                v-if="row[head.name]&& row[head.name].component"
                :icon-class="row[head.name].component.iconClass"
                :text="row[head.name].component.text"
              />
              <span
                v-else-if="permissions.can_see !== false && head.clickable_from && row[head.clickable_from]"
                class="py-1 whitespace-no-wrap  block"
                dir="auto"
              >
                <a
                  :href="row[head.clickable_from]"
                  class="text-blue-dark no-underline hover:underline "
                >
                  {{ getRowValue(row,head) }}
                </a>
              </span>
              <span
                v-else-if="row[head.name] && row[head.name].length > 10"
                v-tooltip="{placement: 'left',content:row[head.name],classes:['tooltip-datatable']}"
                class="whitespace-no-wrap  block"
                dir="auto"
              >
                {{ getRowValue(row,head) }}
              </span>
              <span
                v-else
                dir="auto"
                class="whitespace-no-wrap"
              >
                {{ getRowValue(row,head) }}
              </span>
            </td>

            <slot
              :row="row"
              name="end-td"
            />

            <slot
              :row="row"
              name="extra"
            />
          </tr>
        </tbody>
      </table>
    </div>

    <EmptyState
      v-else
      custom-class="min-h-200 text-lg mb-4"
    />
    <div
      v-if="rows.length > 0"
      class=" text-xs my-3 pl-2"
    >
      {{ 'irc.viewing' | trans }} {{ rows.length }} {{ 'irc.out_of' | trans }} {{ pagination.total }}
    </div>
    <Pagination
      v-if="pagination.lastPage > 1"
      :total-pages="pagination.lastPage"
      :total="pagination.total"
      :per-page="pagination.perPage"
      :current-page="pagination.currentPage"
      :per-page-enabled="perPageEnabled"
      @pagechanged="$emit('pagechanged', $event)"
      @perPage="$emit('perPage', $event)"
    />
  </div>
</template>


<script>

  export default {
    /**
     * all props have their needed types
     * and are passed using the mixin
     */
    props: {
      perPageEnabled:{
        type:Boolean,
        default: true
      },
      loading: {
        type: Boolean,
        default: false
      },
      fixedHeader: {
        type: Boolean,
        default: false
      },
      pagination: {
        type: Object,
        default: () => ({
          lastPage: 1,
          perPage: 15,
          total: 0,
          currentPage: 1
        })
      },
      sorting: {
        type: Object,
        default: () => ({})
      },
      striped: {
        type: Boolean,
        default: true
      },
      header: {
        type: Array,
        default: () => []
      },
      rows: {
        type: Array,
        default: () => []
      },
      permissions : {
        type: Object,
        default:() => ({})
      }
    },
    methods:{
      getRowValue(row,{valueHandler,name}){
        if(valueHandler && typeof valueHandler === "function"){
          return valueHandler(row)
        }
        return row[name];
      }
    }
  }
</script>

<style lang="scss">

</style>