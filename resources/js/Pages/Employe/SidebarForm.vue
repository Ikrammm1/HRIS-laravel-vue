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
      class="fixed top-0 right-0 w-full sm:w-[80%] lg:w-[60%] h-full bg-white shadow-xl z-50 transform transition-transform duration-300"
      :class="isOpen ? 'translate-x-0' : 'translate-x-full'"
    >

      <!-- Header -->
      <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold">{{title}} Employe</h2>

        <button
          class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-full"
          @click="closeSidebar"
        >
          ✕
        </button>
      </div>

      <!-- Content / Form -->
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
            <p v-if="touched.fullname && errors.fullname" class="text-red-500 text-sm mt-1">
              {{ errors.fullname }}
            </p>
          </div>

          <div class="mb-4">
            <CustomSelect
                 v-model="division"
                 :options="divisionList"
                 label="Division"
                 labelKey="div_name"
                 valueKey="div_code"
                 placeholder="Select Division"/>
            
          </div>
          <div class="mb-4">
            <CustomSelect
                 v-model="department"
                 :options="departmentList"
                 label="Department"
                 labelKey="dept_name"
                 valueKey="dept_code"
                 placeholder="Select Department"/>
            
          </div>

          <div class="mb-4">
            <label class="block font-medium">Position</label>
            <input
              type="text"
              v-model="position"
              @blur="touched.position = true"
              class="w-full mt-1 rounded p-2 border"
              placeholder="Position"
            />
            <p v-if="touched.position && errors.position" class="text-red-500 text-sm mt-1">
              {{ errors.position }}
            </p>
          </div>

          <div class="mb-4">
            <div class="grid grid-cols-3 gap-4">
              
              <!-- Gender -->
              <div>
                <CustomSelect
                 v-model="gender"
                 :options="genderList"
                 label="Gender"
                 labelKey="label"
                 valueKey="value"
                 placeholder="Select Gender"/>
                
              </div>

              <!-- Place of Birth -->
              <div>
                <label class="block font-medium">Place of Birth</label>
                <input
                  type="text"
                  v-model="place_of_birth"
                  @blur="touched.place_of_birth = true"
                  class="w-full mt-1 rounded p-2 border"
                  placeholder="Place of Birth"
                />
                <p
                  v-if="touched.place_of_birth && errors.place_of_birth"
                  class="text-red-500 text-sm mt-1"
                >
                  {{ errors.place_of_birth }}
                </p>
              </div>

              <!-- Date of Birth -->
              <div>
                <label class="block font-medium">Date of Birth</label>
                <input
                  type="date"
                  v-model="date_of_birth"
                  @blur="touched.date_of_birth = true"
                  class="w-full mt-1 rounded p-2 border"
                />
                <p
                  v-if="touched.date_of_birth && errors.date_of_birth"
                  class="text-red-500 text-sm mt-1"
                >
                  {{ errors.date_of_birth }}
                </p>
              </div>


            </div>
          </div>
          <div class="mb-4">
            <div class="grid grid-cols-2 gap-4">
              
              <!-- Religion -->
              <div>
                <CustomSelect
                 v-model="religion"
                 :options="religionList"
                 label="Religion"
                 labelKey="religion_name"
                 valueKey="religion_name"
                 placeholder="Select Religion"/>
                
              </div>

              <!-- Merital Status -->
              <div>
                <CustomSelect
                 v-model="merital_status"
                 :options="meritalStatusList"
                 label="Merital Status"
                 labelKey="status"
                 valueKey="status"
                 placeholder="Select Status"/>
                
              </div>


            </div>
          </div>

          <!-- Address -->
          <div class="mb-4">
            <label class="block font-medium">Address</label>
            <textarea
              v-model="address"
              @blur="touched.address = true"
              class="w-full mt-1 rounded p-2 border"
              placeholder="Address"
            />
            <p v-if="touched.address && errors.address" class="text-red-500 text-sm mt-1">
              {{ errors.address }}
            </p>
          </div>

          <div class="mb-4">
            <div class="grid grid-cols-2 gap-4">
              
              <!-- Direct Svp -->
              
              <div>
                <CustomSelect
                 v-model="direct_svp"
                 :options="svpList"
                 label="Direct SVP"
                 labelKey="full_name"
                 valueKey="id"
                 placeholder="Select Direct SVP"/>
                
              </div>

              <!-- Merital Status -->
              <div>
                <label class="block font-medium">Join Date</label>
                <input
                  type="date"
                  v-model="join_date"
                  @blur="touched.join_date = true"
                  class="w-full mt-1 rounded p-2 border"
                  placeholder="Join Date"
                />
                <p v-if="touched.join_date && errors.join_date" class="text-red-500 text-sm mt-1">
                  {{ errors.join_date }}
                </p>
              </div>


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
      // Form fields
      return {
        id: null,
        fullname: "",
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
        errors: {},
        touched: {
          fullname: false,
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
        }
      };
  },
  watch: {
    fullname() {
      if (this.touched.fullname) this.validateForm()
    },
   
    data: {
        immediate: true,
        handler(val) {
          if (this.action === 'update' && val) {
            this.id = val.id;
            this.fullname = val.full_name;
            this.division = val.div_code; 
            this.department = val.dept_code;
            this.position = val.position;

          }

          if (this.action === 'add') {
            this.resetForm();
          }
        }
    }
  },
  computed: {
    title() {
      return this.action === 'add'
        ? 'Add New Data'
        : 'Update Data'
    },
    submitText() {
      return this.action === 'add'
        ? 'Submit'
        : 'Update'
    },
     isFormValid() {
        if (this.action === 'update') {
          return (
            this.fullname !== '' &&
            this.division !== '' &&
            Object.keys(this.errors).length === 0
          )
        }

        // add
        return (
          this.fullname !== '' &&
          this.division !== '' &&
          this.department !== '' &&
          Object.keys(this.errors).length === 0
        )
      },

      divisionList() {
          return this.$store.state.divisions.datas;
      },
      departmentList() {
        return this.getDepartment(this.division);
      },
      religionList(){
         const religions = [{'id':1,'religion_name':'Islam'},
                {'id':2,'religion_name':'Kristen Protestan'},
                {'id':3,'religion_name':'Katolik'},
                {'id':4,'religion_name':'Hindu'},
                {'id':5,'religion_name':'Buddha'},
                {'id':6,'religion_name':'Konghucu'}];
         return religions;
      },
      meritalStatusList(){
         const maritalStatuses = [{'id':1,'status':'Single'},
                {'id':2,'status':'Married'},
                {'id':3,'status':'Divorced'}];
         return maritalStatuses;
      },
      svpList() {
          return this.$store.state.employe.datas;
      },
      genderList(){
        return [
          { value: 'L', label: 'Male' },
          { value: 'P', label: 'Female' }
        ]
      }
  },
  methods: {
   getDepartment(div_code) {
      return this.$store.state.departments.datas.filter(
        (dept) => dept.div_code === div_code
      );
    },
   validateForm() {
      this.errors = {};

      // fullname
      if (!this.fullname) {
        this.errors.fullname = 'fullname wajib diisi';
      }

      // division
      if (!this.division) {
        this.errors.division = 'Division wajib diisi';
      }

      // department
      if (!this.department) {
        if (this.action === 'add') {
          this.errors.department = 'Department wajib diisi';
        }
      }

      // position
      if (!this.position) {
        this.errors.position = 'Position wajib diisi';
      }

      // place of birth
      if (!this.place_of_birth) {
        this.errors.place_of_birth = 'Place of Birth wajib diisi';
      }

      // date of birth
      if (!this.date_of_birth) {
        this.errors.date_of_birth = 'Date of Birth wajib diisi';
      }

      // religion
      if (!this.religion) {
        this.errors.religion = 'Religion wajib diisi';
      }

      // marital status
      if (!this.merital_status) {
        this.errors.merital_status = 'Marital Status wajib diisi';
      }

      // address
      if (!this.address) {
        this.errors.address = 'Address wajib diisi';
      }

      // direct svp
      if (!this.direct_svp) {
        this.errors.direct_svp = 'Direct SVP wajib diisi';
      }

      // join date
      if (!this.join_date) {
        this.errors.join_date = 'Join Date wajib diisi';
      }
    },

    closeSidebar() {
      this.$emit("close");
      this.resetForm()
    },
    resetForm() {
      this.id = null;
      this.fullname = "";
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
      this.errors = {};
      Object.keys(this.touched).forEach(key => {
        this.touched[key] = false;
      });
    },


    submitForm() {
      Object.keys(this.touched).forEach(key => {
        this.touched[key] = true;
      });

      this.validateForm();

      if (Object.keys(this.errors).length > 0) return;

      const formData = new FormData();
      const user = JSON.parse(localStorage.getItem('authUser'));

      if (this.id) {
        formData.append('id', this.id);
      }
      formData.append('fullname', this.fullname);
      formData.append('division', this.division);
      formData.append('department', this.department);
      formData.append('position', this.position);
      formData.append('gender', this.gender);
      formData.append('place_of_birth', this.place_of_birth);
      formData.append('date_of_birth', this.date_of_birth);
      formData.append('religion', this.religion);
      formData.append('merital_status', this.merital_status);
      formData.append('address', this.address);
      formData.append('direct_svp', this.direct_svp);
      formData.append('join_date', this.join_date);
      if (user?.user.id) {
        formData.append('user_id', user.user.id);
      }

      const id = this.id;

      this.$store.commit("SET_LOADING", true);

      this.$store.dispatch("employe/process", { formData, id })
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
