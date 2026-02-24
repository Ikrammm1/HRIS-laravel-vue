<template>
  <div>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-40 z-40" @click="closeSidebar" />

    <div
      class="fixed top-0 right-0 w-full sm:w-[80%] lg:w-[50%] h-full bg-white shadow-xl z-50 transform transition-transform duration-300"
      :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
    >
      <!-- Header -->
      <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">{{ title }} Shift</h2>
        <button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-full" @click="closeSidebar">✕</button>
      </div>

      <!-- Form -->
      <div class="p-6 overflow-y-auto h-[calc(100%-120px)]">

        <div class="grid grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block font-medium mb-1">Code</label>
            <input v-model="form.code" @blur="touched.code = true" type="text" placeholder="SH001" class="w-full border rounded p-2" />
            <p v-if="touched.code && errors.code" class="text-red-500 text-sm mt-1">{{ errors.code }}</p>
          </div>
          <div>
            <label class="block font-medium mb-1">Name</label>
            <input v-model="form.name" @blur="touched.name = true" type="text" placeholder="Shift Pagi" class="w-full border rounded p-2" />
            <p v-if="touched.name && errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block font-medium mb-1">Start Time</label>
            <input v-model="form.start_time" @blur="touched.start_time = true" type="time" class="w-full border rounded p-2" />
            <p v-if="touched.start_time && errors.start_time" class="text-red-500 text-sm mt-1">{{ errors.start_time }}</p>
          </div>
          <div>
            <label class="block font-medium mb-1">End Time</label>
            <input v-model="form.end_time" @blur="touched.end_time = true" type="time" class="w-full border rounded p-2" />
            <p v-if="touched.end_time && errors.end_time" class="text-red-500 text-sm mt-1">{{ errors.end_time }}</p>
          </div>
        </div>

        <div class="mb-4">
          <label class="block font-medium mb-1">Break Duration (menit)</label>
          <input v-model.number="form.break_duration" type="number" min="0" class="w-full border rounded p-2" />
        </div>

        <div class="mb-4 flex items-center gap-6">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" v-model="form.is_overnight" class="w-4 h-4" />
            <span class="font-medium">Overnight (lintas hari)</span>
          </label>
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
import 'vue3-toastify/dist/index.css';

export default {
  props: { isOpen: Boolean, action: String, data: Object },
  emits: ['close'],
  data() {
    return {
      form: this.defaultForm(),
      touched: { code: false, name: false, start_time: false, end_time: false },
    };
  },
  computed: {
    errors() {
      const e = {};
      if (!this.form.code) e.code = 'Code is required';
      if (!this.form.name) e.name = 'Name is required';
      if (!this.form.start_time) e.start_time = 'Start time is required';
      if (!this.form.end_time) e.end_time = 'End time is required';
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
          this.form = { ...val };
        } else {
          this.form = this.defaultForm();
        }
      },
    },
  },
  methods: {
    defaultForm() {
      return { code: '', name: '', start_time: '', end_time: '', break_duration: 60, is_overnight: false, is_active: true };
    },
    resetForm() {
      this.form = this.defaultForm();
      Object.keys(this.touched).forEach(k => (this.touched[k] = false));
    },
    closeSidebar() {
      this.$emit('close');
      this.resetForm();
    },
    submitForm() {
      Object.keys(this.touched).forEach(k => (this.touched[k] = true));
      // if (!this.isFormValid) return;

        this.$store.commit('SET_LOADING', true);
        
        // Convert boolean fields to 1/0 for backend
        const formData = {
          ...this.form,
          is_overnight: this.form.is_overnight ? 1 : 0,
          is_active: this.form.is_active ? 1 : 0,
        };

        this.$store.dispatch('shift/process', {
          formData: formData,
          id: this.action === 'update' ? this.form.id : null,
        }).then(() => {
          console.log('Shift saved successfully');
          toast.success(
            `Data has been ${this.action === 'update' ? 'updated' : 'added'}`,
            {
              position: 'bottom-center',
              autoClose: 2000,
            }
          )

          this.closeSidebar()
          this.$store.commit("SET_LOADING", false)
        }).catch((err) => {
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
        });
        // toast.success(`Shift berhasil ${this.action === 'add' ? 'ditambahkan' : 'diupdate'}`, { position: 'bottom-center', autoClose: 2000 });
        // this.closeSidebar();
     
    },
  },
};
</script>