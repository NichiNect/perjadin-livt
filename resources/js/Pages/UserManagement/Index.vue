<script setup>
import { ref, onMounted, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Button from '@/Components/Base/Button.vue';
import Pagination from '@/Components/Base/Pagination.vue';
import TextInput from '@/Components/TextInput.vue';
import ModalConfirm from '@/Components/Base/Modal/ModalConfirm.vue';
import ModalSide from '@/Components/Base/Modal/ModalSide.vue';
// import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faXmark } from '@fortawesome/free-solid-svg-icons';
import InputLabel from '@/Components/InputLabel.vue';
import SelectOptionInput from '@/Components/SelectOptionInput.vue';
import axios from 'axios';
import Loading from '@/Components/Base/Loading.vue';


/**
 * * Variables
 */
const props = defineProps({
    user: Object,
    flash: Object,
    errors: Object
});

const loading = ref(false);
const modalDeleteShow = ref(false);
const modalCreateShow = ref(false);
const modalUpdateShow = ref(false);

const paginateData = ref({
  total: 100,
  paginateRow: 10,
  pageActive: 1
});
const search = ref('');
const selectedData = ref(null);
const roleOptions = ref([]);
const form = useForm({
    name: '',
    email: '',
    role_id: 0,
    password: ''
});


/**
 * * Methods
 */
const onPaginateChange = async (data) => {

    paginateData.value = data;
    router.get(`/users`, {
        page: data.pageActive
    });
}


/**
 * * Method Handlers
 */
const deleteHandler = async (data, confirmation = false) => {

    modalDeleteShow.value = true;
    selectedData.value = data;

    if (confirmation == true) {
        console.log('remove data', data);

        router.delete(`/users/${data?.id}`);

        modalDeleteShow.value = false;
    }
}

const createNewHandler = async () => {

    loading.value = true;

    try {
        form.post('/users', {
            onSuccess: () => {
                modalCreateShow.value = false;
                form.reset();
            }
        });
    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
    }
}

const updateHandler = async (data, confirmation = false) => {

    modalUpdateShow.value = true;
    selectedData.value = data;

    if (confirmation == true) {

        loading.value = true;

        try {
            form.put(`/users/${data.id}`, {
                onSuccess: () => {
                    modalUpdateShow.value = false;
                    form.reset();
                }
            });
        } catch (err) {
            console.error(err);
        } finally {
            loading.value = false;
        }
    } else {
        // Set Default Data
        form.name = data.name;
        form.email = data.email;
        form.role_id = data.role_id;
    }
}

/**
 * * Hooks & Watchers
 */
onMounted(() => {
    paginateData.value = {
        total: props.user?.total,
        paginateRow: props.user?.per_page,
        pageActive: props.user?.current_page,
    }
});

let debounceSearchStateTimer;
watch(search, () => {

    clearTimeout(debounceSearchStateTimer);
    debounceSearchStateTimer = setTimeout( async () => {
        router.get(`/users`, {
            page: paginateData.value?.pageActive,
            search: search.value
        });
    }, 800);
});

watch ([modalCreateShow, modalUpdateShow], async () => {

    if (modalCreateShow.value == true || modalUpdateShow.value == true) {

        loading.value = true;
        try {
            const { data } = await axios.get('/api/picklist/role');

            if (data?.data) {
                roleOptions.value = data.data;
            }
        } catch (err) {
            console.error('Failed to fetch picklist role', err);
        } finally {
            loading.value = false;
        }
    }
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Management</h2>
        </template>

        <div v-if="$page.props.flash?.message" class="container rounded py-3 px-4 my-5" :class="props.flash?.class">
            <div class="flex flex-row justify-between">
                <p class="text-white">{{ props.flash?.message }}</p>
                <!-- <FontAwesomeIcon :icon="faXmark" class="text-white w-3 hover:cursor-pointer" @click="(e) => e.target.parentNode.parentNode.remove()" /> -->
                <FontAwesomeIcon :icon="faXmark" class="text-white w-3 hover:cursor-pointer" @click="() => props.flash.message = null" />
            </div>
        </div>

        <div class="my-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg py-4 px-4">
                    <div class="flex flex-row justify-between my-2">
                        <div class="w-1/4 flex flex-row gap-3">
                            <TextInput
                                type="text"
                                class="mt-1 block w-full"
                                v-model="search"
                                placeholder="Search.."
                            />
                        </div>
                        <div>
                            <Button
                                color="primary"
                                size="sm"
                                @click="() => modalCreateShow = true"
                            >
                                Add New User
                            </Button>
                        </div>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs font-semibold text-gray-800 uppercase bg-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Role
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in props.user?.data" class="bg-white border-b hover:bg-gray-100">
                                    <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ (props.user?.current_page - 1) * props.user?.per_page + index + 1 }}
                                    </th>
                                    <th scope="row" class="px-6 py-4">
                                        {{ item.name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ item.email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="border border-green-500 rounded-lg py-1 px-3 mx-4 text-sm text-green-500">
                                            {{ item?.role?.name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-wrap justify-center items-center gap-2">
                                            <Button
                                                color="warning"
                                                size="xs"
                                                @click="updateHandler(item)"
                                            >
                                                Edit
                                            </Button>
                                            <Button
                                                color="danger"
                                                size="xs"
                                                @click="deleteHandler(item)"
                                            >
                                                Delete
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <Pagination
                            :total="paginateData.total"
                            :paginateRow="paginateData.paginateRow"
                            :pageActive="paginateData.pageActive"
                            @onChange="onPaginateChange"
                        />
                    </div>
                </div>
            </div>
        </div>

        <ModalConfirm 
            :show="modalDeleteShow"
            :noAction="false"
            @onClose="() => modalDeleteShow = false"
            @onSubmit="deleteHandler(selectedData, true)"
            >
            <template #title>Are you sure?</template>

            <template #content>
                <p class="text-center">Want to delete this user</p>
            </template>
        </ModalConfirm>

        <ModalSide
            :show="modalCreateShow"
            width="40%"
            @onClose="() => modalCreateShow = false"
        >
        <template #title>
            <h6 class='text-xl font-semibold text-gray-600'>Create New User</h6>
            <p class='text-md text-gray-400 leading-4 mt-1'>Lorem ipsum dolor sit amet consectetur.</p>
            </template>

            <template #content>
                <Loading v-if="loading" class="p-20" />
                <form v-else @submit.prevent="createNewHandler" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <InputLabel for="name" value="Name" />
                        <TextInput
                            id="name"
                            type="text"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <div v-if="props.errors.name" class="text-danger text-sm">{{ props.errors.name }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="email"
                        />
                        <div v-if="props.errors.email" class="text-danger text-sm">{{ props.errors.email }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="role" value="Role" />
                        <SelectOptionInput
                            id="role_id"
                            v-model="form.role_id"
                            required
                            :options="roleOptions"
                        />
                        <div v-if="props.errors.role_id" class="text-danger text-sm">{{ props.errors.role_id }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="password" value="Password" />
                        <TextInput
                            id="password"
                            type="password"
                            v-model="form.password"
                            autocomplete="current-password"
                        />
                        <div v-if="props.errors.password" class="text-danger text-sm">{{ props.errors.password }}</div>
                    </div>
                    <div class="flex flex-col justify-end content-end">
                        <Button
                            type="submit"
                            color="primary"
                            size="sm"
                            :disabled="form.processing"
                        >
                            Submit
                        </Button>
                    </div>
                </form>
            </template>
        </ModalSide>

        <ModalSide
            :show="modalUpdateShow"
            width="40%"
            @onClose="() => modalUpdateShow = false"
        >
        <template #title>
            <h6 class='text-xl font-semibold text-gray-600'>Create New User</h6>
            <p class='text-md text-gray-400 leading-4 mt-1'>Lorem ipsum dolor sit amet consectetur.</p>
            </template>

            <template #content>
                <Loading v-if="loading" class="p-20" />
                <form v-else @submit.prevent="updateHandler(selectedData, true)" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <InputLabel for="updateName" value="Name" />
                        <TextInput
                            id="updateName"
                            type="text"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <div v-if="props.errors.name" class="text-danger text-sm">{{ props.errors.name }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="updateEmail" value="Email" />
                        <TextInput
                            id="updateEmail"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="email"
                        />
                        <div v-if="props.errors.email" class="text-danger text-sm">{{ props.errors.email }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="updateRole_id" value="Role" />
                        <SelectOptionInput
                            id="updateRole_id"
                            v-model="form.role_id"
                            required
                            :options="roleOptions"
                        />
                        <div v-if="props.errors.role_id" class="text-danger text-sm">{{ props.errors.role_id }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="updatePassword" value="Password" />
                        <TextInput
                            id="updatePassword"
                            type="password"
                            v-model="form.password"
                            autocomplete="current-password"
                        />
                        <div v-if="props.errors.password" class="text-danger text-sm">{{ props.errors.password }}</div>
                    </div>
                    <div class="flex flex-col justify-end content-end">
                        <Button
                            type="submit"
                            color="primary"
                            size="sm"
                            :disabled="form.processing"
                        >
                            Submit
                        </Button>
                    </div>
                </form>
            </template>
        </ModalSide>
    </AuthenticatedLayout>
</template>
