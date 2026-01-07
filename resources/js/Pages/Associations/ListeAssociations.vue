<script setup>
import Header from '@/Components/header.vue';
import { Head, Link } from '@inertiajs/vue3';
defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    assos: {
        type: Array,
    }
});
</script>

<template>
<Head title="listeAsso" />

<Header :can-login="canLogin" :can-register="canRegister" />

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
        >
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class=" text-black mb-4 text-2xl font-bold">Liste des Associations</h1>
                <ul>
                <li v-for="asso in assos" :key="asso.id">
                    {{ asso.nom }}
                </li>
            </ul>
            </div>
            
        </div>
    </div>
</div>
</template>