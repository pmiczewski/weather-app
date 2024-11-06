<script setup lang="ts">

import { Head } from "@inertiajs/vue3";
import ForecastList from "@/Pages/Forecast/Partials/ForecastList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Location } from "@/types/location";

defineProps<{
    location?: Location;
}>();

</script>

<template>
    <Head title="Location"/>

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <div class="mb-4">
                        <h3 class="text-lg font-medium">City: {{ location?.city }}</h3>
                        <h3 class="text-lg font-medium">State: {{ location?.state }}</h3>
                    </div>
                    <forecast-list :forecasts="location?.forecasts"></forecast-list>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
