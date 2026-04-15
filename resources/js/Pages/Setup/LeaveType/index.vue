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
                        <p class="text-gray-500 text-sm">Kelola jenis cuti beserta alur approval-nya</p>
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
                        :rows="leaveTypes"
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
                            <span v-else-if="props.column.field === 'max_days'">
                                {{ props.formattedRow.max_days }} hari
                                
                            </span>
                            <span v-else-if="props.column.field === 'is_paid'">
                                <span class="px-2 py-1 rounded text-xs" :class="props.formattedRow.is_paid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'">
                                  {{ props.formattedRow.is_paid ? 'Paid' : 'Unpaid' }}
                                </span>
                            </span>
                            <span v-else-if="props.column.field === 'gender_specific'">
                                {{ props.formattedRow.gender_specific ? props.formattedRow.gender_specific : 'All' }}
                            </span>
                            <span v-else-if="props.column.field === 'approval_steps'">
                                <div class="flex items-center gap-1 flex-wrap">
                                  <template v-for="(step, idx) in props.formattedRow.approval_steps" :key="step.id">
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs border border-blue-200">
                                      {{ idx + 1 }}. {{ formatApproverType(step.approver_type) }}
                                    </span>
                                    <span v-if="idx < props.formattedRow.approval_steps.length - 1" class="text-gray-400 text-xs">→</span>
                                  </template>
                                </div>
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
      title: 'Leave Type',
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
                        label: 'Max Day',
                        field: 'max_days',
                        sortable: false,
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Start Time',
                        },
                    },
                    {
                        label: 'Paid',
                        field: 'is_paid',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Paid',
                        },
                    },
                    {
                        label: 'Gender',
                        field: 'gender_specific',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Gender',
                        },
                    },
                    {
                        label: 'Approval Steps',
                        field: 'approval_steps',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Approval Steps',
                        },
                    },
                    // {
                    //     label: 'Status', 
                    //     field: 'is_active',
                    //     filterOptions: {
                    //         enabled: true,
                    //         placeholder: 'Pilih Status',
                    //         filterDropdownItems: [
                    //         { value: 1, text: 'Active' },
                    //         { value: 0, text: 'Inactive' },
                    //         ],
                    //     },
                    // },
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
    // leaveTypes(){
    //   return [];
    // },
    leaveTypes() { return this.$store.state.leaveType.datas; },
    loading() { return this.$store.state.leaveType.loading; },

  },
  mounted() {
    this.$store.dispatch('leaveType/fetchAll');
  },
  // async created() {
  //     this.$store.commit("SET_LOADING", true);
  //     try {
  //         await this.$store.dispatch("leaveType/datas");
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

        this.$store.dispatch("leaveType/remove", { formData, id }).then(() => {
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
    },
    formatApproverType(type) {
      const map = {
        direct_svp: 'Direct SVP',
        division_head: 'Div. Head',
        hr: 'HR',
        specific_user: 'Specific User',
      };
      return map[type] || type;
    },
  },
};
</script>