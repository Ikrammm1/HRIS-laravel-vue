<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ title }}
            </h2>
        </template>

        <div class="py-4 h-screen overflow-y-auto pb-28 box-border">
            <div class="mx-auto max-w-7xl sm:px-2 lg:px-4">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                            <h2 class="text-2xl mb-4">{{ title }}</h2>
                            <div class="flex justify-start mb-4">
                                <button
                                    @click="openSidebar('add')"
                                    class="px-4 py-2 font-semibold text-white bg-teal-600 rounded-full hover:bg-teal-500 text-sm flex items-center"
                                >
                                    <Icon name="plus" class="inline-block mr-2 text-base" />
                                    Add New
                                </button>
                            </div>

                        <vue-good-table
                            :line-numbers="true"
                            :columns="columns"
                            :rows="departmentList"
                            :search-options="{ enabled: false }"
                            :pagination-options="{
                                enabled: true,
                                mode: 'records'
                            }"
                             theme="polar-bear"
                        >
                            <!-- Vue 3 slot syntax -->
                            <template #table-row="props">
                                <div
                                    v-if="props.column.field === 'icon'"
                                    class="flex items-center justify-center w-full h-full"
                                >
                                    <Icon :name="props.row.icon" class="text-md" />
                                </div>
                                <span v-else-if="props.column.field === 'division'">
                                    {{ 
                                        props.row.division?.div_name ?? '-'
                                        }}
                                </span>
                                <span
                                    v-else-if="props.column.field === 'action'"
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
import Icon from '@/Components/icons/Icon.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import menu from '@/store/modules/menu';
import SidebarForm from "./SidebarForm.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ConfirmDialog from '../../../Components/ConfirmDialog.vue'

export default {
    components: { AuthenticatedLayout, Icon, SidebarForm,  ConfirmDialog},

    data() {
        return {
                title: "Department Management",
                columns: [
                    {
                        label: 'Dept Code', // Kolom untuk nama
                        field: 'dept_code',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Code',
                        },
                    },
                    {
                        label: 'Department', // Kolom untuk deskripsi
                        field: 'dept_name',
                        sortable: false, // Deskripsi panjang mungkin tidak perlu di-sort
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Department',
                        },
                    },
                    {
                        label: 'Division', // Kolom untuk deskripsi
                        field: 'division',
                        sortable: false, // Deskripsi panjang mungkin tidak perlu di-sort
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Division',
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
            
                // Contoh Data (Rows)
                isSidebarOpen: false,
                actionType: 'add',   // add | update
                selectedData: null, // data row
                showConfirm: false,
                deleteId: null,
            };
    },
    computed: {
        departmentList() {
            return this.$store.state.departments.datas;
        },
    },
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

            this.$store.dispatch("departments/delete", { formData, id }).then(() => {
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
            this.onDelete(this.deleteId)
            this.showConfirm = false
        }
    },
    async created() {
        this.$store.commit("SET_LOADING", true);
        try {
            await this.$store.dispatch("departments/list");
            await this.$store.dispatch("divisions/list");
        } finally {
            this.$store.commit("SET_LOADING", false);
        }
    },
};
</script>
