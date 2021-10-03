<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Заявки
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-5">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-3 py-3">
                    <div class="flex gap-6">
                        <div class="col-span-4 sm:col-span-4">
                            <jet-label for="name" value="Имя отправителя"/>
                            <jet-input id="name" type="text" v-model="filter.name" />
                        </div>
                        <div class="col-span-4 sm:col-span-4">
                            <jet-label for="status_id" value="Cтатус"/>
                            <select
                                id="status_id"
                                v-model="filter.status_id"
                                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option value="">Выберите статус</option>
                                <option v-for="status in applicationStatuses" :value="status.id">{{ status.name }}</option>
                            </select>
                        </div>
                        <div class="col-span-4 sm:col-span-4">
                            <jet-label for="created_at" value="Дата заявки"/>
                            <jet-input id="created_at" type="date" v-model="filter.created_at" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-col">
                        <div class="w-full">
                            <div class="border-b border-gray-200 shadow">
                                <table class="w-full divide-y divide-gray-300 ">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-2 text-xs text-gray-500">
                                                ID
                                            </th>
                                            <th class="px-6 py-2 text-xs text-gray-500">
                                                Name
                                            </th>
                                            <th class="px-6 py-2 text-xs text-gray-500">
                                                Статус
                                            </th>
                                            <th class="px-6 py-2 text-xs text-gray-500">
                                                Коментарий
                                            </th>
                                            <th class="px-6 py-2 text-xs text-gray-500">
                                                Дата заявки
                                            </th>
                                            <th class="px-6 py-2 text-xs text-gray-500">
                                                Действия
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-300">
                                        <tr v-for="application in applications" class="whitespace-nowrap text-center">
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ application.id }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    {{ application.name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-500">{{ application.status?.name }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-500">{{ application.comment }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ application.created_at }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <template v-if="!application.status_id">
                                                    <div class="pb-3">
                                                        <button @click="approveApplication(application.id)" class="px-4 py-1 text-sm text-green-600 bg-green-200 rounded-full">Принять</button>
                                                    </div>
                                                    <button @click="rejectApplication(application.id)" class="px-4 py-1 text-sm text-red-600 bg-red-200 rounded-full">Отклонить</button>
                                                </template>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';

export default {
    name: "Applications",
    components: {
        AppLayout,
        JetInput,
        JetLabel,
    },
    data() {
        return {
            applications: [],
            applicationStatuses: [],
            filter: {
                name: '',
                status_id: '',
                created_at: '',
            }
        };
    },
    computed: {
      params() {
          return {
              ...this.filter
          }
      }
    },
    mounted() {
        this.getApplications();
        this.getApplicationStatuses();
    },
    methods: {
        async getApplications() {
            try {
                const { data } = await axios.get('/api/applications', {params: this.params});
                this.applications = data.applications;
            } catch(e) {
                throw e;
            }
        },
        async getApplicationStatuses() {
            const {data} = await axios.get('/api/applications/statuses');
            this.applicationStatuses = data.application_statuses;
        },
        async approveApplication(id) {
            await window.axios.put(`/api/applications/${id}/approve`);
            await this.getApplications();
        },
        async rejectApplication(id) {
            window.Swal.fire({
                title: 'Напишите причину',
                input: 'textarea',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Отклонить',
                cancelButtonText: 'Закрыть',
                showLoaderOnConfirm: true,
                preConfirm: (comment) => {
                    return window.axios.put(`/api/applications/${id}/reject`, {comment})
                        .catch(err => {
                            const {status, data} =err.response;
                            if (status === 422) {
                                Object.values(data.errors).forEach((error) => {
                                    Swal.showValidationMessage( error[0])
                                });
                            } else {
                                Swal.showValidationMessage(
                                    `Request failed: ${err}`
                                )
                            }

                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    this.getApplications();
                }
            });
        },
    },
    watch: {
        filter: {
            deep: true,
            handler() {
                this.getApplications();
            }
        }
    }
}
</script>

<style scoped>

</style>
