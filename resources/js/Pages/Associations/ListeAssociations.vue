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
        default: () => [] // Ajoutez une valeur par défaut
    }
});
</script>

<template>
    <Head title="listeAsso" />
    <Header :can-login="canLogin" :can-register="canRegister" />
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-black mb-4 text-2xl font-bold">
                        Liste des Associations ({{ assos.length }})
                    </h1>
                    
                    <!-- Debug: Affichez tout l'objet -->
                    <div v-if="assos.length === 0" class="text-red-500">
                        Aucune association trouvée
                    </div>
                    
                    <ul class="space-y-2">
                        <li v-for="asso in assos" :key="asso.id_association" class="border p-3 rounded">
                            <strong>{{ asso.id }}</strong><br>
                            
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>