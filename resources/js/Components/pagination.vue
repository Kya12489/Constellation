<script setup>
import { computed } from 'vue';

const props = defineProps({
    page: {
        type: Number,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
    perPage: {
        type: Number,
        default: 10,
    },
    loading: {
        type: Boolean,
        default: false,
    }
});

const emit = defineEmits(['update:page', 'previous', 'next']);

// Calculer s'il y a une page suivante
const hasNextPage = computed(() => {
    return (props.page + 1) * props.perPage < props.total;
});

// Calculer les informations d'affichage
const displayInfo = computed(() => {
    const start = (props.page * props.perPage) + 1;
    const end = Math.min((props.page + 1) * props.perPage, props.total);
    return { start, end };
});

// Gestion du clic précédent
const handlePrevious = () => {
    if (props.page > 0 && !props.loading) {
        emit('update:page', props.page - 1);
        emit('previous');
    }
};

// Gestion du clic suivant
const handleNext = () => {
    if (hasNextPage.value && !props.loading) {
        emit('update:page', props.page + 1);
        emit('next');
    }
};
</script>

<template>
    <div class="flex items-center justify-between border-t pt-6">
        <!-- Bouton Précédent -->
        <button
            v-if="page > 0"
            @click="handlePrevious"
            :disabled="loading"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
            ← Précédent
        </button>
        <div v-else class="w-24"></div>
        
        <!-- Info page -->
        <div class="text-center">
            <p class="text-sm text-gray-600">
                Page <span class="font-bold text-gray-900">{{ page + 1 }}</span>
            </p>
            <p class="text-xs text-gray-500 mt-1">
                {{ displayInfo.start }}-{{ displayInfo.end }} sur {{ total }} résultats
            </p>
        </div>
        
        <!-- Bouton Suivant -->
        <button
            v-if="hasNextPage"
            @click="handleNext"
            :disabled="loading"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
            Suivant →
        </button>
        <div v-else class="w-24"></div>
    </div>
</template>