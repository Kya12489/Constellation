<script setup>
import { ref, onMounted, computed } from 'vue';
import Header from '@/Components/header.vue';
import MapWidget from '@/Components/mapWidget.vue';
import CommentsSection from '@/Components/CommentsSection.vue';
import { Head, usePage } from '@inertiajs/vue3';
import RnaAPI from '@/Services/Api/RnaAPI';
import axios from 'axios';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    association: Object,
    favoris: Array,
    comments: Array,
    averageRating: Number,
    totalRatings: Number,
    userComment: Object,
});

const page = usePage();
const isAuthenticated = computed(() => page.props.auth?.user != null);

const infoAssoApi = ref(null);
const loading = ref(true);
const error = ref(null);

onMounted(async () => {
    console.log("test"+isAuthenticated);
    try {
        console.log("Récupération de l'association avec l'ID :", props.association.rna_id);
        
        const response = await RnaAPI.getAssociationById(props.association.rna_id);
        
        console.log('Response complète:', response);
        
        if (response && response.success && response.data) {
            infoAssoApi.value = response.data;
            console.log('Association trouvée:', infoAssoApi.value);
        } else {
            error.value = 'Association non trouvée';
            console.error('Association non trouvée ou erreur:', response);
        }
    } catch (e) {
        error.value = 'Erreur lors du chargement';
        console.error('Erreur:', e);
    } finally {
        loading.value = false;
    }
});

const makeFav = function(){
    route.put("/user/favoris/add/"+props.association.id);

}
const removeFav = function(){
    route.delete("/user/favoris/remove/"+props.association.id);
}
</script>

<template>
    <Head title="Détails de l'association" />
    <Header :can-login="canLogin" :can-register="canRegister" />
    
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Grid avec 2 colonnes sur grand écran -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Colonne de gauche : Informations -->
                <div class="space-y-6">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <!-- Loading -->
                        <div v-if="loading" class="p-12 text-center">
                            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                            <p class="mt-4 text-gray-600">Chargement des détails...</p>
                        </div>

                        <!-- Erreur -->
                        <div v-else-if="error" class="p-6 text-center">
                            <p class="text-lg text-red-600 font-bold">❌ {{ error }}</p>
                            <p class="text-sm text-gray-500 mt-2">RNA ID: {{ association.rna_id }}</p>
                        </div>

                        <!-- Contenu -->
                        <div v-else-if="infoAssoApi" class="p-6">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800">
                                {{ infoAssoApi.title || 'Association' }}
                                <button
                                    @click="favoris.isFavoris ? removeFav() : makeFav()"
                                    :class="favoris.isFavoris ? 'text-red-500' : 'text-gray-400'"
                                >
                                    ❤
                                </button>
                            </h2>
                            
                            <!-- Tableau -->
                            <table class="w-full">
                                <tbody class="divide-y divide-gray-200">
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 font-bold w-1/3 text-gray-700">SIRET:</td>
                                        <td class="py-3 text-gray-600">{{ infoAssoApi.siret || 'N/A' }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 font-bold text-gray-700">RNA ID:</td>
                                        <td class="py-3 text-gray-600">{{ infoAssoApi.id || association.rna_id }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 font-bold text-gray-700">Objet:</td>
                                        <td class="py-3 text-gray-600">{{ infoAssoApi.object || 'N/A' }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 font-bold text-gray-700">Adresse:</td>
                                        <td class="py-3 text-gray-600">
                                            {{ infoAssoApi.street_number_asso }} 
                                            {{ infoAssoApi.street_type_asso }} 
                                            {{ infoAssoApi.street_name_asso || infoAssoApi.comp_address_asso || 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 font-bold text-gray-700">Code postal:</td>
                                        <td class="py-3 text-gray-600">{{ infoAssoApi.pc_address_asso || 'N/A' }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 font-bold text-gray-700">Ville:</td>
                                        <td class="py-3 text-gray-600">{{ infoAssoApi.com_name_asso || 'N/A' }}</td>
                                    </tr>
                                    <tr v-if="infoAssoApi.creation_date" class="hover:bg-gray-50">
                                        <td class="py-3 font-bold text-gray-700">Date de création:</td>
                                        <td class="py-3 text-gray-600">{{ infoAssoApi.creation_date }}</td>
                                    </tr>
                                    <tr v-if="infoAssoApi.website" class="hover:bg-gray-50">
                                        <td class="py-3 font-bold text-gray-700">Site web:</td>
                                        <td class="py-3">
                                            <a v-if="infoAssoApi.website !== 'Invalid'"
                                               :href="infoAssoApi.website" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               class="text-blue-600 hover:underline inline-flex items-center gap-1">
                                                {{ infoAssoApi.website }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                            <span v-else class="text-red-500">Site invalide</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Section commentaires et notes -->
                    <CommentsSection
                        v-if="!loading && !error"
                        :association="association"
                        :comments="comments"
                        :average-rating="averageRating"
                        :total-ratings="totalRatings"
                        :user-comment="userComment"
                        :is-authenticated="isAuthenticated"
                    />
                </div>

                <!-- Colonne de droite : Carte -->
                <div v-if="infoAssoApi && !loading && !error" class="lg:sticky lg:top-6 h-fit">
                    <MapWidget 
                        :association="infoAssoApi"
                        :show-user-location="true"
                        height="600px"
                    />
                </div>
            </div>
        </div>
    </div>
</template>