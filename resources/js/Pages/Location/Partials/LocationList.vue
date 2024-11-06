<script setup lang="ts">

import DangerButton from "@/Components/DangerButton.vue";
import { router } from "@inertiajs/vue3";
import { Location } from "@/types/location";

defineProps<{
    locations?: Array<Location>;
}>();

</script>

<template>
    <section>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                <tr class="bg-gray-200 text-gray-600">
                    <th class="py-2 px-4 border">City</th>
                    <th class="py-2 px-4 border">state</th>
                    <th class="py-2 px-4 border">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="location in locations" :key="location.id" class="hover:bg-gray-100 cursor-pointer"
                    @click="router.get(route('location.show', location.id))">
                    <td class="py-2 px-4 border">{{ location.city }}</td>
                    <td class="py-2 px-4 border">{{ location.state }}</td>
                    <td class="py-2 px-4 border text-center">
                        <DangerButton
                            class="ms-3"
                            @click.stop="router.delete(route('location.destroy', location.id))"
                        >
                            Delete
                        </DangerButton>
                    </td>
                </tr>
                <tr v-if="!locations?.length">
                    <td class="px-6 py-4 border-t" colspan="7">Could not find any location</td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>
