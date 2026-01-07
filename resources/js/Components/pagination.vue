<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    page: {
        type: Number,
        required: true,
    },
})
</script>

<template>
    <div class="flex items-center justify-center space-x-2">
        <Link
            v-if="page > 1"
            :href="`?page=${page - 1}`"  
            
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
        >
            Previous
        </Link>
        
        <span class="px-4 py-2 bg-gray-100 text-gray-500 rounded">
            Page {{ page }}
        </span>
        
        <Link
            :href="`?page=${page + 1}`"  
      
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
        >
            Next
        </Link>
    </div>
</template>