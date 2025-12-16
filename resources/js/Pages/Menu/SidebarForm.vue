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
      class="fixed top-0 right-0 w-full sm:w-[600px] lg:w-[600px] h-full bg-white shadow-xl z-50 transform transition-transform duration-300"
      :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
    >

      <!-- Header -->
      <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">{{title}} Menu</h2>

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
            <label class="block font-medium">Menu Name</label>
            <input
              type="text"
              v-model="menu_name"
              class="w-full mt-1  rounded p-2"
              placeholder="Menu name"
            >
          </div>

          <div class="mb-4">
            <label class="block font-medium">Description</label>
            <textarea
              v-model="description"
              class="w-full mt-1 rounded p-2"
              placeholder="Description"
              rows="4"
            ></textarea>
          </div>


          <div class="mb-4">
            <label class="block font-medium">URL</label>
            <input
              type="text"
              v-model="url"
              class="w-full mt-1 rounded p-2"
              placeholder="/dashboard"
            >
          </div>
          <div class="mb-4">
            <label class="block font-medium">Icon</label>
            <input
              type="text"
              v-model="icon"
              class="w-full mt-1  rounded p-2"
              placeholder="Icon name"
            >
          </div>

          <div class="mb-4">
            <label class="block font-medium">Level</label>
            <select
              v-model="level"
              class="w-full mt-1  rounded p-2"
            >
              <option
                v-for="option in levelOptions"
                :key="option.value"
                :value="option.value">
                {{ option.text }}
              </option>
            </select>
          </div>
          <div class="mb-4" v-if="level && level>1">
            <label class="block font-medium">Parent Menu</label>
            <select
              v-model="parent_id"
              class="w-full mt-1  rounded p-2"
            >
              <option
                v-for="option in parentList"
                :key="option.id"
                :value="option.id">
                {{ option.menu_name }}
              </option>
            </select>
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
      menu_name: "",
      description: "",
      url: "",
      level: 1,
      parent_id: '',
      levelOptions: [
        { value: 1, text: "Header" },
        { value: 2, text: "Parent" },
        { value: 3, text: "Children" },
      ],
      icon: "",
      
    };
  },
  watch: {
    data: {
        immediate: true,
        handler(val) {
          if (this.action === 'update' && val) {
            this.id = val.id;
            this.menu_name = val.menu_name;
            this.description = val.description; 
            this.url = val.url;
            this.level = val.level;
            this.icon = val.icon;
            this.parent_id = val.parent_id;

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

    parentList() {
      console.log(this.$store.state.menu.menus.filter(menu => ((this.level && this.level>1)? menu.level == this.level - 1 :  menu.level != 3)));
      return this.$store.state.menu.menus.filter(menu => ((this.level && this.level>1)? menu.level == this.level - 1 :  menu.level != 3));
    },
    isFormValid() {
        if (this.action === 'update') {
          return (
            this.menu_name !== '' &&
            this.url !== '' &&
            this.level !== '' &&
            this.icon !== ''
          )
        }

        // add
        return (
            this.menu_name !== '' &&
            this.url !== '' &&
            this.level !== '' &&
            this.icon !== ''
        )
      }
  },
  methods: {
    closeSidebar() {
      this.$emit("close");
      this.resetForm()
    },
    resetForm() {
      this.menu_name = "";
      this.description = "";
      this.url = "";
      this.level = 1;
      this.icon = "";
      this.parent_id = null;
      this.id = null;
      
      
    },

    submitForm() {
      const formData = new FormData()
      const user = JSON.parse(localStorage.getItem('authUser'))

      if (this.id) {
        formData.append('id', this.id) // update saja
      }
      formData.append('menu_name', this.menu_name)
      formData.append('description', this.description)
      formData.append('level', this.level)
      formData.append('icon', this.icon)
      formData.append('url', this.url)
      formData.append('parent_id', this.parent_id)
      if (user?.user.id) {
        formData.append('user_id', user.user.id)
      }

      const id = this.id

      this.$store.commit("SET_LOADING", true)

      this.$store.dispatch("menu/process", { formData, id })
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
