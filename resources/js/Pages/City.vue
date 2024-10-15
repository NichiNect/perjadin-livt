<script setup>
import { ref, onMounted, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Button from '@/Components/Base/Button.vue';
import Pagination from '@/Components/Base/Pagination.vue';
import TextInput from '@/Components/TextInput.vue';
import ModalConfirm from '@/Components/Base/Modal/ModalConfirm.vue';
import ModalSide from '@/Components/Base/Modal/ModalSide.vue';
import { faXmark } from '@fortawesome/free-solid-svg-icons';
import InputLabel from '@/Components/InputLabel.vue';
import SelectOptionInput from '@/Components/SelectOptionInput.vue';
import axios from 'axios';
import Loading from '@/Components/Base/Loading.vue';
import Checkbox from '@/Components/Checkbox.vue';


/**
 * * Variables
 */
const props = defineProps({
    city: Object,
    flash: Object,
    errors: Object,
    auth: Object
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
const provinceOptions = ref([]);
const form = useForm({
    province_id: 0,
    name: '',
    island: '',
    latitude: 0,
    longitude: 0,
    is_foreign_country: false,
});


/**
 * * Methods
 */
const onPaginateChange = async (data) => {

    paginateData.value = data;
    router.get(`/cities`, {
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

        router.delete(`/cities/${data?.id}`);

        modalDeleteShow.value = false;
    }
}

const createNewHandler = async () => {

    loading.value = true;

    try {
        form.post('/cities', {
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
            form.put(`/cities/${data.id}`, {
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
        form.province_id = data.province_id;
        form.name = data.name;
        form.island = data.island;
        form.latitude = data.latitude;
        form.longitude = data.longitude;
        form.is_foreign_country = data.is_foreign_country;
    }
}

/**
 * * Hooks & Watchers
 */
onMounted(() => {
    paginateData.value = {
        total: props.city?.total,
        paginateRow: props.city?.per_page,
        pageActive: props.city?.current_page,
    }
});

let debounceSearchStateTimer;
watch(search, () => {

    clearTimeout(debounceSearchStateTimer);
    debounceSearchStateTimer = setTimeout( async () => {
        router.get(`/cities`, {
            page: paginateData.value?.pageActive,
            search: search.value
        });
    }, 800);
});

watch ([modalCreateShow, modalUpdateShow], async () => {

    if (modalCreateShow.value == true || modalUpdateShow.value == true) {

        loading.value = true;
        try {
            const { data } = await axios.get('/api/picklist/province');

            if (data?.data) {
                provinceOptions.value = data.data;
            }
        } catch (err) {
            console.error('Failed to fetch picklist province', err);
        } finally {
            loading.value = false;
        }
    } else {
        form.reset();
    }
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master City</h2>
        </template>

        <div v-if="props.flash?.message" class="container rounded py-3 px-4 my-5" :class="props.flash?.class">
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
                        <div v-if="props.auth.can['master_city.create']">
                            <Button
                                color="primary"
                                size="sm"
                                @click="() => modalCreateShow = true"
                            >
                                Add New City
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
                                        City Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Province
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Island
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Foreign Country
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Latitude
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Longitude
                                    </th>
                                    <th 
                                        v-if="props.auth.can['master_city.edit'] || props.auth.can['master_city.delete']"
                                        scope="col" class="px-6 py-3"
                                    >
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in props.city?.data" class="bg-white border-b hover:bg-gray-100">
                                    <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ (props.city?.current_page - 1) * props.city?.per_page + index + 1 }}
                                    </th>
                                    <th scope="row" class="px-6 py-4">
                                        {{ item.name }}
                                    </th>
                                    <td class="px-6 py-4 capitalize">
                                        {{ item?.province?.name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ item.island }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ item.is_foreign_country ? 'Yes' : 'No' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ item.latitude }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ item.longitude }}
                                    </td>
                                    <td 
                                        v-if="props.auth.can['master_city.edit'] || props.auth.can['master_city.delete']"
                                        class="px-6 py-4 text-right"
                                    >
                                        <div class="flex flex-wrap justify-center items-center gap-2">
                                            <Button
                                                v-if="props.auth.can['master_city.edit']"
                                                color="warning"
                                                size="xs"
                                                @click="updateHandler(item)"
                                            >
                                                Edit
                                            </Button>
                                            <Button
                                                v-if="props.auth.can['master_city.delete']"
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
                <p class="text-center">Want to delete this city</p>
            </template>
        </ModalConfirm>

        <ModalSide
            :show="modalCreateShow"
            width="40%"
            @onClose="() => modalCreateShow = false"
        >
        <template #title>
            <h6 class='text-xl font-semibold text-gray-600'>Create New City</h6>
            <p class='text-md text-gray-400 leading-4 mt-1'>Lorem ipsum dolor sit amet consectetur.</p>
            </template>

            <template #content>
                <Loading v-if="loading" class="p-20" />
                <form v-else @submit.prevent="createNewHandler" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <InputLabel for="name" value="City Name" />
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
                        <InputLabel for="island" value="Island Name" />
                        <TextInput
                            id="island"
                            type="text"
                            v-model="form.island"
                            required
                            autocomplete="island"
                        />
                        <div v-if="props.errors.island" class="text-danger text-sm">{{ props.errors.island }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="province_id" value="Province" />
                        <SelectOptionInput
                            id="province_id"
                            v-model="form.province_id"
                            required
                            :options="provinceOptions"
                        />
                        <div v-if="props.errors.province_id" class="text-danger text-sm">{{ props.errors.province_id }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="latitude" value="Latitude" />
                        <TextInput
                            id="latitude"
                            type="text"
                            v-model="form.latitude"
                            required
                            autocomplete="latitude"
                        />
                        <div v-if="props.errors.latitude" class="text-danger text-sm">{{ props.errors.latitude }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="longitude" value="Longitude" />
                        <TextInput
                            id="longitude"
                            type="text"
                            v-model="form.longitude"
                            required
                            autocomplete="longitude"
                        />
                        <div v-if="props.errors.longitude" class="text-danger text-sm">{{ props.errors.longitude }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <div class="flex flex-row justify-start items-center gap-2">
                            <InputLabel for="is_foreign_country" value="Foreign Country" />
                            <Checkbox
                                name="remember"
                                v-model:checked="form.is_foreign_country"
                            />
                        </div>
                        <div v-if="props.errors.is_foreign_country" class="text-danger text-sm">{{ props.errors.is_foreign_country }}</div>
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
            <h6 class='text-xl font-semibold text-gray-600'>Create New City</h6>
            <p class='text-md text-gray-400 leading-4 mt-1'>Lorem ipsum dolor sit amet consectetur.</p>
            </template>

            <template #content>
                <Loading v-if="loading" class="p-20" />
                <form v-else @submit.prevent="updateHandler(selectedData, true)" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <InputLabel for="name" value="City Name" />
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
                        <InputLabel for="island" value="Island Name" />
                        <TextInput
                            id="island"
                            type="text"
                            v-model="form.island"
                            required
                            autocomplete="island"
                        />
                        <div v-if="props.errors.island" class="text-danger text-sm">{{ props.errors.island }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="province_id" value="Province" />
                        <SelectOptionInput
                            id="province_id"
                            v-model="form.province_id"
                            required
                            :options="provinceOptions"
                        />
                        <div v-if="props.errors.province_id" class="text-danger text-sm">{{ props.errors.province_id }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="latitude" value="Latitude" />
                        <TextInput
                            id="latitude"
                            type="text"
                            v-model="form.latitude"
                            required
                            autocomplete="latitude"
                        />
                        <div v-if="props.errors.latitude" class="text-danger text-sm">{{ props.errors.latitude }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="longitude" value="Longitude" />
                        <TextInput
                            id="longitude"
                            type="text"
                            v-model="form.longitude"
                            required
                            autocomplete="longitude"
                        />
                        <div v-if="props.errors.longitude" class="text-danger text-sm">{{ props.errors.longitude }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <div class="flex flex-row justify-start items-center gap-2">
                            <InputLabel for="is_foreign_country" value="Foreign Country" />
                            <Checkbox
                                name="remember"
                                v-model:checked="form.is_foreign_country"
                            />
                        </div>
                        <div v-if="props.errors.is_foreign_country" class="text-danger text-sm">{{ props.errors.is_foreign_country }}</div>
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
