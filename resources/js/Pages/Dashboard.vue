<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

/**
 * * Variables
 */
const props = defineProps({
    totalUser: Number,
    totalCity: Number,
    totalDutyTrip: Number,
    totalDutyTripProposed: Number,
    totalDutyTripApproved: Number,
    totalDutyTripRejected: Number,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 justify-center items center gap-5">
                    <div class="bg-white rounded-tl-[2rem] rounded-br-[2rem] shadow-lg hover:shadow-xl p-6">
                        <div class="flex flex-col">
                            <div class="text-gray-900 text-left">Total Master City</div>
                        </div>
                        <div class="flex flex-col justify-end items-end">
                            <div class="text-gray-900 font-semibold text-2xl text-left">{{ props.totalCity }}</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-tl-[2rem] rounded-br-[2rem] shadow-lg hover:shadow-xl p-6">
                        <div class="flex flex-col">
                            <div class="text-gray-900 text-left">Total User/Employee</div>
                        </div>
                        <div class="flex flex-col justify-end items-end">
                            <div class="text-gray-900 font-semibold text-2xl text-left">{{ props.totalUser }}</div>
                        </div>
                    </div>
                    <div></div>
                    <div></div>
                    <div class="bg-white rounded-tl-[2rem] rounded-br-[2rem] shadow-lg hover:shadow-xl p-6">
                        <div class="flex flex-col">
                            <div class="text-gray-900 text-left">Total Duty Trip Proposal</div>
                        </div>
                        <div class="flex flex-col justify-end items-end">
                            <div class="text-gray-900 font-semibold text-2xl text-left">{{ props.totalDutyTrip }}</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-tl-[2rem] rounded-br-[2rem] shadow-lg hover:shadow-xl p-6">
                        <div class="flex flex-col">
                            <div class="text-gray-900 text-left">Total Duty Trip Proposed</div>
                        </div>
                        <div class="flex flex-col justify-end items-end">
                            <div class="text-gray-900 font-semibold text-2xl text-left">{{ props.totalDutyTripProposed }}</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-tl-[2rem] rounded-br-[2rem] shadow-lg hover:shadow-xl p-6">
                        <div class="flex flex-col">
                            <div class="text-gray-900 text-left">Total Duty Trip Approved</div>
                        </div>
                        <div class="flex flex-col justify-end items-end">
                            <div class="text-gray-900 font-semibold text-2xl text-left">{{ props.totalDutyTripApproved }}</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-tl-[2rem] rounded-br-[2rem] shadow-lg hover:shadow-xl p-6">
                        <div class="flex flex-col">
                            <div class="text-gray-900 text-left">Total Duty Trip Rejected</div>
                        </div>
                        <div class="flex flex-col justify-end items-end">
                            <div class="text-gray-900 font-semibold text-2xl text-left">{{ props.totalDutyTripRejected }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
