<template>
  <div>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-40 z-40" @click="closeSidebar" />

    <div
      class="fixed top-0 right-0 w-full sm:w-[85%] lg:w-[60%] h-full bg-white shadow-xl z-50 transform transition-transform duration-300"
      :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
    >
      <!-- Header -->
      <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">{{ title }} Leave Type</h2>
        <button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-full" @click="closeSidebar">✕</button>
      </div>

      <!-- Body -->
      <div class="p-6 overflow-y-auto h-[calc(100%-120px)] space-y-4">

        <!-- Code & Name -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block font-medium mb-1">Code</label>
            <input v-model="form.code" @blur="touched.code = true" type="text" placeholder="LT001" class="w-full border rounded p-2" />
            <p v-if="touched.code && errors.code" class="text-red-500 text-sm mt-1">{{ errors.code }}</p>
          </div>
          <div>
            <label class="block font-medium mb-1">Name</label>
            <input v-model="form.name" @blur="touched.name = true" type="text" placeholder="Cuti Tahunan" class="w-full border rounded p-2" />
            <p v-if="touched.name && errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>
        </div>

        <!-- Max Days & Gender -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block font-medium mb-1">Max Days</label>
            <input v-model.number="form.max_days" @blur="touched.max_days = true" type="number" min="1" class="w-full border rounded p-2" />
            <p v-if="touched.max_days && errors.max_days" class="text-red-500 text-sm mt-1">{{ errors.max_days }}</p>
          </div>
          <div>
            <label class="block font-medium mb-1">Gender Specific</label>
            <select v-model="form.gender_specific" class="w-full border rounded p-2">
              <option value="all">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>
        </div>

        <!-- Min Working Days -->
        <div>
          <label class="block font-medium mb-1">Min Working Days (masa kerja)</label>
          <input v-model.number="form.min_working_days" type="number" min="0" class="w-full border rounded p-2" />
        </div>

        <!-- Checkboxes -->
        <div class="flex flex-wrap gap-6">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" v-model="form.is_paid" class="w-4 h-4" />
            <span class="font-medium">Paid Leave</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" v-model="form.carry_over" class="w-4 h-4" />
            <span class="font-medium">Carry Over</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" v-model="form.requires_document" class="w-4 h-4" />
            <span class="font-medium">Requires Document</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" v-model="form.is_active" class="w-4 h-4" />
            <span class="font-medium">Active</span>
          </label>
        </div>

        <!-- Max Carry Over (conditional) -->
        <div v-if="form.carry_over">
          <label class="block font-medium mb-1">Max Carry Over Days</label>
          <input v-model.number="form.max_carry_over_days" type="number" min="0" class="w-full border rounded p-2" />
        </div>

        <!-- ============ APPROVAL STEPS ============ -->
        <div class="border rounded-lg p-4 bg-gray-50">
          <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-gray-700">Approval Steps</h3>
            <button
              type="button"
              class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-500"
              @click="addStep"
            >
              + Add Step
            </button>
          </div>

          <p v-if="touched.approval_steps && errors.approval_steps" class="text-red-500 text-sm mb-2">
            {{ errors.approval_steps }}
          </p>

          <!-- Empty state -->
          <p v-if="form.approval_steps.length === 0" class="text-gray-400 text-sm text-center py-4">
            Belum ada step approval. Klik "+ Add Step" untuk menambahkan.
          </p>

          <!-- Step List -->
          <div
            v-for="(step, idx) in form.approval_steps"
            :key="idx"
            class="flex items-center gap-3 mb-3 bg-white p-3 rounded border"
          >
            <!-- Step number badge -->
            <div class="w-7 h-7 rounded-full bg-teal-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
              {{ idx + 1 }}
            </div>

            <!-- Approver Type -->
            <select
              v-model="step.approver_type"
              class="flex-1 border rounded p-2 text-sm"
            >
              <option value="">-- Pilih Approver --</option>
              <option value="direct_svp">Direct SVP</option>
              <option value="division_head">Division Head</option>
              <option value="hr">HR</option>
              <option value="specific_user">Specific User</option>
            </select>

            <!-- Specific User dropdown (conditional) -->
            <select
              v-if="step.approver_type === 'specific_user'"
              v-model="step.approver_user_id"
              class="flex-1 border rounded p-2 text-sm"
            >
              <option value="">-- Pilih User --</option>
              <option v-for="user in userList" :key="user.id" :value="user.id">
                {{ user.full_name }}
              </option>
            </select>

            <!-- Mandatory toggle -->
            <label class="flex items-center gap-1 text-sm cursor-pointer flex-shrink-0">
              <input type="checkbox" v-model="step.is_mandatory" class="w-4 h-4" />
              <span>Wajib</span>
            </label>

            <!-- Move Up / Down -->
            <div class="flex gap-1">
              <button
                type="button"
                :disabled="idx === 0"
                class="px-2 py-1 text-xs bg-gray-100 rounded disabled:opacity-30 hover:bg-gray-200"
                @click="moveStep(idx, -1)"
              >↑</button>
              <button
                type="button"
                :disabled="idx === form.approval_steps.length - 1"
                class="px-2 py-1 text-xs bg-gray-100 rounded disabled:opacity-30 hover:bg-gray-200"
                @click="moveStep(idx, 1)"
              >↓</button>
            </div>

            <!-- Delete Step -->
            <button
              type="button"
              class="text-red-500 hover:text-red-700 text-lg flex-shrink-0"
              @click="removeStep(idx)"
            >✕</button>
          </div>
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
      touched: {
        code: false, name: false, max_days: false, approval_steps: false,
      },
    };
  },
  computed: {
    userList() {
      return this.$store.state.employe.datas;
    },
    errors() {
      const e = {};
      if (!this.form.code) e.code = 'Code wajib diisi';
      if (!this.form.name) e.name = 'Name wajib diisi';
      if (!this.form.max_days || this.form.max_days < 1) e.max_days = 'Max days minimal 1';
      if (this.form.approval_steps.length === 0) e.approval_steps = 'Minimal 1 approval step';
      const hasEmptyType = this.form.approval_steps.some(s => !s.approver_type);
      if (hasEmptyType) e.approval_steps = 'Semua step harus memilih approver type';
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
            approval_steps: (val.approval_steps || []).map(s => ({ ...s })),
          };
        } else {
          this.form = this.defaultForm();
        }
      },
    },
  },
  methods: {
    defaultForm() {
      return {
        code: '', name: '', max_days: null, is_paid: true,
        carry_over: false, max_carry_over_days: 0,
        gender_specific: 'all', requires_document: false,
        min_working_days: 0, is_active: true,
        approval_steps: [],
      };
    },
    addStep() {
      this.form.approval_steps.push({
        step_order: this.form.approval_steps.length + 1,
        approver_type: '',
        approver_user_id: null,
        is_mandatory: true,
      });
    },
    removeStep(idx) {
      this.form.approval_steps.splice(idx, 1);
      this.reorderSteps();
    },
    moveStep(idx, direction) {
      const steps = this.form.approval_steps;
      const target = idx + direction;
      if (target < 0 || target >= steps.length) return;
      [steps[idx], steps[target]] = [steps[target], steps[idx]];
      this.reorderSteps();
    },
    reorderSteps() {
      this.form.approval_steps.forEach((s, i) => { s.step_order = i + 1; });
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
        await this.$store.dispatch('leaveType/process', {
          formData: this.form,
          id: this.action === 'update' ? this.form.id : null,
        });
        toast.success(`Leave type saved successfully`, {
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