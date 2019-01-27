<template>
  <!--
    -->
  <div :class="panelStyle">
    <div
      v-if="title"
      class="text-black text-left font-bold text-lg mb-4 flex"
    >
      <span class="flex-1">
        {{ title }}
      </span>

      <slot name="tools" />
    </div>
    <slot />
  </div>
</template>


<script>

  import panelStyle from '../mixins/PanelStyle'

  export default {
    name: "Panel",
    mixins: [panelStyle],
    /**
     * all props have their needed types
     * and are passed using the mixin
     */
    props: {
      hasTitle: {
        type: [Boolean, String],
        default: false
      },
      title: {
        type: String,
        default: null
      }
    }
  }
</script>

<style lang="scss">

</style>