<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="sendApplication" class="px-4 py-5 bg-white sm:p-6 shadow">
                        <h1>Заявка на буквенное имя</h1>
                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="name" value="Имя отправителя"/>
                            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name"/>
                            <jet-input-error v-if="errors" :message="errors.name" class="mt-2"/>
                        </div>
                        <div class="pt-4">
                            <jet-button type="submit">Отправить</jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetButton from '@/Jetstream/Button.vue'

export default defineComponent({
    components: {
        AppLayout,
        JetInput,
        JetInputError,
        JetLabel,
        JetButton,
    },
    data() {
        return {
            form: {
                name: '',
            },
            errors: {}
        }
    },
    methods: {
        async sendApplication() {
            try {
                const {data} = await window.axios.post('/api/applications', this.form);
                this.clearForm();
                window.Swal.fire(data.message, '', 'success')
            } catch (e) {
                const { status, data } = e.response;
                if (status === 422) {
                    this.errors = data.errors
                }
            }
        },
        clearForm() {
            this.form.name = ''
            this.errors = {}
        }
    }
})
</script>
