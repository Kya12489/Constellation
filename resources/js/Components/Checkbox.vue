<script setup>
/**
 * Composant Checkbox - Case à cocher réutilisable
 * Utilise v-model pour la liaison bidirectionnelle avec un array ou boolean
 */
import { computed } from 'vue';

/**
 * Emit du composant
 * @event update:checked Émis quand l'état de la case change
 */
const emit = defineEmits(['update:checked']);

/**
 * Props du composant
 * @property {Array|Boolean} checked - Valeur courante de la case (array pour checkbox groupe, bool pour simple)
 * @property {*} value - Valeur associée à cette case
 */
const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
    />
</template>
