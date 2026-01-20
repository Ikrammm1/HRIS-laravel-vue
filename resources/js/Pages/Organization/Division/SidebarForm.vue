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
        <h2 class="text-xl font-semibold">{{title}} Division</h2>

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
            <label class="block font-medium">Division Code</label>
            <input
              type="text"
              v-model="div_code"
              @blur="touched.div_code = true"
              class="w-full mt-1 rounded p-2 border"
              placeholder="Division Code"
            />
            <p v-if="touched.div_code && errors.div_code" class="text-red-500 text-sm mt-1">
              {{ errors.div_code }}
            </p>
          </div>

          <div class="mb-4">
            <label class="block font-medium">Division Name</label>
            <input
              type="text"
              v-model="div_name"
              @blur="touched.div_name = true"
              class="w-full mt-1 rounded p-2 border"
              placeholder="Division Name"
            />
            <p v-if="touched.div_name && errors.div_name" class="text-red-500 text-sm mt-1">
              {{ errors.div_name }}
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
      div_code: "",
      div_name: "",
      errors: {},
      touched: {
        div_code: false,
        div_name: false,
      }
    };
  },
  watch: {
    div_code() {
      if (this.touched.div_code) this.validateForm()
    },
    div_name() {
      if (this.touched.div_name) this.validateForm()
    },
    data: {
        immediate: true,
        handler(val) {
          if (this.action === 'update' && val) {
            this.id = val.id;
            this.div_code = val.div_code;
            this.div_name = val.div_name;
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
            this.div_code !== '' &&
            this.div_name !== '' &&
            Object.keys(this.errors).length === 0
          )
        }

        // add
        return (
          this.div_code !== '' &&
          this.div_name !== '' &&
          Object.keys(this.errors).length === 0
        )
      }


  },
  methods: {
   validateForm() {
      this.errors = {}

      // div_code
      if (!this.div_code) {
        this.errors.div_code = 'Division Code wajib diisi'
      }
      if (!this.div_name) {
        this.errors.div_name = 'Division Name wajib diisi'
      }

      
    },

    closeSidebar() {
      this.$emit("close");
      this.resetForm()
    },
    resetForm() {
      this.id = null;
      this.errors = {};
      this.touched = {
        div_code: false,
        div_name: false,
      };
      this.div_code = "";
      this.div_name = "";
      
    },

    submitForm() {
     Object.keys(this.touched).forEach(key => {
        this.touched[key] = true
      })

      this.validateForm()

      if (Object.keys(this.errors).length > 0) return

      const formData = new FormData()

      if (this.id) {
        formData.append('id', this.id) 
      }
      formData.append('div_code', this.div_code)
      formData.append('div_name', this.div_name)

      const id = this.id

      this.$store.commit("SET_LOADING", true)

      this.$store.dispatch("divisions/process", { formData, id })
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
