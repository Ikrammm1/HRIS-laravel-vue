<template>
  <div>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-40 z-40" @click="closeSidebar"></div>

    <div
      class="fixed top-0 right-0 w-full sm:w-[80%] lg:w-[60%] h-full bg-white shadow-xl z-50 transform transition-transform duration-300"
      :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
    >
      <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">{{ title }} Employe</h2>
        <button class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-full" @click="closeSidebar">✕</button>
      </div>

      <div class="p-4 overflow-y-auto h-[calc(100%-120px)]">
        <form @submit.prevent="submitForm">

          <div class="mb-4">
            <label class="block font-medium">Full Name</label>
            <input
              type="text"
              v-model="fullname"
              @blur="touched.fullname = true"
              class="w-full mt-1 rounded p-2 border"
              placeholder="Full Name"
            />
            <p v-if="touched.fullname && errors.fullname" class="text-red-500 text-sm mt-1">{{ errors.fullname }}</p>
          </div>

         
          <div class="mb-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block font-medium">Email</label>
                <input
                  type="email"
                  v-model="email"
                  @blur="touched.email = true"
                  class="w-full mt-1 rounded p-2 border"
                  placeholder="Email"
                />
                <p v-if="touched.email && errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
              </div>
              <div>
                <label class="block font-medium">Phone Number</label>
                <input
                  type="text"
                  v-model="phone_number"
                  @blur="touched.phone_number = true"
                  class="w-full mt-1 rounded p-2 border"
                  placeholder="Phone Number"
                />
                <p v-if="touched.phone_number && errors.phone_number" class="text-red-500 text-sm mt-1">{{ errors.phone_number }}</p>
              </div>
            </div>
          </div>

          <div class="mb-4">
            <div class="grid grid-cols-3 gap-4">
              <div>
                <CustomSelect
                  v-model="gender"
                  :options="genderList"
                  label="Gender"
                  labelKey="label"
                  valueKey="value"
                  placeholder="Select Gender"
                  @change="touched.gender = true"
                />
                <p v-if="touched.gender && errors.gender" class="text-red-500 text-sm mt-1">{{ errors.gender }}</p>
              </div>

              <div>
                <label class="block font-medium">Place of Birth</label>
                <input
                  type="text"
                  v-model="place_of_birth"
                  @blur="touched.place_of_birth = true"
                  class="w-full mt-1 rounded p-2 border"
                  placeholder="Place of Birth"
                />
                <p v-if="touched.place_of_birth && errors.place_of_birth" class="text-red-500 text-sm mt-1">{{ errors.place_of_birth }}</p>
              </div>

              <div>
                <label class="block font-medium">Date of Birth</label>
                <input
                  type="date"
                  v-model="date_of_birth"
                  @blur="touched.date_of_birth = true"
                  class="w-full mt-1 rounded p-2 border"
                />
                <p v-if="touched.date_of_birth && errors.date_of_birth" class="text-red-500 text-sm mt-1">{{ errors.date_of_birth }}</p>
              </div>
            </div>
          </div>

          <div class="mb-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <CustomSelect
                  v-model="religion"
                  :options="religionList"
                  label="Religion"
                  labelKey="religion_name"
                  valueKey="id"
                  placeholder="Select Religion"
                  @change="touched.religion = true"
                />
                <p v-if="touched.religion && errors.religion" class="text-red-500 text-sm mt-1">{{ errors.religion }}</p>
              </div>

              <div>
                <CustomSelect
                  v-model="merital_status"
                  :options="meritalStatusList"
                  label="Merital Status"
                  labelKey="status"
                  valueKey="id"
                  placeholder="Select Status"
                  @change="touched.merital_status = true"
                />
                <p v-if="touched.merital_status && errors.merital_status" class="text-red-500 text-sm mt-1">{{ errors.merital_status }}</p>
              </div>
            </div>
          </div>

          <div class="mb-4">
            <label class="block font-medium">Address</label>
            <textarea
              v-model="address"
              @blur="touched.address = true"
              class="w-full mt-1 rounded p-2 border"
              placeholder="Address"
            />
            <p v-if="touched.address && errors.address" class="text-red-500 text-sm mt-1">{{ errors.address }}</p>
          </div>

          <div class="mb-4">
            <CustomSelect
              v-model="division"
              :options="divisionList"
              label="Division"
              labelKey="div_name"
              valueKey="div_code"
              placeholder="Select Division"
              @change="touched.division = true"
            />
            <p v-if="touched.division && errors.division" class="text-red-500 text-sm mt-1">{{ errors.division }}</p>
          </div>

          <div class="mb-4">
            <CustomSelect
              v-model="department"
              :options="departmentList"
              label="Department"
              labelKey="dept_name"
              valueKey="dept_code"
              placeholder="Select Department"
              @change="touched.department = true"
            />
            <p v-if="touched.department && errors.department" class="text-red-500 text-sm mt-1">{{ errors.department }}</p>
          </div>


          <div class="mb-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block font-medium">Position</label>
                <input
                  type="text"
                  v-model="position"
                  @blur="touched.position = true"
                  class="w-full mt-1 rounded p-2 border"
                  placeholder="Position"
                />
                <p v-if="touched.position && errors.position" class="text-red-500 text-sm mt-1">{{ errors.position }}</p>
              </div>
              <div>
               <CustomSelect
                  v-model="employee_status"
                  :options="employeeStatusList"
                  label="Employee Status"
                  labelKey="label"
                  valueKey="value"
                  placeholder="Select Employee Status"
                  @change="touched.employee_status = true"
                />
                <p v-if="touched.employee_status && errors.employee_status" class="text-red-500 text-sm mt-1">{{ errors.employee_status }}</p>
              </div>
            </div>
          </div>

          <div class="mb-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <CustomSelect
                  v-model="direct_svp"
                  :options="svpList"
                  label="Direct SVP"
                  labelKey="full_name"
                  valueKey="id"
                  placeholder="Select Direct SVP"
                  @change="touched.direct_svp = true"
                />
                <p v-if="touched.direct_svp && errors.direct_svp" class="text-red-500 text-sm mt-1">{{ errors.direct_svp }}</p>
              </div>

              <div>
                <label class="block font-medium">Join Date</label>
                <input
                  type="date"
                  v-model="join_date"
                  @blur="touched.join_date = true"
                  class="w-full mt-1 rounded p-2 border"
                />
                <p v-if="touched.join_date && errors.join_date" class="text-red-500 text-sm mt-1">{{ errors.join_date }}</p>
              </div>
            </div>
          </div>

        </form>
      </div>

      <div class="pt-2 pl-6 pr-6 border-t flex justify-between">
        <button class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300" @click="resetForm">Reset</button>
        <button
          type="submit"
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
import department from '@/store/modules/department';
import division from '@/store/modules/division';
import { icon } from '@fortawesome/fontawesome-svg-core';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import CustomSelect from '@/Components/CustomSelect.vue';

