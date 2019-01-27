<template>
  <!--
    -->
  <div class="notebox py-5 px-8 text-left  border border-solid  border-grey-light relative mb-3 flex">
    <div class="flex-1">
      <div class="uppercase text-grey font-bold text-xs mb-2">
        Added {{ jobOpening.date_opened_text }}
      </div>
      <div class="text-black font-bold text-base ">
        Job {{ jobOpening.job_id }} - {{ jobOpening.job_title }}
      </div>
      <div class=" text-black font-bold text-sm mb-1">
        {{ city }}
      </div>
    </div>
    <a
      :href="jobOpening.saved_matches_url"
      class="no-underline flex-1 text-right text-green-dark font-bold"
    >
      {{ jobOpening.matches_number }} Matches
    </a>
  </div>
</template>


<script>

  export default {
    props: {
      jobOpening: {
        type: Object,
        required: true
      },
      city: {
        type: String,
        default: ""
      },
      canSee:{
        type:Boolean,
        default: true
      }
    }
  }
</script>

<style lang="scss">

</style>