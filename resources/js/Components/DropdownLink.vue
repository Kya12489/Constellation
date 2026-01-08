<script setup>
/**
 * Composant DropdownLink - Lien à l'intérieur d'un dropdown/menu
 * Utilisé dans les menus déroulants pour les actions contextuelles
 */
import { Link } from '@inertiajs/vue3';

/**
 * Props du composant
 * @property {String} href - URL cible du lien (obligatoire)
 */
defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
    >
        <slot />
    </Link>
</template>
