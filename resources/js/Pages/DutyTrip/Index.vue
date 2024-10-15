<script setup>
import { ref, onMounted, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Button from '@/Components/Base/Button.vue';
import Pagination from '@/Components/Base/Pagination.vue';
import TextInput from '@/Components/TextInput.vue';
import ModalSide from '@/Components/Base/Modal/ModalSide.vue';
import { faArrowRight, faEye, faXmark } from '@fortawesome/free-solid-svg-icons';
import InputLabel from '@/Components/InputLabel.vue';
import SelectOptionInput from '@/Components/SelectOptionInput.vue';
import axios from 'axios';
import Loading from '@/Components/Base/Loading.vue';
import { DateTime } from "luxon";
import ModalDefault from '@/Components/Base/Modal/ModalDefault.vue';


/**
 * * Variables
 */
const props = defineProps({
    dutyTripProposal: Object,
    flash: Object,
    errors: Object,
    auth: Object
});

const loading = ref(false);
const modalCreateShow = ref(false);
const modalDetailShow = ref(false);

const paginateData = ref({
  total: 100,
  paginateRow: 10,
  pageActive: 1
});
const search = ref('');
const statusFilter = ref('');

const selectedData = ref({
    applicant_user_id: 0,
    from_city_id: 0,
    destination_city_id: 0,
});
const cityOptions = ref([]);
const userOptions = ref([]);
const form = useForm({
    from_city_id: 0,
    destination_city_id: 0,
    description: '',
    start_date: '',
    final_date: '',
    applicant_user_id: 0,
});

let urlParams = new URLSearchParams(window.location.search);
let statusFilterQuery = urlParams.get('statusFilter');
console.log(urlParams.get('statusFilter'));


/**
 * * Methods
 */
const onPaginateChange = async (data) => {

    paginateData.value = data;
    router.get(`/cities`, {
        page: data.pageActive
    });
}

const formatRupiah = (val) => {

    if (val && val != null) {

        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(val);
    }

    return '-';
}


/**
 * * Method Handlers
 */
const detailHandler = (data) => {
    
    modalDetailShow.value = true;
    selectedData.value = data;
}

const createNewHandler = async () => {

    loading.value = true;

    try {
        form.post('/duty-trip-proposals', {
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

const approveOrRejectHandler = async (approvalStatus) => {
    // approvalStatus needs boolean value
    loading.value = true;

    try {
        router.post(`/duty-trip-proposals/change-status`, {
            id: selectedData.value?.id,
            approvalStatus
        });
    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
        modalDetailShow.value = false;
    }
}

/**
 * * Hooks & Watchers
 */
onMounted(() => {
    paginateData.value = {
        total: props.dutyTripProposal?.total,
        paginateRow: props.dutyTripProposal?.per_page,
        pageActive: props.dutyTripProposal?.current_page,
    }

});

let debounceSearchStateTimer;
watch(search, () => {

    clearTimeout(debounceSearchStateTimer);
    debounceSearchStateTimer = setTimeout( async () => {
        router.get(`/duty-trip-proposals`, {
            page: paginateData.value?.pageActive,
            search: search.value
        });
    }, 800);
});

watch(statusFilter, (newVal, oldVal) => {

    if (newVal != oldVal) {
        router.get('/duty-trip-proposals', {
            page: paginateData.value?.pageActive,
            statusFilter: statusFilter.value,
            search: search.value,
        });
    }
});

watch ([modalCreateShow, modalDetailShow], async () => {

    if (modalCreateShow.value == true || modalDetailShow.value == true) {

        loading.value = true;
        try {
            const { data } = await axios.get('/api/picklist/city');

            if (data?.data) {
                cityOptions.value = data.data;
            }
        } catch (err) {
            console.error('Failed to fetch picklist city', err);
        } finally {
            loading.value = false;
        }

        if (props.auth?.user?.role_id != 3) {  // not `Staff`

            loading.value = true;
            try {
                const { data } = await axios.get('/api/picklist/user');

                if (data?.data) {
                    userOptions.value = data.data;
                }
            } catch (err) {
                console.error('Failed to fetch picklist user', err);
            } finally {
                loading.value = false;
            }
        }
        
    } else {
        form.reset();
    }
});

</script>

<template>
    <Head title="Duty Trip Proposals" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Duty Trip Proposals</h2>
        </template>

        <div v-if="$page.props.flash?.message" class="container rounded py-3 px-4 my-5" :class="props.flash?.class">
            <div class="flex flex-row justify-between">
                <p class="text-white">{{ props.flash?.message }}</p>
                <!-- <FontAwesomeIcon :icon="faXmark" class="text-white w-3 hover:cursor-pointer" @click="(e) => e.target.parentNode.parentNode.remove()" /> -->
                <FontAwesomeIcon :icon="faXmark" class="text-white w-3 hover:cursor-pointer" @click="() => props.flash.message = null" />
            </div>
        </div>

        <div class="mt-10 mb-0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg py-3 px-4">
                    <div class="flex flex-row items-center gap-1">
                        <Button
                            :variant="statusFilter == 'all' || statusFilterQuery == 'all' ? 'solid' : 'outline'"
                            type="submit"
                            color="primary"
                            size="xs"
                            @click="() => statusFilter = 'all'"
                        >
                            All
                        </Button>
                        <Button
                            :variant="statusFilter == 'proposed' || statusFilterQuery == 'proposed' ? 'solid' : 'outline'"
                            type="submit"
                            color="warning"
                            size="xs"
                            @click="() => statusFilter = 'proposed'"
                        >
                            Proposed
                        </Button>
                        <Button
                            :variant="statusFilter == 'approved' || statusFilterQuery == 'approved' ? 'solid' : 'outline'"
                            type="submit"
                            color="success"
                            size="xs"
                            @click="() => statusFilter = 'approved'"
                        >
                            Approved
                        </Button>
                        <Button
                            :variant="statusFilter == 'rejected' || statusFilterQuery == 'rejected' ? 'solid' : 'outline'"
                            type="submit"
                            color="danger"
                            size="xs"
                            @click="() => statusFilter = 'rejected'"
                        >
                            Rejected
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg py-4 px-4">
                    <div class="flex flex-row justify-between items-center my-2">
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
                                Add New Proposals
                            </Button>
                        </div>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg scroll_control">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs font-semibold text-gray-800 uppercase bg-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Ticket Number
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Applicant
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        City
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in props.dutyTripProposal?.data" class="bg-white border-b hover:bg-gray-100">
                                    <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ (props.dutyTripProposal?.current_page - 1) * props.dutyTripProposal?.per_page + index + 1 }}
                                    </th>
                                    <th scope="row" class="px-6 py-4">
                                        {{ item?.ticket_number }}
                                    </th>
                                    <th scope="row" class="px-6 py-4">
                                        {{ item?.applicant_user?.name }}
                                    </th>
                                    <td class="px-6 py-4 capitalize">
                                        <div class="flex flex-row gap-2">
                                            {{ item?.from_city?.name }}
                                            <FontAwesomeIcon :icon="faArrowRight" class="w-3" />
                                            {{ item?.destination_city?.name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-row justify-center gap-2">
                                            {{ DateTime.fromFormat(item.start_date, 'yyyy-MM-dd').toFormat('dd-LLL-yyyy') }}
                                            <FontAwesomeIcon :icon="faArrowRight" class="w-3" />
                                            {{ DateTime.fromFormat(item.final_date, 'yyyy-MM-dd').toFormat('dd-LLL-yyyy') }}
                                        </div>
                                        <span>({{ DateTime.fromFormat(item.final_date, 'yyyy-MM-dd').diff(DateTime.fromFormat(item.start_date, 'yyyy-MM-dd'), ['days']).toObject()?.days }} days)</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span v-if="item?.status == 'approved'" class="border border-green-500 rounded-lg py-1 px-3 mx-4 text-sm text-green-500 uppercase">
                                            {{ item?.status }}
                                        </span>
                                        <span v-else-if="item?.status == 'rejected'" class="border border-red-500 rounded-lg py-1 px-3 mx-4 text-sm text-red-500 uppercase">
                                            {{ item?.status }}
                                        </span>
                                        <span v-else-if="item?.status == 'proposed'" class="border border-yellow-500 rounded-lg py-1 px-3 mx-4 text-sm text-yellow-500 uppercase">
                                            {{ item?.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-wrap justify-center items-center gap-2">
                                            <Button
                                                color="success"
                                                size="sm"
                                                variant="squareIcon"
                                                :icon="faEye"
                                                @click="detailHandler(item)"
                                            >
                                                <FontAwesomeIcon :icon="faEye" class="w-4" />
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

        <ModalSide
            :show="modalCreateShow"
            width="40%"
            @onClose="() => modalCreateShow = false"
        >
        <template #title>
            <h6 class='text-xl font-semibold text-gray-600'>Create New Duty Trip Proposal</h6>
            <p class='text-md text-gray-400 leading-4 mt-1'>Lorem ipsum dolor sit amet consectetur.</p>
            </template>

            <template #content>
                <Loading v-if="loading" class="p-20" />
                <form v-else @submit.prevent="createNewHandler" class="flex flex-col gap-4">
                    <div v-if="props.auth?.user?.role_id != 3" class="flex flex-col gap-1">
                        <InputLabel for="applicant_user_id" value="Employee" />
                        <SelectOptionInput
                            id="applicant_user_id"
                            v-model="form.applicant_user_id"
                            required
                            :options="userOptions"
                        />
                        <div v-if="props.errors.applicant_user_id" class="text-danger text-sm">{{ props.errors.applicant_user_id }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="from_city_id" value="From City" />
                        <SelectOptionInput
                            id="from_city_id"
                            v-model="form.from_city_id"
                            required
                            :options="cityOptions"
                        />
                        <div v-if="props.errors.from_city_id" class="text-danger text-sm">{{ props.errors.from_city_id }}</div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="destination_city_id" value="Destination City" />
                        <SelectOptionInput
                            id="destination_city_id"
                            v-model="form.destination_city_id"
                            required
                            :options="cityOptions"
                        />
                        <div v-if="props.errors.destination_city_id" class="text-danger text-sm">{{ props.errors.destination_city_id }}</div>
                    </div>
                    <div class="flex flex-row gap-3">
                        <div class="w-full flex flex-col gap-1">
                            <InputLabel for="start_date" value="Start Date" />
                            <input 
                                type="date" 
                                id="start_date" 
                                v-model="form.start_date"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            />
                            <div v-if="props.errors.start_date" class="text-danger text-sm">{{ props.errors.start_date }}</div>
                        </div>
                        <div class="w-full flex flex-col gap-1">
                            <InputLabel for="final_date" value="Final Date" />
                            <input 
                                type="date" 
                                id="final_date" 
                                v-model="form.final_date"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            />
                            <div v-if="props.errors.final_date" class="text-danger text-sm">{{ props.errors.final_date }}</div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="description" value="Reasons" />
                        <textarea
                            id="description"
                            type="text"
                            v-model="form.description"
                            required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        />
                        <div v-if="props.errors.description" class="text-danger text-sm">{{ props.errors.description }}</div>
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

        <ModalDefault
            :show="modalDetailShow"
            @onClose="() => modalDetailShow = false"
        >
            <template #title>Ticket {{ selectedData?.ticket_number }}</template>

            <template #content>
                <div class="flex flex-col gap-4">
                    <div v-if="props.auth?.user?.role_id != 3" class="flex flex-col gap-1">
                        <InputLabel for="applicant_user_id" value="Employee" />
                        <SelectOptionInput
                            id="applicant_user_id"
                            v-model="selectedData.applicant_user_id"
                            required
                            :options="userOptions"
                            disabled
                        />
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="from_city_id" value="From City" />
                        <SelectOptionInput
                            id="from_city_id"
                            v-model="selectedData.from_city_id"
                            required
                            :options="cityOptions"
                            disabled
                        />
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="destination_city_id" value="Destination City" />
                        <SelectOptionInput
                            id="destination_city_id"
                            v-model="selectedData.destination_city_id"
                            required
                            :options="cityOptions"
                            disabled
                        />
                    </div>
                    <div class="flex flex-row gap-3">
                        <div class="w-full flex flex-col gap-1">
                            <InputLabel for="start_date" value="Start Date" />
                            <input 
                                type="date" 
                                id="start_date" 
                                :value="selectedData?.start_date"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                disabled
                            />
                        </div>
                        <div class="w-full flex flex-col gap-1">
                            <InputLabel for="final_date" value="Final Date" />
                            <input 
                                type="date" 
                                id="final_date" 
                                :value="selectedData?.final_date"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                disabled
                            />
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel for="description" value="Reasons" />
                        <textarea
                            id="description"
                            type="text"
                            :value="selectedData?.description"
                            required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            disabled
                        />
                    </div>
                    <div v-if="props.auth?.user?.role_id != 3" class="bg-gray-200 rounded">
                        <div class="grid grid-cols-3 gap-3 bg-blue-300 rounded-t text-center py-1">
                            <div>Total Day</div>
                            <div>Allowance Info</div>
                            <div>Total Allowance</div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 items-center text-center py-2">
                            <div v-if="selectedData">
                                {{ DateTime.fromFormat(selectedData?.final_date ?? '', 'yyyy-MM-dd').diff(DateTime.fromFormat(selectedData?.start_date ?? '', 'yyyy-MM-dd'), ['days']).toObject()?.days }} days
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <div>{{ selectedData?.distance }} KM</div>
                                <div>{{ formatRupiah(selectedData?.allowance_per_day) }} / day</div>
                            </div>
                            <div>
                                {{ formatRupiah(selectedData?.total_allowance) }}
                            </div>
                        </div>
                    </div>
                    <div 
                        v-if="selectedData?.status == 'proposed' && $page.props.auth.can['duty_trip.resolve']"
                        class="flex flex-row justify-center gap-4"
                    >
                        <Button
                            type="submit"
                            color="danger"
                            size="sm"
                            @click="approveOrRejectHandler(0)"
                        >
                            Reject
                        </Button>
                        <Button
                            type="submit"
                            color="primary"
                            size="sm"
                            @click="approveOrRejectHandler(1)"
                        >
                            Approve
                        </Button>
                    </div>
                </div>
            </template>
        </ModalDefault>
    </AuthenticatedLayout>
</template>
