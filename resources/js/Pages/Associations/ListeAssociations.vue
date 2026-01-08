<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import Pagination from '@/Components/pagination.vue'; // P majuscule
import Header from '@/Components/header.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    assos: {
        type: Array,
        default: () => []
    },
    currentPage: { 
        type: Number,
        default: 1
    },
    total: {
        type: Number,
        default: 0
    }
});

// Variable de page locale
const page = ref(props.currentPage);

// Watcher pour naviguer quand la page change
watch(page, (newPage) => {
    router.visit(`/associations?page=${newPage}`, {
        preserveScroll: true,
        preserveState: true,
    });
});
</script>

<template>
    <Head title="Liste des Associations" />
    <Header :can-login="canLogin" :can-register="canRegister" />
    
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">Liste des Associations</h1>
                    
                    <!-- Liste -->
                    <ul class="space-y-3 mb-6">
                        <li  v-for="asso in assos" :key="asso.id_association" class="border p-4 rounded-lg hover:bg-gray-50 flex lg:col-start-2 lg:justify-center ">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg">{{ asso.title }}</h3>
                            <p class="text-black/50">{{ asso.object }}</p>
                            <p>Créée le {{ asso.creation_date }}</p>
                            <p>Adresse: {{ asso.street_name_manager }}, {{ asso.pc_address_manager }} {{ asso.country_address_manager }}</p>
                            </div>
                            <div class=" ">
                                <a v-if="asso.website "
                                    :href=" asso.website"
                                    class="ml-4 rounded-md px-3 py-2 text-blue-500 ring-1 ring-transparent transition focus:outline-none"
                                >
                                    Visiter le site
                                </a>
                                <p v-else class="ml-4 rounded-md px-3 py-2 text-blue-500 ring-1 ring-transparent transition focus:outline-none">
                                    Aucun site web.
                                </p>

                            </div>
                        </li>
                    </ul>
                    
                    <!-- Pagination simple -->
                    <Pagination 
                        v-model:page="page" 
                        :total="total"
                        :per-page="10"
                    />
                </div>
            </div>
        </div>
    </div>
</template>