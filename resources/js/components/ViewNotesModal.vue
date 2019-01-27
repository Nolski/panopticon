<template>
  <!--
    -->
  <Modal
    v-if="showModal"
    custom-class="max-h-400 overflow-y-auto"
    @close="closeModal"
  >
    <div
      slot="header"
      class="relative w-full text-left mb-8"
    >
      <div class=" text-lg text-grey-darker">
        {{ 'irc.all_notes' | trans }}
      </div>
    </div>

    <div slot="body">
      <NotesList
        :case-type="caseType"
        :case-id="caseId"
      />
    </div>
  </Modal>
</template>


<script>
  export default {
    props: {
      showModal: {
        type: Boolean,
        default: false
      },
      caseType: {
        type: String,
        required: true
      },
      caseId: {
        type: Number,
        required: true
      },
    },
    methods:{
      closeModal() {
        this.$emit('close');
      },
    }
  }
</script>

<style lang="scss">

</style>