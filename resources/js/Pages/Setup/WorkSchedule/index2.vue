<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Work Schedule</h1>
        <p class="text-gray-500 text-sm">Kelola jadwal kerja karyawan</p>
      </div>
      <button
        class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-500"
        @click="openAdd"
      >
        + Add Work Schedule
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
            <th class="px-4 py-3 text-left">Shift</th>
            <th class="px-4 py-3 text-left">Work Days</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-left">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading">
            <td colspan="7" class="py-8 text-center text-gray-400">Loading...</td>
          </tr>
          <tr v-else-if="workSchedules.length === 0">
            <td colspan="7" class="py-8 text-center text-gray-400">No data found</td>
          </tr>
          <tr v-for="(item, i) in workSchedules" :key="item.id" class="border-t hover:bg-gray-50">
            <td class="px-4 py-3">{{ i + 1 }}</td>
            <td class="px-4 py-3 font-mono text-xs">{{ item.code }}</td>
            <td class="px-4 py-3 font-medium">{{ item.name }}</td>
            <td class="px-4 py-3">
              <span v-if="item.shift" class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs border border-blue-200">
                {{ item.shift.name }}
              </span>
              <span v-else class="text-gray-400 text-xs">-</span>
            </td>
            <td class="px-4 py-3">
              <div class="flex gap-1 flex-wrap">
                <span
                  v-for="day in item.work_days"
                  :key="day"
                  class="px-2 py-0.5 bg-teal-50 text-teal-700 rounded text-xs border border-teal-200"
                >
                  {{ day }}
                </span>
              </div>
            </td>
            <td class="px-4 py-3">
              <span
                class="px-2 py-1 rounded text-xs font-medium"
                :class="item.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'"
              >
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
    <WorkScheduleForm
      :isOpen="sidebarOpen"
      :action="formAction"
      :data="selectedData"
      @close="sidebarOpen = false"
    />

    <!-- Delete Confirm Modal -->
    <ConfirmDialog
      v-if="deleteId"
      message="Yakin ingin menghapus work schedule ini?"
      @confirm="doDelete"
      @cancel="deleteId = null"
    />
  </div>
</template>

<script>
import WorkScheduleForm from './WorkScheduleForm.vue';
import ConfirmDialog from '../../../Components/ConfirmDialog.vue'

export default {
  components: { WorkScheduleForm, ConfirmDialog },
  data() {
    return {
      sidebarOpen: false,
      formAction: 'add',
      selectedData: null,
      deleteId: null,
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
      await this.$store.dispatch('workSchedule/remove', this.deleteId);
      this.deleteId = null;
    },
  },
};
</script>