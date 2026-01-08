<script setup>
/**
 * Composant NavLink - Lien de navigation dans la barre de navigation
 * S'active selon la route actuelle et applique les styles appropriés
 */
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

/**
 * Props du composant
 * @property {String} href - URL cible du lien
 * @property {Boolean} active - Indique si le lien est actif (optionnel)
 */
const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

/**
 * Computed - Classes CSS dynamiques selon l'état actif
 * @returns {String} Classes Tailwind appropriées
 */
const classes = computed(() =>
    props.active
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
