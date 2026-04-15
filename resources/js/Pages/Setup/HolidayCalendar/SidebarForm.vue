<template>
  <div>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-40 z-40" @click="closeSidebar" />

    <div
      class="fixed top-0 right-0 w-full sm:w-[80%] lg:w-[45%] h-full bg-white shadow-xl z-50 transform transition-transform duration-300"
      :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
    >
      <!-- Header -->
      <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">{{ title }} Holiday</h2>
        <button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-full" @click="closeSidebar">✕</button>
      </div>

      <!-- Body -->
      <div class="p-6 overflow-y-auto h-[calc(100%-120px)] space-y-4">

        <!-- Nama -->
        <div>
          <label class="block font-medium mb-1">Nama Hari Libur</label>
          <input
            v-model="form.name"
            @blur="touched.name = true"
            type="text"
            placeholder="Hari Raya Idul Fitri"
            class="w-full border rounded p-2"
          />
          <p v-if="touched.name && errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
        </div>

        <!-- Tanggal & Tipe -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block font-medium mb-1">Tanggal</label>
            <input
              v-model="form.date"
              @blur="touched.date = true"
              type="date"
              class="w-full border rounded p-2"
            />
            <p v-if="touched.date && errors.date" class="text-red-500 text-sm mt-1">{{ errors.date }}</p>
          </div>
          <div>
            <label class="block font-medium mb-1">Tipe</label>
            <select
              v-model="form.type"
              @change="touched.type = true"
              class="w-full border rounded p-2"
            >
              <option value="">-- Pilih Tipe --</option>
              <option value="national">Nasional</option>
              <option value="company">Perusahaan</option>
            </select>
            <p v-if="touched.type && errors.type" class="text-red-500 text-sm mt-1">{{ errors.type }}</p>
          </div>
        </div>

        <!-- Preview hari -->
        <div v-if="form.date" class="flex items-center gap-2 p-3 bg-teal-50 border border-teal-200 rounded">
          <span class="text-teal-700 text-sm">📅</span>
          <span class="text-teal-700 text-sm font-medium">{{ previewDay }}</span>
        </div>

        <!-- Keterangan -->
        <div>
          <label class="block font-medium mb-1">Keterangan <span class="text-gray-400 text-xs">(opsional)</span></label>
          <textarea
            v-model="form.description"
            rows="3"
            placeholder="Deskripsi tambahan..."
            class="w-full border rounded p-2 text-sm"
          />
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
      touched: { name: false, date: false, type: false },
    };
  },
  computed: {
    errors() {
      const e = {};
      if (!this.form.name) e.name = 'Nama wajib diisi';
      if (!this.form.date) e.date = 'Tanggal wajib diisi';
      if (!this.form.type) e.type = 'Tipe wajib dipilih';
      return e;
    },
    isFormValid() { return Object.keys(this.errors).length === 0; },
    title() { return this.action === 'add' ? 'Add' : 'Update'; },
    submitText() { return this.action === 'add' ? 'Submit' : 'Update'; },
    previewDay() {
      if (!this.form.date) return '';
      return new Date(this.form.date).toLocaleDateString('id-ID', {
        weekday: 'long', day: '2-digit', month: 'long', year: 'numeric',
      });
    },
  },
  watch: {
    data: {
      immediate: true,
      handler(val) {
        if (this.action === 'update' && val) {
          this.form = {
            ...val,
            date: val.date ? val.date.substring(0, 10) : ''
          };
        } else {
          this.form = this.defaultForm();
        }
      },
    },
  },
  methods: {
    defaultForm() {
      return { name: '', date: '', type: '', description: '', is_active: true };
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
      if (!this.isFormValid) return;

        this.$store.commit('SET_LOADING', true);
         this.$store.dispatch('holidayCalendar/process', {
          formData: this.form,
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
    },
  },
};
</script>