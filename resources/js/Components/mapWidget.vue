<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import MapAPI from '/App/Services/Api/MapAPI';

const props = defineProps({
    association: {
        type: Object,
        required: true
    },
    showUserLocation: {
        type: Boolean,
        default: true
    },
    height: {
        type: String,
        default: '400px'
    }
});

const associationCoords = ref(null);
const userCoords = ref(null);
const loading = ref(true);
const error = ref(null);
const distance = ref(null);
const mapContainer = ref(null);
let map = null;
let markers = [];

// Limites de la France métropolitaine
const FRANCE_BOUNDS = [
    [41.33, -5.14],  // Sud-Ouest
    [51.09, 9.56]    // Nord-Est
];

onMounted(async () => {
    try {
        // Charger Leaflet CSS et JS
        await loadLeaflet();
        
        // 1. Obtenir les coordonnées de l'association
        console.log('Récupération des coordonnées de l\'association...');
        const assoResult = await MapAPI.getAssociationCoordinates(props.association);
        
        if (!assoResult.success) {
            error.value = 'Impossible de localiser l\'association';
            loading.value = false;
            return;
        }
        
        associationCoords.value = assoResult;
        console.log('Coordonnées association:', assoResult);
        
        // 2. Obtenir la position de l'utilisateur si demandé
        if (props.showUserLocation) {
            console.log('Récupération de la position utilisateur...');
            const userResult = await MapAPI.getUserLocation();
            
            if (userResult.success) {
                userCoords.value = userResult;
                console.log('Coordonnées utilisateur:', userResult);
                
                // Calculer la distance
                distance.value = MapAPI.calculateDistance(
                    userCoords.value.latitude,
                    userCoords.value.longitude,
                    associationCoords.value.latitude,
                    associationCoords.value.longitude
                );
            } else {
                console.warn('Position utilisateur non disponible:', userResult.error);
            }
        }
        
        // 3. Initialiser la carte
        initMap();
        
    } catch (err) {
        console.error('Erreur lors du chargement de la carte:', err);
        error.value = 'Erreur lors du chargement de la carte';
    } finally {
        loading.value = false;
    }
});

onUnmounted(() => {
    if (map) {
        map.remove();
    }
});

