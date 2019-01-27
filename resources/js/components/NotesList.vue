<template>
  <div>
    <EmptyState
      v-if="notes.length === 0 && !loading"
      icon="icon-Note_x40_2xpng_2 text-3xl mt-3 block"
      :message="'irc.no_notes_available' | trans"
      custom-class="mt-5 min-h-200 text-lg"
    />
    <PageLoader
      v-else-if="loading"
      :message="'irc.notes_loading' | trans"
    />
    <div v-else>
      <Notebox
        v-for="note in notes"
        :id="note.id"
        :key="note.id"
        :date="note.created_at_text"
        :type="note.type"
        :author="note.user.name"
        :body="note.note"
        :is-starred="note.is_starred"
        @noteStarred="changeStarredNote"
      />

      <div
        v-if="notes.length > 0"
        class=" text-xs my-3 pl-2"
      >
        {{ 'irc.viewing' | trans }} {{ notes.length }} {{ 'irc.out_of' | trans }} {{ pagination.total }}
      </div>

      <Pagination
        v-if="pagination.lastPage > 1"
        :total-pages="pagination.lastPage"
        :total="pagination.total"
        :per-page="pagination.perPage"
        :current-page="pagination.currentPage"
        :per-page-enabled="false"
        @pagechanged="fetchNotes({page:$event})"
      />
    </div>
  </div>
</template>

<script>
  import {get as getNotes} from '../API/noteAPI'
  import PaginationMixin from '../mixins/paginationMixin'
  import {setNoteStarred as starNote} from '../API/noteAPI'

  export default {
    name: "NotesList",
    mixins: [PaginationMixin],
    /**
     * all props have their needed types
     * and are passed using the mixin
     */
    props: {
      caseType: {
        type: String,
        required: true
      },
      caseId: {
        type: Number,
        required: true
      },
      additionalNote:{
        type:[Object],
        default: null
      }
    },
    data() {
      return {
        notes: [],
        loading: true,
        lastAdditionalNote: {}
      }
    },
    watch:{
      additionalNote(newValue){
        if(!newValue){
          return
        }

        if(this.lastAdditionalNote === newValue.id ){
          return
        }
        this.pagination.total +=1;
        this.notes.unshift(newValue)
      }
    },
    created() {
      this.fetchNotes();
    },
    methods: {
      fetchNotes(params = {}) {
        this.loading = true;
        getNotes(this.caseType, this.caseId, params)
          .then(({data}) => {

            this.notes = data.data;

            this.setPaginationFromMeta(data.meta)

            this.loading = false;

            this.$emit('fetched', data)
          })
      },
      changeStarredNote(note) {
        starNote(this.caseType, this.caseId, note.id)
          .then(({data}) => {

            this.$toasted.show(data.message, {
              icon: 'icon-Stars_x40_2xpng_2 mr-2'
            })

            this.notes.forEach(note => {
              note.is_starred = (data.note.id === note.id) && (data.note.is_starred);
            })

            this.$emit('starred', data.note.is_starred ? data.note : null)
          })
          .catch(error => {
            console.log('Error ', error)
          });
        ;
      }
    },
  }
</script>

<style scoped>

</style>