export default {
  components:{
    CustomSelect
  },
  props: {
    isOpen: Boolean,
    action: String,
    data: Object
  },
  emits: ["close", "submit"],
    data() {
    return {
      id: null,
      fullname: "",
      email: "",
      phone_number: "",
      division: "",
      department: "",
      position: "",
      gender: "",
      place_of_birth: "",
      date_of_birth: "",
      religion: "",
      merital_status: "",
      address: "",
      direct_svp: "",
      join_date: "",
      employee_status: "",
      touched: {
        fullname: false,
        email: false,
        phone_number: false,
        division: false,
        department: false,
        position: false,
        place_of_birth: false,
        date_of_birth: false,
        religion: false,
        merital_status: false,
        address: false,
        direct_svp: false,
        join_date: false,
        employee_status: false,
      }
    };
  },

  computed: {
    // ✅ errors sekarang computed — otomatis reaktif
    errors() {
      const errs = {};

      if (!this.fullname) errs.fullname = 'Full name is required';
      if (!this.division) errs.division = 'Division is required';
      if (!this.department && this.action === 'add') errs.department = 'Department is required';
      if (!this.position) errs.position = 'Position is required';
      if (!this.religion) errs.religion = 'Religion is required';
      if (!this.merital_status) errs.merital_status = 'Marital status is required';
      if (!this.join_date) errs.join_date = 'Join date is required';
      if (!this.employee_status) errs.employee_status = 'Employee status is required';

      return errs;
    },

    isFormValid() {
      return Object.keys(this.errors).length === 0;
    },

    title() {
      return this.action === 'add' ? 'Add New Data' : 'Update Data';
    },
    submitText() {
      return this.action === 'add' ? 'Submit' : 'Update';
    },

    divisionList() {
      return this.$store.state.divisions.datas;
    },
    departmentList() {
      return this.getDepartment(this.division);
    },
    religionList() {
      return [
        { id: 1, religion_name: 'Islam' },
        { id: 2, religion_name: 'Kristen Protestan' },
        { id: 3, religion_name: 'Katolik' },
        { id: 4, religion_name: 'Hindu' },
        { id: 5, religion_name: 'Buddha' },
        { id: 6, religion_name: 'Konghucu' },
      ];
    },
    meritalStatusList() {
      return [
        { id: 1, status: 'Single' },
        { id: 2, status: 'Married' },
        { id: 3, status: 'Divorced' },
      ];
    },
    svpList() {
      return this.$store.state.employe.datas;
    },
    genderList() {
      return [
        { value: 'L', label: 'Male' },
        { value: 'P', label: 'Female' },
      ];
    },
    employeeStatusList() {
      return [
        { value: 'Contract', label: 'Contract' },
        { value: 'Full Time', label: 'Full Time' },
        { value: 'Probation', label: 'Probation' },
      ];
    }
  },

  watch: {
    // ✅ Hapus watcher fullname yang lama, tidak diperlukan lagi

    // ✅ Reset department jika division berubah
    // division(newVal, oldVal) {
    //   if (newVal !== oldVal) {
    //     this.department = '';
    //   }
    // },

    data: {
      immediate: true,
      handler(val) {
        if (this.action === 'update' && val) {
          this.id = val.id;
          this.fullname = val.full_name;
          this.email = val.email;
          this.phone_number = val.phone_number;
          this.division = val.div_code;
          this.department = val.dept_code;
          this.position = val.position;
          this.gender = val.gender;
          this.place_of_birth = val.place_of_birth;
          this.date_of_birth = val.date_of_birth;
          this.religion = val.religion || '';
          this.merital_status = val.merital_status || '';
          this.address = val.address;
          this.direct_svp = val.svp || '';
          this.join_date = val.join_date;
          this.employee_status = val.employee_status || '';
        }

        // console.log('data', val);
        // console.log('religion', this.religion);
        // console.log('merital_status', this.merital_status);

        if (this.action === 'add') {
          this.resetForm();
        }
      }
    }
  },

  methods: {
    getDepartment(div_code) {
      return this.$store.state.departments.datas.filter(dept => dept.div_code === div_code);
    },

    // ✅ validateForm hanya untuk trigger touched semua field sebelum submit
    touchAll() {
      Object.keys(this.touched).forEach(key => {
        this.touched[key] = true;
      });
    },

    closeSidebar() {
      this.$emit("close");
      this.resetForm();
    },

    resetForm() {
      this.id = null;
      this.fullname = "";
      this.email = "";
      this.phone_number = "";
      this.division = "";
      this.department = "";
      this.position = "";
      this.gender = "";
      this.place_of_birth = "";
      this.date_of_birth = "";
      this.religion = "";
      this.merital_status = "";
      this.address = "";
      this.direct_svp = "";
      this.join_date = "";
      this.employee_status = "";
      Object.keys(this.touched).forEach(key => {
        this.touched[key] = false;
      });
    },

    submitForm() {
      // ✅ Tampilkan semua error sebelum submit
      this.touchAll();

      if (!this.isFormValid) return;

      const formData = new FormData();
      const user = JSON.parse(localStorage.getItem('authUser'));

      if (this.id) formData.append('id', this.id);
      formData.append('full_name', this.fullname);
      formData.append('email', this.email);
      formData.append('div_code', this.division);
      formData.append('dept_code', this.department);
      formData.append('position', this.position);
      formData.append('gender', this.gender);
      formData.append('place_of_birth', this.place_of_birth);
      formData.append('date_of_birth', this.date_of_birth);
      formData.append('religion', this.religion);
      formData.append('merital_status', this.merital_status); // perbaikan typo
      formData.append('address', this.address);
      formData.append('svp', this.direct_svp);
      formData.append('join_date', this.join_date);
      formData.append('phone_number', this.phone_number);
      formData.append('employee_status', this.employee_status);

      if (user?.user.id) {
        formData.append('user_id', user.user.id);
      }

      const id = this.id;
      this.$store.commit("SET_LOADING", true);

      this.$store.dispatch("employe/process", { formData, id })
        .then(() => {
          toast.success(`Data has been ${!this.id ? 'added' : 'updated'}`, {
            position: 'bottom-center',
            autoClose: 2000,
          });
          this.closeSidebar();
          this.$store.commit("SET_LOADING", false);
        })
        .catch((err) => {
          console.error('Error saving item:', err);
          toast.error(err?.response?.data?.message || 'Something went wrong', {
            position: 'bottom-center',
            autoClose: 2000,
          });
          this.closeSidebar();
          this.$store.commit("SET_LOADING", false);
        });
    }
  }
};
</script>