<script setup lang="ts">
import LocationForm from "@/Components/LocationForm.vue";

const fetchForecast = (form) => {
    form.post(route('forecast.fetch'))
}
</script>

<template>
    <section>
        <location-form :button-handler="fetchForecast" button-text="FETCH">
            <template #header-title>
                Fetch forecast
            </template>
            <template #header-subtitle>
                Fetch weather forecast for specified location
            </template>
        </location-form>
    </section>
</template>
