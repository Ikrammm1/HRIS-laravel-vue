<template>
  <div>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-40 z-40" @click="closeSidebar" />

    <div
      class="fixed top-0 right-0 w-full sm:w-[80%] lg:w-[50%] h-full bg-white shadow-xl z-50 transform transition-transform duration-300"
      :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
    >
      <!-- Header -->
      <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">{{ title }} Work Schedule</h2>
        <button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-full" @click="closeSidebar">✕</button>
      </div>

      <!-- Body -->
      <div class="p-6 overflow-y-auto h-[calc(100%-120px)] space-y-4">

        <!-- Code & Name -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block font-medium mb-1">Code</label>
            <input
              v-model="form.code"
              @blur="touched.code = true"
              type="text"
              placeholder="WS001"
              class="w-full border rounded p-2"
            />
            <p v-if="touched.code && errors.code" class="text-red-500 text-sm mt-1">{{ errors.code }}</p>
          </div>
          <div>
            <label class="block font-medium mb-1">Name</label>
            <input
              v-model="form.name"
              @blur="touched.name = true"
              type="text"
              placeholder="5 Hari Kerja"
              class="w-full border rounded p-2"
            />
            <p v-if="touched.name && errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>
        </div>

        <!-- Shift -->
        <div>
          <label class="block font-medium mb-1">Shift</label>
          <select
            v-model="form.shift_id"
            @change="touched.shift_id = true"
            class="w-full border rounded p-2"
          >
            <option value="">-- Pilih Shift --</option>
            <option v-for="shift in shiftList" :key="shift.id" :value="shift.id">
              {{ shift.name }} ({{ shift.start_time }} - {{ shift.end_time }})
            </option>
          </select>
          <p v-if="touched.shift_id && errors.shift_id" class="text-red-500 text-sm mt-1">{{ errors.shift_id }}</p>
        </div>

        <!-- Work Days Picker -->
        <div>
          <label class="block font-medium mb-2">Work Days</label>
          <div class="flex gap-2 flex-wrap">
            <button
              v-for="day in dayOptions"
              :key="day.value"
              type="button"
              class="w-12 h-12 rounded-full border-2 text-sm font-medium transition-colors"
              :class="form.work_days.includes(day.value)
                ? 'bg-teal-600 text-white border-teal-600'
                : 'bg-white text-gray-600 border-gray-300 hover:border-teal-400'"
              @click="toggleDay(day.value)"
            >
              {{ day.label }}
            </button>
          </div>
          <p v-if="touched.work_days && errors.work_days" class="text-red-500 text-sm mt-2">{{ errors.work_days }}</p>

          <!-- Quick select shortcuts -->
          <div class="flex gap-2 mt-3">
            <button type="button" class="text-xs text-teal-600 hover:underline" @click="selectWeekdays">
              Senin–Jumat
            </button>
            <span class="text-gray-300">|</span>
            <button type="button" class="text-xs text-teal-600 hover:underline" @click="selectSixDays">
              Senin–Sabtu
            </button>
            <span class="text-gray-300">|</span>
            <button type="button" class="text-xs text-red-500 hover:underline" @click="form.work_days = []">
              Clear
            </button>
          </div>
        </div>

        <!-- Active -->
        <div>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" v-model="form.is_active" class="w-4 h-4" />
            <span class="font-medium">Active</span>
          </label>
        </div>

      </div>

      <!-- Footer -->
      <div class="pt-2 px-6 border-t flex justify-between">
        <button class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300" @click="resetForm">Reset</button>
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
import { toast } from 'vue3-toastify';

export default {
  props: { isOpen: Boolean, action: String, data: Object },
  emits: ['close'],
  data() {
    return {
      form: this.defaultForm(),
      touched: { code: false, name: false, shift_id: false, work_days: false },
      dayOptions: [
        { value: 'Mon', label: 'Sen' },
        { value: 'Tue', label: 'Sel' },
        { value: 'Wed', label: 'Rab' },
        { value: 'Thu', label: 'Kam' },
        { value: 'Fri', label: 'Jum' },
        { value: 'Sat', label: 'Sab' },
        { value: 'Sun', label: 'Min' },
      ],
    };
  },
  computed: {
    shiftList() {
      return this.$store.state.shift.datas.filter(s => s.is_active);
    },
    errors() {
      const e = {};
      if (!this.form.code) e.code = 'Code wajib diisi';
      if (!this.form.name) e.name = 'Name wajib diisi';
      if (!this.form.shift_id) e.shift_id = 'Shift wajib dipilih';
      if (!this.form.work_days || this.form.work_days.length === 0) e.work_days = 'Pilih minimal 1 hari kerja';
      return e;
    },
    isFormValid() { return Object.keys(this.errors).length === 0; },
    title() { return this.action === 'add' ? 'Add' : 'Update'; },
    submitText() { return this.action === 'add' ? 'Submit' : 'Update'; },
  },
  watch: {
    data: {
      immediate: true,
      handler(val) {
        if (this.action === 'update' && val) {
          this.form = {
            ...val,
            work_days: Array.isArray(val.work_days) ? [...val.work_days] : JSON.parse(val.work_days || '[]'),
            shift_id: val.shift_id,
          };
        } else {
          this.form = this.defaultForm();
        }
      },
    },
  },
  methods: {
    defaultForm() {
      return { code: '', name: '', shift_id: '', work_days: [], is_active: true };
    },
    toggleDay(day) {
      const idx = this.form.work_days.indexOf(day);
      if (idx === -1) {
        this.form.work_days.push(day);
      } else {
        this.form.work_days.splice(idx, 1);
      }
      this.touched.work_days = true;
    },
    selectWeekdays() {
      this.form.work_days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
    },
    selectSixDays() {
      this.form.work_days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    },
    resetForm() {
      this.form = this.defaultForm();
      Object.keys(this.touched).forEach(k => (this.touched[k] = false));
    },
    closeSidebar() {
      this.$emit('close');
      this.resetForm();
    },
    async submitForm() {
      Object.keys(this.touched).forEach(k => (this.touched[k] = true));
      if (!this.isFormValid) return;

      try {
        this.$store.commit('SET_LOADING', true);
        await this.$store.dispatch('workSchedule/process', {
          formData: this.form,
          id: this.action === 'update' ? this.form.id : null,
        });
        toast.success(`Work schedule berhasil ${this.action === 'add' ? 'ditambahkan' : 'diupdate'}`, {
          position: 'bottom-center', autoClose: 2000,
        });
        this.closeSidebar();
      } catch (err) {
        toast.error(err?.response?.data?.message || 'Something went wrong', {
          position: 'bottom-center', autoClose: 2000,
        });
      } finally {
        this.$store.commit('SET_LOADING', false);
      }
    },
  },
};
</script>