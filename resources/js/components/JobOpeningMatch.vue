<template>
  <CaseListing
    :per-page="50"
    :end-point="route"
    type="job-seeker"
    :export-allowed="false"
    @fetch="onFetch"
  >
    <template slot="header">
      <div class="text-left mb-2">
        <div class="flex mb-2">
          <h1 class="flex-1">
            {{ jobOpening.job_title }}
          </h1>
          <Btn
            v-if="!isFetching"
            btn-class="text-xs mr-2"
            theme="success"
            :loading="loading"
            :disabled="loading"
            @click="saveMatches"
          >
            <span slot="text">
              {{ 'irc.save_matches' | trans }}
            </span>
          </Btn>

          <AnchorLink
            v-if="!isFetching && savedSelections.length > 0"
            btn-class="text-xs"
            theme="primary"
            :loading="loading"
            :disabled="loading"
            :href="savedMatchesUrl"
          >
            <span slot="text">
              {{ 'irc.saved_matches' | trans }}
            </span>
          </AnchorLink>
        </div>
        <div class="mb-4">
          <label class="text-green-theme font-bold text-xs uppercase mb-2">
            {{ 'irc.firm_name' | trans }}
          </label>
          <div class="text-black">
            {{ jobOpening.firm.firm_name }}
          </div>
        </div>

        <div class="mb-4">
          <label class="text-green-theme font-bold text-xs uppercase mb-2">
            {{ 'irc.job_description' | trans }}
          </label>
          <div class="text-black">
            {{ jobOpening.job_description }}
          </div>
        </div>
      </div>
    </template>
    <template
      slot="head-start-td"
    >
      Select
    </template>

    <template
      slot="start-td"
      slot-scope="{row}"
      class="pl-6"
    >
      <CheckboxField
        :checked="selections.indexOf(row.id) !== -1"
        label=""
        label-class="theme-radio-label -mt-6"
        @change="handleSelection(row.id)"
      />
    </template>
  </CaseListing>
</template>

<script>
  import axios from 'axios'

  export default {
    name: "JobOpeningMatch",
    props: {
      jobOpening: {
        type: Object,
        required: true
      },
      route: {
        type: String,
        required: true
      }
    },
    data() {
      return {
        selections: [],
        savedSelections:[],
        loading: false,
        isFetching : true
      }
    },
    computed:{
      savedMatchesUrl(){
        const currentLocation = window.location.pathname.replace(/^\/|\/$/g, '');
        return `/${currentLocation}/saved`
      }
    },
    methods: {
      onFetch(response) {
        this.selections = [...new Set([...this.selections, ...response.matches])]
        this.savedSelections = [...response.matches]
        this.isFetching = false;
      },
      handleSelection(id) {
        const index = this.selections.indexOf(id)

        if (index !== -1) {
          this.selections.splice(index, 1)
        } else {
          this.selections.push(id)
        }
      },
      saveMatches() {
        this.loading = true;
        let _self = this;
        axios.post(`/api/job-openings/${this.jobOpening.id}/matches`, {
          matches: this.selections
        }).then(({data}) => {

          this.$toasted.show(this.$options.filters.trans('irc.matches_saved'), {
            icon: 'icon-Floppy_Disk_1_1',
          })

          setTimeout(() => {
            window.location = this.savedMatchesUrl
          }, 700)
        })
      },
    }
  }
</script>

<style scoped>

</style>