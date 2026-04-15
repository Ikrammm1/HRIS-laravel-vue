<template>
  <AuthenticatedLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Leave Type</h1>
          <p class="text-gray-500 text-sm">Kelola jenis cuti beserta alur approval-nya</p>
        </div>
        <button class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-500" @click="openAdd">
          + Add Leave Type
        </button>
      </div>
  
      <!-- Table -->
      <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
              <th class="px-4 py-3 text-left">#</th>
              <th class="px-4 py-3 text-left">Code</th>
              <th class="px-4 py-3 text-left">Name</th>
              <th class="px-4 py-3 text-left">Max Days</th>
              <th class="px-4 py-3 text-left">Paid</th>
              <th class="px-4 py-3 text-left">Gender</th>
              <th class="px-4 py-3 text-left">Approval Steps</th>
              <th class="px-4 py-3 text-left">Status</th>
              <th class="px-4 py-3 text-left">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="9" class="py-8 text-center text-gray-400">Loading...</td>
            </tr>
            <tr v-else-if="leaveTypes.length === 0">
              <td colspan="9" class="py-8 text-center text-gray-400">No data found</td>
            </tr>
            <tr v-for="(item, i) in leaveTypes" :key="item.id" class="border-t hover:bg-gray-50">
              <td class="px-4 py-3">{{ i + 1 }}</td>
              <td class="px-4 py-3 font-mono text-xs">{{ item.code }}</td>
              <td class="px-4 py-3 font-medium">{{ item.name }}</td>
              <td class="px-4 py-3">{{ item.max_days }} hari</td>
              <td class="px-4 py-3">
                <span class="px-2 py-1 rounded text-xs" :class="item.is_paid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'">
                  {{ item.is_paid ? 'Paid' : 'Unpaid' }}
                </span>
              </td>
              <td class="px-4 py-3 capitalize">{{ item.gender_specific }}</td>
              <td class="px-4 py-3">
                <!-- Tampilkan step approval sebagai badge berurutan -->
                <div class="flex items-center gap-1 flex-wrap">
                  <template v-for="(step, idx) in item.approval_steps" :key="step.id">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs border border-blue-200">
                      {{ idx + 1 }}. {{ formatApproverType(step.approver_type) }}
                    </span>
                    <span v-if="idx < item.approval_steps.length - 1" class="text-gray-400 text-xs">→</span>
                  </template>
                </div>
              </td>
              <td class="px-4 py-3">
                <span class="px-2 py-1 rounded text-xs" :class="item.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'">
                  {{ item.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-4 py-3 flex gap-2">
                <button class="text-blue-600 hover:underline text-xs" @click="openEdit(item)">Edit</button>
                <button class="text-red-500 hover:underline text-xs" @click="confirmDelete(item.id)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <!-- Sidebar Form -->
      <LeaveTypeForm
        :isOpen="sidebarOpen"
        :action="formAction"
        :data="selectedData"
        @close="sidebarOpen = false"
      />
  
      <!-- Delete Confirm Modal -->
      <ConfirmDialog
        v-if="deleteId"
        message="Yakin ingin menghapus leave type ini? Semua approval step-nya akan ikut terhapus."
        @confirm="doDelete"
        @cancel="deleteId = null"
      />
    </div>
  </AuthenticatedLayout>
</template>

<script>
import LeaveTypeForm from './LeaveTypeForm.vue';
import ConfirmDialog from '../../../Components/ConfirmDialog.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
  components: { LeaveTypeForm, ConfirmDialog,AuthenticatedLayout },
  data() {
    return {
      sidebarOpen: false,
      formAction: 'add',
      selectedData: null,
      deleteId: null,
    };
  },
  computed: {
    leaveTypes() { return this.$store.state.leaveType.datas; },
    loading() { return this.$store.state.leaveType.loading; },
  },
  mounted() {
    this.$store.dispatch('leaveType/fetchAll');
  },
  methods: {
    openAdd() {
      this.formAction = 'add';
      this.selectedData = null;
      this.sidebarOpen = true;
    },
    openEdit(item) {
      this.formAction = 'update';
      this.selectedData = item;
      this.sidebarOpen = true;
    },
    confirmDelete(id) { this.deleteId = id; },
    async doDelete() {
      await this.$store.dispatch('leaveType/remove', this.deleteId);
      this.deleteId = null;
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