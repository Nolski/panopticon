<template>
  <div
    v-if="!isAdmin"
    class="flex flex-wrap"
  >
    <div class="sm:w-full xl:w-2/3">
      <div class="flex flex-wrap">
        <div
          v-for="card in cards"
          :key="card.label + card.value"
          class="lg:pr-2 w-full sm:w-full lg:w-1/3 qhd:w-1/3"
        >
          <Component
            :is="card.component"
            :icon-class="card.icon || 'icon-Add_x40_2xpng_2'"
            :value="parseInt(card.value)"
            :label="card.label"
          />
        </div>
        <div class=" flex-grow w-full lg:pr-2">
          <Panel
            custom-class="min-h-784 pt-8 overflow-y-auto"
            :title="`irc.followups` | trans"
          >
            <ul class=" list-reset border-0 custom-navs mb-4 absolute pin-r pin-t mt-4 mr-4">
              <li
                class="inline-flex"
                @click="changeViewType('calendar')"
              >
                <button
                  data-toggle="tab"
                  :class="`nav-link border-0
                        flex items-center
                        rounded-full p-3 w-40 h-40
                        text-grey-dark text-xl font-semibold ${viewType == 'calendar' ? 'active':''}`"
                >
                  <i class="icon-Calendar_2_x40_2xpng_2" />
                </button>
              </li>
              <li
                class="inline-flex"
                @click="changeViewType('table')"
              >
                <button
                  data-toggle="tab"
                  :class="`nav-link border-0
                        flex items-center
                        rounded-full p-3 w-40 h-40
                        text-grey-dark text-xl font-semibold ${viewType != 'calendar' ? 'active':''}`"
                >
                  <i
                    class="icon-List_3_x40_2xpng_2"
                  />
                </button>
              </li>
            </ul>

            <div class="tab-content">
              <div
                v-show="viewType =='calendar'"
                id="calendarTab"
                class="tab-pane fade in active show "
              >
                <div class="calendar xl:pr-24">
                  <FullCalendar
                    ref="fullCalendar"
                    :events="events"
                    :config="calConfig"
                    @day-click="dayClicked"
                  />
                </div>

                <div class="selected-day mt-4">
                  <EmptyState
                    v-if="!daySelected && !loading"
                    icon="icon-Calendar_1_x40_2xpng_2 text-5xl mt-3 block"
                    :message="'irc.no_date_selected' | trans"
                    custom-class="mt-5 min-h-300 text-lg"
                  />
                  <PageLoader
                    v-else-if="loading"
                    :message="'irc.events_are_being_loaded' | trans"
                  />

                  <div v-else>
                    <Panel
                      :title="selectedDateHuman"
                      bg="light"
                      custom-class="border-transparent border-0"
                    >
                      <template
                        v-if="followups.length > 0"
                        slot="tools"
                      >
                        <button
                          class="bg-white text-base hover:bg-blue text-blue-dark font-semibold hover:text-white py-2 px-3 border border-blue hover:border-transparent rounded"
                          @click="exportData"
                        >
                          {{ 'irc.export' | trans }}
                        </button>
                      </template>

                      <Datatable
                        :header="tableHeaders"
                        :rows="followups"
                        :pagination="pagination"
                        :fixed-header="true"
                        :has-pagination="true"
                        :has-filters="false"
                        :striped="false"
                        :per-page-enabled="false"
                        @pagechanged="getFollowups(selectedDate,$event)"
                      />
                    </Panel>
                  </div>
                </div>
              </div>

              <div
                v-show="viewType !='calendar'"
                id="list"
                class="tab-pane fade in max-h-600"
              >
                <Datatable
                  :header="tableHeaders"
                  :rows="followups"
                  :pagination="pagination"
                  :has-pagination="true"
                  :has-filters="false"
                  :per-page-enabled="false"
                  @pagechanged="getFollowups(null,$event)"
                />
              </div>
            </div>
          </Panel>
        </div>
      </div>
    </div>
    <div class="w-full sm:w-full  pb-3 xl:w-1/3">
      <Panel
        :title="`irc.recent_activity` | trans"
        custom-class=" pl-6 xl:pr-2  min-h-full max-h-800 overflow-y-auto "
      >
        <div class="days-container overflow-y-auto">
          <div
            v-for="day in recent"
            :key="day.name"
            class=""
          >
            <div class="text-left text-green-dark font-bold text-xs mt-8 -mb-1">
              {{ lang === 'ar' ? getArabicDateRecent(day.name) : day.name }}
            </div>
            <ul class="list-reset">
              <template
                v-for="activity in day.items"
              >
                <li
                  v-if="activity.entity && activity.entity.id "
                  :key="activity.id"
                  class="relative text-left text-black border-grey-lighter border-solid
                              border-b-2 font-semibold text-md  py-5 flex items-center "
                >
                  <Component
                    :is="activity.component.name"
                    v-if="activity.component"
                    :icon-class="activity.component.value+' text-grey-darker text-1xl mr-2'"
                  />
                  <a
                    class="no-underline text-black"
                    :href="activity.entity.details_url"
                  >
                    <span class="text-sm">
                      {{ activity.title }} •  {{ activity.entity.name }}
                    </span>
                  </a>
                </li>
              </template>
            </ul>
          </div>
        </div>
      </Panel>
    </div>
  </div>

  <div v-else>
    <EmptyState
      icon=" icon-Grid_10_1 text-5xl mt-3 block"
      :message="'irc.admin_dashboard' | trans"
      custom-class="mt-5 min-h-500 text-lg border border-grey-light rounded"
    />
  </div>
</template>


<script>
  import moment from 'moment';
  import {FullCalendar} from 'vue-full-calendar'
  import {upcomingFollowups as getFollowups} from '../API/followupAPI'
  import {upcomingFollowupsCount as getCounts} from '../API/followupAPI'
  import {exportData as exportDataByUrl} from '../API/followupAPI'
  import {get as getRecentActivity} from '../API/recentActivityAPI'
  import {get as getCards} from '../API/cardsAPI'
  import exportDataHelper from '../helpers/ExportData'

  export default {
    components: {
      FullCalendar
    },
    filters: {},
    props: {},
    data() {
      return {
        selectedDateHuman: "",
        endPoint: "",
        cards: [],
        userRoles: '',
        isAdmin: null,
        tableHeaders: [
          {
            name: "type",
            label: this.$options.filters.trans('irc.type')

          },
          {
            name: "followup",
            label: this.$options.filters.trans('irc.name'),
            valueHandler: (row) => row.followup.name

          },
          {
            name: "due_date",
            label: this.$options.filters.trans('irc.due_date')

          },
          {
            name: "background",
            label: this.$options.filters.trans('irc.background'),
            valueHandler: (row) => Object.values(row.followup.background).join(', ')

          }
        ],
        loading: false,
        viewType: 'calendar',
        events: [],
        followups: [],
        recent: [],
        selectedDate: '',
        daySelected: false,
        hasTitle: true,
        calConfig: {
          editable: false,
          self: this,
          eventLimitText: "",
          views: {
            month: {
              eventLimit: 1// adjust to 6 only for agendaWeek/agendaDay
            }
          },
          eventLimit: true,
          fixedWeekCount: false,
          aspectRatio: 1.3,
          contentHeight: 250,
          defaultView: 'month',
          header: {
            left: 'title',
            center: '',
            right: 'prev,next'
          },
          locale: document.documentElement.lang === 'ar' ? 'ar' : 'en',
          isRTL: document.documentElement.lang === 'ar',
          themeSystem: 'bootstrap4',
          themeButtonIcons: {
            prev: 'left-single-arrow',
            next: 'fa-arrow-right',
          },
          eventRender: function (event, element) {

          },
          viewRender: function (view, el) {
            this.getCounts(
                moment(view.calendar.currentDate).locale('en').format("YYYY-MM")
            )

          }.bind(this)
        },
        page: 1,
        lang: document.documentElement.lang === 'ar' ? 'ar' : 'en',
        pagination: {
          total: 0,
          lastPage: 3,
          perPage: 15,
          currentPage: 1
        },

      }
    },
    computed: {},
    watch: {},
    created() {
      this.userRoles = window.userRoles.map(role => role.id);
      this.isAdmin = this.userRoles.indexOf(1) > -1;
    },
    mounted() {
      this.getRecentActivity();
      getCards()
          .then(resp => {
            this.cards = resp.data;
          })
          .catch(error => {
          });

      this.dayClicked(moment())
    },
    methods: {
      getArabicDateRecent(date){
        return moment(date).locale('ar').format("dddd DD MMMM")
      },
      getRecentActivity() {
        getRecentActivity()
            .then(resp => {
              this.recent = resp.data.data;
            })
            .catch(error => {
            });
      },
      getCounts(date) {
        this.loading = true;
        this.events = [];
        getCounts(date)
            .then(resp => {
              this.counts = resp.data.data;
              this.counts.forEach(day => {
                console.log('kkk ')
                // console.log('kkk ',convertNumberToArabic(toString(day.followup_count)))
                this.events.push({
                  start: day.followup_date,
                  title: this.lang === 'ar' ? (day.followup_count).toLocaleString('ar-EG') : day.followup_count,
                  className: moment().diff(moment(day.followup_date), 'days') > 0 ? 'past' : 'future',
                })
              });
              this.loading = false;
            })
            .catch(error => {
              console.log('error ggg');
            });
      },
      changeViewType(type) {
        this.viewType = type;
        if (type === 'table') {
          this.getFollowups(null, this.pagination.currentPage);
        }
        else {
          this.dayClicked(moment())

        }
      },
      dayClicked: function (date, jsEvent, view) {
        this.daySelected = true;
        let selectedString = moment(date, "DD MMMM");
        this.selectedDateHuman = this.calConfig.isRTL ? selectedString.locale('ar').format("DD MMMM") : selectedString.format("DD MMMM");
        this.selectedDate = selectedString.locale('en').format("YYYY-MM-DD");
        this.loading = true;
        this.getFollowups(this.selectedDate, this.pagination.page);
      },
      getFollowups(date, page) {
        this.loading = true;
        getFollowups(date, page)
            .then(resp => {
              this.followups = resp.data.data;
              this.pagination = {
                total: resp.data.meta.total,
                lastPage: resp.data.meta.last_page,
                perPage: resp.data.meta.per_page,
                currentPage: resp.data.meta.current_page
              }
              this.loading = false;

            })
            .catch(error => {
            });
      },
      dayUnselected(view, el) {
        if (!view.target.className || !(view.target.className === 'fc-day-number')) {
          this.daySelected = false;
        }


      },
      exportData() {

        let selectedDate = this.selectedDate;
        if (this.viewType == "table") {
          selectedDate = null;
        }

        exportDataByUrl(selectedDate, this.pagination.page, {export: true})
            .then(response => {
              exportDataHelper.exportCallback(response);
            });
      }

    }
  }
</script>