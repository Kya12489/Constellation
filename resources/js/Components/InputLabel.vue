<script setup>
/**
 * Composant InputLabel - Étiquette pour les champs de formulaire
 * Affiche un label avec texte passé en prop ou slot
 */

/**
 * Props du composant
 * @property {String} value - Texte du label (si fourni, slot est ignoré)
 */
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block text-sm font-medium text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
