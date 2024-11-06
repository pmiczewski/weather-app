<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

defineProps<{
    buttonHandler: Function;
    buttonText: String;
}>();

const form = useForm({
    city: '',
    state: '',
});

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                <slot name="header-title"/>
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                <slot name="header-subtitle"/>
            </p>
        </header>

        <form
            @submit.prevent="buttonHandler(form)"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="city" value="City"/>

                <TextInput
                    id="city"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.city"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.city"/>
            </div>

            <div>
                <InputLabel for="state" value="State"/>

                <TextInput
                    id="state"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.state"
                    required
                />

                <InputError class="mt-2" :message="form.errors.state"/>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">{{ buttonText }}</PrimaryButton>
            </div>
        </form>
    </section>
</template>
