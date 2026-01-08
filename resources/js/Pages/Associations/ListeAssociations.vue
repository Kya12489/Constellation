<script setup>
import { ref, watch, onMounted, nextTick } from 'vue';
import Header from '@/Components/header.vue';
import PaginationWidget from '@/Components/pagination.vue';
import { Head } from '@inertiajs/vue3';
import RnaAPI from '/App/Services/Api/RnaAPI';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const assos = ref([]);
const page = ref(0);


//variable de filtre
const filterTitle = ref('');
const filterKeyWords = ref('');
const filterCity = ref('');

const total = ref(0);
const loading = ref(false);
const error = ref(null);

// Référence pour l'input
const inputRef = ref(null);

// Timer pour le debounce
let debounceTimer = null;

// Fonction pour charger les associations
const fetchAssociations = async (maintainFocus = false) => {
    // Sauvegarder l'élément actif si nécessaire
    const activeElement = maintainFocus ? document.activeElement : null;
    
    loading.value = true;
    error.value = null;
    
    const filters = {};
    if (filterTitle.value) {
        filters.title = filterTitle.value;
    }

    if (filterKeyWords.value) {
        filters.keyWords = filterKeyWords.value;
    }

    if (filterCity.value) {
        filters.city = filterCity.value;
    }
    
    const result = await RnaAPI.searchAssociations(page.value, 10, filters);
    
    if (result.success) {
        // Traiter les sites web
        assos.value = RnaAPI.processWebsites(result.results);
        total.value = result.total;
    } else {
        error.value = result.error.message;
        console.error('Erreur détaillée:', result.error);
    }
    
    loading.value = false;
    
    // Restaurer le focus si l'input était actif
    if (maintainFocus && activeElement === inputRef.value) {
        await nextTick();
        inputRef.value?.focus();
    }
};

// Watcher pour le filtre avec debounce
watch(filterTitle, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        page.value = 0; // Réinitialiser à la page 0
        fetchAssociations(true); // Maintenir le focus
    }, 500);
});
watch(filterKeyWords, () => {
    
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        page.value = 0; // Réinitialiser à la page 0
        fetchAssociations(true); // Maintenir le focus
    }, 500);
});

watch(filterCity, () => {
    
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        page.value = 0; // Réinitialiser à la page 0
        fetchAssociations(true); // Maintenir le focus
    }, 500);
});


// Watcher pour la pagination
watch(page, () => {
    fetchAssociations(false); // Pas de focus pour la pagination
});

// Charger les données au montage
onMounted(() => {
    fetchAssociations();
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
                    
                    <!-- Filtre -->
                    <div class="mb-6 flex  gap-4">
                        <input 
                            ref="inputRef"
                            type="text" 
                            placeholder="Filtrer par nom..." 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            v-model="filterTitle"
                            :disabled="loading"
                        />
                        <input 
                            ref="inputRef"
                            type="text" 
                            placeholder="Mots-clés (mot1,mot2,mot3)..." 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            v-model="filterKeyWords"
                            :disabled="loading"
                        />
                        <input
                        ref="inputRef"
                            placeholder="Recherche par ville..."
                            type="text"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            v-model="filterCity"
                            :disabled="loading"
                        />
                    </div>
                    
                    <!-- Loader -->
                    <div v-if="loading" class="text-center py-12">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        <p class="mt-4 text-gray-600 font-medium">Chargement des associations...</p>
                    </div>
                    
                    <!-- Erreur -->
                    <div v-else-if="error" class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded mb-6">
                        <p class="font-bold">Erreur</p>
                        <p>{{ error }}</p>
                        <button 
                            @click="fetchAssociations" 
                            class="mt-2 text-sm underline hover:no-underline"
                        >
                            Réessayer
                        </button>
                    </div>
                    
                    <!-- Liste des associations -->
                    <template v-else>
                        <div v-if="assos.length === 0" class="text-center py-12 text-gray-500">
                            <p class="text-lg">Aucune association trouvée</p>
                            <p class="text-sm mt-2">Essayez de modifier votre recherche</p>
                        </div>
                        
                        <ul v-else class="space-y-4 mb-8">
                            <li v-for="asso in assos" :key="asso.id_association" 
                                class="border border-gray-200 p-5 rounded-lg hover:shadow-md transition-shadow flex flex-col lg:flex-row gap-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg text-gray-900 mb-2">{{ asso.title }}</h3>
                                    <p class="text-gray-600 mb-2">{{ asso.object }}</p>
                                    <div class="text-sm text-gray-500 space-y-1">
                                        <p v-if="asso.creation_date">
                                            <span class="font-medium">Créée le:</span> {{ asso.creation_date }}
                                        </p>
                                        <p>
                                            <span class="font-medium">Adresse:</span> 
                                            {{ asso.street_number_asso }} {{ asso.street_type_asso }} {{ asso.street_name_asso ? asso.street_name_asso : asso.comp_address_asso}}, 
                                            {{ asso.pc_address_manager ? asso.pc_address_manager : asso.pc_address_asso }} FRANCE
                                            
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start flex-col justify-center lg:items-center">
                                    <a v-if="asso.website && asso.website !== 'Invalid'"
                                        :href="asso.website"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="px-4 py-2 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors inline-flex items-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Visiter le site
                                    </a>
                                    <span v-else-if="asso.website === 'Invalid'" 
                                       class="px-4 py-2 text-gray-400 text-sm">
                                        Site invalide
                                    </span>
                                    <span v-else class="px-4 py-2 text-gray-400 text-sm">
                                        Pas de site web
                                    </span>
                                    <a
                                        :href="'/association/insert/' + asso.id"
                                        class="ml-4 px-4 py-2 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors inline-flex items-center gap-2"
                                    >
                                        Voir détails
                                    </a>
                                </div>
                            </li>
                        </ul>
                        
                        <!-- Pagination -->
                        <PaginationWidget
                            v-model:page="page"
                            :total="total"
                            :per-page="10"
                            :loading="loading"
                        />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>