const loadLeaflet = () => {
    return new Promise((resolve, reject) => {
        // Vérifier si Leaflet est déjà chargé
        if (window.L) {
            resolve();
            return;
        }

        // Charger le CSS
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
        document.head.appendChild(link);

        // Charger le JS
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
};

const initMap = () => {
    if (!mapContainer.value || !associationCoords.value) return;

    // Créer la carte centrée sur l'association
    map = window.L.map(mapContainer.value, {
        center: [associationCoords.value.latitude, associationCoords.value.longitude],
        zoom: 13,
        minZoom: 6,  // Zoom minimum (vue de la France entière)
        maxZoom: 18, // Zoom maximum
        maxBounds: FRANCE_BOUNDS, // Limites strictes
        maxBoundsViscosity: 1.0   // Rendre les limites "collantes"
    });

    // Ajouter la couche OpenStreetMap
    window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Marqueur pour l'association (bleu)
    const assoIcon = window.L.divIcon({
        className: 'custom-marker',
        html: `
            <div style="position: relative;">
                <svg width="32" height="40" viewBox="0 0 32 40" fill="none">
                    <path d="M16 0C7.163 0 0 7.163 0 16c0 12 16 24 16 24s16-12 16-24c0-8.837-7.163-16-16-16z" fill="#3B82F6"/>
                    <circle cx="16" cy="16" r="6" fill="white"/>
                </svg>
            </div>
        `,
        iconSize: [32, 40],
        iconAnchor: [16, 40],
        popupAnchor: [0, -40]
    });

    const assoMarker = window.L.marker(
        [associationCoords.value.latitude, associationCoords.value.longitude],
        { icon: assoIcon }
    ).addTo(map);
    
    assoMarker.bindPopup(`
        <div style="font-family: system-ui; padding: 4px;">
            <strong style="color: #1F2937; font-size: 14px;">${props.association.title || 'Association'}</strong>
            <p style="margin: 4px 0 0 0; color: #6B7280; font-size: 12px;">
                ${associationCoords.value.displayName || 'Localisation'}
            </p>
        </div>
    `);
    
    markers.push(assoMarker);

    // Marqueur pour l'utilisateur (vert) si disponible
    if (userCoords.value) {
        const userIcon = window.L.divIcon({
            className: 'custom-marker',
            html: `
                <div style="position: relative;">
                    <svg width="32" height="40" viewBox="0 0 32 40" fill="none">
                        <path d="M16 0C7.163 0 0 7.163 0 16c0 12 16 24 16 24s16-12 16-24c0-8.837-7.163-16-16-16z" fill="#10B981"/>
                        <circle cx="16" cy="16" r="6" fill="white"/>
                    </svg>
                </div>
            `,
            iconSize: [32, 40],
            iconAnchor: [16, 40],
            popupAnchor: [0, -40]
        });

        const userMarker = window.L.marker(
            [userCoords.value.latitude, userCoords.value.longitude],
            { icon: userIcon }
        ).addTo(map);
        
        userMarker.bindPopup(`
            <div style="font-family: system-ui; padding: 4px;">
                <strong style="color: #1F2937; font-size: 14px;">Votre position</strong>
            </div>
        `);
        
        markers.push(userMarker);

        // Ajuster la vue pour inclure les deux marqueurs
        const bounds = window.L.latLngBounds([
            [associationCoords.value.latitude, associationCoords.value.longitude],
            [userCoords.value.latitude, userCoords.value.longitude]
        ]);
        map.fitBounds(bounds, { padding: [50, 50] });
    }
};

const openInMaps = () => {
    if (!associationCoords.value) return;
    
    const lat = associationCoords.value.latitude;
    const lon = associationCoords.value.longitude;
    
    window.open(`https://www.google.com/maps/search/?api=1&query=${lat},${lon}`, '_blank');
};

const getDirections = () => {
    if (!associationCoords.value || !userCoords.value) return;
    
    const assoLat = associationCoords.value.latitude;
    const assoLon = associationCoords.value.longitude;
    const userLat = userCoords.value.latitude;
    const userLon = userCoords.value.longitude;
    
    window.open(
        `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLon}&destination=${assoLat},${assoLon}`,
        '_blank'
    );
};
</script>

<template>
    <div class="map-widget border rounded-lg overflow-hidden shadow-sm bg-white">
        <!-- Header -->
        <div class="bg-gray-50 px-4 py-3 border-b flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h3 class="font-semibold text-gray-800">Localisation</h3>
            </div>
            
            <div v-if="distance" class="text-sm text-gray-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <span class="font-medium">{{ distance }} km</span>
            </div>
        </div>

        <!-- Carte -->
        <div class="relative bg-gray-100" :style="{ height: height }">
            <!-- Loading -->
            <div v-if="loading" class="absolute inset-0 flex items-center justify-center bg-gray-50 z-10">
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                    <p class="text-sm text-gray-600">Chargement de la carte...</p>
                </div>
            </div>

            <!-- Erreur -->
            <div v-else-if="error" class="absolute inset-0 flex items-center justify-center bg-gray-50 z-10">
                <div class="text-center px-4">
                    <svg class="w-12 h-12 text-red-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm text-gray-600">{{ error }}</p>
                </div>
            </div>

            <!-- Container Leaflet -->
            <div ref="mapContainer" class="w-full h-full"></div>
        </div>

        <!-- Footer avec actions -->
        <div v-if="associationCoords && !loading && !error" class="bg-gray-50 px-4 py-3 border-t flex gap-2">
            <button
                @click="openInMaps"
                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                Ouvrir dans Maps
            </button>
            
            <button
                v-if="userCoords"
                @click="getDirections"
                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                Itinéraire
            </button>
        </div>

        <!-- Info adresse -->
        <div v-if="associationCoords && !loading && !error" class="px-4 py-2 bg-gray-50 border-t text-xs text-gray-600">
            <p v-if="associationCoords.displayName">
                📍 {{ associationCoords.displayName }}
            </p>
            <p class="mt-1">
                Coordonnées: {{ associationCoords.latitude.toFixed(6) }}, {{ associationCoords.longitude.toFixed(6) }}
                <span v-if="associationCoords.source === 'geocoding'" class="text-orange-600">(géocodé)</span>
            </p>
        </div>
    </div>
</template>

<style scoped>
.map-widget {
    background: white;
}

/* Styles pour Leaflet */
:deep(.leaflet-container) {
    font-family: system-ui, -apple-system, sans-serif;
}

:deep(.custom-marker) {
    background: none;
    border: none;
}

:deep(.leaflet-popup-content-wrapper) {
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

:deep(.leaflet-popup-tip) {
    display: none;
}
</style>