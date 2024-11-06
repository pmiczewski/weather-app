<script setup lang="ts">
import LocationForm from "@/Components/LocationForm.vue";

const storeLocation = (form) => {
    form.post(route('location.store'), {
        onSuccess: () => form.reset(),
    })
}
</script>

<template>
    <section>
       <location-form :button-handler="storeLocation" button-text="SAVE">
           <template #header-title>
               Create Location
           </template>
           <template #header-subtitle>
               Save your location of preference to get weather forecast
           </template>
       </location-form>
    </section>
</template>
