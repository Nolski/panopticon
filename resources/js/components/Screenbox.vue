<template>
  <!--
    -->
  <div :class="' notebox p-8 text-left  border  border-grey-light relative mb-3 '+' '+`${customClass}`">
    <div class=" text-grey-dark text-sm mb-1">
      {{ screen.created_at_text }}
    </div>

    <div class="mb-4 text-base text-black font-bold ">
      {{ screen.title }}
    </div>
    <div
      v-if="screen.note"
      class=" text-sm text-black font-semibold"
    >
      {{ screen.note }}
    </div>
  </div>
</template>


<script>

  export default {
    mixins: [],
    /**
     * all props have their needed types
     * and are passed using the mixin
     */
    props: {
      screen: {
        type: Object,
        default: () => {
          {
          }
        }
      },
      customClass: {
        type: String,
        default: ''
      }
    }
  }
</script>

<style lang="scss">

</style>