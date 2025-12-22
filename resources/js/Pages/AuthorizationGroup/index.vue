<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Menu Management
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
                            :rows="menuList"
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

                               <div
                                    v-else-if="props.column.field == 'users'"
                                    class="flex flex-wrap gap-2 max-w-[200px]" 
                                >
                                    <span v-for="value in props.row.users" :key="value.id" class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                        {{ value.name }}
                                    </span>
                                </div>

                                <div
                                    v-else-if="props.column.field == 'menus'"
                                    class="flex flex-wrap gap-2 max-w-[250px]"
                                >
                                    <span v-for="value in props.row.menus" :key="value.id" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10">
                                        {{ value.menu_name }}
                                    </span>
                                </div>



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
import { showConfirmDialog } from '@rmsjs/vue3-dialog-tw'
import ConfirmDialog from '../../Components/ConfirmDialog.vue'

export default {
    components: { AuthenticatedLayout, Icon, SidebarForm,  ConfirmDialog},

    data() {
        return {
                title: "Authorization Group",
                columns: [
                    {
                        label: 'Name', // Kolom untuk nama
                        field: 'name',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Nama',
                        },
                    },
                    {
                        label: 'Description', // Kolom untuk deskripsi
                        field: 'description',
                        sortable: false, // Deskripsi panjang mungkin tidak perlu di-sort
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Filter Deskripsi',
                        },
                    },
                    {
                        label: 'Users', // Kolom untuk URL
                        field: 'users',
                        sortable: false,
                        width: '300px',
                       
                    },
                    {
                        label: 'Menu', // Kolom untuk URL
                        field: 'menus',
                        sortable: false,
                        width: '300px',
                       
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
                isSidebarOpen: false,
                actionType: 'add',   // add | update
                selectedData: null, // data row
                showConfirm: false,
                deleteId: null,
            };
    },
    computed: {
        menuList() {
            return this.$store.state.authorizationGroup.datas;
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

            this.$store.dispatch('authorizationGroup/delete', id).then(() => {
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
            await this.$store.dispatch("authorizationGroup/list");
            await this.$store.dispatch("users/list");
            await this.$store.dispatch("menu/menuList");
        } finally {
            this.$store.commit("SET_LOADING", false);
        }
    },
};
</script>
