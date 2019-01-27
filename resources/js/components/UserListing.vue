<template>
  <div>
    <CaseListing
      :end-point="usersEndPoint"
      :change-url="true"
      :export-allowed="false"
      :has-filters="true"
      type="firm"
    >
      <div
        slot="header"
        class="text-right mb-3"
      >
        <Btn
          theme="success"
          @click="addUser"
        >
          <div
            slot="text"
            class="flex items-center"
          >
            <i class="icon-Add_x40_2xpng_2 mr-2" />
            {{ 'irc.create_new_user' | trans }}
          </div>
        </Btn>
      </div>

      <template
        slot="end-td"
        slot-scope="{row,loadData: loadUsers}"
      >
        <button
          v-tooltip="{placement: 'top',content:$options.filters.trans('irc.view'),classes:['tooltip-datatable']}"
          class="flex-1 text-xl mr-1 text-green-dark"
          @click="viewAccount(row)"
        >
          <i class="icon-Eye_x40_2xpng_2" />
        </button>

        <button
          v-tooltip="{placement: 'top',content:$options.filters.trans('irc.edit'),classes:['tooltip-datatable']}"
          class="flex-1 text-xl mr-1 text-green-dark"
          @click="editAccount(row)"
        >
          <i class="icon-Pencil_x40_2xpng_2" />
        </button>

        <button
          v-if="row.status ==='activated'"
          v-tooltip="{placement: 'top',content:$options.filters.trans('irc.deactivate'),classes:['tooltip-datatable']}"
          class="flex-1 text-xl  text-green-dark"
          @click="deActivateUser(row, loadUsers)"
        >
          <i class="icon-Lock_x40_2xpng_2" />
        </button>

        <button
          v-else
          v-tooltip="{placement: 'top',content: $options.filters.trans('irc.activate') ,classes:['tooltip-datatable']}"
          class="flex-1 text-xl  text-green-dark"
          @click="reActivateUser(row, loadUsers)"
        >
          <i class="icon-Unlock_x40_2xpng_2" />
        </button>
      </template>
    </CaseListing>
  </div>
</template>

<script>

  import queryString from '../helpers/QueryString'
  import {get as getUsers} from '../API/userAPI'
  import {activateUser as activateUser} from '../API/userAPI'
  import {deactivateUser as deactivateUser} from '../API/userAPI'
  import paginationMixin from "../mixins/paginationMixin";

  export default {
    mixins: [paginationMixin],
    props: {},
    data() {
      return {
        usersEndPoint:'',
        loading: false,
      }
    },
    created() {
      this.usersEndPoint = `api/users`;
    },
    methods: {
      deActivateUser(user, loadData) {
        Vue.swal({
          title:  this.$options.filters.trans('irc.confirm_deactivate_user'),
          type: 'warning',
          allowEscapeKey: true,
          confirmButtonText: this.$options.filters.trans('irc.deactivate'),
          showCancelButton: true,
          cancelButtonText: this.$options.filters.trans('irc.cancel'),
        }).then(result => {
          if(result.value){
            deactivateUser(user.id)
                .then(resp => {
                  user.status = 'deactivated';

                  this.$toasted.show(resp.data.message, {
                    icon: 'icon-Lock_x40_2xpng_2'
                  });

                  if(typeof loadData === 'function'){
                    loadData();
                  }

                })
                .catch(error => {
                  console.log(' error !', error);
                });
          }
          // console.log(' res is ', result)
        });

      },
      reActivateUser(user, loadData) {
        Vue.swal({
          title:  this.$options.filters.trans('irc.confirm_activate_user'),
          type: 'warning',
          allowEscapeKey: true,
          confirmButtonText: this.$options.filters.trans('irc.activate'),
          showCancelButton: true,
          cancelButtonText: this.$options.filters.trans('irc.cancel'),
        }).then(result => {
          if(result.value){
            activateUser(user.id)
                .then(resp => {
                  user.status = 'activated';
                  this.$toasted.show(resp.data.message, {
                    icon: 'icon-Unlock_x40_2xpng_2'
                  })

                  if(typeof loadData === 'function'){
                    loadData();
                  }
                })
                .catch(error => {
                  console.log(' error !', error);
                });
          }
        });

      },
      editAccount(user) {
        window.location.href = `users/${user.id}/edit`;
      },
      viewAccount(user) {
        window.location.href = `users/${user.id}`;
      },
      addUser(){
        window.location.href = `users/create`;

      }


    },

  }
</script>