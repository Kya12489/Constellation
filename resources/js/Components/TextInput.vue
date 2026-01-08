<script setup>
/**
 * Composant TextInput - Champ de texte réutilisable avec v-model
 * 
 * Enveloppe un input HTML avec styling Tailwind et support du focus automatique
 */
import { onMounted, ref } from 'vue';

/**
 * Modèle v-model du composant
 * @property {String} model - Valeur du champ de texte (bind bidirectionnel)
 */
const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        v-model="model"
        ref="input"
    />
</template>
