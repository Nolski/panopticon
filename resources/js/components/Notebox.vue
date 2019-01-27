<template>
  <!--
    -->
  <div :class="'notebox p-8 text-left  border-solid  border-grey-light relative mb-3 '+' '+`${customClass}`">
    <button
      v-if="showStar"
      class="absolute pin-t pin-r text-xl hover:text-2xl p-2 font-bold text-green-dark"
      @click="noteStarClicked"
    >
      <Transition
        v-if="!isStarred"
        mode="out-in"
        name=""
      >
        <i :class="`icon-Star_x40_2xpng_2`" />
      </Transition>

      <Transition
        v-else
        mode="out-in"
        name="bounce"
      >
        <img
          width="20"
          src="../../../public/svg/star-filled.svg"
        >
      </Transition>
    </button>
    <div class=" text-left text-sm text-black font-bold">
      {{ body }}
    </div>
    <div
      v-if="type"
      class="note-type text-green text-xs mt-4"
    >
      {{ type }}
    </div>
    <div class="flex ">
      <div
        v-if="showCreatorDetails"
        class="flex-1"
      >
        <div class="text-black font-bold ">
          {{ 'irc.from' | trans }} {{ author }}
        </div>
        <div class="uppercase">
          {{ getTranslatedDate }}
        </div>
      </div>

      <div class="flex-1 text-right pt-4">
        <button
          class="uppercase flex items-center ml-auto text-green-dark text-sm font-bold ml-2"
          @click="showFullNoteModal"
        >
          {{ 'irc.view_more' | trans }}
          <i class=" align-text-bottom icon-Right_Arrow_1_1 text-xl ml-2" />
        </button>
      </div>
    </div>

    <Modal
      v-if="showFullNote"
      width="30%"
      @close="showFullNote = false"
    >
      <div
        slot="header"
        class="mb-6"
      >
        <div class="text-xl mb-1 text-black font-bold">
          {{ 'irc.note_from' | trans }} {{ author }}
        </div>
        <div class="text-grey text-sm mb-1">
          {{ getTranslatedDate }}
        </div>
        <button
          v-if="showStar"
          class="absolute pin-t pin-r text-xl hover:text-2xl px-3 py-4 font-bold text-green-dark"
          @click="noteStarClicked"
        >
          <Transition
            v-if="!isStarred"
            mode="out-in"
            name=""
          >
            <i :class="`icon-Star_x40_2xpng_2`" />
          </Transition>

          <Transition
            v-else
            mode="out-in"
            name="bounce"
          >
            <img
              width="20"
              src="../../../public/svg/star-filled.svg"
            >
          </Transition>
        </button>
      </div>

      <div
        slot="body"
        class=""
      >
        <div class="text-black font-bold text-base">
          {{ body }}
        </div>
      </div>
    </Modal>
  </div>
</template>


<script>
  import moment from 'moment'

  export default {
    /**
     * all props have their needed types
     * and are passed using the mixin
     */
    mixins: [],
    props: {
      isStarred: {
        type: Boolean,
        default: false
      },
      showModal: {
        type: Boolean,
        default: false
      },
      id: {
        type: Number,
        default: 0
      },
      date: {
        type: String,
        default: 'Wednesday 12 November'
      },
      author: {
        type: String,
        default: 'Mohammad Karmi'
      },
      type: {
        type: String,
        default: null
      },
      body: {
        type: String,
        default: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s\n' +
        '            standard dummy text ever since the 1500s, when an unknown printer\n' +
        '            took a galley of type and scrambled it to make a type specimen book.\n' +
        '            It has survived not only five centuries, but also the leap into electronic\n' +
        '            typesetting, remaining essentially unchanged'
      },
      showStar: {
        type: [Boolean, String],
        default: true
      },
      showCreatorDetails: {
        type: [Boolean, String],
        default: true
      },
      customClass: {
        type: [String],
        default: 'border'
      }
    },
    data() {
      return {
        showFullNote: false,
        locale: document.documentElement.lang === 'ar' ? 'ar' : 'en'
      }
    },
    computed: {
      getTranslatedDate() {
        if (this.date) {
          return moment(this.date).locale(this.locale).format('dddd DD MMMM')
        }

        return null
      }
    },
    methods: {
      noteStarClicked() {
        this.$emit('noteStarred', {
          id: this.id,
          body: this.body,
          date: this.date,
          author: this.author
        })
      },
      showFullNoteModal() {
        this.showFullNote = true;
      }
    },
  }
</script>

<style lang="scss">

</style>