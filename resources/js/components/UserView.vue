<template>
  <div class="userData-container">
    <Panel
      custom-class=""
      :title="name"
    >
      <a
        v-if="viewOnly && !showEdit"
        :href="`${homeUrl}/users/${user.id}/edit`"
        class="m-3 absolute pin-r pin-t text-xs lg:text-sm rounded-full py-2 px-4 bg-white border no-underline
        border-grey-light hover:bg-grey-lightest text-grey-darkest"
      >
        <div slot="text">
          <i class="icon-Pencil_x40_2xpng_2 mr-2" />
          {{ 'irc.edit_user' | trans }}
        </div>
      </a>
      <div class="w-full flex-wrap flex mb-10">
        <div class="w-full sm:w-full lg:w-1/3 hd:w-1/5 pt-5 pl-5">
          <Avatar
            class="mx-auto sm:mx-auto"
            :src="profileImagePreview"
            :size="150"
            :username="userData && userData.name ? userData.name : 'New User'"
          />
          <Transition name="fade">
            <div
              v-if="showEdit"
              class="mx-auto sm:mx-auto w-150 mt-5 sm:mb-5 text-center"
            >
              <span
                class=" rounded-full cursor-pointer inline-block overflow-hidden rounded   border border-grey-light py-2 px-4 "
              >
                <div class="absolute pl-4 pt-1 flex items-center text-grey-darkest font-bold">
                  <i class=" icon-Photo_x40_2xpng_2 mr-2" />
                  {{ 'irc.upload' | trans }}
                </div>
                <input
                  ref="fileInput"
                  class="cursor-pointer w-full h-full opacity-0 "
                  accept="image/*"
                  type="file"
                  @change="handleFileChange"
                >
              </span>
            </div>
          </Transition>
        </div>
        <div class="sm:w-full lg:w-2/3 ">
          <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
              <label
                class="text-left block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                for="grid-first-name"
              >
                {{ 'irc.name' | trans }}
              </label>
              <input
                id="grid-first-name"
                v-model="userData.name"
                :disabled="!showEdit"
                :class="`appearance-none block w-full bg-grey-lighter text-grey-darker rounded py-3
               px-4 leading-tight focus:outline-none focus:bg-white ${!showEdit ? 'cursor-not-allowed':''}  ${!internalError['name'] ? 'focus:border-grey border border-grey-lighter focus:border-grey':'border border-red '}`"
                type="text"
                :placeholder="'irc.name' | trans"
              >
              <p
                v-if="internalError['name']"
                class="text-left text-red text-xs italic"
              >
                {{ internalError['name'][0] }}
              </p>
            </div>
            <div class="w-full md:w-1/2 px-3">
              <label
                class="text-left block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                for="email"
              >
                {{ 'irc.email' | trans }}
              </label>
              <input
                id="email"
                v-model="userData.email"
                :disabled="!showEdit"
                :class="`appearance-none block w-full bg-grey-lighter text-grey-darker rounded py-3
               px-4 leading-tight focus:outline-none focus:bg-white  ${!internalError['email']? 'focus:border-grey border border-grey-lighter focus:border-grey':'border border-red '}`"
                type="text"
                :placeholder="'irc.email' | trans"
              >

              <p
                v-if="internalError['email']"
                class="text-left text-red text-xs italic"
              >
                {{ internalError['email'][0] }}
              </p>
            </div>
          </div>
          <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3">
              <label
                class="text-left block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                for="password"
              >
                {{ 'irc.password' | trans }}
              </label>
              <input
                id="password"
                v-model="userData.password"
                :disabled="!showEdit"
                :class="`appearance-none block w-full bg-grey-lighter text-grey-darker rounded py-3
               px-4 leading-tight focus:outline-none focus:bg-white  ${!internalError['password']? 'focus:border-grey border border-grey-lighter focus:border-grey':'border border-red '}`"
                type="password"
                placeholder="******"
              >

              <p
                v-if="internalError['password']"
                class="text-left text-red text-xs italic"
              >
                {{ internalError['password'][0] }}
              </p>
            </div>


            <div class="w-full md:w-1/2 px-3">
              <label
                class="text-left block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                for="password_confirm"
              >
                {{ 'irc.confirm_password' | trans }}
              </label>
              <input
                id="password_confirm"
                v-model="userData.password_confirmation"
                :disabled="!showEdit"
                :class="`appearance-none block w-full bg-grey-lighter text-grey-darker rounded py-3
               px-4 leading-tight focus:outline-none focus:bg-white  ${!internalError['password']? 'focus:border-grey border border-grey-lighter focus:border-grey':'border border-red '}`"
                type="password"
                placeholder="******"
              >
              <p
                v-if="internalError['password']"
                class="text-left text-red text-xs italic"
              >
                {{ internalError['password'][0] }}
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full md:w-1/2 px-3">
          <label
            class="text-left block uppercase tracking-wide text-grey-darker text-md font-bold mb-4"
          >
            {{ 'irc.user_roles' | trans }}
          </label>

          <div class="mb-6">
            <CheckboxGroup
              v-if="availableRoles.length > 0"
              :disabled="viewOnly"
              :checkboxes="availableRoles"
              @change="onRoleSelection"
            />
            <div v-else>
              {{ 'irc.no_assigned_roles' | trans }}
            </div>
          </div>
        </div>
      </div>

      <Transition name="fade">
        <div
          v-if="showEdit"
          class="w-full text-right"
        >
          <Btn
            theme="default"
            @click="handleUserCreate"
          >
            <p slot="text">
              {{ create ? 'irc.create_user':'irc.update_user' | trans }}
            </p>
          </Btn>
        </div>
      </Transition>
    </Panel>
  </div>
</template>

<script>

  import Avatar from 'vue-avatar'
  import {update as updateUser, create as createUser} from '../API/userAPI'

  export default {
    components: {Avatar},
    props: {
      user: {
        type: Object,
        default: () => ({})
      },
      viewOnly: {
        type: Boolean,
        default: false
      },
      roles: {
        type: Array,
        default: () => []
      }
    },
    data() {
      return {
        internalError: [],
        showEdit: true,
        uploadedImage: null,
        uploadedProfileImagePreview: null,
        userData: {

        },
        name: '',
        checkboxes: [],
        availableRoles: [],
        selectedRoles: [],
        create: true,
        homeUrl: window.homeUrl
      }
    },
    computed: {
      formData() {
        const fd = new FormData;

        fd.append('name', this.userData.name)
        fd.append('email', this.userData.email)

        if (this.userData.password) {
          fd.append('password', this.userData.password)
        }

        if (this.userData.password_confirmation) {
          fd.append('password_confirmation', this.userData.password_confirmation)
        }

        this.selectedRoles.forEach(role => {
          fd.append('roles[]', role)
        })

        if (this.uploadedImage) {
          fd.append('profile_picture', this.uploadedImage)
        }
        return fd
      },

      profileImagePreview() {
        if (this.uploadedProfileImagePreview) {
          return this.uploadedProfileImagePreview
        }

        return this.userData.profile_picture
      }
    },
    beforeMount() {
      if (this.viewOnly) {
        this.showEdit = false;
      }
    },
    mounted() {
      if (Object.keys( this.user).length !== 0 &&  this.user.constructor === Object) {
        this.userData = this.user;
        this.name = this.userData.name;
      } else {
        this.userData = {
          name:"",
          email:"",
          password:"",
          password_confirmation:""

        };
        this.uploadedImage = "";
        this.name = this.$options.filters.trans('irc.create_new_user');
      }
      this.create = Object.keys(this.user).length === 0 && this.user.constructor === Object;

      this.availableRoles = this.roles.map(role => ({
        ...role,
        label: role.name,
        value: role.id,
        checked: role.has_role
      }))

      this.selectedRoles = this.roles.filter(role => role.has_role === true).map(role => role.id)
    },
    methods: {
      handleUserCreate() {
        if (this.userData.id) {
          updateUser(this.userData.id, this.formData)
              .then(resp => {
                this.$toasted.show(resp.data.message, {
                  icon: 'icon-Checkmark_2_x40_2xpng_2'
                });
                this.name = this.userData.name;
                window.location.href = `${this.homeUrl}/users/${resp.data.user.id}`
              })
              .catch(error => {
                if (error.response.status === 422) {
                  this.internalError = error.response.data.errors
                } else {
                  this.$toasted.error("Something went wrong, cannot update userData.");
                }
              });
        }
        else {
          createUser(this.formData)
              .then(resp => {
                this.name = this.userData.name;
                this.internalError = [];
                this.$toasted.show(resp.data.message, {
                  icon: 'icon-Checkmark_2_x40_2xpng_2'
                });
                window.location.href = `${this.homeUrl}/users/${resp.data.user.id}`
              })
              .catch(error => {
                if (error.response.status === 422) {
                  this.internalError = error.response.data.errors
                } else {
                  this.$toasted.error("Something went wrong, cannot create userData.");
                }
              });
        }
      },

      enableFields() {
        this.showEdit = true;
      },

      openViewer() {
        this.$refs.fileInput.click();
      },

      handleFileChange(e) {

        this.uploadedImage = e.target.files[0];

        if (!this.uploadedImage) {
          this.uploadedProfileImagePreview = this.userData.profile_picture
          return
        }


        let reader = new FileReader();

        reader.addEventListener("load", () => {
          this.uploadedProfileImagePreview = reader.result;
        }, false);

        if (this.uploadedImage) {

          if (/\.(jpe?g|png|gif)$/i.test(this.uploadedImage.name)) {
            /*
              Fire the readAsDataURL method which will read the file in and
              upon completion fire a 'load' event which we will listen to and
              display the image in the preview.
            */
            reader.readAsDataURL(this.uploadedImage);
          }
        }

      },

      onRoleSelection(selection) {
        this.selectedRoles = selection.map(selection => selection.value)
      }
    },

  }
</script>