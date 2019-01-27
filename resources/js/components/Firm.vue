<template>
  <div class="flex flex-wrap">
    <div class="w-full lg:w-1/3 lg:pr-2 pb-3">
      <Panel
        custom-class="min-h-full"
        :has-title="hasTitle"
        :title="firm.firm_name"
      >
        <div class="firm-id uppercase text-green text-left font-bold mt-4 mb-4 flex items-center">
          {{ 'irc.id' | trans }}
          <Clipboard
            :to-be-copied="firm.commcare_id"
          >
            <span
              slot="copyTemplate"
              class="truncate max-w-2xl pl-2"
            >
              {{ firm.commcare_id }}
            </span>
          </Clipboard>
        </div>
        <ul class="firm-info list-reset text-left pr-8 mb-4">
          <ListItem
            v-if="firm.sector"
            icon="icon-Storefront_x40_2xpng_2"
          >
            <span>
              {{ firm.sector }}
            </span>
          </ListItem>

          <ListItem
            v-if="firm.city || firm.district"
            icon="icon-Location_Pin_1_1"
          >
            <span>
              {{ 'irc.located_in' | trans }}
              <a
                v-if="firm.city"
                class="link text-blue-dark font-bold"
                :href="`${homeUrl}/firms?filters[city]=${firm.city_key}`"
              >
                {{ firm.city }}
              </a>

              <span
                v-if="firm.city && firm.district"
                class="mx-1 link text-blue-dark font-bold"
              >
                •
              </span>

              <a
                v-if="firm.district"
                class="link text-blue-dark font-bold"
                :href="`${homeUrl}/firms?filters[district]=${firm.district}`"
              >
                {{ firm.district }}
              </a>
            </span>
          </ListItem>

          <ListItem
            v-if="firm.contact_mobile"
            icon="icon-Phone_1_x40_2xpng_2"
          >
            <span>
              {{ firm.contact_mobile }}
            </span>
          </ListItem>

          <ListItem
            v-if="jobOpenings.length > 0"
            icon="icon-Briefcase_x40_2xpng_2"
          >
            <span>
              {{ 'irc.looking_for' | trans }} {{ jobOpenings[jobOpenings.length - 1].job_title }}
            </span>
          </ListItem>
        </ul>


        <div class="stared-note uppercase text-green text-left font-bold mt-6 -mb-2">
          {{ 'irc.starred_note' | trans }}
        </div>

        <Notebox
          v-if="starredNote"
          :body="starredNote.note"
          :date="starredNote.created_at_text"
          :author="starredNote.user.name"
          :type="starredNote.type"
          :show-star="false"
          :show-creator-details="false"
          custom-class="border-none pl-0"
        />

        <EmptyState
          v-else
          icon="icon-Stars_x40_2xpng_2 text-5xl mt-3 block"
          :message="'irc.no_starred_note' | trans "
          custom-class="mt-5 min-h-200 text-lg"
        />
      </Panel>
    </div>
    <div class="w-full lg:w-2/3 lg:px-2">
      <Panel
        :has-title="hasTitle"
        custom-class="min-h-full min-h-700 max-h-700 overflow-y-auto"
        :title="'irc.job_openings' | trans"
      >
        <ul class="list-reset flex border-0 custom-navs mb-4">
          <li class="flex-inline mr-2">
            <button
              :class="{active:viewType === 'current'}"
              class="nav-link border-0
                                 rounded-full py-2 px-4
                                 text-grey-dark text-sm font-semibold "
              @click="changeViewType('current')"
            >
              {{ 'irc.current' | trans }}
            </button>
          </li>
          <li class=" flex-inline mr-2">
            <button
              :class="{active:viewType === 'matches'}"
              class="nav-link border-0 py-2 px-4
                                text-grey-dark text-sm font-semibold"
              @click="changeViewType('matches')"
            >
              {{ 'irc.match_statuses.hired' | trans }}
            </button>
          </li>

          <li class=" flex-inline mr-2">
            <button
              :class="{active:viewType === 'notes'}"
              class="nav-link border-0 py-2 px-4
                                text-grey-dark text-sm font-semibold"
              @click="changeViewType('notes')"
            >
              {{ 'irc.notes' | trans }}
            </button>
          </li>
        </ul>
        <div class="tab-content">
          <div
            v-if="viewType === 'current'"
            id="current"
            class="tab-pane fade in active show"
          >
            <div
              v-if="jobOpenings.length > 0"
            >
              <JobOpening
                v-for="jobOpening in jobOpenings"
                :key="jobOpening.id"
                :can-see="canSeeMatches"
                :city="firm.city || firm.district"
                :job-opening="jobOpening"
              />
            </div>
            <PageLoader
              v-else-if="jobOpeningsLoading"
              :message="'irc.job_openings_loading' | trans"
            />
            <EmptyState
              v-else
              custom-class="min-h-200 text-lg"
            />
          </div>

          <div
            v-if="viewType === 'matches'"
            id="matches"
            class="tab-pane fade in"
          >
            <CaseListing
              key="matches"
              :end-point="matchedEndPoint"
              type="firm"
              :has-filters="false"
              :change-url="false"
            />
          </div>

          <div
            v-show="viewType === 'notes'"
            id="notes"
            class="tab-pane fade in"
          >
            <Btn
              :theme="'success'"
              :btn-class="'mb-2 uppercase absolute pin-t pin-r mt-4 mr-4'"
              @click="showAddModalNote = true"
            >
              <template slot="text">
                {{ 'irc.add_note' | trans }}
              </template>
            </Btn>

            <NotesList
              case-type="firm"
              :case-id="firm.id"
              :additional-note="addedNote"
              @starred="starredNote = $event"
              @fetched="starredNote = $event.starred"
            />
          </div>
        </div>
      </Panel>
    </div>

    <AddNoteModal
      :show-modal="showAddModalNote"
      @note-added="addNoteToList"
      @close="showAddModalNote = false;"
    />
  </div>
</template>


<script>
  import {get as getCaseListing} from '../API/caseListing'
  import {get as getNotes} from '../API/noteAPI'
  import {post as addNote} from '../API/noteAPI'
  import {setNoteStarred as starNote} from '../API/noteAPI'

  export default {
    props: {
      firm: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        notesLoading: true,
        showAddModalNote: false,
        viewType: 'current',
        hasTitle: true,
        showStar: false,
        filters: false,
        jobOpenings: [],
        matches: [],
        matchedEndPoint: '',
        jobOpeningsLoading: true,
        starredNote: null,
        addedNote: null,
        canSeeMatches: true
      }
    },
    computed: {
      homeUrl: function () {
        return window.homeUrl;
      }
    },
    mounted() {
      this.matchedEndPoint = `api/firms/${this.firm.id}/matches`;

      getCaseListing('job-opening', {
        filters: {
          firm_id: this.firm.id,
        },
        sorting: {
          column: 'date_opened',
          type: 'asc',
        },
        paginate: false,

      }).then(({data}) => {
        this.jobOpeningsLoading = false
        this.jobOpenings = data.data
        this.canSeeMatches = data.permissions.can_see
      });
    },
    methods: {
      addNoteToList(noteText, type) {
        addNote('firm', this.firm.id, {note: noteText, type: type})
            .then(resp => {
              this.addedNote = resp.data.note;
            })
            .catch(error => {
              this.$toasted.show(error.response.data.errors.note[0], {
                icon: 'icon-Error_x40_2xpng_2',
                className: 'toast-error'
              })
            });
      },
      changeViewType(type) {
        this.viewType = type;
      },
      btnClick() {

      }
    }
  }
</script>