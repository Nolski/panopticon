<template>
  <!--

    -->
  <GoogleMap
    :options="options"
    :center="center"
    :markers="markers"
    :clusters-enabled="clustersEnabled"
  />
</template>


<script>
    import googleMap from "./googleMap"
    import mapMixin from "../mixins/MapMixin"

    export default {
        components: {
            googleMap
        },
        mixins: [mapMixin],
        /**
         *
         */
        props: {},
        methods: {
        }
    }
</script>

<style lang="scss">

</style>