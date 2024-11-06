<script setup lang="ts">

import { Forecast } from "@/types/forecast";

defineProps<{
    forecasts?: Array<Forecast>;
}>();

const getWeatherIconUrl = (iconId: Forecast) => {
    const iconUrl = import.meta.env.VITE_OPEN_WEATHER_ICON_URL
    return `${iconUrl}${iconId}.png`;
}

</script>

<template>
    <section>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                <tr class="bg-gray-200 text-gray-600">
                    <th class="py-2 px-4 border" colspan="2">Description</th>
                    <th class="py-2 px-4 border">Temperature</th>
                    <th class="py-2 px-4 border">Min Temperature</th>
                    <th class="py-2 px-4 border">Max Temperature</th>
                    <th class="py-2 px-4 border">Date</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="forecast in forecasts" :key="forecast.id" class="hover:bg-gray-100">
                    <td class="py-2 px-4 border" colspan="2">
                        <img :src="getWeatherIconUrl(forecast.weather_icon_id)">
                        {{ forecast.weather_description }}
                    </td>
                    <td class="py-2 px-4 border">{{ forecast.temperature }}</td>
                    <td class="py-2 px-4 border">{{ forecast.min_temperature }}</td>
                    <td class="py-2 px-4 border">{{ forecast.max_temperature }}</td>
                    <td class="py-2 px-4 border">{{ forecast.date }}</td>
                </tr>
                <tr v-if="!forecasts?.length">
                    <td class="px-6 py-4 border-t" colspan="7">Could not find any forecast</td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>
