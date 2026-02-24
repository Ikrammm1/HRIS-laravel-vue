<template>
  <AuthenticatedLayout>
    <div class="py-4 h-screen overflow-y-auto pb-28 box-border">
            <div class="mx-auto max-w-7xl sm:px-2 lg:px-4">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                  <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                      <div>
                        
                        <h2 class="text-2xl">{{ title }}</h2>
                        <p class="text-gray-500 text-sm">Kelola jadwal kerja karyawan</p>
                      </div>
                      <button
                          @click="openSidebar('add')"
                          class="px-4 py-2 font-semibold text-white bg-teal-600 rounded-full hover:bg-teal-500 text-sm flex items-center"
                      >
                          <Icon name="plus" class="inline-block mr-2 text-base" />
                          Add New
                      </button>
                    </div>
                
                    <!-- Table -->
                    <vue-good-table
                        :line-numbers="true"
                        :columns="columns"
                        :rows="workSchedules"
                        :search-options="{ enabled: false }"
                        :pagination-options="{
                            enabled: true,
                            mode: 'records'
                        }"
                          theme="polar-bear"
                    >
                        <!-- Vue 3 slot syntax -->
                        <template #table-row="props">
                            
                            <span
                                v-if="props.column.field === 'action'"
                                class="flex items-center gap-2"
                            >
                                <button
                                    @click="openSidebar('update', props.row)"
                                    class="w-6 h-6 flex items-center justify-center text-white bg-amber-500 rounded-full hover:bg-amber-600"
                                >
                                    <Icon name="edit" class="text-xs" />
                                </button>

                                <button
                                    @click="openConfirm(props.row.id)"
                                    class="w-6 h-6 flex items-center justify-center text-white bg-red-500 rounded-full hover:bg-red-600"
                                >
                                    <Icon name="trash" class="text-xs"  />
                                </button>
                            </span>
                            <span v-else-if="props.column.field === 'work_days'">
                                <span v-for="value in props.row.work_days" :key="value" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10">
                                        {{ value }}
                                    </span>
                            </span>
                            <span v-else-if="props.column.field === 'shift_name'">
                                {{ props.row.shift.name ?? '-'}}
                            </span>

                                

                            <span v-else>
                                {{ props.formattedRow[props.column.field] ?? '-'}}
                            </span>
                        </template>

                    </vue-good-table>
                    <SidebarForm
                        :is-open="isSidebarOpen"
                        :action="actionType"
                        :data="selectedData"
                        @close="isSidebarOpen = false"
                    />
    
                  </div>
                </div>
              </div>
    </div>
    <ConfirmDialog
            :show="showConfirm"
            title="Delete Item?"
            message="Data yang dihapus tidak bisa dikembalikan."
            confirm="Delete"
            @confirm="confirmDelete"
            @cancel="showConfirm = false"
        />
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SidebarForm from './SidebarForm.vue';
import ConfirmDialog from '../../../Components/ConfirmDialog.vue'
import Icon from '@/Components/icons/Icon.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
  components: { AuthenticatedLayout, SidebarForm, ConfirmDialog, Icon },
  data() {
    return {
      title: 'Work Schedule',
      columns: [
                    
                    {
                        label: 'Code', 
                        field: 'code',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Code',
                        },
                    },
                    {
                        label: 'Name', // Kolom untuk 
                        field: 'name',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Name',
                        },
                    },
                    {
                        label: 'Shift', // Kolom untuk shift
                        field: 'shift_name',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Shift',
                        },
                    },
                    {
                        label: 'Work Days', // Kolom untuk hari kerja
                        field: 'work_days',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Work Days',
                        },
                    },
                    {
                        label: 'Action', // Kolom untuk tombol aksi (Edit/Delete)
                        field: 'action',
                        sortable: false, // Kolom aksi tidak bisa di-sort
                        filterOptions: {
                            enabled: false, // Filter dinonaktifkan untuk kolom Action
                        },
                    },
                ],
      isSidebarOpen: false,
      actionType: 'add',
      selectedData: null,
      deleteId: null,
      showConfirm: false,
    };
  },
  computed: {
    workSchedules() { return this.$store.state.workSchedule.datas; },
    loading() { return this.$store.state.workSchedule.loading; },

  },
  mounted() {
     this.$store.dispatch('workSchedule/fetchAll');
    this.$store.dispatch('shift/fetchAll'); // untuk dropdown di form
  },
  // async created() {
  //     this.$store.commit("SET_LOADING", true);
  //     try {
  //         await this.$store.dispatch("shift/datas");
  //     } finally {
  //         this.$store.commit("SET_LOADING", false);
  //     }
  // },
  methods: {
    openSidebar(action, row = null) {
        this.actionType = action
        this.selectedData = row
        this.isSidebarOpen = true
    },

    onDelete(id){
        const formData = new FormData()
        formData.append('id', id)
        this.$store.commit("SET_LOADING", true)

        this.$store.dispatch("shift/remove", { formData, id }).then(() => {
        toast.success(
            `Data has been deleted`,
            {
            position: 'bottom-center',
            autoClose: 2000,
            }
        )

        this.$store.commit("SET_LOADING", false)
        })
        .catch((err) => {
        console.error('Error deleting item:', err)

        toast.error(
            err?.response?.data?.message || 'Something went wrong',
            {
            position: 'bottom-center',
            autoClose: 2000,
            }
        )
        this.$store.commit("SET_LOADING", false)
        })
    },

    openConfirm(id) {
        this.deleteId = id
        this.showConfirm = true
    },
    confirmDelete() {
      console.log('Deleting item with id:', this.deleteId)
        this.onDelete(this.deleteId)
        this.showConfirm = false
    }
  },
};
</script>