<template>
  <CaseListing
    :end-point="route"
    type="job-seeker"
  >
    <template slot="header">
      <div class="text-left mb-2">
        <div class="flex mb-2">
          <h1 class="flex-1">
            {{ jobOpening.job_title }}
          </h1>
          <AnchorLink
            v-if="canBack"
            btn-class="text-xs"
            theme="success"
            :loading="loading"
            :disabled="loading"
            :href="matchesUrl"
          >
            <span slot="text">
              {{ 'irc.back' | trans }}
            </span>
          </AnchorLink>
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
      },
      matchesUrl:{
        type:String,
        required: true
      },
      canBack:{
        type:Boolean,
        default: true
      }
    },
    data() {
      return {
        loading: false
      }
    },
    methods: {
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

          this.$toasted.show('Matches saved', {
            icon: 'icon-Floppy_Disk_1_1',
          })

          setTimeout(function () {
            const currentLocation = window.location.pathname.replace(/^\/|\/$/g, '');
            window.location = `/${currentLocation}/saved`
          }, 700)
        })

      }
    }
  }
</script>

<style scoped>

</style>