<script setup>
/**
 * Composant InputError - Affichage des messages d'erreur de formulaire
 * Affiche un message d'erreur en rouge seulement si un message est fourni
 */

/**
 * Props du composant
 * @property {String} message - Message d'erreur à afficher
 */
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-600">
            {{ message }}
        </p>
    </div>
</template>
