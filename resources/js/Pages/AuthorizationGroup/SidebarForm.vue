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
      class="fixed top-0 right-0 w-full sm:w-[700px] lg:w-[700px] h-full bg-white shadow-xl z-50 transform transition-transform duration-300"
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
        <form >
          <div class="mb-4">
            <label class="block font-medium">Group Name</label>
            <input
              type="text"
              v-model="groupName"
              class="w-full mt-1 border rounded p-2"
              placeholder="Group name"
            >
          </div>

          <div class="mb-4">
            <label class="block font-medium">Description</label>
            <textarea
              v-model="description"
              class="w-full mt-1 border rounded p-2"
              placeholder="Description"
              rows="4"
            ></textarea>
          </div>


          <div class="mb-4">
            <label class="block font-medium">Assign Users</label>
           <vue-good-table
              :columns="columns"
              :rows="processedUsers"
              :row-key="'id'"
              :select-options="{ 
                enabled: true,
                selectOnCheckboxOnly: true,
                mode: 'multiple'
              }"
              :pagination-options="{
                                enabled: true,
                                mode: 'records'
                            }"
              theme="polar-bear"
              @selected-rows-change="selectionChanged">
            </vue-good-table>
            <div class="mt-2 text-sm text-gray-600">
              Selected: {{ selectedUserIds.length }} user(s)
            </div>
          </div>

          <div class="mb-4">
            <label class="block font-medium">Assign Menus</label>
           <vue-good-table
              :columns="column_menu"
              :rows="processedMenus"
              :row-key="'id'"
              :select-options="{ 
                enabled: true,
                selectOnCheckboxOnly: true,
                mode: 'multiple'
              }"
              :pagination-options="{
                                enabled: true,
                                mode: 'records'
                            }"
              theme="polar-bear"
              @selected-rows-change="selectionChangedMenu"
            >

            </vue-good-table>
            <div class="mt-2 text-sm text-gray-600">
              Selected: {{ selectedMenuIds.length }} menu(s)
            </div>
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
      id: null,
      groupName: "",
      description: "",
      selectedUserIds: [],
      selectedMenuIds: [],
      userTableKey: 0,
      menuTableKey: 0,
      isInitialized: false,
      columns: [
        {
          label: 'Name',
          field: 'name',
        },
      ],
      column_menu: [
        {
          label: 'Name',
          field: 'menu_name',
        },
      ],
    };
  },
  watch: {
    data: {
      immediate: true,
      handler(val) {
        if (this.action === 'update' && val) {
          this.id = val.id;
          this.groupName = val.name;
          this.description = val.description;
          
          // Set selected IDs dari data yang ada
          this.selectedUserIds = (val.users || []).map(u => u.id);
          this.selectedMenuIds = (val.menus || []).map(m => m.id);
          
          this.isInitialized = true;
          
          // Force re-render tables dengan key
          this.$nextTick(() => {
            this.userTableKey++;
            this.menuTableKey++;
          });
        }

        if (this.action === 'add') {
          this.resetForm();
        }
      }
    }
  },
  computed: {
    users() {
      return this.$store.state.users.datas || [];
    },
    menus() {
      return this.$store.state.menu.menus || [];
    },
    // Process users dengan vgtSelected
    processedUsers() {
      return this.users.map(user => ({
        ...user,
        vgtSelected: this.selectedUserIds.includes(user.id)
      }));
    },
    // Process menus dengan vgtSelected
    processedMenus() {
      return this.menus.map(menu => ({
        ...menu,
        vgtSelected: this.selectedMenuIds.includes(menu.id)
      }));
    },
    // Get actual selected user objects
    selectedUsers() {
      return this.users.filter(u => this.selectedUserIds.includes(u.id));
    },
    // Get actual selected menu objects
    selectedMenus() {
      return this.menus.filter(m => this.selectedMenuIds.includes(m.id));
    },
    title() {
      return this.action === 'add' ? 'Add New Data' : 'Update Data';
    },
    submitText() {
      return this.action === 'add' ? 'Submit' : 'Update';
    },
    isFormValid() {
      return (
        this.groupName !== ''
        //  && this.selectedUserIds.length !== 0 &&
        // this.selectedMenuIds.length !== 0
      );
    }
  },
  methods: {
    closeSidebar() {
      this.$emit("close");
      this.resetForm();
    },
    
    resetForm() {
      this.groupName = "";
      this.description = "";
      this.selectedUserIds = [];
      this.selectedMenuIds = [];
      this.id = null;
      this.isInitialized = false;
      
      // Force re-render tables
      this.userTableKey++;
      this.menuTableKey++;
    },

    selectionChanged(params) {
      // Get IDs from params (yang baru di-click)
      const newSelectedIds = params.selectedRows.map(row => row.id);
      
      // Untuk setiap user, cek apakah ada di newSelectedIds
      const updatedIds = this.users
        .filter(user => {
          // Ambil status saat ini
          const wasSelected = this.selectedUserIds.includes(user.id);
          const isSelected = newSelectedIds.includes(user.id);
          
          // Jika statusnya berubah dari selected ke unselected
          if (wasSelected && !isSelected) {
            return false; // Remove dari selection
          }
          
          // Jika di-select (baik baru atau sudah dari sebelumnya)
          if (isSelected) {
            return true;
          }
          
          // Jika tidak di-select dan tidak ada di event, pertahankan status sebelumnya
          // Ini handle kasus preselected yang tidak muncul di event
          return wasSelected;
        })
        .map(u => u.id);
      
      this.selectedUserIds = updatedIds;
    },

    selectionChangedMenu(params) {
      // Get IDs from params (yang baru di-click)
      const newSelectedIds = params.selectedRows.map(row => row.id);
    
      // Untuk setiap menu, cek apakah ada di newSelectedIds
      const updatedIds = this.menus
        .filter(menu => {
          // Ambil status saat ini
          const wasSelected = this.selectedMenuIds.includes(menu.id);
          const isSelected = newSelectedIds.includes(menu.id);
          
          // Jika statusnya berubah dari selected ke unselected
          if (wasSelected && !isSelected) {
            return false; // Remove dari selection
          }
          
          // Jika di-select (baik baru atau sudah dari sebelumnya)
          if (isSelected) {
            return true;
          }
          
          // Jika tidak di-select dan tidak ada di event, pertahankan status sebelumnya
          // Ini handle kasus preselected yang tidak muncul di event
          return wasSelected;
        })
        .map(m => m.id);
      
      this.selectedMenuIds = updatedIds;
    },

    submitForm() {
      const formData = new FormData();
      const user = JSON.parse(localStorage.getItem('authUser'));

      console.log('=== SUBMITTING DATA ===');
      console.log('Group Name:', this.groupName);
      console.log('Description:', this.description);
      console.log('Selected User IDs:', this.selectedUserIds);
      console.log('Selected Users:', this.selectedUsers);
      console.log('Selected Menu IDs:', this.selectedMenuIds);
      console.log('Selected Menus:', this.selectedMenus);

      if (this.id) {
        formData.append('id', this.id);
      }
      
      formData.append('name', this.groupName);
      formData.append('description', this.description);
      
      // Append selected user IDs
      this.selectedUserIds.forEach((userId, index) => {
        formData.append(`user_ids[${index}]`, userId);
      });
      
      // Append selected menu IDs
      this.selectedMenuIds.forEach((menuId, index) => {
        formData.append(`menu_ids[${index}]`, menuId);
      });

      if (user?.user.id) {
        formData.append('user_id', user.user.id)
      }

      const id = this.id;

      this.$store.commit("SET_LOADING", true);

      this.$store.dispatch("authorizationGroup/process", { formData, id })
        .then(() => {
          toast.success(
            `Data has been ${!this.id ? 'added' : 'updated'}`,
            {
              position: 'bottom-center',
              autoClose: 2000,
            }
          );
          this.closeSidebar();
          this.$store.commit("SET_LOADING", false);
        })
        .catch((err) => {
          console.error('Error saving item:', err);
          toast.error(
            err?.response?.data?.message || 'Something went wrong',
            {
              position: 'bottom-center',
              autoClose: 2000,
            }
          );
          this.closeSidebar();
          this.$store.commit("SET_LOADING", false);
        });
    }
  },
};
</script>