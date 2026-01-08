<script setup>
import { ref } from 'vue';
import Header from '@/Components/header.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <Header :canLogin="true" :canRegister="false" />
    <div>
        
        <div class="min-h-screen bg-gray-100">
            <!-- Header Component -->
            
            
            <!-- Page Heading -->
            <header
                class="bg-white shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
            
            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>