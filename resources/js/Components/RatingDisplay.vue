<script setup>
/**
 * Composant RatingDisplay - Affichage des évaluations sous forme d'étoiles
 * 
 * Récupère et affiche la note moyenne d'une association avec les étoiles et le nombre d'avis
 * Les données sont chargées via une API lors du montage du composant
 */
import { ref, onMounted, computed } from 'vue';
import RnaAPI from '/App/Services/Api/RnaAPI';

/**
 * Props du composant
 * @property {String} rnaId - Identifiant RNA de l'association (obligatoire)
 * @property {Boolean} showCount - Afficher le nombre d'avis
 * @property {String} starSize - Taille des étoiles en Tailwind (4, 6, 8, etc.)
 */
const props = defineProps({
    rnaId: {
        type: String,
        required: true
    },
    showCount: {
        type: Boolean,
        default: true
    },
    starSize: {
        type: String,
        default: '4'
    }
});

const averageRating = ref(null);
const totalRatings = ref(0);
const loading = ref(true);

onMounted(async () => {
    const result = await RnaAPI.getRateData(props.rnaId);
    
    if (result.success) {
        averageRating.value = result.averageRating;
        totalRatings.value = result.totalRatings;
    }
    
    loading.value = false;
});

const stars = computed(() => {
    if (!averageRating.value) return Array(5).fill(false);
    const rating = Math.round(averageRating.value);
    return Array(5).fill(0).map((_, i) => i < rating);
});

console.log(props.starSize);
</script>

<template>
    <div class="flex items-center gap-2">
        <!-- Loading -->
        <div v-if="loading" class="flex gap-1">
            <div class="w-4 h-4 bg-gray-200 rounded animate-pulse"></div>
            <div class="w-4 h-4 bg-gray-200 rounded animate-pulse"></div>
            <div class="w-4 h-4 bg-gray-200 rounded animate-pulse"></div>
            <div class="w-4 h-4 bg-gray-200 rounded animate-pulse"></div>
            <div class="w-4 h-4 bg-gray-200 rounded animate-pulse"></div>
        </div>

        <!-- Étoiles -->
        <div v-else class="flex items-center gap-1">
            <div class="flex gap-1">
                <svg
                    v-for="(filled, i) in stars"
                    :key="i"
                    :class="['w-' + starSize, 'h-' + starSize, filled ? 'text-yellow-400' : 'text-gray-300']"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </div>
            
            <!-- Note moyenne et nombre d'avis -->
            <div v-if="showCount" class="flex items-center gap-1 text-xs text-gray-600">
                <span class="font-medium">{{ averageRating }}</span>
                <span v-if="showCount">({{ totalRatings }})</span>
            </div>
            <span v-if="totalRatings <= 0" class="text-xs text-gray-400">Pas d'avis</span>
        </div>
    </div>
</template>