<template>
  <!--
    -->
  <div class="">
    <Panel custom-class="relative px-3 py-4 min-h-115">
      <div class="absolute flex items-center justify-center w-50 h-50 flex p-2 pin-t pin-r rounded-tr-lg bg-green-opac">
        <i :class="iconClasses" />
      </div>
      <div
        class="count-number
                        pr-16 text-black
                        w-1/3 border-green-dark
                        text-left border-solid
                        font-bold border-b-4
                        text-xl"
      >
        {{ getValue }}
      </div>

      <div
        class="text text-black
                        text-left font-bold
                        text-xs mt-2 uppercase"
      >
        {{ label }}
      </div>
    </Panel>
  </div>
</template>


<script>
  import classNames from "classnames"

  export default {
    props: {
      iconClass: {
        type: String,
        default: ""
      },
      value: {
        type: [String, Number],
        default: 0
      },
      label: {
        type: String,
        default: ""
      }
    },
    /**
     * all props have their needed types
     * and are passed using the mixin
     */
    data() {
      return {
        lang: document.documentElement.lang === 'ar' ? 'ar' : 'en'
      }
    },
    computed: {
      getValue() {
        if (this.value) {
          if (this.lang === 'ar') {
            return (this.value).toLocaleString('ar-EG')
          }
          else {
            return this.value;
          }
        }
        if (this.lang === 'ar') {
          return "٠"
        }
        return 0;
      },
      iconClasses() {
        return classNames([this.iconClass, 'text-xl',
          'text-green-dark']);
      }
    }
  }
</script>

<style lang="scss">

</style>