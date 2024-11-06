<script setup lang="ts">

import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import LocationList from "@/Pages/Location/Partials/LocationList.vue";
import LocationStore from "@/Pages/Location/Partials/LocationStore.vue";
import { Location } from "@/types/location";

defineProps<{
    locations?: Array<Location>;
    location?: Location;
}>();

</script>

<template>
    <Head title="Locations"/>

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <location-store></location-store>
                </div>
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <location-list :locations></location-list>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
