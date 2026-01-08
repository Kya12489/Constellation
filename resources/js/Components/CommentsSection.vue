<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import RatingDisplay from './RatingDisplay.vue';

const props = defineProps({
    association: {
        type: Object,
        required: true
    },
    comments: {
        type: Array,
        default: () => []
    },
    averageRating: {
        type: Number,
        default: null
    },
    totalRatings: {
        type: Number,
        default: 0
    },
    userComment: {
        type: Object,
        default: null
    },
    isAuthenticated: {
        type: Boolean,
        default: false
    }
});

const showCommentForm = ref(false);
const editingCommentId = ref(null);
const formData = ref({
    content: '',
    rating: 5
});

const isEditing = computed(() => editingCommentId.value !== null);

// Initialiser le formulaire avec le commentaire existant si l'utilisateur a déjà commenté
const initForm = () => {
    if (props.userComment) {
        formData.value = {
            content: props.userComment.content,
            rating: props.userComment.rating
        };
        editingCommentId.value = props.userComment.id;
        showCommentForm.value = true;
    } else {
        formData.value = {
            content: '',
            rating: 5
        };
        editingCommentId.value = null;
    }
};

const submitComment = () => {
    if (isEditing.value) {
        // Modifier le commentaire existant
        router.put(`/association/${props.association.rna_id}/comment/${editingCommentId.value}`, formData.value, {
            onSuccess: () => {
                showCommentForm.value = false;
                editingCommentId.value = null;
                formData.value = { content: '', rating: 5 };
            }
        });
    } else {
        // Créer un nouveau commentaire
        router.post(`/association/${props.association.rna_id}/comment`, formData.value, {
            onSuccess: () => {
                showCommentForm.value = false;
                formData.value = { content: '', rating: 5 };
            }
        });
    }
};

const deleteComment = (commentId) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
        router.delete(`/association/${props.association.rna_id}/comment/${commentId}`, {
            onSuccess: () => {
                editingCommentId.value = null;
                showCommentForm.value = false;
            }
        });
    }
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};

const getStars = (rating) => {
    return Array(5).fill(0).map((_, i) => i < rating);
};
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <!-- En-tête avec note moyenne -->
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-3">Avis et commentaires</h3>
            
            <div v-if="totalRatings > 0" class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-3xl font-bold text-gray-900">{{ averageRating }}</span>
                    <RatingDisplay :show-count="false" :rna-id="association.rna_id" star-size="8"/>
                </div>
                <span class="text-sm text-gray-600">{{ totalRatings }} avis</span>
            </div>
            <p v-else class="text-gray-500 text-sm">Aucun avis pour le moment</p>
        </div>

        <!-- Formulaire d'ajout/modification de commentaire (utilisateurs connectés) -->
        <div v-if="isAuthenticated" class="mb-6">
            <button
                v-if="!showCommentForm && !userComment"
                @click="showCommentForm = true; initForm()"
                class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Laisser un avis
            </button>

            <button
                v-if="!showCommentForm && userComment"
                @click="showCommentForm = true; initForm()"
                class="w-full px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Modifier mon avis
            </button>

            <!-- Formulaire -->
            <div v-if="showCommentForm" class="mt-4 border rounded-lg p-4 bg-gray-50">
                <form @submit.prevent="submitComment">
                    <!-- Sélection de la note -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Note <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <button
                                v-for="star in 5"
                                :key="star"
                                type="button"
                                @click="formData.rating = star"
                                class="focus:outline-none transition-transform hover:scale-110"
                            >
                                <svg
                                    class="w-8 h-8"
                                    :class="star <= formData.rating ? 'text-yellow-400' : 'text-gray-300'"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Commentaire <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            v-model="formData.content"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Partagez votre expérience avec cette association... (minimum 10 caractères)"
                            required
                            minlength="10"
                            maxlength="1000"
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">{{ formData.content.length }}/1000 caractères</p>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-2">
                        <button
                            type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                        >
                            {{ isEditing ? 'Modifier' : 'Publier' }}
                        </button>
                        <button
                            type="button"
                            @click="showCommentForm = false; editingCommentId = null"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors"
                        >
                            Annuler
                        </button>
                        <button
                            v-if="isEditing"
                            type="button"
                            @click="deleteComment(editingCommentId)"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                        >
                            Supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Message pour utilisateurs non connectés -->
        <div v-else class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800">
                <a href="/login" class="font-medium underline hover:text-blue-900">Connectez-vous</a>
                pour laisser un avis sur cette association.
            </p>
        </div>

        <!-- Liste des commentaires -->
        <div class="space-y-4">
            <div
                v-for="comment in comments"
                :key="comment.id"
                class="border rounded-lg p-4 hover:bg-gray-50 transition-colors"
            >
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ comment.user.name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ comment.user.name }}</p>
                            <p class="text-xs text-gray-500">{{ formatDate(comment.created_at) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <svg
                            v-for="(filled, i) in getStars(comment.rating)"
                            :key="i"
                            class="w-4 h-4"
                            :class="filled ? 'text-yellow-400' : 'text-gray-300'"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>
               

                <p class="text-gray-700 whitespace-pre-wrap">{{ comment.content }}</p>
            </div>

            <div v-if="comments.length === 0" class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p>Aucun commentaire pour le moment.</p>
                <p class="text-sm mt-1">Soyez le premier à laisser un avis !</p>
            </div>
        </div>
    </div>
</template>