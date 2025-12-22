<template>
  <div>
    <!-- Overlay -->
    <div
      v-if="isOpen"
      class="fixed inset-0 bg-black bg-opacity-40 z-40"
      @click="closeSidebar"
    ></div>

    <!-- Sidebar -->
    <div
      class="fixed top-0 right-0 w-full sm:w-[80%] lg:w-[40%] h-full bg-white shadow-xl z-50 transform transition-transform duration-300"
      :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
    >

      <!-- Header -->
      <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">{{title}} User</h2>

        <button
          class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-full"
          @click="closeSidebar"
        >
          ✕
        </button>
      </div>

      <!-- Content / Form -->
      <div class="p-4 overflow-y-auto h-[calc(100%-120px)]">
        <form @submit.prevent="submitForm">
            <div class="mb-4">
            <label class="block font-medium">User Name</label>
            <input
              type="text"
              v-model="username"
              @blur="touched.username = true"
              class="w-full mt-1 rounded p-2 border"
              placeholder="User name"
            />
            <p v-if="touched.username && errors.username" class="text-red-500 text-sm mt-1">
              {{ errors.username }}
            </p>
          </div>

          <div class="mb-4">
            <label class="block font-medium">Email</label>
            <input
              type="email"
              v-model="email"
              @blur="touched.email = true"
              class="w-full mt-1 rounded p-2 border"
              placeholder="example@mail.com"
            />
            <p v-if="touched.email && errors.email" class="text-red-500 text-sm mt-1">
              {{ errors.email }}
            </p>
          </div>

          <div class="mb-4">
            <label class="block font-medium">Password</label>
            <input
              type="password"
              v-model="pass"
              @blur="touched.pass = true"
              class="w-full mt-1 rounded p-2 border"
              placeholder="Password"
            />
            <p v-if="touched.pass && errors.pass" class="text-red-500 text-sm mt-1">
              {{ errors.pass }}
            </p>
          </div>

          <div class="mb-4">
            <label class="block font-medium">Confirm Password</label>
            <input
              type="password"
              v-model="cPass"
              @blur="touched.cPass = true"
              class="w-full mt-1 rounded p-2 border"
              placeholder="Confirm password"
            />
            <p v-if="touched.cPass && errors.cPass" class="text-red-500 text-sm mt-1">
              {{ errors.cPass }}
            </p>
          </div>

        </form>

      </div>

      <!-- Footer -->
      <div class="pt-2 pl-6 pr-6 border-t flex justify-between">
        <button
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
          @click="resetForm"
        >
          Reset
        </button>

        <button
          type="submit"
          :disabled="!isFormValid"
          class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-500 disabled:opacity-50"
          @click="submitForm"
        >
          {{ submitText }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { icon } from '@fortawesome/fontawesome-svg-core';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
  props: {
    isOpen: Boolean,
    action: String,
    data: Object
  },
  emits: ["close", "submit"],
  data() {
    return {
      // isOpen: false,
      id:null,
      username: "",
      email: "",
      pass: "",
      cPass: '',
      errors: {},
      touched: {
        username: false,
        email: false,
        pass: false,
        cPass: false,
      }
    };
  },
  watch: {
    username() {
      if (this.touched.username) this.validateForm()
    },
    email() {
      if (this.touched.email) this.validateForm()
    },
    pass() {
      if (this.touched.pass) this.validateForm()
    },
    cPass() {
      if (this.touched.cPass) this.validateForm()
    },
    data: {
        immediate: true,
        handler(val) {
          if (this.action === 'update' && val) {
            this.id = val.id;
            this.username = val.name;
            this.email = val.email; 
            this.pass = val.pass;
            this.cPass = val.cPass;

          }

          if (this.action === 'add') {
            this.resetForm();
          }
        }
    }
  },
  computed: {
    title() {
      return this.action === 'add'
        ? 'Add New Data'
        : 'Update Data'
    },
    submitText() {
      return this.action === 'add'
        ? 'Submit'
        : 'Update'
    },
     isFormValid() {
        if (this.action === 'update') {
          return (
            this.username !== '' &&
            this.email !== '' &&
            (this.pass === '' || this.pass === this.cPass) &&
            Object.keys(this.errors).length === 0
          )
        }

        // add
        return (
          this.username !== '' &&
          this.email !== '' &&
          this.pass !== '' &&
          this.pass === this.cPass &&
          Object.keys(this.errors).length === 0
        )
      }


  },
  methods: {
   validateForm() {
      this.errors = {}

      // Username
      if (!this.username) {
        this.errors.username = 'Username wajib diisi'
      }

      // Email
      if (!this.email) {
        this.errors.email = 'Email wajib diisi'
      } else if (!this.isValidEmail(this.email)) {
        this.errors.email = 'Format email tidak valid'
      }

      // Password
      if (!this.pass) {
        if(this.action == 'Add'){
          this.errors.pass = 'Password wajib diisi'
        }
      } else if (this.pass.length < 6) {
        this.errors.pass = 'Password minimal 6 karakter'
      }

      // Confirm Password
      if (!this.cPass) {
        if(this.action == 'Add'){
          this.errors.cPass = 'Confirm password wajib diisi'
        }
      } else if (this.cPass !== this.pass) {
        this.errors.cPass = 'Password tidak sama'
      }
    },

    isValidEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
    },
    closeSidebar() {
      this.$emit("close");
      this.resetForm()
    },
    resetForm() {
      this.username = "";
      this.email = "";
      this.pass = "";
      this.cPass = '';
      this.id = null;
      this.errors = {};
      
    },

    submitForm() {
     Object.keys(this.touched).forEach(key => {
        this.touched[key] = true
      })

      this.validateForm()

      if (Object.keys(this.errors).length > 0) return

      const formData = new FormData()
      const user = JSON.parse(localStorage.getItem('authUser'))

      if (this.id) {
        formData.append('id', this.id) 
      }
      formData.append('username', this.username)
      formData.append('email', this.email)
      formData.append('pass', this.pass)
      formData.append('cPass', this.cpass)
      if (user?.user.id) {
        formData.append('user_id', user.user.id)
      }

      const id = this.id

      this.$store.commit("SET_LOADING", true)

      this.$store.dispatch("users/process", { formData, id })
        .then(() => {
          toast.success(
            `Data has been ${!this.id ? 'added' : 'updated'}`,
            {
              position: 'bottom-center',
              autoClose: 2000,
            }
          )

          this.closeSidebar()
          this.$store.commit("SET_LOADING", false)
        })
        .catch((err) => {
          console.error('Error saving item:', err)

          toast.error(
            err?.response?.data?.message || 'Something went wrong',
            {
              position: 'bottom-center',
              autoClose: 2000,
            }
          )

          this.closeSidebar()
          this.$store.commit("SET_LOADING", false)
        })
    }

  },
};
</script>
