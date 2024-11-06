<script lang="ts" setup>

import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ForecastForm from "@/Pages/Forecast/Partials/ForecastForm.vue";
import ForecastList from "@/Pages/Forecast/Partials/ForecastList.vue";
import { Forecast } from "@/types/forecast";

defineProps<{
    forecasts?: Array<Forecast>;
}>();

</script>

<template>
    <Head title="Forecasts"/>

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <forecast-form></forecast-form>
                </div>
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <forecast-list :forecasts></forecast-list>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
